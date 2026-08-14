"""High-level client: the only interface a strategy is given.

Wires together transport, state and risk. Strategies receive typed events and
may request orders — every request goes through `RiskGate`, and there is no
method on this class that reaches the socket without one.
"""

from __future__ import annotations

import asyncio
import logging
from decimal import Decimal

from . import protocol
from .config import AppConfig
from .connection import DasConnection
from .events import Event, OrderUpdate, PositionUpdate, Quote, Side, TradeUpdate
from .protocol import TokenGenerator
from .risk import KillSwitch, OrderIntent, RiskDecision, RiskGate
from .state import AccountState
from .strategy import Strategy

log = logging.getLogger(__name__)


class DasClient:
    def __init__(self, config: AppConfig, strategy: Strategy | None = None) -> None:
        self.config = config
        self.state = AccountState()
        self.kill_switch = KillSwitch()
        self.risk = RiskGate(config.risk, self.state, self.kill_switch)
        self.strategy = strategy
        self.tokens = TokenGenerator()
        self.connection = DasConnection(
            config.connection,
            on_event=self._handle_event,
            on_reconnect=self._on_reconnect,
        )
        self._stopped = asyncio.Event()

    # -- lifecycle -------------------------------------------------------

    async def run(self) -> None:
        if self.config.dry_run:
            log.warning(
                "DRY RUN: orders will be risk-checked and logged but NOT sent "
                "to the broker. Set DAS_DRY_RUN=false to trade for real."
            )
        unverified = protocol.unverified_layouts()
        if unverified:
            log.warning(
                "%d message layout(s) are UNVERIFIED against the broker spec: %s",
                len(unverified),
                ", ".join(layout.prefix for layout in unverified),
            )

        await self.connection.start()
        for symbol in self.config.connection.watchlist:
            await self.connection.subscribe(symbol)

        if self.strategy is not None:
            await self.strategy.on_start(self)

        await self._stopped.wait()

    async def stop(self) -> None:
        if self.strategy is not None:
            try:
                await self.strategy.on_stop(self)
            except Exception:  # noqa: BLE001
                log.exception("strategy on_stop raised")
        await self.connection.stop()
        self._stopped.set()

    async def _on_reconnect(self) -> None:
        """Resynchronise after any reconnect.

        The book is marked stale until DAS answers POSREFRESH, and the risk gate
        denies new orders while stale — we could have been filled, or stopped
        out, during the gap.
        """
        log.info("resynchronising account state after reconnect")
        self.state.mark_positions_stale()
        await self.connection.send(protocol.position_refresh())
        await self.connection.send(protocol.buying_power())

    # -- event pipeline ---------------------------------------------------

    async def _handle_event(self, event: Event) -> None:
        self.state.apply(event)

        if isinstance(event, (PositionUpdate, TradeUpdate, Quote)):
            self.risk.check_loss_limit()
            if self.kill_switch.tripped:
                await self._on_kill_switch()

        if self.strategy is None:
            return

        if isinstance(event, Quote):
            await self.strategy.on_quote(self, event)
        elif isinstance(event, OrderUpdate):
            await self.strategy.on_order_update(self, event)
        elif isinstance(event, TradeUpdate):
            await self.strategy.on_fill(self, event)
        elif isinstance(event, PositionUpdate):
            await self.strategy.on_position(self, event)
        await self.strategy.on_event(self, event)

    async def _on_kill_switch(self) -> None:
        """Cancel everything working. Positions are left alone deliberately.

        Auto-liquidating into whatever caused the loss limit to trip can turn a
        bad day into a worse one, and it is a decision a human should make. We
        stop the bleeding from *new* orders and hand control back.
        """
        log.critical(
            "kill switch active (%s) — cancelling working orders. "
            "OPEN POSITIONS ARE NOT LIQUIDATED: %s",
            self.kill_switch.reason,
            self.state.summary(),
        )
        try:
            await self.cancel_all()
        except Exception:  # noqa: BLE001
            log.exception("failed to cancel orders after kill switch")

    # -- trading ----------------------------------------------------------

    async def place_order(
        self,
        symbol: str,
        side: Side,
        quantity: int,
        price: Decimal | None = None,
        order_type: str = "LIMIT",
        route: str | None = None,
        tag: str = "",
    ) -> tuple[bool, RiskDecision, int | None]:
        """Risk-check and send an order.

        Returns (sent, decision, token). `sent` is False in dry-run mode even
        when the decision allows it.
        """
        intent = OrderIntent(
            symbol=symbol.upper(),
            side=side,
            quantity=quantity,
            price=price or Decimal(0),
            order_type=order_type,
            route=route or self.config.connection.default_route,
            tag=tag,
        )

        decision = self.risk.evaluate(intent)
        if not decision.allowed:
            log.warning(
                "REJECTED %s %s %s @ %s: %s",
                intent.side.value,
                intent.quantity,
                intent.symbol,
                intent.price,
                decision.reason_text,
            )
            return False, decision, None

        token = self.tokens.next()
        command = protocol.new_order(
            token=token,
            side=intent.side,
            symbol=intent.symbol,
            quantity=intent.quantity,
            route=intent.route,
            price=intent.price if order_type.upper() in ("LIMIT", "LMT") else None,
            order_type=order_type,
        )

        if self.config.dry_run:
            log.info("[DRY RUN] would send: %s", command)
            return False, decision, token

        # Register before sending. If the send throws after the bytes reached
        # the wire, an untracked live order is the worst possible outcome.
        self.state.register_sent_order(
            token, intent.symbol, intent.side, intent.quantity, intent.price
        )
        try:
            await self.connection.send(command)
        except Exception:
            log.exception("send failed for token=%s; order state is UNCERTAIN", token)
            raise

        self.risk.record_sent()
        log.info(
            "SENT token=%s %s %s %s @ %s",
            token,
            intent.side.value,
            intent.quantity,
            intent.symbol,
            intent.price,
        )
        return True, decision, token

    async def cancel(self, token_or_id: str | int) -> None:
        if self.config.dry_run:
            log.info("[DRY RUN] would cancel %s", token_or_id)
            return
        await self.connection.send(protocol.cancel_order(token_or_id))

    async def cancel_all(self) -> None:
        if self.config.dry_run:
            log.info("[DRY RUN] would cancel all orders")
            return
        await self.connection.send(protocol.cancel_all())

    async def refresh_positions(self) -> None:
        await self.connection.send(protocol.position_refresh())

    async def subscribe(self, symbol: str) -> None:
        await self.connection.subscribe(symbol)
