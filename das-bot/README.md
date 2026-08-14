# dasbot

Automated trading against the **DAS Trader CMD API** (Cobra Trading and other
DAS-based brokers).

Python 3.11+, no runtime dependencies — stdlib `asyncio` handles the socket.

---

## Status: not yet verified against the broker specification

The DAS CMD API is a line-oriented TCP protocol whose exact message layouts
vary between DAS releases. The layouts in `src/dasbot/protocol.py` are marked
`UNVERIFIED` and must be checked against your broker's specification document
before this trades real money. See [`docs/VERIFYING_THE_PROTOCOL.md`](docs/VERIFYING_THE_PROTOCOL.md).

Everything else — transport, reconnect, state tracking, risk gate, tests — is
independent of those layouts and does not change when you correct them.

---

## Quick start

```bash
cd das-bot
python3 -m venv .venv && source .venv/bin/activate
pip install -e ".[dev]"

cp .env.example .env       # then fill in credentials
```

Run the test suite (no broker connection needed):

```bash
pytest
```

Run against the bundled mock server, which simulates quotes, orders and fills:

```bash
# terminal 1
python scripts/mock_das_server.py --port 9910

# terminal 2
DAS_HOST=127.0.0.1 DAS_PORT=9910 DAS_USERNAME=test DAS_PASSWORD=test \
DAS_ACCOUNT=TEST python -m dasbot --strategy observer
```

Against the real broker, starting with the strategy that sends nothing:

```bash
python -m dasbot --strategy observer
```

---

## Safety model

Four independent layers, because any one of them can be wrong.

**1. Dry run is the default.** `DAS_DRY_RUN` defaults to `true` even if unset or
missing. Orders are built and risk-checked, then logged instead of sent. Live
trading needs `--live` *and* typing `LIVE` at a prompt.

**2. The risk gate has no bypass.** Every order goes through `RiskGate.evaluate`.
There is no method on `DasClient` that reaches the socket without one.

| Limit | Variable | Default |
|---|---|---|
| Shares per order | `RISK_MAX_SHARES_PER_ORDER` | 100 |
| Total position (per symbol) | `RISK_MAX_POSITION_SHARES` | 200 |
| Notional per order | `RISK_MAX_NOTIONAL_PER_ORDER` | 5000 |
| Concurrent working orders | `RISK_MAX_OPEN_ORDERS` | 5 |
| Daily loss before halt | `RISK_MAX_DAILY_LOSS` | 500 |
| Orders per minute | `RISK_MAX_ORDERS_PER_MINUTE` | 10 |
| Symbol allowlist | `RISK_SYMBOL_ALLOWLIST` | (any) |
| Short selling | `RISK_ALLOW_SHORT` | false |
| Regular hours only | `RISK_REQUIRE_MARKET_HOURS` | true |

The position check uses the **worst case**: shares held, plus everything
already working, plus the new order — all assumed to fill. That is what makes
the cap hold under partial fills and stops a fast strategy stacking into an
oversized position while earlier orders are still unfilled.

**3. Kill switch.** Trips on the daily loss limit (checked continuously, not
just at order time, so a losing position you are not actively trading still
fires it). Once tripped it cancels working orders and blocks all new ones for
the process lifetime. Re-arming requires a restart — deliberately, so a human
has to look at why it fired.

It does **not** auto-liquidate open positions. Dumping into whatever caused the
loss can turn a bad day into a worse one, and that call belongs to you.

**4. Stale-book denial.** After any disconnect the position book is marked
stale and orders are denied until DAS answers `POSREFRESH`. You may have been
filled or stopped out during the gap; trading on a pre-disconnect view is how
you end up double-sized.

---

## Architecture

```
  strategy.py     your logic — receives typed events, requests orders
       |
  client.py       orchestration: the only path from strategy to socket
       |
  risk.py         pre-trade gate + kill switch    <-- no bypass
       |
  state.py        local book: orders, positions, PnL
       |
  connection.py   socket, login, heartbeat, reconnect, resubscribe
       |
  protocol.py     wire format  <-- the only spec-dependent module
       |
  events.py       typed events; strategies never see raw strings
```

Two properties worth preserving as you extend it:

- **Strategies never touch the socket.** They call `client.place_order(...)`,
  which risk-checks first. Keeping this true is what makes the limits real.
- **`protocol.py` is the only file that knows the wire format.** Message
  layouts are a data table, not parsing logic, so matching your broker's spec
  means editing a tuple.

## Logs

`logs/dasbot.log` — application events at the configured level.
`logs/wire.log` — every byte in and out, always DEBUG, credentials redacted.

The wire log is what you want when a fill looks wrong: reconstructing a
disputed order from application logs is guesswork, from the raw stream it is
not. It is also what you diff against the spec to verify the layouts. Both are
gitignored — they contain account numbers and order ids.

## Writing a strategy

```python
from decimal import Decimal
from dasbot.strategy import Strategy
from dasbot.events import Quote, Side

class MyStrategy(Strategy):
    name = "my-strategy"

    async def on_start(self, client):
        await client.subscribe("AAPL")

    async def on_quote(self, client, quote: Quote):
        if quote.symbol != "AAPL":
            return
        position = client.state.position("AAPL")
        if position.is_flat and quote.spread >= Decimal("0.02"):
            # returns (sent, decision, token); `sent` is False if risk denied
            # it or dry-run withheld it — always check it
            sent, decision, token = await client.place_order(
                symbol="AAPL", side=Side.BUY, quantity=10,
                price=quote.bid + Decimal("0.01"),
            )
            if not sent:
                print("not sent:", decision.reason_text)
```

Register it in `STRATEGIES` in `cli.py`.

Ask `client.state` rather than tracking positions yourself — one book, and it
is the one the risk gate checks against.

`SpreadCaptureExample` in `strategy.py` demonstrates the API surface. It is a
wiring demo, not a profitable strategy; do not run it with real capital.

## Testing

```bash
pytest                          # everything
pytest tests/test_risk.py       # risk gate only
pytest tests/test_integration.py  # real sockets against the mock server
```

The integration tests spin up the mock server in-process and cover connect,
login, order, partial fill, risk denial, kill switch, and recovery from a
dropped socket.

They prove the bot is internally consistent. They cannot prove the wire format
matches your broker, because the mock speaks whatever `protocol.py` says. Only
the spec and a live capture settle that.

## Before trading real money

1. Verify the message layouts against the spec — see `docs/VERIFYING_THE_PROTOCOL.md`.
2. Run `--strategy observer` against a live connection for a full session.
   It sends nothing and its wire log is your verification evidence.
3. Trade a DAS demo/simulator account before a funded one.
4. Go live with limits far below what feels necessary. The worst case these
   bound is a bug in your own strategy, not a bad market.
