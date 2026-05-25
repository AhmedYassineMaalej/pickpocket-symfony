from abc import ABC, abstractmethod


class MissingAttributeException(Exception):
    """Exception raised when trying to contruct element without setting all attributes"""

    pass


class Builder[T](ABC):
    @abstractmethod
    def build(self) -> T:
        pass
