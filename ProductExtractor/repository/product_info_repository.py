from typing import NamedTuple
from repository.utils import insert, select
from repository.repository import Repository


class ProductInfo(NamedTuple):
    product_id: int
    key: str
    value: str


class ProductInfoRepository(Repository[ProductInfo]):
    @classmethod
    def tablename(cls) -> str:
        # Changed "ProductInfo" -> "product_info"
        return "product_info"

    @classmethod
    def add(cls, item: ProductInfo) -> int:
        # Changed ["`Key`"] -> ["`key`"] and fixed the criteria keys
        rows = select(
            cls.tablename(),
            ["`key`"],
            {
                "product_id": item.product_id,
                "`key`": item.key,
            },
        )

        if len(rows) > 0:
            return rows[0][0]

        return insert(
            cls.tablename(),
            {
                "product_id": item.product_id,
                "`key`": item.key,
                "value": item.value,
            },
        )