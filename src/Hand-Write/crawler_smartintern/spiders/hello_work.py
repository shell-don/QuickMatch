from scrapy.linkextractors import LinkExtractor
from scrapy.spiders import CrawlSpider, Rule


class HelloWorkSpider(CrawlSpider):
    name = "hello_work"
    allowed_domains = ["www.hellowork.com"]
    # On remarque que qu'il n'y a pas de lien pour la page suivante
    # Or en regardant l'url on remarque que ?p=2 amène sur la deuxième page.
    start_urls = [
        "https://www.hellowork.com/fr-fr/emploi/ville_toulouse-31000.html"
    ]

    current_page = 1

    def process_value(self, value):
        if HelloWorkSpider.current_page >= 2:
            return None
        if value and value != "":
            return None
        HelloWorkSpider.current_page += 1
        return f"{self.start_urls[0]}?p={HelloWorkSpider.current_page}"

    # Règle : regarde dans l'ensemble des balises du xpath leurs href, et vérifie
    # qu'ils restent sur le site et qu'ils sont dans la bonne catégorie, puis appelle
    # la fonction parse. rules peut contenir plusieurs règle
    rules = (
        # Première règle s'applique pour récupérer la page suivante layer 1.
        Rule(
            LinkExtractor(
                allow=r"https://www.hellowork.com/fr-fr/emplois/",
                allow_domains="www.hellowork.com",
                process_value=process_value,
                tags="button",
                attrs=("href",),  # default
            ),
            callback="parse_offer",
            follow=True,
        )
    )

    def parse_offer(self, response):
        item = {}
        item["nom_offre"] = response.xpath(
            '//a[@data-cy="offerTitle"]//p[contains(@class, "tw-typo-l")]/text()'
        ).get()
        item["nom_entreprise"] = response.xpath(
            '//a[@data-cy="offerTitle"]//p[contains(@class, "tw-typo-s")]/text()'
        ).get()
        yield item

    def parse_offer_details(self, response):
        item = {}
        item["nom_offre"] = response.xpath(
            '//a[@data-cy="offerTitle"]//p[contains(@class, "tw-typo-l")]/text()'
        ).get()
        item["nom_entreprise"] = response.xpath(
            '//a[@data-cy="offerTitle"]//p[contains(@class, "tw-typo-s")]/text()'
        ).get()
        yield item

        # rentrer dans l'anonce
        # next_page = response.xpath('//a[@data-cy="offerTitle"]/@href').get()
