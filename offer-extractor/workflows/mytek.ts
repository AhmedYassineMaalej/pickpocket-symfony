import { identity, Scrape } from "../instructions.ts"
import { Provider } from "../models/provider.ts"
import { Workflow } from "./workflow.ts"

const provider = new Provider(
    "mytek",
    "https://mk-media.mytek.tn/media/logo/stores/1/LOGO-MYTEK-176PX-INVERSE.png",
    "https://www.mytek.tn/",
)

const categories = new Map([
    ["Memory", "https://www.mytek.tn/informatique/composants-informatique/barrettes-memoire.html"],
    ["GPU", "https://www.mytek.tn/informatique/composants-informatique/carte-graphique.html"]
])

export const workflow = new Workflow({
    provider,
    categories,
    productPrice: new Scrape(".product-container span.final-price", "innerHTML", processPrice),
    productName: new Scrape(".product-container a.product-item-link", "innerHTML", (str: string) => str.trim()),
    productLink: new Scrape(".product-container a.product-item-link", "href", identity),
    productImage: new Scrape(".product-container .product-item-photo img", "src", identity),
    productReference: new Scrape(".product-container .sku", "innerHTML", processReference),
    productInfoKey: new Scrape(".data.table th.col.label", "innerHTML", identity),
    productInfoValue: new Scrape(".data.table td.col.data", "innerHTML", identity),
});



function processReference(price: string): string {
    return price.slice("[".length).slice(0, -"]".length)
}


function processPrice(price: string): number {
    price = price.trim().slice(0, -" DT".length).replace(",", ".")
    price = price.replace("\u202f", "")

    return Number.parseFloat(price)
}
