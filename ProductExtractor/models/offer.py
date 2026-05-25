from models.builder import MissingAttributeException
from models.category import Category
from models.product import Product, ProductBuilder
from models.provider import Provider


class Offer:
    def __init__(
        self,
        product: Product,
        provider: Provider,
        price: float,
        link: str,
    ):
        self.product = product
        self.provider = provider
        self.price = price
        self.link = link

    def __repr__(self) -> str:
        res = "ProductOffer(\n"
        for key, val in self.__dict__.items():
            res += f"\t{key} = {str(val)}\n"
        res += ")"
        return res


class OfferBuilder:
    def __init__(self, product: ProductBuilder, price: float, url: str) -> None:
        self.product = product
        self.provider = None
        self.price = price
        self.url = url

    def set_provider(self, provider: Provider):
        self.provider = provider

    def build(self) -> Offer:
        if self.provider is None:
            raise MissingAttributeException(
                "Tried to contruct Offer without setting provider attribute"
            )

        product = self.product.build()

        return Offer(product, self.provider, self.price, self.url)
