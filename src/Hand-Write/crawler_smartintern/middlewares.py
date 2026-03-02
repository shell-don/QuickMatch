# Define here the models for your spider middleware
#
# See documentation in:
# https://docs.scrapy.org/en/latest/topics/spider-middleware.html

from scrapy import signals
import random
import os

# useful for handling different item types with a single interface
from itemadapter import ItemAdapter


class CrawlerSmartinternSpiderMiddleware:
    # Not all methods need to be defined. If a method is not defined,
    # scrapy acts as if the spider middleware does not modify the
    # passed objects.

    @classmethod
    def from_crawler(cls, crawler):
        # This method is used by Scrapy to create your spiders.
        s = cls()
        crawler.signals.connect(s.spider_opened, signal=signals.spider_opened)
        return s

    def process_spider_input(self, response, spider):
        # Called for each response that goes through the spider
        # middleware and into the spider.

        # Should return None or raise an exception.
        return None

    def process_spider_output(self, response, result, spider):
        # Called with the results returned from the Spider, after
        # it has processed the response.

        # Must return an iterable of Request, or item objects.
        for i in result:
            yield i

    def process_spider_exception(self, response, exception, spider):
        # Called when a spider or process_spider_input() method
        # (from other spider middleware) raises an exception.

        # Should return either None or an iterable of Request or item objects.
        pass

    async def process_start(self, start):
        # Called with an async iterator over the spider start() method or the
        # matching method of an earlier spider middleware.
        async for item_or_request in start:
            yield item_or_request

    def spider_opened(self, spider):
        spider.logger.info("Spider opened: %s" % spider.name)


class UARotatorMiddleware:
    def __init__(self, user_agents):
        self.user_agents = user_agents

    @classmethod
    def from_crawler(cls, crawler):
        user_agents = crawler.settings.get("USER_AGENTS", [])
        return cls(user_agents)

    def process_request(self, request, spider):
        user_agent = random.choice(self.user_agents)
        request.headers["User-Agent"] = user_agent


class ProxyDebugMiddleware:
    def process_request(self, request, spider):
        proxy = request.meta.get("proxy")
        spider.logger.info(f"🧭 Proxy utilisé: {proxy}")


# Send "Change IP" signal to tor control port
class TorMiddleware(object):
    def process_request(self, request, spider):
        """
        You must first install the nc program and Tor service on your GNU Linux operating system
        After that and change /etc/tor/torrc, add
        control port and password to it.
        install privoxy for having HTTP and HTTPS over torSOCKS5
        """
        # Deploy : add controlport and password to /etc/tor/torrc
        os.system(
            """(echo authenticate '"jd oæ∂ÈœÈ≈÷‡‡πÂÈ∂÷÷÷÷÷::{÷(é33556œæÈnojjxpf,iej§nkhfioqncu"'; echo signal newnym; echo quit) | nc localhost 9051"""
        )
        request.meta["proxy"] = settings.get("HTTP_PROXY")


import time
from stem import Signal
from stem.control import Controller


class TorProxyMiddleware2:
    def __init__(self, tor_password):
        self.tor_password = tor_password
        self.last_signal = time.time()

    @classmethod
    def from_crawler(cls, crawler):
        return cls(
            tor_password=crawler.settings.get(
                "jd oæ∂ÈœÈ≈÷‡‡πÂÈ∂÷÷÷÷÷::{÷(é33556œæÈnojjxpf,iej§nkhfioqncu"
            )
        )

    def process_request(self, request, spider):
        request.meta["proxy"] = "socks5://127.0.0.1:9050"
        spider.logger.info(f"➡️ Envoi requête via Tor: {request.url}")

    def renew_tor_identity(self):
        with Controller.from_port(port=9051) as controller:
            controller.authenticate(password=self.tor_password)
            controller.signal(Signal.NEWNYM)


class TorProxyMiddleware3:
    def __init__(self):
        self.proxy = "http://127.0.0.1:8118"
        self.tor_password = (
            "jd oæ∂ÈœÈ≈÷‡‡πÂÈ∂÷÷÷÷÷::{÷(é33556œæÈnojjxpf,iej§nkhfioqncu"
        )
        self.request_count = 0
        self.renew_every = 10  # Changer d'IP tous les 10 requêtes

    @classmethod
    def from_crawler(cls, crawler):
        middleware = cls()
        crawler.signals.connect(
            middleware.spider_opened, signal=signals.spider_opened
        )
        return middleware

    def spider_opened(self, spider):
        spider.logger.info("🧅 Middleware Tor activé via Privoxy")
        spider.logger.info(
            f"🔄 Changement d'IP tous les {self.renew_every} requêtes"
        )

    def process_request(self, request, spider):
        # Forcer le proxy Tor
        request.meta["proxy"] = self.proxy

        # Changer d'IP tous les X requêtes
        self.request_count += 1
        if self.request_count % self.renew_every == 0:
            self.renew_tor_identity(spider)

        spider.logger.debug(
            f"➡️ Requête #{self.request_count} via Tor: {request.url}"
        )

    def renew_tor_identity(self, spider):
        """Force Tor à changer de circuit"""
        try:
            with Controller.from_port(port=9050) as controller:
                controller.authenticate(password=self.tor_password)
                controller.signal(Signal.NEWNYM)
                spider.logger.info("🔄 Nouvelle identité Tor activée")
                import time

                time.sleep(5)  # Attendre que le nouveau circuit soit établi
        except Exception as e:
            spider.logger.warning(f"⚠️ Erreur lors du renouvellement Tor: {e}")


class TorProxyMiddleware:
    def __init__(self, tor_password):
        self.tor_password = tor_password
        self.last_signal = time.time()

    @classmethod
    def from_crawler(cls, crawler):
        return cls(tor_password=crawler.settings.get("TOR_PASSWORD"))

    def process_request(self, request, spider):
        request.meta["proxy"] = "socks5://127.0.0.1:9050"

    def renew_tor_identity(self):
        with Controller.from_port(port=9051) as controller:
            controller.authenticate(password=self.tor_password)
            controller.signal(Signal.NEWNYM)


class CrawlerSmartinternDownloaderMiddleware:
    # Not all methods need to be defined. If a method is not defined,
    # scrapy acts as if the downloader middleware does not modify the
    # passed objects.

    def process_response(self, request, response, spider):
        # Called with the response returned from the downloader.

        # Must either;
        # - return a Response object
        # - return a Request object
        # - or raise IgnoreRequest
        return response

    def process_exception(self, request, exception, spider):
        # Called when a download handler or a process_request()
        # (from other downloader middleware) raises an exception.

        # Must either:
        # - return None: continue processing this exception
        # - return a Response object: stops process_exception() chain
        # - return a Request object: stops process_exception() chain
        pass

    def spider_opened(self, spider):
        spider.logger.info("Spider opened: %s" % spider.name)
