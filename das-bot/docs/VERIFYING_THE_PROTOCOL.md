# Verifying the wire protocol against your broker's spec

Everything in `src/dasbot/protocol.py` is currently marked `UNVERIFIED`. This
is the checklist for turning that into `verified=True`, and it is the last
thing standing between this code and live trading.

## Why this is isolated to one file

DAS CMD API message layouts differ between DAS releases and between brokers'
configurations. Rather than guess and scatter assumptions through the codebase,
every layout lives in one table — `MESSAGE_LAYOUTS` in `protocol.py` — as data:

```python
MessageLayout(
    prefix="%ORDER",
    event_type=OrderUpdate,
    positional=(
        FieldSpec("order_id", to_str),
        FieldSpec("token", to_int),
        FieldSpec("symbol", to_upper),
        ...
    ),
)
```

Correcting a layout means reordering that tuple. No other module changes,
because everything downstream consumes typed events from `events.py`.

## Step 1 — read the spec, fix the tables

For each message your broker documents, confirm:

- [ ] **Prefix** — is it `%ORDER`, `#ORDER`, `$ORDER`? Case?
- [ ] **Field order** — exact positional sequence.
- [ ] **Field count** — are trailing fields ever omitted?
- [ ] **Delimiter** — space, or something else?
- [ ] **Key/value form** — does your version append `KEY=VALUE` pairs? The
      parser already accepts both shapes simultaneously.
- [ ] **Short positions** — negative quantity, or a separate side field? The
      code assumes negative. If not, `state.py` position math needs the sign
      applied where `%POS` is handled.
- [ ] **Prices** — decimal strings, or scaled integers?
- [ ] **Status vocabulary** — every status token your broker emits must appear
      in `STATUS_ALIASES`. An unmapped token becomes `UNKNOWN`, which is
      treated as *still working* so we never assume an order died and
      double-send. Safe, but it will leave phantom orders in the book.

Then flip `verified=True` and update `note=`.

## Step 2 — confirm the command formats

Check `new_order()` against the spec:

- [ ] Argument order in `NEWORDER`
- [ ] Valid **route** names (`SMRTL`, `LIMIT`, `ARCA`, …) — an invalid route is
      rejected by the broker, not by us
- [ ] Market order syntax — is it `MKT`, or a separate command?
- [ ] TIF syntax — `TIF=DAY`, or positional?
- [ ] Whether `CANCEL` takes your token or the broker's order id. The code
      accepts either; the spec decides which you should pass.
- [ ] Stop and trailing-stop orders (`SLNEWORDER`) — not implemented yet

## Step 3 — capture a live session

Run the observer strategy against the real connection. It sends no orders.

```bash
python -m dasbot --strategy observer
```

Then read `logs/wire.log` and compare against the spec.

Find every message the parser did not recognise:

```bash
grep '<<' logs/wire.log | grep -v -E '\$QUOTE|%ORDER|%TRADE|%POS|%BP'
```

Anything that appears here is either a message type not yet in the table, or a
prefix mismatch. Add it. Nothing should be unaccounted for.

## Step 4 — verify one order end to end

On a **demo/simulator account**, with the smallest possible size:

1. Set `RISK_MAX_SHARES_PER_ORDER=1` and `RISK_SYMBOL_ALLOWLIST` to one liquid
   symbol.
2. Run with `--live`.
3. Place one order.
4. Confirm, in this order:
   - [ ] The order appears in DAS Trader Pro with the size, side and price you
         intended
   - [ ] `%ORDER` is parsed — status transitions appear in `logs/dasbot.log`
   - [ ] `%TRADE` is parsed on fill
   - [ ] `client.state.position(...)` matches the platform exactly
   - [ ] A cancel works and retires the order from the local book

If the local book and the platform ever disagree, stop. That disagreement is
the bug that costs money — every risk limit is computed from the local book.

## Step 5 — verify the failure paths

These are the ones that get skipped and then matter at the worst moment:

- [ ] **Kill the network mid-session.** The bot must reconnect, re-login,
      re-subscribe, and refresh positions. Confirm it denies orders until
      `POSREFRESH` answers.
- [ ] **Reject an order** (bad route, or a price far through the market).
      Confirm the rejection is parsed and the order is retired locally.
- [ ] **Partial fill.** Confirm `pending_exposure` decreases correctly — this
      is what keeps the position cap honest.
- [ ] **Trip the kill switch** by setting `RISK_MAX_DAILY_LOSS` very low.
      Confirm working orders are cancelled and new ones blocked.

## Step 6 — keep the mock honest

`scripts/mock_das_server.py` speaks whatever `protocol.py` says. Once you have
real captures, update the mock to emit the **real** formats. Then the
integration tests are testing against something true rather than against your
own assumptions — otherwise a wrong layout passes every test.
