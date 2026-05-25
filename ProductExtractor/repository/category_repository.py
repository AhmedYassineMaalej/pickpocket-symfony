from repository.utils import insert, select
from models.category import Category
from repository.repository import Repository


class CategoryRepository(Repository[Category]):
    @classmethod
    def tablename(cls) -> str:
        # Matches lowercase schema
        return "category"

    @classmethod
    def add(cls, item: Category) -> int:
        # Changed ["ID"] -> ["id"] and {"Name": ...} -> {"name": ...}
        rows = select(cls.tablename(), ["id"], {"name": item.name})

        if len(rows) > 0:
            return rows[0][0]

        return insert(
            cls.tablename(),
            {
                "name": item.name, # Matches column 'name' in Workbench
            },
        )