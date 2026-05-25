from repository.provider_repository import ProviderRepository
from repository.repository import Repository
from repository.utils import insert, select
from repository.product_repository import ProductRepository

from models.offer import Offer


class ProductOfferRepository(Repository[Offer]):
    @classmethod
    def tablename(cls) -> str:
        # Correctly matches the 'offer' table in your schema
        return "offer"

    @classmethod
    def add(cls, item: Offer) -> int:
        provider_id = ProviderRepository.add(item.provider)
        product_id = ProductRepository.add(item.product)

        # Updated query targets and keys to match snake_case columns
        rows = select(
            cls.tablename(),
            ["id"],
            {
                "product_id": product_id,
                "provider_id": provider_id,
            },
        )

        # check if offer already exists
        if len(rows) > 0:
            return rows[0][0]

        # Updated insertion dictionary to match snake_case columns
        return insert(
            cls.tablename(),
            {
                "product_id": product_id,
                "link": item.link,
                "price": item.price,
                "provider_id": provider_id,
            },
        )