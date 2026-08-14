"""Typed events decoded from the DAS CMD API socket stream.

Everything the connection layer reads off the wire is turned into one of these
before it reaches strategy code. Strategies never see raw strings.

`Unknown` is deliberately part of the type set: an unrecognised message must
degrade to a logged event, never an exception. A parser crash on an unexpected
line would take down the socket loop while orders are live.
"""

from __future__ import annotations

from dataclasses import dataclass, field
from datetime import UTC, datetime
from decimal import Decimal
from enum import StrEnum
from typing import Any


class Side(StrEnum):
    """Order sides as DAS spells them on the wire."""

    BUY = "B"
    SELL = "S"
    SELL_SHORT = "SS"
    BUY_TO_COVER = "BC"

    @property
    def is_opening_long(self) -> bool:
        return self is Side.BUY

    @property
    def signed_multiplier(self) -> int:
        """+1 if the side increases position, -1 if it decreases it."""
        return 1 if self in (Side.BUY, Side.BUY_TO_COVER) else -1


class OrderStatus(StrEnum):
    """Normalised order lifecycle states.

    DAS reports a wider vocabulary than this; `protocol.STATUS_ALIASES` maps the
    raw tokens onto these. Anything unmapped becomes UNKNOWN rather than being
    silently treated as a terminal state.
    """

    PENDING = "pending"
    ACCEPTED = "accepted"
    PARTIALLY_FILLED = "partially_filled"
    FILLED = "filled"
    CANCELLED = "cancelled"
    REJECTED = "rejected"
    UNKNOWN = "unknown"

    @property
    def is_terminal(self) -> bool:
        return self in (
            OrderStatus.FILLED,
            OrderStatus.CANCELLED,
            OrderStatus.REJECTED,
        )

    @property
    def is_working(self) -> bool:
        """True while the order can still consume buying power."""
        return self in (
            OrderStatus.PENDING,
            OrderStatus.ACCEPTED,
            OrderStatus.PARTIALLY_FILLED,
        )


@dataclass(frozen=True, slots=True)
class Event:
    """Base for every decoded message."""

    received_at: datetime = field(
        default_factory=lambda: datetime.now(UTC),
        kw_only=True,
        compare=False,
    )
    raw: str = field(default="", kw_only=True, compare=False)


@dataclass(frozen=True, slots=True)
class LoginResult(Event):
    success: bool
    message: str = ""


@dataclass(frozen=True, slots=True)
class Quote(Event):
    symbol: str = ""
    bid: Decimal = Decimal(0)
    ask: Decimal = Decimal(0)
    last: Decimal = Decimal(0)
    bid_size: int = 0
    ask_size: int = 0
    volume: int = 0

    @property
    def mid(self) -> Decimal:
        if self.bid > 0 and self.ask > 0:
            return (self.bid + self.ask) / 2
        return self.last

    @property
    def spread(self) -> Decimal:
        if self.bid > 0 and self.ask > 0:
            return self.ask - self.bid
        return Decimal(0)


@dataclass(frozen=True, slots=True)
class OrderUpdate(Event):
    """A change of state for a single order.

    `token` is our client-side id (echoed back by DAS), `order_id` is the
    broker's. We key our own book on `token` because it exists from the moment
    we send the order, whereas `order_id` only appears once DAS accepts it.
    """

    token: int = 0
    order_id: str = ""
    symbol: str = ""
    side: Side | None = None
    status: OrderStatus = OrderStatus.UNKNOWN
    quantity: int = 0
    filled_quantity: int = 0
    leaves_quantity: int = 0
    price: Decimal = Decimal(0)
    average_fill_price: Decimal = Decimal(0)
    route: str = ""
    reason: str = ""

    @property
    def remaining(self) -> int:
        if self.leaves_quantity:
            return self.leaves_quantity
        return max(self.quantity - self.filled_quantity, 0)


@dataclass(frozen=True, slots=True)
class TradeUpdate(Event):
    """An individual execution (fill), as opposed to an order state change."""

    order_id: str = ""
    token: int = 0
    symbol: str = ""
    side: Side | None = None
    quantity: int = 0
    price: Decimal = Decimal(0)
    exec_id: str = ""


@dataclass(frozen=True, slots=True)
class PositionUpdate(Event):
    symbol: str = ""
    quantity: int = 0
    average_price: Decimal = Decimal(0)
    realized_pnl: Decimal = Decimal(0)
    unrealized_pnl: Decimal = Decimal(0)

    @property
    def is_flat(self) -> bool:
        return self.quantity == 0

    @property
    def is_short(self) -> bool:
        return self.quantity < 0


@dataclass(frozen=True, slots=True)
class BuyingPowerUpdate(Event):
    buying_power: Decimal = Decimal(0)
    day_trading_buying_power: Decimal = Decimal(0)
    cash: Decimal = Decimal(0)


@dataclass(frozen=True, slots=True)
class ShortInfo(Event):
    symbol: str = ""
    shortable: bool = False
    available_shares: int = 0


@dataclass(frozen=True, slots=True)
class Heartbeat(Event):
    """Echo/keepalive response confirming the socket is alive."""


@dataclass(frozen=True, slots=True)
class ErrorMessage(Event):
    message: str = ""
    command: str = ""


@dataclass(frozen=True, slots=True)
class Unknown(Event):
    """A line we could not map to a known layout.

    Carries the raw text so it shows up in logs and can be diffed against the
    spec. Never raise on these.
    """

    prefix: str = ""
    fields: tuple[str, ...] = ()
    extra: dict[str, Any] = field(default_factory=dict)
