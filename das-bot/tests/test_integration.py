"""End-to-end tests over a real socket against the in-process mock server.

These cover the wiring — connect, login, subscribe, order, fill, position — and
prove the bot is internally consistent. They do NOT prove the wire format
matches your broker: the mock speaks whatever `protocol.py` currently says.
Only the spec and a live capture settle that.
"""

from __future__ import annotations

import asyncio
import importlib.util
import sys
from decimal import Decimal
from pathlib import Path

import pytest

from dasbot.client import DasClient
from dasbot.config import AppConfig, ConnectionConfig, RiskConfig
from dasbot.events import Side

# The mock server lives in scripts/, which is not a package.
_MOCK_PATH = Path(__file__).parent.parent / "scripts" / "mock_das_server.py"
_spec = importlib.util.spec_from_file_location("mock_das_server", _MOCK_PATH)
assert _spec and _spec.loader
mock_das_server = importlib.util.module_from_spec(_spec)
sys.modules["mock_das_server"] = mock_das_server
_spec.loader.exec_module(mock_das_server)


@pytest.fixture
async def mock_server():
    async def on_client(reader, writer):
        await mock_das_server.MockSession(reader, writer).handle()

    server = await asyncio.start_server(on_client, "127.0.0.1", 0)
    port = server.sockets[0].getsockname()[1]
    async with server:
        yield port
        server.close()


def make_config(port: int, *, dry_run: bool = False, **risk_overrides) -> AppConfig:
    risk_params = {
        "max_shares_per_order": 1000,
        "max_position_shares": 5000,
        "max_notional_per_order": Decimal("1000000"),
        "max_open_orders": 10,
        "max_daily_loss": Decimal("100000"),
        "max_orders_per_minute": 100,
        # Tests must not depend on the wall clock or the calendar.
        "require_market_hours": False,
    }
    risk_params.update(risk_overrides)
    return AppConfig(
        connection=ConnectionConfig(
            host="127.0.0.1",
            port=port,
            username="testuser",
            password="testpass",
            account="TESTACC",
            watchlist=("AAPL",),
            connect_timeout=5.0,
            heartbeat_interval=1.0,
        ),
        risk=RiskConfig(**risk_params),
        dry_run=dry_run,
    )


async def wait_for(predicate, timeout: float = 5.0, interval: float = 0.02) -> bool:
    """Poll until predicate() is true. Beats fixed sleeps for async assertions."""
    loop = asyncio.get_running_loop()
    deadline = loop.time() + timeout
    while loop.time() < deadline:
        if predicate():
            return True
        await asyncio.sleep(interval)
    return False


class TestConnection:
    async def test_connects_and_authenticates(self, mock_server):
        client = DasClient(make_config(mock_server))
        await client.connection.start()
        try:
            assert client.connection.connected
        finally:
            await client.connection.stop()

    async def test_bad_credentials_rejected(self, mock_server):
        config = make_config(mock_server)
        bad = ConnectionConfig(
            **{
                **{
                    f.name: getattr(config.connection, f.name)
                    for f in config.connection.__dataclass_fields__.values()
                },
                "username": "baduser",
                "max_reconnect_attempts": 1,
            }
        )
        client = DasClient(
            AppConfig(connection=bad, risk=config.risk, dry_run=True)
        )
        with pytest.raises(Exception, match="login rejected"):
            await client.connection.start()

    async def test_receives_quotes(self, mock_server):
        client = DasClient(make_config(mock_server))
        await client.connection.start()
        try:
            await client.subscribe("AAPL")
            got = await wait_for(lambda: client.state.quote("AAPL") is not None)
            assert got, "no quote arrived within timeout"
            quote = client.state.quote("AAPL")
            assert quote.bid > 0 and quote.ask > quote.bid
        finally:
            await client.connection.stop()

    async def test_position_refresh_clears_stale_flag(self, mock_server):
        client = DasClient(make_config(mock_server))
        await client.connection.start()
        try:
            assert await wait_for(lambda: not client.state.positions_are_stale)
        finally:
            await client.connection.stop()


class TestOrderFlow:
    async def test_order_fills_and_updates_position(self, mock_server):
        client = DasClient(make_config(mock_server))
        await client.connection.start()
        try:
            await wait_for(lambda: not client.state.positions_are_stale)

            sent, decision, token = await client.place_order(
                symbol="AAPL", side=Side.BUY, quantity=100, price=Decimal("150.00")
            )
            assert sent, decision.reason_text
            assert token is not None

            filled = await wait_for(
                lambda: client.state.position("AAPL").quantity == 100
            )
            assert filled, f"position never reached 100: {client.state.summary()}"
            assert await wait_for(lambda: client.state.open_order_count() == 0)
        finally:
            await client.connection.stop()

    async def test_partial_fill_tracked_before_completion(self, mock_server):
        client = DasClient(make_config(mock_server))
        await client.connection.start()
        try:
            await wait_for(lambda: not client.state.positions_are_stale)
            await client.place_order(
                symbol="AAPL", side=Side.BUY, quantity=100, price=Decimal("150.00")
            )
            # The mock fills in halves; catch the intermediate state.
            saw_partial = await wait_for(
                lambda: 0 < client.state.position("AAPL").quantity < 100
            )
            assert saw_partial
        finally:
            await client.connection.stop()

    async def test_dry_run_sends_nothing(self, mock_server):
        client = DasClient(make_config(mock_server, dry_run=True))
        await client.connection.start()
        try:
            await wait_for(lambda: not client.state.positions_are_stale)
            sent, decision, token = await client.place_order(
                symbol="AAPL", side=Side.BUY, quantity=100, price=Decimal("150.00")
            )
            assert not sent
            assert decision.allowed  # allowed by risk, withheld by dry-run
            await asyncio.sleep(0.5)
            assert client.state.position("AAPL").is_flat
            assert client.state.open_order_count() == 0
        finally:
            await client.connection.stop()

    async def test_risk_denial_never_reaches_the_wire(self, mock_server):
        client = DasClient(make_config(mock_server, max_shares_per_order=10))
        await client.connection.start()
        try:
            await wait_for(lambda: not client.state.positions_are_stale)
            sent, decision, token = await client.place_order(
                symbol="AAPL", side=Side.BUY, quantity=500, price=Decimal("150.00")
            )
            assert not sent
            assert not decision.allowed
            assert token is None
            await asyncio.sleep(0.5)
            assert client.state.position("AAPL").is_flat
        finally:
            await client.connection.stop()

    async def test_kill_switch_blocks_subsequent_orders(self, mock_server):
        client = DasClient(make_config(mock_server))
        await client.connection.start()
        try:
            await wait_for(lambda: not client.state.positions_are_stale)
            client.kill_switch.trip("test halt")
            sent, decision, _ = await client.place_order(
                symbol="AAPL", side=Side.BUY, quantity=10, price=Decimal("150.00")
            )
            assert not sent
            assert "kill switch" in decision.reason_text
        finally:
            await client.connection.stop()


class TestResilience:
    async def test_reconnects_after_server_drop(self, mock_server):
        """The behaviour that matters most in production: the socket dies and
        the bot comes back on its own, with a resynchronised book."""
        client = DasClient(make_config(mock_server))
        await client.connection.start()
        try:
            assert client.connection.connected

            # Kill the socket from underneath the reader.
            writer = client.connection._writer
            assert writer is not None
            writer.close()

            recovered = await wait_for(
                lambda: client.connection.reconnect_count >= 1
                and client.connection.connected,
                timeout=15.0,
            )
            assert recovered, "did not reconnect"
            # And the book was refreshed rather than trusted blindly.
            assert await wait_for(lambda: not client.state.positions_are_stale)
        finally:
            await client.connection.stop()

    async def test_subscriptions_restored_after_reconnect(self, mock_server):
        client = DasClient(make_config(mock_server))
        await client.connection.start()
        try:
            await client.subscribe("MSFT")
            assert "MSFT" in client.connection.subscriptions

            writer = client.connection._writer
            assert writer is not None
            writer.close()

            assert await wait_for(
                lambda: client.connection.reconnect_count >= 1
                and client.connection.connected,
                timeout=15.0,
            )
            assert "MSFT" in client.connection.subscriptions
        finally:
            await client.connection.stop()
