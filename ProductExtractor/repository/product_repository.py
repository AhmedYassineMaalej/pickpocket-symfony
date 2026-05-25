from repository.category_repository import CategoryRepository
from repository.product_info_repository import ProductInfo, ProductInfoRepository
from repository.utils import insert, select
from models.product import Product
from repository.repository import Repository


class ProductRepository(Repository[Product]):
    @classmethod
    def tablename(cls):
        # Symfony usually names tables in lowercase snake_case plural or singular.
        # Check MySQL Workbench. If the table is lowercase 'product', use "product".
        return "product"

    @classmethod
    def add(cls, item: Product) -> int:
        # Changed "ID" -> "id" and "Reference" -> "reference"
        rows = select(cls.tablename(), ["id"], {"reference": item.reference})

        if len(rows) == 1:
            return rows[0][0]

        category_id = CategoryRepository.add(item.category)
        product_id = insert(
            cls.tablename(),
            {
                "reference": item.reference,
                "name": item.name,
                "image": item.image,
                "category_id": category_id, # This fixes the Unknown column 'CategoryID' error
            },
        )

        for key, val in item.info.items():
            ProductInfoRepository.add(ProductInfo(product_id, key, val))

        return product_id