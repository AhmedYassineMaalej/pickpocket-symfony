from abc import ABC, abstractmethod
from typing import Optional


class Repository[T](ABC):
    @classmethod
    @abstractmethod
    def tablename(cls) -> str:
        pass

    @classmethod
    @abstractmethod
    def add(cls, item: T) -> Optional[int]:
        pass
