from repository.repository import Repository
from repository.utils import insert, select

from models.provider import Provider


class ProviderRepository(Repository[Provider]):
    @classmethod
    def tablename(cls) -> str:
        # Changed "Provider" -> "provider"
        return "provider"

    @classmethod
    def add(cls, item: Provider) -> int:
        # Fixed "ID" -> "id" and "Name" -> "name"
        rows = select(cls.tablename(), ["id"], {"name": item.name})
        if len(rows) > 0:
            return rows[0][0]

        return insert(
            ProviderRepository.tablename(),
            {
                "name": item.name,
                "icon": item.icon,
                "link": item.link,
            },
        )