# from instructions import Workflow, Scrape
#
#
# def process_price(price: str) -> float:
#     price = price.strip().removesuffix("DT").replace(",", ".")
#     price = price.replace("&nbsp;", "")
#     return float(price)
#
#
# def process_reference(price: str) -> str:
#     return price.removeprefix("[").removesuffix("]")


# workflow = Workflow(
#     "https://spacenet.tn/25-barrette-memoire",
#
#     price_instruction=ScrapeInstruction(
#         "#box-product-grid .field-product-item span.price", "innerHTML", process_price
#     ),
#     name_instruction=ScrapeInstruction(
#         ".field-product-item h2.product_name a", "innerHTML", str.strip
#     ),
#     image_instruction=ScrapeInstruction(
#         ".field-product-item span.cover_image img", "src"
#     ),
#     url_instruction=ScrapeInstruction(
#         ".field-product-item h2.product_name a",
#         "href",
#     ),
#     reference_instruction=ScrapeInstruction(
#         ".field-product-item .product-reference span", "innerHTML", process_reference
#     ),
# )
