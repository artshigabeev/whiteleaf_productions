from decimal import Decimal

from dasbot.events import (
    BuyingPowerUpdate,
    OrderStatus,
    OrderUpdate,
    PositionUpdate,
    Quote,
    Side,
    TradeUpdate,
)
from dasbot.state import AccountState


class TestOrderTracking:
    def test_sent_order_counts_before_any_ack(self):
        """The send-to-ack window is exactly when a naive book shows nothing
        outstanding and a fast strategy sends again."""
        state = AccountState()
        state.register_sent_order(1, "AAPL", Side.BUY, 100, Decimal("189"))
        assert state.open_order_count() == 1
        assert state.pending_exposure("AAPL") == 100

    def test_fill_retires_order(self):
        state = AccountState()
        state.register_sent_order(1, "AAPL", Side.BUY, 100, Decimal("189"))
        state.apply(
            OrderUpdate(token=1, symbol="AAPL", status=OrderStatus.FILLED,
                        quantity=100, filled_quantity=100)
        )
        assert state.open_order_count() == 0

    def test_rejection_retires_order(self):
        state = AccountState()
        state.register_sent_order(1, "AAPL", Side.BUY, 100, Decimal("189"))
        state.apply(OrderUpdate(token=1, symbol="AAPL", status=OrderStatus.REJECTED))
        assert state.open_order_count() == 0

    def test_partial_fill_stays_working(self):
        state = AccountState()
        state.register_sent_order(1, "AAPL", Side.BUY, 100, Decimal("189"))
        state.apply(
            OrderUpdate(token=1, symbol="AAPL", status=OrderStatus.PARTIALLY_FILLED,
                        quantity=100, filled_quantity=40)
        )
        assert state.open_order_count() == 1
        assert state.pending_exposure("AAPL") == 60

    def test_unknown_status_does_not_retire_order(self):
        """An undecodable status must not be mistaken for a terminal one, or we
        forget about a live order."""
        state = AccountState()
        state.register_sent_order(1, "AAPL", Side.BUY, 100, Decimal("189"))
        state.apply(OrderUpdate(token=1, symbol="AAPL", status=OrderStatus.UNKNOWN))
        assert state.open_order_count() == 1

    def test_untracked_order_is_still_tracked(self):
        state = AccountState()
        state.apply(
            OrderUpdate(token=999, order_id="X1", symbol="AAPL", side=Side.BUY,
                        quantity=50, status=OrderStatus.ACCEPTED)
        )
        assert state.open_order_count() == 1

    def test_order_id_indexed_for_updates_without_token(self):
        state = AccountState()
        state.register_sent_order(5, "AAPL", Side.BUY, 100, Decimal("189"))
        state.apply(
            OrderUpdate(token=5, order_id="ID9", symbol="AAPL",
                        status=OrderStatus.ACCEPTED)
        )
        state.apply(
            OrderUpdate(token=0, order_id="ID9", symbol="AAPL",
                        status=OrderStatus.FILLED, filled_quantity=100)
        )
        assert state.open_order_count() == 0


class TestPositionMath:
    def test_buy_opens_long(self):
        state = AccountState()
        state.apply(
            TradeUpdate(symbol="AAPL", side=Side.BUY, quantity=100,
                        price=Decimal("100"))
        )
        position = state.position("AAPL")
        assert position.quantity == 100
        assert position.average_price == Decimal("100")

    def test_adding_blends_average_price(self):
        state = AccountState()
        state.apply(TradeUpdate(symbol="AAPL", side=Side.BUY, quantity=100,
                                price=Decimal("100")))
        state.apply(TradeUpdate(symbol="AAPL", side=Side.BUY, quantity=100,
                                price=Decimal("102")))
        assert state.position("AAPL").average_price == Decimal("101")

    def test_closing_realizes_pnl(self):
        state = AccountState()
        state.apply(TradeUpdate(symbol="AAPL", side=Side.BUY, quantity=100,
                                price=Decimal("100")))
        state.apply(TradeUpdate(symbol="AAPL", side=Side.SELL, quantity=100,
                                price=Decimal("101")))
        position = state.position("AAPL")
        assert position.quantity == 0
        assert position.realized_pnl == Decimal("100")
        assert state.session_realized_pnl == Decimal("100")

    def test_short_then_cover_realizes_pnl(self):
        state = AccountState()
        state.apply(TradeUpdate(symbol="AAPL", side=Side.SELL_SHORT, quantity=100,
                                price=Decimal("100")))
        assert state.position("AAPL").quantity == -100
        state.apply(TradeUpdate(symbol="AAPL", side=Side.BUY_TO_COVER, quantity=100,
                                price=Decimal("99")))
        assert state.position("AAPL").realized_pnl == Decimal("100")

    def test_partial_close(self):
        state = AccountState()
        state.apply(TradeUpdate(symbol="AAPL", side=Side.BUY, quantity=100,
                                price=Decimal("100")))
        state.apply(TradeUpdate(symbol="AAPL", side=Side.SELL, quantity=40,
                                price=Decimal("101")))
        position = state.position("AAPL")
        assert position.quantity == 60
        assert position.realized_pnl == Decimal("40")

    def test_flip_through_zero_resets_basis(self):
        state = AccountState()
        state.apply(TradeUpdate(symbol="AAPL", side=Side.BUY, quantity=100,
                                price=Decimal("100")))
        state.apply(TradeUpdate(symbol="AAPL", side=Side.SELL_SHORT, quantity=150,
                                price=Decimal("101")))
        position = state.position("AAPL")
        assert position.quantity == -50
        assert position.average_price == Decimal("101")
        assert position.realized_pnl == Decimal("100")

    def test_broker_position_overrides_local_estimate(self):
        """DAS is authoritative — our fill-derived estimate is a stopgap."""
        state = AccountState()
        state.apply(TradeUpdate(symbol="AAPL", side=Side.BUY, quantity=100,
                                price=Decimal("100")))
        state.apply(PositionUpdate(symbol="AAPL", quantity=75,
                                   average_price=Decimal("100.25")))
        assert state.position("AAPL").quantity == 75


class TestQuotesAndPnl:
    def test_quote_marks_unrealized_pnl(self):
        state = AccountState()
        state.apply(PositionUpdate(symbol="AAPL", quantity=100,
                                   average_price=Decimal("100")))
        state.apply(Quote(symbol="AAPL", bid=Decimal("101"), ask=Decimal("101.02")))
        # marked at the mid (101.01), so (101.01 - 100) * 100
        assert state.position("AAPL").unrealized_pnl == Decimal("101.00")

    def test_total_pnl_combines_realized_and_unrealized(self):
        state = AccountState()
        state.session_realized_pnl = Decimal("50")
        state.apply(PositionUpdate(symbol="AAPL", quantity=100,
                                   average_price=Decimal("100"),
                                   unrealized_pnl=Decimal("25")))
        assert state.total_pnl() == Decimal("75")

    def test_buying_power_recorded(self):
        state = AccountState()
        state.apply(BuyingPowerUpdate(buying_power=Decimal("25000")))
        assert state.buying_power == Decimal("25000")


class TestStaleness:
    def test_starts_stale_until_first_position_push(self):
        state = AccountState()
        assert state.positions_are_stale
        state.apply(PositionUpdate(symbol="AAPL", quantity=0))
        assert not state.positions_are_stale

    def test_disconnect_marks_stale_again(self):
        state = AccountState()
        state.apply(PositionUpdate(symbol="AAPL", quantity=0))
        state.mark_positions_stale()
        assert state.positions_are_stale
