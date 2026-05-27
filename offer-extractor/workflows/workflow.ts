import type { Browser } from "../browser.ts";
import { GetCategory, ScrapeCategory, ScrapeOffers, ScrapeProductInfo, ScrapeProvider, type Instruction, type Scrape } from "../instructions.ts";
import { Category } from "../models/category.ts";
import type { Offer } from "../models/offer.ts";
import { Provider } from "../models/provider.ts";

export interface WorkflowConfig {
    provider: Provider;
    productName: Scrape<string>;
    productPrice: Scrape<number>;
    productLink: Scrape<string>;
    productImage: Scrape<string>;
    productReference: Scrape<string>;
    productInfoKey: Scrape<string>;
    productInfoValue: Scrape<string>;
    categories: Map<string, string>;
}


export class Workflow implements Instruction<Offer[]> {
    categories: Map<Category, string>;
    provider: Provider;
    scrapeOffers: ScrapeOffers;


    constructor(workflowConfig: WorkflowConfig) {
        this.provider = workflowConfig.provider;

        this.categories = new Map();

        for (const [category, url] of workflowConfig.categories) {
            this.categories.set(new Category(category), url);
        }

        const scrapeProductInfo = new ScrapeProductInfo({
            scrapeKeys: workflowConfig.productInfoKey,
            scrapeValues: workflowConfig.productInfoValue,
        });

        this.scrapeOffers = new ScrapeOffers({
            scrapeNames: workflowConfig.productName,
            scrapePrice: workflowConfig.productPrice,
            scrapeImages: workflowConfig.productImage,
            scrapeLinks: workflowConfig.productLink,
            scrapeReferences: workflowConfig.productReference,
            scrapeProductInfo: scrapeProductInfo,
        });
    }

    async accept(browser: Browser): Promise<Offer[]> {
        const getCategories: GetCategory[] = [];

        this.categories.forEach((url, category) => {
            getCategories.push(new GetCategory(url, new ScrapeCategory(category, this.scrapeOffers)));
        });

        const scrapeProvider = new ScrapeProvider(this.provider, ...getCategories);

        return await browser.execute(scrapeProvider);
    }
}
