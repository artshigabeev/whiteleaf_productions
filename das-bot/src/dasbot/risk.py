"""Pre-trade risk gate and kill switch.

Every order passes through `RiskGate.evaluate` before it can reach the socket.
There is no bypass path — `client.DasClient` has no other way to send an order.

Design rules:
  * Deny by default. Any check that cannot be evaluated denies the order; an
    unknown position size is treated as dangerous, not as zero.
  * Checks are pure and independently testable — no I/O, no clock reads except
    through an injectable `now` so tests are deterministic.
  * Tripping the kill switch is one-way for the process lifetime. Re-arming is
    a human action (restart), never something a strategy can do to itself.
"""

from __future__ import annotations

import logging
from collections import deque
from dataclasses import dataclass
from datetime import UTC, datetime, time, timedelta
from decimal import Decimal
from zoneinfo import ZoneInfo

from .config import RiskConfig
from .events import Side
from .state import AccountState

log = logging.getLogger(__name__)

MARKET_TZ = ZoneInfo("America/New_York")
REGULAR_OPEN = time(9, 30)
REGULAR_CLOSE = time(16, 0)


@dataclass(frozen=True, slots=True)
class OrderIntent:
    """A proposed order, before any risk decision has been made."""

    symbol: str
    side: Side
    quantity: int
    price: Decimal
    order_type: str = "LIMIT"
    route: str = ""
    tag: str = ""

    @property
    def notional(self) -> Decimal:
        return Decimal(self.quantity) * self.price


@dataclass(frozen=True, slots=True)
class RiskDecision:
    allowed: bool
    reasons: tuple[str, ...] = ()

    @property
    def reason_text(self) -> str:
        return "; ".join(self.reasons)

    @classmethod
    def allow(cls) -> RiskDecision:
        return cls(allowed=True)

    @classmethod
    def deny(cls, *reasons: str) -> RiskDecision:
        return cls(allowed=False, reasons=tuple(reasons))


class KillSwitch:
    """Process-wide trading halt.

    Once tripped, stays tripped. The only way back is a restart, which forces a
    human to look at why it fired.
    """

    def __init__(self) -> None:
        self._tripped = False
        self._reason = ""
        self._tripped_at: datetime | None = None

    def trip(self, reason: str) -> None:
        if self._tripped:
            return
        self._tripped = True
        self._reason = reason
        self._tripped_at = datetime.now(UTC)
        log.critical("KILL SWITCH TRIPPED: %s", reason)

    @property
    def tripped(self) -> bool:
        return self._tripped

    @property
    def reason(self) -> str:
        return self._reason

    @property
    def tripped_at(self) -> datetime | None:
        return self._tripped_at


class RateLimiter:
    """Sliding-window order rate cap.

    Guards against the classic runaway loop: a strategy bug that sends on every
    quote tick can otherwise fire hundreds of orders before anyone notices.
    """

    def __init__(self, max_events: int, window: timedelta = timedelta(minutes=1)) -> None:
        self.max_events = max_events
        self.window = window
        self._events: deque[datetime] = deque()

    def _prune(self, now: datetime) -> None:
        cutoff = now - self.window
        while self._events and self._events[0] < cutoff:
            self._events.popleft()

    def would_exceed(self, now: datetime) -> bool:
        self._prune(now)
        return len(self._events) >= self.max_events

    def record(self, now: datetime) -> None:
        self._prune(now)
        self._events.append(now)

    @property
    def recent_count(self) -> int:
        return len(self._events)


def is_regular_market_hours(now: datetime) -> bool:
    """True during regular US equity session, Mon-Fri.

    Does not know about market holidays — a holiday simply produces no fills,
    which is safe. Add a holiday calendar before relying on this for anything
    other than a safety bound.
    """
    local = now.astimezone(MARKET_TZ)
    if local.weekday() >= 5:
        return False
    return REGULAR_OPEN <= local.time() <= REGULAR_CLOSE


class RiskGate:
    """Applies every pre-trade check and records the outcome."""

    def __init__(
        self,
        config: RiskConfig,
        state: AccountState,
        kill_switch: KillSwitch | None = None,
    ) -> None:
        self.config = config
        self.state = state
        self.kill_switch = kill_switch or KillSwitch()
        self.rate_limiter = RateLimiter(config.max_orders_per_minute)
        self.denied_count = 0
        self.allowed_count = 0

    def evaluate(
        self, intent: OrderIntent, now: datetime | None = None
    ) -> RiskDecision:
        now = now or datetime.now(UTC)
        reasons: list[str] = []

        if self.kill_switch.tripped:
            return self._record(
                RiskDecision.deny(f"kill switch active: {self.kill_switch.reason}")
            )

        # --- sanity ------------------------------------------------------
        if intent.quantity <= 0:
            reasons.append(f"non-positive quantity {intent.quantity}")
        if intent.price <= 0 and intent.order_type.upper() in ("LIMIT", "LMT"):
            reasons.append("limit order requires a positive price")
        if not intent.symbol:
            reasons.append("missing symbol")

        symbol = intent.symbol.upper()

        # --- allowlist ---------------------------------------------------
        if self.config.symbol_allowlist and symbol not in self.config.symbol_allowlist:
            reasons.append(f"{symbol} not in symbol allowlist")

        # --- shorting ----------------------------------------------------
        if intent.side is Side.SELL_SHORT and not self.config.allow_short:
            reasons.append("short selling disabled by configuration")

        # --- per-order size ----------------------------------------------
        if intent.quantity > self.config.max_shares_per_order:
            reasons.append(
                f"quantity {intent.quantity} exceeds per-order cap "
                f"{self.config.max_shares_per_order}"
            )
        if intent.notional > self.config.max_notional_per_order:
            reasons.append(
                f"notional {intent.notional:.2f} exceeds per-order cap "
                f"{self.config.max_notional_per_order:.2f}"
            )

        # --- resulting position ------------------------------------------
        # Worst case: current position, plus everything already working, plus
        # this order — all filling. That is the number the cap must bound.
        position = self.state.position(symbol)
        pending = self.state.pending_exposure(symbol)
        projected = abs(position.quantity) + pending + intent.quantity
        if projected > self.config.max_position_shares:
            reasons.append(
                f"projected position {projected} shares (held "
                f"{position.quantity}, working {pending}, new {intent.quantity}) "
                f"exceeds cap {self.config.max_position_shares}"
            )

        # --- stale book ---------------------------------------------------
        if self.state.positions_are_stale:
            reasons.append(
                "position book is stale (awaiting POSREFRESH after reconnect)"
            )

        # --- open order count ---------------------------------------------
        if self.state.open_order_count() >= self.config.max_open_orders:
            reasons.append(
                f"{self.state.open_order_count()} orders already working, cap is "
                f"{self.config.max_open_orders}"
            )

        # --- daily loss ----------------------------------------------------
        pnl = self.state.total_pnl()
        if pnl <= -self.config.max_daily_loss:
            self.kill_switch.trip(
                f"daily loss limit hit: {pnl:.2f} <= -{self.config.max_daily_loss:.2f}"
            )
            reasons.append(f"daily loss limit reached ({pnl:.2f})")

        # --- rate ----------------------------------------------------------
        if self.rate_limiter.would_exceed(now):
            reasons.append(
                f"order rate limit: {self.rate_limiter.recent_count} orders in the "
                f"last minute, cap is {self.config.max_orders_per_minute}"
            )

        # --- session --------------------------------------------------------
        if self.config.require_market_hours and not is_regular_market_hours(now):
            reasons.append("outside regular market hours")

        if reasons:
            return self._record(RiskDecision.deny(*reasons))
        return self._record(RiskDecision.allow())

    def record_sent(self, now: datetime | None = None) -> None:
        """Called only after an order actually reaches the socket."""
        self.rate_limiter.record(now or datetime.now(UTC))

    def check_loss_limit(self) -> None:
        """Continuous PnL check, independent of order flow.

        Called on every position/quote update so a losing position that we are
        not actively trading still trips the switch.
        """
        pnl = self.state.total_pnl()
        if pnl <= -self.config.max_daily_loss:
            self.kill_switch.trip(
                f"daily loss limit hit: {pnl:.2f} <= -{self.config.max_daily_loss:.2f}"
            )

    def _record(self, decision: RiskDecision) -> RiskDecision:
        if decision.allowed:
            self.allowed_count += 1
        else:
            self.denied_count += 1
            log.warning("order denied: %s", decision.reason_text)
        return decision
