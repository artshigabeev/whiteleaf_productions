"""Configuration, loaded from environment / .env.

Credentials never live in code or in the repo. `.env` is gitignored; the
committed file is `.env.example`, which carries no real values.
"""

from __future__ import annotations

import os
from dataclasses import dataclass, field
from decimal import Decimal
from pathlib import Path


def load_dotenv(path: str | Path = ".env", *, override: bool = False) -> dict[str, str]:
    """Minimal `.env` reader — deliberately dependency-free.

    Supports `KEY=value`, `export KEY=value`, `#` comments and quoted values.
    Keeping this in-tree means the bot runs on a bare Python install with no
    pip step, which matters when you are restarting it in a hurry.
    """
    loaded: dict[str, str] = {}
    env_path = Path(path)
    if not env_path.is_file():
        return loaded

    for raw_line in env_path.read_text(encoding="utf-8").splitlines():
        line = raw_line.strip()
        if not line or line.startswith("#"):
            continue
        if line.startswith("export "):
            line = line[len("export ") :].strip()
        key, sep, value = line.partition("=")
        if not sep:
            continue
        key = key.strip()
        value = value.strip().strip('"').strip("'")
        loaded[key] = value
        if override or key not in os.environ:
            os.environ[key] = value
    return loaded


def _env_str(key: str, default: str | None = None, *, required: bool = False) -> str:
    value = os.environ.get(key, default)
    if required and not value:
        raise ValueError(
            f"missing required configuration {key!r} — set it in .env or the environment"
        )
    return value or ""


def _env_int(key: str, default: int) -> int:
    try:
        return int(os.environ[key])
    except (KeyError, ValueError):
        return default


def _env_decimal(key: str, default: Decimal) -> Decimal:
    try:
        return Decimal(os.environ[key])
    except Exception:
        return default


def _env_bool(key: str, default: bool) -> bool:
    raw = os.environ.get(key)
    if raw is None:
        return default
    return raw.strip().lower() in ("1", "true", "yes", "on")


@dataclass(frozen=True, slots=True)
class ConnectionConfig:
    host: str
    port: int
    username: str
    password: str = field(repr=False)
    account: str
    watchlist: tuple[str, ...] = ()
    default_route: str = "SMRTL"

    # Reconnect/backoff
    connect_timeout: float = 10.0
    heartbeat_interval: float = 20.0
    heartbeat_timeout: float = 60.0
    reconnect_initial_delay: float = 1.0
    reconnect_max_delay: float = 60.0
    max_reconnect_attempts: int = 0  # 0 == unlimited

    def redacted(self) -> str:
        return f"{self.username}@{self.host}:{self.port} account={self.account}"


@dataclass(frozen=True, slots=True)
class RiskConfig:
    max_shares_per_order: int = 100
    max_position_shares: int = 200
    max_notional_per_order: Decimal = Decimal("5000")
    max_open_orders: int = 5
    max_daily_loss: Decimal = Decimal("500")
    max_orders_per_minute: int = 10
    symbol_allowlist: tuple[str, ...] = ()
    allow_short: bool = False
    require_market_hours: bool = True


@dataclass(frozen=True, slots=True)
class AppConfig:
    connection: ConnectionConfig
    risk: RiskConfig
    dry_run: bool = True
    log_dir: Path = Path("logs")
    log_level: str = "INFO"

    @classmethod
    def from_env(cls, env_file: str | Path = ".env") -> AppConfig:
        load_dotenv(env_file)

        watchlist = tuple(
            sym.strip().upper()
            for sym in _env_str("DAS_WATCHLIST", "").split(",")
            if sym.strip()
        )
        allowlist = tuple(
            sym.strip().upper()
            for sym in _env_str("RISK_SYMBOL_ALLOWLIST", "").split(",")
            if sym.strip()
        )

        connection = ConnectionConfig(
            host=_env_str("DAS_HOST", required=True),
            port=_env_int("DAS_PORT", 9910),
            username=_env_str("DAS_USERNAME", required=True),
            password=_env_str("DAS_PASSWORD", required=True),
            account=_env_str("DAS_ACCOUNT", required=True),
            watchlist=watchlist,
            default_route=_env_str("DAS_DEFAULT_ROUTE", "SMRTL"),
            heartbeat_interval=float(_env_int("DAS_HEARTBEAT_INTERVAL", 20)),
            heartbeat_timeout=float(_env_int("DAS_HEARTBEAT_TIMEOUT", 60)),
            max_reconnect_attempts=_env_int("DAS_MAX_RECONNECT_ATTEMPTS", 0),
        )

        risk = RiskConfig(
            max_shares_per_order=_env_int("RISK_MAX_SHARES_PER_ORDER", 100),
            max_position_shares=_env_int("RISK_MAX_POSITION_SHARES", 200),
            max_notional_per_order=_env_decimal(
                "RISK_MAX_NOTIONAL_PER_ORDER", Decimal("5000")
            ),
            max_open_orders=_env_int("RISK_MAX_OPEN_ORDERS", 5),
            max_daily_loss=_env_decimal("RISK_MAX_DAILY_LOSS", Decimal("500")),
            max_orders_per_minute=_env_int("RISK_MAX_ORDERS_PER_MINUTE", 10),
            symbol_allowlist=allowlist,
            allow_short=_env_bool("RISK_ALLOW_SHORT", False),
            require_market_hours=_env_bool("RISK_REQUIRE_MARKET_HOURS", True),
        )

        # dry_run defaults to True everywhere. Going live must be a deliberate,
        # explicit act — never something you inherit from a missing variable.
        return cls(
            connection=connection,
            risk=risk,
            dry_run=_env_bool("DAS_DRY_RUN", True),
            log_dir=Path(_env_str("DAS_LOG_DIR", "logs")),
            log_level=_env_str("DAS_LOG_LEVEL", "INFO").upper(),
        )
