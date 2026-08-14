"""DAS CMD API wire protocol: command construction and response decoding.

    ┌──────────────────────────────────────────────────────────────────┐
    │  THIS IS THE ONLY SPEC-DEPENDENT MODULE.                         │
    │                                                                  │
    │  Field orders below are encoded as data (MESSAGE_LAYOUTS), not   │
    │  as parsing logic, because the exact layouts vary between DAS    │
    │  releases. To match your broker's spec you edit a tuple here —   │
    │  no other module changes. Everything downstream consumes typed   │
    │  events from `events.py`.                                        │
    │                                                                  │
    │  Every layout is marked VERIFIED or UNVERIFIED. Do not trade     │
    │  real size while order-path layouts are still UNVERIFIED.        │
    └──────────────────────────────────────────────────────────────────┘

The protocol is line-oriented ASCII over a raw TCP socket. Commands go out
newline-terminated; responses arrive asynchronously on the same socket and are
not request/response paired — an order ack may be interleaved with quotes.
"""

from __future__ import annotations

import itertools
import shlex
from collections.abc import Callable, Iterator
from dataclasses import dataclass
from decimal import Decimal, InvalidOperation
from typing import Any

from .events import (
    BuyingPowerUpdate,
    ErrorMessage,
    Event,
    Heartbeat,
    LoginResult,
    OrderStatus,
    OrderUpdate,
    PositionUpdate,
    Quote,
    ShortInfo,
    Side,
    TradeUpdate,
    Unknown,
)

LINE_TERMINATOR = "\r\n"
ENCODING = "ascii"


# ---------------------------------------------------------------------------
# Tolerant converters
#
# A malformed field must not abort the whole line: we still want the symbol and
# status off an order message even if one numeric field is junk. Each converter
# falls back to a zero value rather than raising.
# ---------------------------------------------------------------------------


def to_int(value: str) -> int:
    try:
        return int(Decimal(value.strip()))
    except (InvalidOperation, ValueError, AttributeError):
        return 0


def to_decimal(value: str) -> Decimal:
    try:
        return Decimal(value.strip())
    except (InvalidOperation, ValueError, AttributeError):
        return Decimal(0)


def to_str(value: str) -> str:
    return value.strip()


def to_upper(value: str) -> str:
    return value.strip().upper()


def to_bool(value: str) -> bool:
    return value.strip().upper() in ("1", "T", "TRUE", "Y", "YES", "SHORTABLE")


def to_side(value: str) -> Side | None:
    token = value.strip().upper()
    aliases = {
        "B": Side.BUY,
        "BUY": Side.BUY,
        "S": Side.SELL,
        "SELL": Side.SELL,
        "SS": Side.SELL_SHORT,
        "SHORT": Side.SELL_SHORT,
        "SELLSHORT": Side.SELL_SHORT,
        "BC": Side.BUY_TO_COVER,
        "COVER": Side.BUY_TO_COVER,
        "BUYTOCOVER": Side.BUY_TO_COVER,
    }
    return aliases.get(token)


# DAS status vocabulary -> our normalised lifecycle. Unmapped tokens become
# UNKNOWN, which `state.py` treats as "still working" so we never assume an
# order died and double-send.
STATUS_ALIASES: dict[str, OrderStatus] = {
    "PENDING": OrderStatus.PENDING,
    "SENDING": OrderStatus.PENDING,
    "NEW": OrderStatus.ACCEPTED,
    "ACCEPTED": OrderStatus.ACCEPTED,
    "OPEN": OrderStatus.ACCEPTED,
    "LIVE": OrderStatus.ACCEPTED,
    "WORKING": OrderStatus.ACCEPTED,
    "PARTIAL": OrderStatus.PARTIALLY_FILLED,
    "PARTIALLYFILLED": OrderStatus.PARTIALLY_FILLED,
    "PARTIALLY_FILLED": OrderStatus.PARTIALLY_FILLED,
    "FILLED": OrderStatus.FILLED,
    "EXECUTED": OrderStatus.FILLED,
    "COMPLETE": OrderStatus.FILLED,
    "CANCELED": OrderStatus.CANCELLED,
    "CANCELLED": OrderStatus.CANCELLED,
    "CANCELLING": OrderStatus.ACCEPTED,
    "REJECTED": OrderStatus.REJECTED,
    "REJECT": OrderStatus.REJECTED,
    "ERROR": OrderStatus.REJECTED,
}


def to_status(value: str) -> OrderStatus:
    return STATUS_ALIASES.get(
        value.strip().upper().replace("-", "_"), OrderStatus.UNKNOWN
    )


# ---------------------------------------------------------------------------
# Layout table
# ---------------------------------------------------------------------------


@dataclass(frozen=True, slots=True)
class FieldSpec:
    name: str
    convert: Callable[[str], Any]


@dataclass(frozen=True, slots=True)
class MessageLayout:
    """How to turn one line into one event.

    positional : fields read in order after the prefix token.
    key_values : if True, any remaining `KEY=VALUE` tokens are also harvested
                 and matched against `positional` names case-insensitively.
                 Newer DAS builds append key/value pairs to several messages,
                 so supporting both shapes at once absorbs that variation.
    verified   : has this layout been checked against the broker's PDF?
    """

    prefix: str
    event_type: type[Event]
    positional: tuple[FieldSpec, ...] = ()
    key_values: bool = True
    verified: bool = False
    note: str = ""


# NOTE: layouts below follow the commonly documented DAS CMD API shapes and are
# marked UNVERIFIED until checked against the broker-supplied specification.
# `scripts/verify_layouts.py` diffs live captures against this table.
MESSAGE_LAYOUTS: tuple[MessageLayout, ...] = (
    MessageLayout(
        prefix="$QUOTE",
        event_type=Quote,
        positional=(
            FieldSpec("symbol", to_upper),
            FieldSpec("bid", to_decimal),
            FieldSpec("ask", to_decimal),
            FieldSpec("last", to_decimal),
            FieldSpec("bid_size", to_int),
            FieldSpec("ask_size", to_int),
            FieldSpec("volume", to_int),
        ),
        note="UNVERIFIED: confirm field order and whether sizes are in lots.",
    ),
    MessageLayout(
        prefix="%ORDER",
        event_type=OrderUpdate,
        positional=(
            FieldSpec("order_id", to_str),
            FieldSpec("token", to_int),
            FieldSpec("symbol", to_upper),
            FieldSpec("side", to_side),
            FieldSpec("quantity", to_int),
            FieldSpec("price", to_decimal),
            FieldSpec("status", to_status),
            FieldSpec("filled_quantity", to_int),
            FieldSpec("leaves_quantity", to_int),
            FieldSpec("average_fill_price", to_decimal),
            FieldSpec("route", to_upper),
        ),
        note="UNVERIFIED: critical order path — verify before live trading.",
    ),
    MessageLayout(
        prefix="%TRADE",
        event_type=TradeUpdate,
        positional=(
            FieldSpec("order_id", to_str),
            FieldSpec("token", to_int),
            FieldSpec("symbol", to_upper),
            FieldSpec("side", to_side),
            FieldSpec("quantity", to_int),
            FieldSpec("price", to_decimal),
            FieldSpec("exec_id", to_str),
        ),
        note="UNVERIFIED: critical order path — verify before live trading.",
    ),
    MessageLayout(
        prefix="%POS",
        event_type=PositionUpdate,
        positional=(
            FieldSpec("symbol", to_upper),
            FieldSpec("quantity", to_int),
            FieldSpec("average_price", to_decimal),
            FieldSpec("realized_pnl", to_decimal),
            FieldSpec("unrealized_pnl", to_decimal),
        ),
        note="UNVERIFIED: confirm short positions arrive as negative quantity.",
    ),
    MessageLayout(
        prefix="%BP",
        event_type=BuyingPowerUpdate,
        positional=(
            FieldSpec("buying_power", to_decimal),
            FieldSpec("day_trading_buying_power", to_decimal),
            FieldSpec("cash", to_decimal),
        ),
        note="UNVERIFIED.",
    ),
    MessageLayout(
        prefix="$SHORTINFO",
        event_type=ShortInfo,
        positional=(
            FieldSpec("symbol", to_upper),
            FieldSpec("shortable", to_bool),
            FieldSpec("available_shares", to_int),
        ),
        note="UNVERIFIED.",
    ),
)

_LAYOUTS_BY_PREFIX: dict[str, MessageLayout] = {
    layout.prefix: layout for layout in MESSAGE_LAYOUTS
}

# Prefixes handled by bespoke logic rather than the table.
LOGIN_OK_TOKENS = ("#LOGIN SUCCESSED", "#LOGIN SUCCEEDED", "#LOGIN SUCCESS")
LOGIN_FAIL_TOKENS = ("#LOGIN FAILED", "#LOGIN FAILURE", "#LOGIN INVALID")


def unverified_layouts() -> tuple[MessageLayout, ...]:
    """Layouts not yet checked against the spec — surfaced at startup."""
    return tuple(layout for layout in MESSAGE_LAYOUTS if not layout.verified)


# ---------------------------------------------------------------------------
# Parsing
# ---------------------------------------------------------------------------


def _split_line(line: str) -> list[str]:
    """Tokenise a wire line, honouring quoted fields.

    Reject reasons come back as free text that can contain spaces and, when
    quoted, embedded punctuation. shlex keeps those intact; if the line has
    unbalanced quotes shlex raises, so fall back to a plain split.
    """
    try:
        return shlex.split(line, posix=False)
    except ValueError:
        return line.split()


def _harvest_key_values(tokens: list[str]) -> dict[str, str]:
    out: dict[str, str] = {}
    for token in tokens:
        if "=" in token:
            key, _, value = token.partition("=")
            out[key.strip().lower()] = value.strip().strip('"')
    return out


def parse_line(line: str) -> Event | None:
    """Decode one wire line into an event.

    Returns None for blank lines. Never raises: an undecodable line becomes
    `Unknown`, because throwing here would kill the reader task mid-session.
    """
    text = line.strip()
    if not text:
        return None

    upper = text.upper()

    # --- bespoke prefixes -------------------------------------------------
    if upper.startswith(LOGIN_OK_TOKENS):
        return LoginResult(success=True, message=text, raw=text)
    if upper.startswith(LOGIN_FAIL_TOKENS):
        return LoginResult(success=False, message=text, raw=text)
    if upper.startswith("#ECHO") or upper == "#HEARTBEAT":
        return Heartbeat(raw=text)
    if upper.startswith(("#ERROR", "$ERROR", "%ERROR")):
        remainder = text.split(None, 1)
        return ErrorMessage(
            message=remainder[1] if len(remainder) > 1 else text,
            command="",
            raw=text,
        )

    tokens = _split_line(text)
    if not tokens:
        return None

    prefix = tokens[0].upper()
    layout = _LAYOUTS_BY_PREFIX.get(prefix)
    if layout is None:
        return Unknown(prefix=prefix, fields=tuple(tokens[1:]), raw=text)

    body = tokens[1:]
    values: dict[str, Any] = {}

    # Positional fields first. A short line simply leaves later fields at their
    # dataclass defaults rather than erroring — DAS omits trailing optional
    # fields in some builds.
    positional_tokens = [tok for tok in body if "=" not in tok]
    # strict=False is deliberate: a short line must leave later fields at their
    # defaults rather than raise. DAS omits trailing optional fields.
    for spec, token in zip(layout.positional, positional_tokens, strict=False):
        values[spec.name] = spec.convert(token)

    # Then key/value overrides, which win when both are present.
    if layout.key_values:
        by_name = {spec.name: spec for spec in layout.positional}
        for key, raw_value in _harvest_key_values(body).items():
            spec = by_name.get(key)
            if spec is not None:
                values[spec.name] = spec.convert(raw_value)

    try:
        return layout.event_type(**values, raw=text)  # type: ignore[arg-type]
    except TypeError:
        # Layout and event dataclass disagree — a coding error, but not worth
        # killing a live session over. Surface it as Unknown for the logs.
        return Unknown(
            prefix=prefix,
            fields=tuple(body),
            extra={"error": "layout/event field mismatch"},
            raw=text,
        )


def parse_stream(chunk: str) -> Iterator[Event]:
    """Decode every complete line in a chunk of already-buffered text."""
    for line in chunk.splitlines():
        event = parse_line(line)
        if event is not None:
            yield event


# ---------------------------------------------------------------------------
# Command construction
# ---------------------------------------------------------------------------


class TokenGenerator:
    """Monotonic client order ids.

    Seeded above any token that could still be alive from an earlier run in the
    same session window, so a reconnect cannot collide with a working order.
    """

    def __init__(self, start: int = 1) -> None:
        self._counter = itertools.count(start)

    def next(self) -> int:
        return next(self._counter)


def encode(command: str) -> bytes:
    return (command + LINE_TERMINATOR).encode(ENCODING, errors="replace")


def login(username: str, password: str, account: str) -> str:
    return f"LOGIN {username} {password} {account}"


def client_identify(name: str, version: str) -> str:
    return f"CLIENT {name}/{version}"


def echo(on: bool = True) -> str:
    return f"ECHO {'ON' if on else 'OFF'}"


def subscribe_quote(symbol: str, level: str = "Lv1") -> str:
    return f"SB {symbol.upper()} {level}"


def unsubscribe_quote(symbol: str, level: str = "Lv1") -> str:
    return f"UNSB {symbol.upper()} {level}"


def subscribe_orders() -> str:
    """Ask DAS to push order/trade/position updates on this socket."""
    return "SUBSCRIBE ORDER"


def position_refresh() -> str:
    return "POSREFRESH"


def buying_power() -> str:
    return "GET BP"


def short_info(symbol: str) -> str:
    return f"SHORTINFO {symbol.upper()}"


def new_order(
    token: int,
    side: Side,
    symbol: str,
    quantity: int,
    route: str,
    price: Decimal | None = None,
    order_type: str = "LIMIT",
    time_in_force: str = "DAY",
    display_quantity: int | None = None,
) -> str:
    """Build a NEWORDER command.

    A market order carries no price. We refuse to synthesise one — sending a
    limit priced at zero would be filled at any price, which is the single most
    expensive mistake available here.
    """
    normalised = order_type.upper()
    if normalised in ("LIMIT", "LMT") and (price is None or price <= 0):
        raise ValueError(f"limit order for {symbol} requires a positive price")
    if quantity <= 0:
        raise ValueError(f"order quantity must be positive, got {quantity}")

    parts = [
        "NEWORDER",
        str(token),
        side.value,
        symbol.upper(),
        route.upper(),
        str(quantity),
    ]
    if normalised in ("MARKET", "MKT"):
        parts.append("MKT")
    else:
        parts.append(f"{price:.4f}".rstrip("0").rstrip("."))

    parts.append(f"TIF={time_in_force.upper()}")
    if display_quantity is not None:
        parts.append(f"Display={display_quantity}")
    return " ".join(parts)


def cancel_order(order_ref: str | int) -> str:
    return f"CANCEL {order_ref}"


def cancel_all() -> str:
    return "CANCEL ALL"


def logout() -> str:
    return "QUIT"
