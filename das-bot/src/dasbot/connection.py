"""Async TCP transport for the DAS CMD API.

Responsibilities, and nothing else:
  * hold one long-lived socket open
  * log in, and re-login on reconnect
  * frame the byte stream into lines and hand them to a callback
  * heartbeat, and detect a socket that has gone quiet without closing
  * reconnect with exponential backoff, restoring subscriptions

Half-open sockets are the failure mode that matters here. A TCP connection to a
broker can stop delivering data without ever raising: `recv` simply blocks
forever. Without the heartbeat watchdog below, the bot sits there looking
healthy while holding positions it can no longer manage.
"""

from __future__ import annotations

import asyncio
import contextlib
import logging
import random
from collections.abc import Awaitable, Callable
from datetime import UTC, datetime

from . import protocol
from .config import ConnectionConfig
from .events import Event, Heartbeat, LoginResult

log = logging.getLogger(__name__)
wire_log = logging.getLogger("dasbot.wire")

EventHandler = Callable[[Event], Awaitable[None] | None]


class ConnectionError_(Exception):
    """Transport-level failure (connect, login, or write)."""


class DasConnection:
    def __init__(
        self,
        config: ConnectionConfig,
        on_event: EventHandler,
        on_reconnect: Callable[[], Awaitable[None]] | None = None,
    ) -> None:
        self.config = config
        self._on_event = on_event
        self._on_reconnect = on_reconnect

        self._reader: asyncio.StreamReader | None = None
        self._writer: asyncio.StreamWriter | None = None
        self._write_lock = asyncio.Lock()

        self._reader_task: asyncio.Task[None] | None = None
        self._heartbeat_task: asyncio.Task[None] | None = None

        self._running = False
        self._logged_in = asyncio.Event()
        self._login_failed_reason: str | None = None
        self._last_inbound: datetime = datetime.now(UTC)
        self._subscriptions: set[str] = set()
        self.reconnect_count = 0

    # -- lifecycle -------------------------------------------------------

    @property
    def connected(self) -> bool:
        return (
            self._writer is not None
            and not self._writer.is_closing()
            and self._logged_in.is_set()
        )

    async def start(self) -> None:
        """Connect, log in, and keep the connection up until `stop`."""
        self._running = True
        await self._connect_with_retry()

    async def stop(self) -> None:
        self._running = False
        for task in (self._heartbeat_task, self._reader_task):
            if task is not None:
                task.cancel()
                with contextlib.suppress(asyncio.CancelledError):
                    await task
        await self._close_socket(send_logout=True)
        log.info("connection stopped")

    async def _close_socket(self, *, send_logout: bool = False) -> None:
        writer = self._writer
        self._logged_in.clear()
        if writer is None:
            return
        try:
            if send_logout and not writer.is_closing():
                writer.write(protocol.encode(protocol.logout()))
                await asyncio.wait_for(writer.drain(), timeout=2.0)
        except (TimeoutError, OSError, ConnectionResetError):
            pass  # already gone; nothing useful to do
        finally:
            with contextlib.suppress(OSError):
                writer.close()
            with contextlib.suppress(
                OSError, asyncio.TimeoutError, ConnectionResetError
            ):
                await asyncio.wait_for(writer.wait_closed(), timeout=5.0)
            self._writer = None
            self._reader = None

    # -- connect / reconnect ---------------------------------------------

    async def _connect_with_retry(self) -> None:
        delay = self.config.reconnect_initial_delay
        attempt = 0

        while self._running:
            attempt += 1
            try:
                await self._connect_once()
                log.info("connected and authenticated: %s", self.config.redacted())
                return
            except asyncio.CancelledError:
                raise
            except Exception as exc:  # noqa: BLE001 - retry on anything
                limit = self.config.max_reconnect_attempts
                if limit and attempt >= limit:
                    raise ConnectionError_(
                        f"giving up after {attempt} connection attempts: {exc}"
                    ) from exc

                # Jitter matters: without it, a broker-side restart brings every
                # reconnecting client back in lockstep and they collide again.
                sleep_for = min(delay, self.config.reconnect_max_delay)
                sleep_for *= 0.5 + random.random()
                log.warning(
                    "connection attempt %d failed (%s); retrying in %.1fs",
                    attempt,
                    exc,
                    sleep_for,
                )
                await asyncio.sleep(sleep_for)
                delay = min(delay * 2, self.config.reconnect_max_delay)

    async def _connect_once(self) -> None:
        await self._close_socket()
        self._login_failed_reason = None
        self._logged_in.clear()

        log.info("connecting to %s:%s", self.config.host, self.config.port)
        self._reader, self._writer = await asyncio.wait_for(
            asyncio.open_connection(self.config.host, self.config.port),
            timeout=self.config.connect_timeout,
        )
        self._last_inbound = datetime.now(UTC)

        # Reader must be running before login: the login response arrives on the
        # same stream as everything else, and there is no request/response pairing.
        self._reader_task = asyncio.create_task(
            self._read_loop(), name="dasbot-reader"
        )

        await self._send_raw(protocol.client_identify("dasbot", "0.1.0"))
        await self._send_raw(
            protocol.login(
                self.config.username, self.config.password, self.config.account
            )
        )

        try:
            await asyncio.wait_for(
                self._logged_in.wait(), timeout=self.config.connect_timeout
            )
        except TimeoutError as exc:
            raise ConnectionError_("login timed out") from exc

        if self._login_failed_reason:
            # Bad credentials will not fix themselves; do not hammer the broker.
            raise ConnectionError_(f"login rejected: {self._login_failed_reason}")

        await self._send_raw(protocol.subscribe_orders())

        if self._heartbeat_task is None or self._heartbeat_task.done():
            self._heartbeat_task = asyncio.create_task(
                self._heartbeat_loop(), name="dasbot-heartbeat"
            )

        if self._on_reconnect is not None:
            await self._on_reconnect()

        for symbol in sorted(self._subscriptions):
            await self._send_raw(protocol.subscribe_quote(symbol))

    async def _handle_disconnect(self, reason: str) -> None:
        if not self._running:
            return
        self.reconnect_count += 1
        log.error("disconnected (%s); reconnecting", reason)
        self._logged_in.clear()
        await self._close_socket()
        await self._connect_with_retry()

    # -- io ---------------------------------------------------------------

    async def _read_loop(self) -> None:
        reader = self._reader
        if reader is None:
            return
        try:
            while self._running:
                line_bytes = await reader.readline()
                if not line_bytes:
                    raise ConnectionResetError("peer closed the connection")

                self._last_inbound = datetime.now(UTC)
                line = line_bytes.decode(protocol.ENCODING, errors="replace").strip()
                if not line:
                    continue

                wire_log.debug("<< %s", line)
                event = protocol.parse_line(line)
                if event is None:
                    continue

                if isinstance(event, LoginResult):
                    if event.success:
                        self._logged_in.set()
                    else:
                        self._login_failed_reason = event.message
                        self._logged_in.set()  # unblock the waiter to report it
                    continue
                if isinstance(event, Heartbeat):
                    continue

                await self._dispatch(event)
        except asyncio.CancelledError:
            raise
        except Exception as exc:  # noqa: BLE001
            # Reconnect on its own task: this one is about to die.
            asyncio.create_task(self._handle_disconnect(f"read error: {exc}"))

    async def _dispatch(self, event: Event) -> None:
        """Deliver an event to the application.

        A strategy exception must never take down the socket — log it and keep
        reading. Losing the connection is strictly worse than losing one event.
        """
        try:
            result = self._on_event(event)
            if asyncio.iscoroutine(result):
                await result
        except Exception:  # noqa: BLE001
            log.exception("event handler raised on %r", event)

    async def _heartbeat_loop(self) -> None:
        try:
            while self._running:
                await asyncio.sleep(self.config.heartbeat_interval)
                if not self.connected:
                    continue

                silence = (
                    datetime.now(UTC) - self._last_inbound
                ).total_seconds()
                if silence > self.config.heartbeat_timeout:
                    # Half-open socket: writes succeed, nothing comes back.
                    asyncio.create_task(
                        self._handle_disconnect(f"no data for {silence:.0f}s")
                    )
                    return

                try:
                    await self._send_raw(protocol.echo(True))
                except Exception as exc:  # noqa: BLE001
                    asyncio.create_task(
                        self._handle_disconnect(f"heartbeat write failed: {exc}")
                    )
                    return
        except asyncio.CancelledError:
            raise

    async def _send_raw(self, command: str) -> None:
        writer = self._writer
        if writer is None or writer.is_closing():
            raise ConnectionError_("socket is not open")
        async with self._write_lock:
            redacted = (
                command.split()[0] + " ***"
                if command.upper().startswith("LOGIN")
                else command
            )
            wire_log.debug(">> %s", redacted)
            writer.write(protocol.encode(command))
            await writer.drain()

    async def send(self, command: str) -> None:
        """Send a command, requiring an authenticated session."""
        if not self.connected:
            raise ConnectionError_("not connected/authenticated")
        await self._send_raw(command)

    # -- subscriptions ----------------------------------------------------

    async def subscribe(self, symbol: str) -> None:
        symbol = symbol.upper()
        self._subscriptions.add(symbol)
        if self.connected:
            await self._send_raw(protocol.subscribe_quote(symbol))

    async def unsubscribe(self, symbol: str) -> None:
        symbol = symbol.upper()
        self._subscriptions.discard(symbol)
        if self.connected:
            await self._send_raw(protocol.unsubscribe_quote(symbol))

    @property
    def subscriptions(self) -> frozenset[str]:
        return frozenset(self._subscriptions)
