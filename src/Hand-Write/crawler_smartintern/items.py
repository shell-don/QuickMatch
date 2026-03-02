# Define here the models for your scraped items
#
# See documentation in:
# https://docs.scrapy.org/en/latest/topics/items.html

import scrapy


class OffreItem(scrapy.Item):
    nom_offre = scrapy.Field()
    nom_entreprise = scrapy.Field()
    url_offre = scrapy.Field()
    tags = scrapy.Field()
    description_poste = scrapy.Field()
    profile_recherche = scrapy.Field()


class CrawlerSmartinternItem(scrapy.Item):
    # define the fields for your item here like:
    # name = scrapy.Field()
    pass
