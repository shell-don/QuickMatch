import scrapy
from scrapy_selenium import SeleniumRequest
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC


class PageJauneSpider(scrapy.Spider):

    name = "page_jaune"
    allowed_domains = ["www.pagesjaunes.fr"]
    start_urls = [
        "https://www.pagesjaunes.fr/annuaire/toulouse-31/professionnels"
    ]

    def __init__(self):
        self.current_page = 1

    def process_next_page(self) -> str:
        # Condition d'arrêt
        if self.current_page >= 1 or self.current_page >= 974:
            return None
        self.current_page += 1
        return f"{self.start_urls[0]}/{self.current_page}"

    def start_requests(self):
        yield SeleniumRequest(
            url=self.start_urls[0],
            callback=self.parse,
        )

    def parse(self, response):

        yield response
