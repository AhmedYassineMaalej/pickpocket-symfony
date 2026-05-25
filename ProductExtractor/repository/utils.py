import os
from mysql.connector.pooling import PooledMySQLConnection
from mysql.connector.abstracts import MySQLConnectionAbstract
from dotenv import load_dotenv
import mysql.connector

# Load the .env file from the root directory one level up
load_dotenv("../.env", override=True)


def get_connection() -> PooledMySQLConnection | MySQLConnectionAbstract:
    # Uses the updated, centralized keys from your cleaned .env file
    connection = mysql.connector.connect(
        host=os.getenv("DB_HOST", "127.0.0.1"),
        port=int(os.getenv("DB_PORT", 3306)),
        user=os.getenv("DB_USER"),
        password=os.getenv("DB_PASS"),
        database=os.getenv("DB_NAME", "pickpocket"),
    )

    return connection


def execute_query(sql: str, params: tuple) -> list:
    connection = get_connection()
    cursor = connection.cursor()

    cursor.execute(sql, params)
    rows = cursor.fetchall()

    cursor.close()
    connection.close()

    assert type(rows) is list

    return rows


def execute_statement(sql: str, params: tuple) -> int:
    connection = get_connection()
    cursor = connection.cursor()

    cursor.execute(sql, params)
    id = cursor.lastrowid

    connection.commit()

    cursor.close()
    connection.close()

    assert type(id) is int

    return id


def insert(tablename: str, values: dict[str, str | float | int]) -> int:
    params_string = ", ".join(values.keys())
    placeholder_string = ", ".join(["%s"] * len(values))
    sql = f"INSERT INTO {tablename} ({params_string}) VALUES ({placeholder_string})"
    params = tuple(values.values())

    return execute_statement(sql, params)


def select(tablename: str, fields: list[str], where: dict[str, str | int]):
    selected = ", ".join(fields)
    where_string = " AND ".join(map(lambda key: f"{key} = %s", where.keys()))

    sql = f"SELECT {selected} FROM {tablename} WHERE {where_string}"
    params = tuple(where.values())

    return execute_query(sql, params)