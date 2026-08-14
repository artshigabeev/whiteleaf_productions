"""Strategy interface.

Subclass `Strategy` and override the callbacks you care about. Every hook is
async and every hook is optional.

Strategies should stay stateless about the account: ask `client.state` rather
than tracking positions yourself, so there is one book and it is the one the
risk gate is checking against.
"""

from __future__ import annotations

import logging
from decimal import Decimal
from typing import TYPE_CHECKING

from .events import Event, OrderUpdate, PositionUpdate, Quote, Side, TradeUpdate

if TYPE_CHECKING:  # avoids a circular import at runtime
    from .client import DasClient

log = logging.getLogger(__name__)


class Strategy:
    """Base class — all hooks are no-ops."""

    name: str = "strategy"

    async def on_start(self, client: DasClient) -> None: ...

    async def on_stop(self, client: DasClient) -> None: ...

    async def on_quote(self, client: DasClient, quote: Quote) -> None: ...

    async def on_order_update(self, client: DasClient, update: OrderUpdate) -> None: ...

    async def on_fill(self, client: DasClient, fill: TradeUpdate) -> None: ...

    async def on_position(self, client: DasClient, position: PositionUpdate) -> None: ...

    async def on_event(self, client: DasClient, event: Event) -> None:
        """Catch-all, called after the specific hook above."""


class ObserverStrategy(Strategy):
    """Sends nothing. Logs the stream.

    Run this first against a live connection: it exercises the socket, the
    parser and the state book with zero order risk, and its log is what you
    diff against the spec to verify the message layouts.
    """

    name = "observer"

    def __init__(self) -> None:
        self._quote_count = 0

    async def on_start(self, client: DasClient) -> None:
        log.info(
            "observer started — watching %s",
            ", ".join(client.config.connection.watchlist) or "(no symbols)",
        )

    async def on_quote(self, client: DasClient, quote: Quote) -> None:
        self._quote_count += 1
        if self._quote_count % 50 == 0:
            log.info(
                "%s bid=%s ask=%s last=%s (%d quotes seen)",
                quote.symbol,
                quote.bid,
                quote.ask,
                quote.last,
                self._quote_count,
            )

    async def on_fill(self, client: DasClient, fill: TradeUpdate) -> None:
        log.info(
            "FILL %s %s %s @ %s | %s",
            fill.side.value if fill.side else "?",
            fill.quantity,
            fill.symbol,
            fill.price,
            client.state.summary(),
        )

    async def on_order_update(self, client: DasClient, update: OrderUpdate) -> None:
        log.info(
            "ORDER token=%s id=%s %s %s status=%s filled=%d/%d",
            update.token,
            update.order_id,
            update.symbol,
            update.side.value if update.side else "?",
            update.status.value,
            update.filled_quantity,
            update.quantity,
        )


class SpreadCaptureExample(Strategy):
    """A deliberately trivial reference strategy.

    Posts a bid one tick inside the spread when flat, and exits at a fixed
    target. This exists to demonstrate the API surface — it is NOT a profitable
    strategy and should not be run with real money. Replace it with your own
    logic; the framework around it is the reusable part.
    """

    name = "spread-capture-example"

    def __init__(
        self,
        symbol: str,
        shares: int = 10,
        min_spread: Decimal = Decimal("0.02"),
        profit_target: Decimal = Decimal("0.05"),
    ) -> None:
        self.symbol = symbol.upper()
        self.shares = shares
        self.min_spread = min_spread
        self.profit_target = profit_target

    async def on_start(self, client: DasClient) -> None:
        await client.subscribe(self.symbol)
        log.warning(
            "%s is an EXAMPLE strategy for wiring demonstration only — "
            "do not run it with real capital",
            self.name,
        )

    async def on_quote(self, client: DasClient, quote: Quote) -> None:
        if quote.symbol != self.symbol:
            return
        if quote.spread < self.min_spread or quote.bid <= 0:
            return

        position = client.state.position(self.symbol)

        # Never stack orders: if anything is working on this symbol, wait.
        if client.state.working_orders(self.symbol):
            return

        if position.is_flat:
            await client.place_order(
                symbol=self.symbol,
                side=Side.BUY,
                quantity=self.shares,
                price=quote.bid + Decimal("0.01"),
                tag="entry",
            )
        elif position.quantity > 0:
            target = position.average_price + self.profit_target
            if quote.ask >= target:
                await client.place_order(
                    symbol=self.symbol,
                    side=Side.SELL,
                    quantity=position.quantity,
                    price=max(target, quote.bid),
                    tag="exit",
                )
