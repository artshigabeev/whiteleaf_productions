"""Command-line entry point.

    python -m dasbot --strategy observer
    python -m dasbot --strategy observer --live      # sends real orders

Live trading requires the explicit `--live` flag *and* a typed confirmation.
Defaulting to dry-run means a forgotten environment variable cannot cost money.
"""

from __future__ import annotations

import argparse
import asyncio
import contextlib
import logging
import os
import signal
import sys

from .client import DasClient
from .config import AppConfig
from .logging_setup import setup_logging
from .strategy import ObserverStrategy, SpreadCaptureExample, Strategy

log = logging.getLogger(__name__)

STRATEGIES = {
    "observer": lambda args: ObserverStrategy(),
    "example": lambda args: SpreadCaptureExample(symbol=args.symbol or "AAPL"),
}


def build_parser() -> argparse.ArgumentParser:
    parser = argparse.ArgumentParser(
        prog="dasbot", description="DAS Trader CMD API automation"
    )
    parser.add_argument(
        "--strategy", default="observer", choices=sorted(STRATEGIES),
        help="strategy to run (default: observer, which sends no orders)",
    )
    parser.add_argument("--symbol", help="symbol for strategies that take one")
    parser.add_argument("--env-file", default=".env", help="path to .env")
    parser.add_argument(
        "--live", action="store_true",
        help="send real orders. Without this, everything runs in dry-run.",
    )
    parser.add_argument("--log-level", default=None, help="override log level")
    return parser


def confirm_live() -> bool:
    """Require a typed confirmation before arming live order flow."""
    if os.environ.get("DAS_SKIP_LIVE_CONFIRMATION", "").lower() == "true":
        log.warning("live confirmation skipped via DAS_SKIP_LIVE_CONFIRMATION")
        return True
    if not sys.stdin.isatty():
        log.error("--live requires an interactive terminal for confirmation")
        return False
    print("\n*** LIVE TRADING: this will send real orders to your broker. ***")
    answer = input("Type LIVE to confirm: ").strip()
    return answer == "LIVE"


async def run(args: argparse.Namespace) -> int:
    config = AppConfig.from_env(args.env_file)
    if args.live:
        config = AppConfig(
            connection=config.connection,
            risk=config.risk,
            dry_run=False,
            log_dir=config.log_dir,
            log_level=args.log_level or config.log_level,
        )

    setup_logging(config.log_dir, args.log_level or config.log_level)

    if not config.dry_run and not confirm_live():
        print("Live trading not confirmed — exiting.")
        return 1

    strategy: Strategy = STRATEGIES[args.strategy](args)
    client = DasClient(config, strategy)

    loop = asyncio.get_running_loop()
    stopping = False

    def request_stop(signame: str) -> None:
        nonlocal stopping
        if stopping:
            return
        stopping = True
        log.info("received %s — shutting down", signame)
        asyncio.create_task(client.stop())

    for sig in (signal.SIGINT, signal.SIGTERM):
        with contextlib.suppress(NotImplementedError, AttributeError):
            loop.add_signal_handler(sig, request_stop, sig.name)

    try:
        await client.run()
    except Exception:
        log.exception("fatal error")
        with contextlib.suppress(Exception):
            await client.stop()
        return 1

    log.info("final state: %s", client.state.summary())
    return 0


def main() -> int:
    args = build_parser().parse_args()
    try:
        return asyncio.run(run(args))
    except KeyboardInterrupt:
        return 130


if __name__ == "__main__":
    raise SystemExit(main())
