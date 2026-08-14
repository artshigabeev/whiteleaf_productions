"""A fake DAS CMD API server for local testing.

Speaks the layouts currently encoded in `protocol.py`, so it lets you exercise
the full path — connect, login, subscribe, order, fill, reconnect — without a
broker connection, without API fees, and without the market being open.

    python scripts/mock_das_server.py --port 9910
    DAS_HOST=127.0.0.1 DAS_PORT=9910 python -m dasbot --strategy observer

Important: this server is only as correct as `protocol.py`. Passing tests
against it prove the bot is internally consistent — NOT that the wire format
matches your broker. Only the real spec and a live capture prove that.
"""

from __future__ import annotations

import argparse
import asyncio
import contextlib
import logging
import random
from decimal import Decimal

logging.basicConfig(
    level=logging.INFO, format="%(asctime)s %(levelname)-7s [mock] %(message)s"
)
log = logging.getLogger("mock_das")


class MockSession:
    def __init__(self, reader: asyncio.StreamReader, writer: asyncio.StreamWriter):
        self.reader = reader
        self.writer = writer
        self.logged_in = False
        self.subscriptions: set[str] = set()
        self.prices: dict[str, Decimal] = {}
        self.next_order_id = 1000
        self._tasks: list[asyncio.Task[None]] = []

    async def send(self, line: str) -> None:
        self.writer.write((line + "\r\n").encode("ascii", errors="replace"))
        await self.writer.drain()
        log.debug(">> %s", line)

    async def handle(self) -> None:
        peer = self.writer.get_extra_info("peername")
        log.info("client connected: %s", peer)
        try:
            while True:
                raw = await self.reader.readline()
                if not raw:
                    break
                line = raw.decode("ascii", errors="replace").strip()
                if line:
                    log.info("<< %s", line)
                    await self.dispatch(line)
        except (ConnectionResetError, BrokenPipeError):
            pass
        finally:
            for task in self._tasks:
                task.cancel()
            log.info("client disconnected: %s", peer)
            self.writer.close()

    async def dispatch(self, line: str) -> None:
        parts = line.split()
        if not parts:
            return
        command = parts[0].upper()

        if command == "LOGIN":
            # Any non-empty credentials succeed. Use the username "baduser" to
            # exercise the login-failure path.
            if len(parts) >= 2 and parts[1].lower() == "baduser":
                await self.send("#LOGIN FAILED invalid credentials")
            else:
                self.logged_in = True
                await self.send("#LOGIN SUCCESSED")
        elif command == "CLIENT":
            pass
        elif command == "ECHO":
            await self.send("#Echo ON")
        elif command == "SB":
            symbol = parts[1].upper() if len(parts) > 1 else ""
            if symbol:
                self.subscriptions.add(symbol)
                self.prices.setdefault(symbol, Decimal(random.randint(20, 300)))
                self._tasks.append(asyncio.create_task(self._stream_quotes(symbol)))
        elif command == "UNSB":
            if len(parts) > 1:
                self.subscriptions.discard(parts[1].upper())
        elif command == "SUBSCRIBE":
            pass
        elif command == "POSREFRESH":
            await self.send("%POS AAPL 0 0.00 0.00 0.00")
        elif command == "GET" and len(parts) > 1 and parts[1].upper() == "BP":
            await self.send("%BP 25000.00 100000.00 25000.00")
        elif command == "SHORTINFO":
            symbol = parts[1].upper() if len(parts) > 1 else "?"
            await self.send(f"$SHORTINFO {symbol} 1 5000")
        elif command == "NEWORDER":
            await self._handle_new_order(parts)
        elif command == "CANCEL":
            target = parts[1] if len(parts) > 1 else "ALL"
            await self.send(f"#Cancel accepted {target}")
        elif command == "QUIT":
            self.writer.close()
        else:
            await self.send(f"#ERROR unknown command {command}")

    async def _handle_new_order(self, parts: list[str]) -> None:
        # NEWORDER <token> <side> <symbol> <route> <shares> <price|MKT> [TIF=..]
        try:
            token = int(parts[1])
            side = parts[2].upper()
            symbol = parts[3].upper()
            route = parts[4].upper()
            shares = int(parts[5])
            price_token = parts[6]
        except (IndexError, ValueError):
            await self.send("#ERROR malformed NEWORDER")
            return

        price = (
            self.prices.get(symbol, Decimal("100"))
            if price_token.upper() in ("MKT", "MARKET")
            else Decimal(price_token)
        )
        order_id = str(self.next_order_id)
        self.next_order_id += 1

        await self.send(
            f"%ORDER {order_id} {token} {symbol} {side} {shares} {price} "
            f"Accepted 0 {shares} 0.00 {route}"
        )
        self._tasks.append(
            asyncio.create_task(
                self._simulate_fill(order_id, token, symbol, side, shares, price, route)
            )
        )

    async def _simulate_fill(
        self,
        order_id: str,
        token: int,
        symbol: str,
        side: str,
        shares: int,
        price: Decimal,
        route: str,
    ) -> None:
        """Fill in two parts, to exercise partial-fill handling."""
        await asyncio.sleep(random.uniform(0.2, 1.0))
        first = max(shares // 2, 1)
        await self.send(f"%TRADE {order_id} {token} {symbol} {side} {first} {price} E1")
        await self.send(
            f"%ORDER {order_id} {token} {symbol} {side} {shares} {price} "
            f"Partial {first} {shares - first} {price} {route}"
        )

        if first < shares:
            await asyncio.sleep(random.uniform(0.2, 1.0))
            rest = shares - first
            await self.send(
                f"%TRADE {order_id} {token} {symbol} {side} {rest} {price} E2"
            )
            await self.send(
                f"%ORDER {order_id} {token} {symbol} {side} {shares} {price} "
                f"Filled {shares} 0 {price} {route}"
            )

        signed = shares if side in ("B", "BC") else -shares
        await self.send(f"%POS {symbol} {signed} {price} 0.00 0.00")

    async def _stream_quotes(self, symbol: str) -> None:
        try:
            while symbol in self.subscriptions:
                base = self.prices.get(symbol, Decimal("100"))
                drift = Decimal(random.randint(-5, 5)) / 100
                base = max(base + drift, Decimal("1"))
                self.prices[symbol] = base
                bid = base - Decimal("0.01")
                ask = base + Decimal("0.01")
                await self.send(
                    f"$QUOTE {symbol} {bid:.2f} {ask:.2f} {base:.2f} "
                    f"{random.randint(1, 20)} {random.randint(1, 20)} "
                    f"{random.randint(10000, 900000)}"
                )
                await asyncio.sleep(0.5)
        except asyncio.CancelledError:
            raise


async def main() -> None:
    parser = argparse.ArgumentParser(description="Mock DAS CMD API server")
    parser.add_argument("--host", default="127.0.0.1")
    parser.add_argument("--port", type=int, default=9910)
    args = parser.parse_args()

    async def on_client(
        reader: asyncio.StreamReader, writer: asyncio.StreamWriter
    ) -> None:
        await MockSession(reader, writer).handle()

    server = await asyncio.start_server(on_client, args.host, args.port)
    log.info("mock DAS server listening on %s:%s", args.host, args.port)
    async with server:
        await server.serve_forever()


if __name__ == "__main__":
    with contextlib.suppress(KeyboardInterrupt):
        asyncio.run(main())
