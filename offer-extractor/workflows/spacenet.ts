import { identity, Scrape } from "../instructions.ts"
import { Provider } from "../models/provider.ts"
import { Workflow } from "./workflow.ts"

const provider = new Provider(
    "Spacenet",
    "https://spacenet.tn/52249-large_default/-abonnement-iptv-spacenet.jpg",
    "https://www.spacenet.tn/",
)

const categories = new Map([
    ["Memory", "https://spacenet.tn/25-barrette-memoire"],
    ["GPU", "https://spacenet.tn/397-cartes-graphiques"]
])

export const workflow = new Workflow({
    provider,
    categories,
    productPrice: new Scrape(".products #box-product-grid span.price", "innerHTML", processPrice),
    productName: new Scrape(".products #box-product-grid .product_name a", "innerHTML", (str: string) => str.trim()),
    productLink: new Scrape(".products #box-product-grid .product_name a", "href", identity),
    productImage: new Scrape(".products #box-product-grid .cover_image img", "src", identity),
    productReference: new Scrape(".products #box-product-grid .product-reference span", "innerHTML", processReference),
    productInfoKey: new Scrape("dt.name", "innerHTML", identity),
    productInfoValue: new Scrape("dd.value", "innerHTML", identity),
});


function processReference(price: string): string {
    return price.trim()
}

function processPrice(price: string): number {
    price = price.replace("&nbsp;", "").replace("\u202f", "")
    price = price.slice(0, -"DT".length).replace(',', '.')

    return Number.parseFloat(price)
}
