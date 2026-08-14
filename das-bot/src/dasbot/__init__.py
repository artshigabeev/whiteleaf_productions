"""dasbot — automated trading against the DAS Trader CMD API."""

from .config import AppConfig, ConnectionConfig, RiskConfig
from .events import OrderStatus, OrderUpdate, PositionUpdate, Quote, Side, TradeUpdate
from .risk import KillSwitch, OrderIntent, RiskDecision, RiskGate
from .state import AccountState, Position, WorkingOrder
from .strategy import ObserverStrategy, Strategy

__version__ = "0.1.0"

__all__ = [
    "AccountState",
    "AppConfig",
    "ConnectionConfig",
    "KillSwitch",
    "ObserverStrategy",
    "OrderIntent",
    "OrderStatus",
    "OrderUpdate",
    "Position",
    "PositionUpdate",
    "Quote",
    "RiskConfig",
    "RiskDecision",
    "RiskGate",
    "Side",
    "Strategy",
    "TradeUpdate",
    "WorkingOrder",
    "__version__",
]
