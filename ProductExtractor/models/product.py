from models.builder import Builder, MissingAttributeException
from models.category import Category


class Product:
    def __init__(
        self,
        reference: str,
        name: str,
        image: str,
        info: dict[str, str],
        category: Category,
    ):
        self.reference = reference
        self.name = name
        self.image = image
        self.category = category
        self.info = info

    def __repr__(self) -> str:
        res = "Product(\n"
        for key, val in self.__dict__.items():
            res += f"\t{key} = {str(val)}\n"
        res += ")"
        return res


class ProductBuilder(Builder[Product]):
    def __init__(self, reference: str, name: str):
        self.reference = reference
        self.name = name
        self.image = None
        self.category = None
        self.info = None

    def __repr__(self) -> str:
        res = "Product(\n"
        for key, val in self.__dict__.items():
            res += f"\t{key} = {str(val)}\n"
        res += ")"
        return res

    def set_image(self, image: str):
        self.image = image

    def set_category(self, category: Category):
        self.category = category

    def set_info(self, info: dict[str, str]):
        self.info = info

    def build(self) -> Product:
        if self.image is None:
            raise MissingAttributeException(
                "Attempted to construct Product without setting image attribute"
            )

        if self.info is None:
            raise MissingAttributeException(
                "Attempted to construct Product without setting info attribute"
            )

        if self.category is None:
            raise MissingAttributeException(
                "Attempted to construct Product without setting category attribute"
            )

        return Product(
            self.reference,
            self.name,
            self.image,
            self.info,
            self.category,
        )
