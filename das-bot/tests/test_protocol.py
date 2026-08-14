from decimal import Decimal

import pytest

from dasbot import protocol
from dasbot.events import (
    BuyingPowerUpdate,
    ErrorMessage,
    Heartbeat,
    LoginResult,
    OrderStatus,
    OrderUpdate,
    PositionUpdate,
    Quote,
    Side,
    TradeUpdate,
    Unknown,
)


class TestParsing:
    def test_login_success(self):
        event = protocol.parse_line("#LOGIN SUCCESSED")
        assert isinstance(event, LoginResult)
        assert event.success

    def test_login_failure(self):
        event = protocol.parse_line("#LOGIN FAILED bad password")
        assert isinstance(event, LoginResult)
        assert not event.success
        assert "bad password" in event.message

    def test_heartbeat(self):
        assert isinstance(protocol.parse_line("#Echo ON"), Heartbeat)

    def test_error(self):
        event = protocol.parse_line("#ERROR route not permitted")
        assert isinstance(event, ErrorMessage)
        assert "route not permitted" in event.message

    def test_quote(self):
        event = protocol.parse_line("$QUOTE AAPL 189.50 189.52 189.51 3 5 1200000")
        assert isinstance(event, Quote)
        assert event.symbol == "AAPL"
        assert event.bid == Decimal("189.50")
        assert event.ask == Decimal("189.52")
        assert event.spread == Decimal("0.02")
        assert event.mid == Decimal("189.51")

    def test_order_update(self):
        event = protocol.parse_line(
            "%ORDER 1001 42 AAPL B 100 189.50 Filled 100 0 189.49 SMRTL"
        )
        assert isinstance(event, OrderUpdate)
        assert event.order_id == "1001"
        assert event.token == 42
        assert event.side is Side.BUY
        assert event.status is OrderStatus.FILLED
        assert event.filled_quantity == 100
        assert event.average_fill_price == Decimal("189.49")

    def test_trade_update(self):
        event = protocol.parse_line("%TRADE 1001 42 AAPL B 50 189.50 EXEC1")
        assert isinstance(event, TradeUpdate)
        assert event.quantity == 50
        assert event.price == Decimal("189.50")
        assert event.exec_id == "EXEC1"

    def test_position_short_is_negative(self):
        event = protocol.parse_line("%POS TSLA -200 242.10 -35.00 12.50")
        assert isinstance(event, PositionUpdate)
        assert event.quantity == -200
        assert event.is_short

    def test_buying_power(self):
        event = protocol.parse_line("%BP 25000.00 100000.00 25000.00")
        assert isinstance(event, BuyingPowerUpdate)
        assert event.buying_power == Decimal("25000.00")

    def test_blank_line_is_none(self):
        assert protocol.parse_line("   ") is None


class TestParserRobustness:
    """The parser must never raise. A crash here kills the socket loop while
    orders are live, which is far worse than a misparsed field."""

    @pytest.mark.parametrize(
        "line",
        [
            "%ORDER",                                   # prefix only
            "%ORDER 1001",                              # truncated
            "$QUOTE AAPL notanumber 189.52 x y z w",    # junk numerics
            "%POS AAPL abc def ghi jkl",                # all junk
            "GARBAGE and nonsense",                     # unknown prefix
            '%ORDER 1 2 AAPL B 100 1.0 Rejected 0 0 0 SMRTL "unbalanced',
            "%ORDER 1 2 AAPL ZZZ 100 1.0 Weird 0 0 0 R",  # unknown side+status
            "\x00\xff binary junk",
        ],
    )
    def test_never_raises(self, line):
        event = protocol.parse_line(line)
        assert event is None or hasattr(event, "raw")

    def test_unknown_prefix_preserves_raw(self):
        event = protocol.parse_line("%NEWTHING 1 2 3")
        assert isinstance(event, Unknown)
        assert event.prefix == "%NEWTHING"
        assert event.raw == "%NEWTHING 1 2 3"

    def test_truncated_order_uses_defaults(self):
        event = protocol.parse_line("%ORDER 1001 42 AAPL B")
        assert isinstance(event, OrderUpdate)
        assert event.symbol == "AAPL"
        assert event.quantity == 0

    def test_unknown_status_is_unknown_not_terminal(self):
        event = protocol.parse_line(
            "%ORDER 1 2 AAPL B 100 1.00 SomethingNew 0 100 0 SMRTL"
        )
        assert event.status is OrderStatus.UNKNOWN
        assert not event.status.is_terminal

    def test_junk_numeric_falls_back_to_zero(self):
        event = protocol.parse_line("$QUOTE AAPL bad 189.52 189.51 3 5 100")
        assert event.bid == Decimal(0)
        assert event.ask == Decimal("189.52")

    def test_key_value_overrides_positional(self):
        event = protocol.parse_line(
            "%ORDER 1001 42 AAPL B 100 189.50 Accepted 0 100 0 SMRTL "
            "filled_quantity=25"
        )
        assert event.filled_quantity == 25


class TestCommands:
    def test_login_command(self):
        assert protocol.login("u", "p", "ACC") == "LOGIN u p ACC"

    def test_limit_order(self):
        command = protocol.new_order(
            token=7, side=Side.BUY, symbol="aapl", quantity=100,
            route="SMRTL", price=Decimal("189.50"),
        )
        assert command.startswith("NEWORDER 7 B AAPL SMRTL 100 189.5")
        assert "TIF=DAY" in command

    def test_market_order_has_no_price(self):
        command = protocol.new_order(
            token=8, side=Side.SELL, symbol="AAPL", quantity=10,
            route="SMRTL", order_type="MARKET",
        )
        assert " MKT " in command

    def test_limit_order_without_price_rejected(self):
        """A limit order priced at zero fills at any price. Never synthesise one."""
        with pytest.raises(ValueError, match="positive price"):
            protocol.new_order(
                token=9, side=Side.BUY, symbol="AAPL", quantity=100, route="SMRTL",
            )

    def test_zero_quantity_rejected(self):
        with pytest.raises(ValueError, match="quantity must be positive"):
            protocol.new_order(
                token=10, side=Side.BUY, symbol="AAPL", quantity=0,
                route="SMRTL", price=Decimal("1"),
            )

    def test_tokens_are_unique_and_monotonic(self):
        gen = protocol.TokenGenerator()
        tokens = [gen.next() for _ in range(100)]
        assert len(set(tokens)) == 100
        assert tokens == sorted(tokens)

    def test_encode_terminates_line(self):
        assert protocol.encode("PING") == b"PING\r\n"


def test_layout_names_match_event_fields():
    """Guards the table against typos: every FieldSpec name must be a real
    attribute of its event dataclass, or parsing silently drops the field."""
    for layout in protocol.MESSAGE_LAYOUTS:
        valid = set(layout.event_type.__dataclass_fields__)
        for spec in layout.positional:
            assert spec.name in valid, (
                f"{layout.prefix}: field {spec.name!r} is not on "
                f"{layout.event_type.__name__}"
            )
