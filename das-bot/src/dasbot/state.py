"""Local mirror of orders and positions.

The strategy must never have to ask the broker "am I flat?" mid-decision — that
is a round trip during which the answer can change. This module keeps a local
book updated from the event stream, and reconciles it against DAS on every
reconnect.

Conservative bias throughout: when the wire is ambiguous, assume we have *more*
exposure and *more* working orders than we might, so risk checks tighten rather
than loosen.
"""

from __future__ import annotations

import logging
from dataclasses import dataclass, field
from datetime import UTC, date, datetime
from decimal import Decimal

from .events import (
    BuyingPowerUpdate,
    Event,
    OrderStatus,
    OrderUpdate,
    PositionUpdate,
    Quote,
    Side,
    TradeUpdate,
)

log = logging.getLogger(__name__)


@dataclass(slots=True)
class WorkingOrder:
    token: int
    symbol: str
    side: Side | None
    quantity: int
    price: Decimal
    status: OrderStatus = OrderStatus.PENDING
    order_id: str = ""
    filled_quantity: int = 0
    average_fill_price: Decimal = Decimal(0)
    sent_at: datetime = field(default_factory=lambda: datetime.now(UTC))
    last_update: datetime = field(default_factory=lambda: datetime.now(UTC))
    reason: str = ""

    @property
    def remaining(self) -> int:
        return max(self.quantity - self.filled_quantity, 0)

    @property
    def is_working(self) -> bool:
        return self.status.is_working


@dataclass(slots=True)
class Position:
    symbol: str
    quantity: int = 0
    average_price: Decimal = Decimal(0)
    realized_pnl: Decimal = Decimal(0)
    unrealized_pnl: Decimal = Decimal(0)
    last_price: Decimal = Decimal(0)

    @property
    def is_flat(self) -> bool:
        return self.quantity == 0

    @property
    def exposure(self) -> Decimal:
        price = self.last_price or self.average_price
        return abs(Decimal(self.quantity)) * price


class AccountState:
    """Single source of truth for what we own and what is working."""

    def __init__(self) -> None:
        self._orders: dict[int, WorkingOrder] = {}
        self._order_id_index: dict[str, int] = {}
        self._positions: dict[str, Position] = {}
        self._quotes: dict[str, Quote] = {}
        self.buying_power: Decimal = Decimal(0)
        self.day_trading_buying_power: Decimal = Decimal(0)
        self.session_realized_pnl: Decimal = Decimal(0)
        self.session_date: date = datetime.now(UTC).date()
        self._positions_are_stale: bool = True

    # -- registration ----------------------------------------------------

    def register_sent_order(
        self, token: int, symbol: str, side: Side, quantity: int, price: Decimal
    ) -> WorkingOrder:
        """Record an order at send time, before any ack.

        Doing this pre-ack is what makes the max-open-orders check meaningful:
        between send and ack there is a window in which a naive book shows
        nothing outstanding and a fast strategy would happily send again.
        """
        order = WorkingOrder(
            token=token,
            symbol=symbol.upper(),
            side=side,
            quantity=quantity,
            price=price,
        )
        self._orders[token] = order
        return order

    # -- event application -----------------------------------------------

    def apply(self, event: Event) -> None:
        if isinstance(event, OrderUpdate):
            self._apply_order(event)
        elif isinstance(event, TradeUpdate):
            self._apply_trade(event)
        elif isinstance(event, PositionUpdate):
            self._apply_position(event)
        elif isinstance(event, BuyingPowerUpdate):
            self.buying_power = event.buying_power
            self.day_trading_buying_power = event.day_trading_buying_power
        elif isinstance(event, Quote):
            self._apply_quote(event)

    def _apply_order(self, event: OrderUpdate) -> None:
        token = event.token
        if not token and event.order_id:
            token = self._order_id_index.get(event.order_id, 0)

        order = self._orders.get(token)
        if order is None:
            # An order we did not send, or one that outlived a restart. Track it
            # anyway — it consumes buying power and must count against limits.
            order = WorkingOrder(
                token=token,
                symbol=event.symbol,
                side=event.side,
                quantity=event.quantity,
                price=event.price,
            )
            if token:
                self._orders[token] = order
            log.warning(
                "order update for untracked order token=%s id=%s symbol=%s",
                token,
                event.order_id,
                event.symbol,
            )

        if event.order_id:
            order.order_id = event.order_id
            if token:
                self._order_id_index[event.order_id] = token
        if event.symbol:
            order.symbol = event.symbol
        if event.side is not None:
            order.side = event.side
        if event.quantity:
            order.quantity = event.quantity
        if event.filled_quantity:
            order.filled_quantity = event.filled_quantity
        if event.average_fill_price:
            order.average_fill_price = event.average_fill_price
        if event.reason:
            order.reason = event.reason

        # UNKNOWN means we could not decode the status. Keep the previous state
        # rather than overwriting with a value that might read as terminal.
        if event.status is not OrderStatus.UNKNOWN:
            order.status = event.status
        order.last_update = datetime.now(UTC)

        if order.status.is_terminal:
            self._retire(order)

    def _apply_trade(self, event: TradeUpdate) -> None:
        """Apply a fill to our position estimate.

        This is an estimate only. `%POS` from DAS is authoritative and will
        overwrite it; we maintain it so the strategy has a correct view in the
        milliseconds between a fill and the position push.
        """
        if not event.symbol or event.side is None or not event.quantity:
            return
        position = self._positions.setdefault(
            event.symbol, Position(symbol=event.symbol)
        )
        signed = event.quantity * event.side.signed_multiplier
        previous_qty = position.quantity
        new_qty = previous_qty + signed

        if previous_qty == 0 or (previous_qty > 0) == (signed > 0):
            # Opening or adding: blend the average price.
            total_cost = (
                abs(Decimal(previous_qty)) * position.average_price
                + Decimal(event.quantity) * event.price
            )
            total_shares = abs(previous_qty) + event.quantity
            if total_shares:
                position.average_price = total_cost / total_shares
        else:
            # Reducing or flipping: realise PnL on the closed portion.
            closed = min(abs(previous_qty), event.quantity)
            direction = Decimal(1 if previous_qty > 0 else -1)
            position.realized_pnl += (
                (event.price - position.average_price) * closed * direction
            )
            self.session_realized_pnl += (
                (event.price - position.average_price) * closed * direction
            )
            if new_qty != 0 and (new_qty > 0) != (previous_qty > 0):
                position.average_price = event.price  # flipped through zero

        position.quantity = new_qty
        if new_qty == 0:
            position.average_price = Decimal(0)
            position.unrealized_pnl = Decimal(0)

    def _apply_position(self, event: PositionUpdate) -> None:
        position = self._positions.setdefault(
            event.symbol, Position(symbol=event.symbol)
        )
        position.quantity = event.quantity
        position.average_price = event.average_price
        position.realized_pnl = event.realized_pnl
        position.unrealized_pnl = event.unrealized_pnl
        self._positions_are_stale = False

    def _apply_quote(self, event: Quote) -> None:
        self._quotes[event.symbol] = event
        position = self._positions.get(event.symbol)
        if position is not None and not position.is_flat:
            mark = event.mid or event.last
            if mark:
                position.last_price = mark
                position.unrealized_pnl = (
                    mark - position.average_price
                ) * position.quantity

    def _retire(self, order: WorkingOrder) -> None:
        self._orders.pop(order.token, None)
        if order.order_id:
            self._order_id_index.pop(order.order_id, None)

    # -- queries ---------------------------------------------------------

    def working_orders(self, symbol: str | None = None) -> list[WorkingOrder]:
        orders = [o for o in self._orders.values() if o.is_working]
        if symbol:
            symbol = symbol.upper()
            orders = [o for o in orders if o.symbol == symbol]
        return orders

    def open_order_count(self) -> int:
        return len(self.working_orders())

    def position(self, symbol: str) -> Position:
        return self._positions.get(symbol.upper(), Position(symbol=symbol.upper()))

    def positions(self) -> list[Position]:
        return [p for p in self._positions.values() if not p.is_flat]

    def quote(self, symbol: str) -> Quote | None:
        return self._quotes.get(symbol.upper())

    def pending_exposure(self, symbol: str) -> int:
        """Shares that *would* be added if every working order on this symbol filled.

        Counted as absolute size on the assumption that everything fills, which
        is what makes the position cap hold under partial fills.
        """
        return sum(o.remaining for o in self.working_orders(symbol))

    def total_pnl(self) -> Decimal:
        unrealized = sum(
            (p.unrealized_pnl for p in self._positions.values()), Decimal(0)
        )
        return self.session_realized_pnl + unrealized

    def mark_positions_stale(self) -> None:
        """Called on disconnect: the book may have moved while we were away."""
        self._positions_are_stale = True

    @property
    def positions_are_stale(self) -> bool:
        return self._positions_are_stale

    def roll_session_if_needed(self) -> bool:
        today = datetime.now(UTC).date()
        if today != self.session_date:
            self.session_date = today
            self.session_realized_pnl = Decimal(0)
            return True
        return False

    def summary(self) -> str:
        positions = ", ".join(
            f"{p.symbol}:{p.quantity}@{p.average_price:.2f}" for p in self.positions()
        )
        return (
            f"orders={self.open_order_count()} "
            f"positions=[{positions or 'flat'}] "
            f"pnl={self.total_pnl():.2f} bp={self.buying_power:.2f}"
        )
