from datetime import UTC, datetime, timedelta
from decimal import Decimal
from zoneinfo import ZoneInfo

import pytest

from dasbot.config import RiskConfig
from dasbot.events import PositionUpdate, Side
from dasbot.risk import KillSwitch, OrderIntent, RiskGate, is_regular_market_hours
from dasbot.state import AccountState

MARKET_TZ = ZoneInfo("America/New_York")
# A Wednesday, 10:30 ET — inside the regular session.
OPEN_TIME = datetime(2026, 3, 4, 10, 30, tzinfo=MARKET_TZ).astimezone(UTC)


def make_gate(**overrides) -> tuple[RiskGate, AccountState]:
    params = {
        "max_shares_per_order": 100,
        "max_position_shares": 200,
        "max_notional_per_order": Decimal("5000"),
        "max_open_orders": 3,
        "max_daily_loss": Decimal("500"),
        "max_orders_per_minute": 10,
    }
    params.update(overrides)
    config = RiskConfig(**params)
    state = AccountState()
    # Tests exercise the limits, not the reconnect path; start with a fresh book.
    state.apply(PositionUpdate(symbol="SEED", quantity=0))
    return RiskGate(config, state), state


def intent(**overrides) -> OrderIntent:
    params = {
        "symbol": "AAPL",
        "side": Side.BUY,
        "quantity": 10,
        "price": Decimal("100"),
    }
    params.update(overrides)
    return OrderIntent(**params)


class TestBasicLimits:
    def test_allows_a_normal_order(self):
        gate, _ = make_gate()
        assert gate.evaluate(intent(), now=OPEN_TIME).allowed

    def test_rejects_oversized_order(self):
        gate, _ = make_gate()
        decision = gate.evaluate(intent(quantity=500), now=OPEN_TIME)
        assert not decision.allowed
        assert "per-order cap" in decision.reason_text

    def test_rejects_excess_notional(self):
        gate, _ = make_gate()
        decision = gate.evaluate(
            intent(quantity=100, price=Decimal("500")), now=OPEN_TIME
        )
        assert not decision.allowed
        assert "notional" in decision.reason_text

    def test_rejects_zero_quantity(self):
        gate, _ = make_gate()
        assert not gate.evaluate(intent(quantity=0), now=OPEN_TIME).allowed

    def test_rejects_zero_price_limit(self):
        gate, _ = make_gate()
        decision = gate.evaluate(intent(price=Decimal(0)), now=OPEN_TIME)
        assert not decision.allowed
        assert "positive price" in decision.reason_text

    def test_short_disabled_by_default(self):
        gate, _ = make_gate()
        decision = gate.evaluate(intent(side=Side.SELL_SHORT), now=OPEN_TIME)
        assert not decision.allowed
        assert "short selling disabled" in decision.reason_text

    def test_short_allowed_when_enabled(self):
        gate, _ = make_gate(allow_short=True)
        assert gate.evaluate(intent(side=Side.SELL_SHORT), now=OPEN_TIME).allowed

    def test_allowlist_blocks_other_symbols(self):
        gate, _ = make_gate(symbol_allowlist=("MSFT",))
        assert not gate.evaluate(intent(symbol="AAPL"), now=OPEN_TIME).allowed
        assert gate.evaluate(intent(symbol="MSFT"), now=OPEN_TIME).allowed


class TestPositionLimits:
    def test_existing_position_counts_toward_cap(self):
        gate, state = make_gate()
        state.apply(PositionUpdate(symbol="AAPL", quantity=195))
        decision = gate.evaluate(intent(quantity=10), now=OPEN_TIME)
        assert not decision.allowed
        assert "projected position" in decision.reason_text

    def test_short_position_counts_by_absolute_size(self):
        gate, state = make_gate()
        state.apply(PositionUpdate(symbol="AAPL", quantity=-195))
        assert not gate.evaluate(intent(quantity=10), now=OPEN_TIME).allowed

    def test_working_orders_count_toward_cap(self):
        """The check that stops a fast strategy stacking into an oversized
        position while earlier orders are still unfilled."""
        gate, state = make_gate()
        state.register_sent_order(1, "AAPL", Side.BUY, 100, Decimal("100"))
        state.register_sent_order(2, "AAPL", Side.BUY, 95, Decimal("100"))
        decision = gate.evaluate(intent(quantity=50), now=OPEN_TIME)
        assert not decision.allowed
        assert "projected position" in decision.reason_text

    def test_open_order_cap(self):
        gate, state = make_gate()
        for i in range(3):
            state.register_sent_order(i, "MSFT", Side.BUY, 1, Decimal("1"))
        decision = gate.evaluate(intent(), now=OPEN_TIME)
        assert not decision.allowed
        assert "already working" in decision.reason_text

    def test_stale_book_blocks_orders(self):
        """After a reconnect we do not know our true position — deny until
        POSREFRESH answers."""
        gate, state = make_gate()
        state.mark_positions_stale()
        decision = gate.evaluate(intent(), now=OPEN_TIME)
        assert not decision.allowed
        assert "stale" in decision.reason_text


class TestLossLimitAndKillSwitch:
    def test_daily_loss_trips_kill_switch(self):
        gate, state = make_gate()
        state.session_realized_pnl = Decimal("-600")
        decision = gate.evaluate(intent(), now=OPEN_TIME)
        assert not decision.allowed
        assert gate.kill_switch.tripped

    def test_kill_switch_blocks_everything_after(self):
        gate, _ = make_gate()
        gate.kill_switch.trip("manual halt")
        decision = gate.evaluate(intent(), now=OPEN_TIME)
        assert not decision.allowed
        assert "kill switch" in decision.reason_text

    def test_kill_switch_is_one_way(self):
        """No code path re-arms it. Recovery is a restart, which forces a human
        to look at why it fired."""
        switch = KillSwitch()
        switch.trip("first reason")
        switch.trip("second reason")
        assert switch.tripped
        assert switch.reason == "first reason"
        assert not hasattr(switch, "reset")

    def test_unrealized_loss_also_trips(self):
        gate, state = make_gate()
        state.apply(
            PositionUpdate(
                symbol="AAPL", quantity=100, unrealized_pnl=Decimal("-700")
            )
        )
        gate.check_loss_limit()
        assert gate.kill_switch.tripped


class TestRateLimit:
    def test_rate_limit_blocks_runaway_loop(self):
        gate, _ = make_gate(max_orders_per_minute=3)
        for _ in range(3):
            assert gate.evaluate(intent(), now=OPEN_TIME).allowed
            gate.record_sent(OPEN_TIME)
        decision = gate.evaluate(intent(), now=OPEN_TIME)
        assert not decision.allowed
        assert "rate limit" in decision.reason_text

    def test_rate_limit_window_slides(self):
        gate, _ = make_gate(max_orders_per_minute=3)
        for _ in range(3):
            gate.record_sent(OPEN_TIME)
        later = OPEN_TIME + timedelta(seconds=61)
        assert gate.evaluate(intent(), now=later).allowed


class TestMarketHours:
    @pytest.mark.parametrize(
        "when,expected",
        [
            (datetime(2026, 3, 4, 10, 30, tzinfo=MARKET_TZ), True),   # Wed midday
            (datetime(2026, 3, 4, 9, 29, tzinfo=MARKET_TZ), False),   # pre-open
            (datetime(2026, 3, 4, 16, 1, tzinfo=MARKET_TZ), False),   # after close
            (datetime(2026, 3, 7, 12, 0, tzinfo=MARKET_TZ), False),   # Saturday
            (datetime(2026, 3, 8, 12, 0, tzinfo=MARKET_TZ), False),   # Sunday
        ],
    )
    def test_regular_hours(self, when, expected):
        assert is_regular_market_hours(when.astimezone(UTC)) is expected

    def test_orders_blocked_outside_hours(self):
        gate, _ = make_gate()
        night = datetime(2026, 3, 4, 22, 0, tzinfo=MARKET_TZ).astimezone(UTC)
        decision = gate.evaluate(intent(), now=night)
        assert not decision.allowed
        assert "market hours" in decision.reason_text

    def test_can_be_disabled(self):
        gate, _ = make_gate(require_market_hours=False)
        night = datetime(2026, 3, 4, 22, 0, tzinfo=MARKET_TZ).astimezone(UTC)
        assert gate.evaluate(intent(), now=night).allowed


def test_all_failures_reported_together():
    """One pass should surface every reason, so a fix does not just reveal the
    next problem on the following attempt."""
    gate, _ = make_gate(symbol_allowlist=("MSFT",))
    decision = gate.evaluate(
        intent(symbol="AAPL", quantity=9999, price=Decimal("900")), now=OPEN_TIME
    )
    assert not decision.allowed
    assert len(decision.reasons) >= 3
