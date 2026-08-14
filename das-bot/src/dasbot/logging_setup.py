"""Logging configuration.

Two sinks, on purpose:
  * `dasbot.log`  — application log at the configured level
  * `wire.log`    — every byte in and out, always DEBUG

The wire log is the one that matters when a fill looks wrong. Reconstructing a
disputed order from application-level logs is guesswork; reconstructing it from
the raw stream is not. It is also what you diff against the spec to confirm the
message layouts in `protocol.py`.
"""

from __future__ import annotations

import logging
import logging.handlers
from datetime import UTC, datetime
from pathlib import Path

WIRE_LOGGER = "dasbot.wire"


class UTCFormatter(logging.Formatter):
    """Timestamps in UTC. Local time plus DST is not a debugging aid."""

    def formatTime(self, record: logging.LogRecord, datefmt: str | None = None) -> str:
        dt = datetime.fromtimestamp(record.created, tz=UTC)
        return dt.isoformat(timespec="milliseconds")


def setup_logging(log_dir: Path, level: str = "INFO") -> None:
    log_dir.mkdir(parents=True, exist_ok=True)

    app_format = UTCFormatter(
        "%(asctime)s %(levelname)-8s %(name)-22s %(message)s"
    )
    wire_format = UTCFormatter("%(asctime)s %(message)s")

    root = logging.getLogger()
    root.setLevel(logging.DEBUG)
    for handler in list(root.handlers):
        root.removeHandler(handler)

    console = logging.StreamHandler()
    console.setLevel(getattr(logging, level, logging.INFO))
    console.setFormatter(app_format)
    root.addHandler(console)

    app_file = logging.handlers.RotatingFileHandler(
        log_dir / "dasbot.log", maxBytes=10_000_000, backupCount=10, encoding="utf-8"
    )
    app_file.setLevel(logging.DEBUG)
    app_file.setFormatter(app_format)
    root.addHandler(app_file)

    # Wire log is separate and never propagates: at DEBUG it is high volume and
    # would drown the application log.
    wire = logging.getLogger(WIRE_LOGGER)
    wire.setLevel(logging.DEBUG)
    wire.propagate = False
    for handler in list(wire.handlers):
        wire.removeHandler(handler)
    wire_file = logging.handlers.RotatingFileHandler(
        log_dir / "wire.log", maxBytes=50_000_000, backupCount=20, encoding="utf-8"
    )
    wire_file.setLevel(logging.DEBUG)
    wire_file.setFormatter(wire_format)
    wire.addHandler(wire_file)
