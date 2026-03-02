# Scrapy settings for crawler_smartintern project
#
# For simplicity, this file contains only settings considered important or
# commonly used. You can find more settings consulting the documentation:
#
#     https://docs.scrapy.org/en/latest/topics/settings.html
#     https://docs.scrapy.org/en/latest/topics/downloader-middleware.html
#     https://docs.scrapy.org/en/latest/topics/spider-middleware.html

# from rotating_proxies.middlewares import (
#    RotatingProxyMiddleware,
#    BanDetectionMiddleware,
# )


BOT_NAME = "None"

SPIDER_MODULES = ["crawler_smartintern.spiders"]
NEWSPIDER_MODULE = "crawler_smartintern.spiders"

ADDONS = {}


# Crawl responsibly by identifying yourself (and your website) on the user-agent
# USER_AGENT = [
#    "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.111 Safari/537.36 OPR/72.0.3815.186",
#    "Mozilla/5.0 (iPad; CPU OS 15_6_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Mobile/15E148 OPT/3.2.13",
#    "Mozilla/5.0 (Windows NT 10.0; Win64; x64; WebView/3.0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36 Edge/16.16299",
#    "Mozilla/5.0 (X11) AppleWebKit/62.41 (KHTML, like Gecko) Edge/17.10859 Safari/452.6",
#    "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/54.0.2840.71 Safari/537.36",
#    "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.120 Safari/537.36",
#    "Chrome/103.0.5060.63 CFNetwork/1390 Darwin/22.0.0",
#    "Mozilla/5.0 (X11; OpenBSD i386) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/36.0.1985.125 Safari/537.36",
#    "Mozilla/5.0 (X11; NetBSD) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/27.0.1453.116 Safari/537.36",
# ]

# Tor services
# HTTP_PROXY = "http://localhost:8118"
# Configuration Tor via Privoxy
# HTTP_PROXY = "http://127.0.0.1:8118"
# HTTPS_PROXY = "http://127.0.0.1:8118"

DOWNLOADER_MIDDLEWARES = {
    #    "rotating_proxies.middlewares.RotatingProxyMiddleware": 610,
    #    "rotating_proxies.middlewares.BanDetectionMiddleware": 620,
    # "crawler_smartintern.middlewares.TorProxyMiddleware3": 100,
    # "crawler_smartintern.middlewares.ProxyDebugMiddleware": 690,
}

# Obey robots.txt rules
ROBOTSTXT_OBEY = False

# Concurrency and throttling settings
CONCURRENT_REQUESTS = 2
CONCURRENT_REQUESTS_PER_DOMAIN = 1
DOWNLOAD_TIMEOUT = 30
DOWNLOAD_DELAY = 1  # 1 seconds between requests
AUTOTHROTTLE_ENABLED = False

# ROTATING_PROXY_LIST_PATH = "/Users/mathis/Documents/Projets/Informatique/Python/TradingPipeline/Code/Modules/Scrapping/crawler_smartintern/https.txt"

# Disable cookies (enabled by default)
COOKIES_ENABLED = False

# Disable Telnet Console (enabled by default)
# TELNETCONSOLE_ENABLED = False

# Override the default request headers:
# DEFAULT_REQUEST_HEADERS = {
#    "Accept": "text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
#    "Accept-Language": "en",
# }
DEFAULT_REQUEST_HEADERS = {
    "Accept": "text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
    "Accept-Language": "fr-FR,fr;q=0.9,en-US;q=0.8,en;q=0.7",
}

# Enable or disable spider middlewares
# See https://docs.scrapy.org/en/latest/topics/spider-middleware.html
# SPIDER_MIDDLEWARES = {
#    "crawler_smartintern.middlewares.CrawlerSmartinternSpiderMiddleware": 543,
# }

# Enable or disable extensions
# See https://docs.scrapy.org/en/latest/topics/extensions.html
# EXTENSIONS = {
#    "scrapy.extensions.telnet.TelnetConsole": None,
# }

# Configure item pipelines
# See https://docs.scrapy.org/en/latest/topics/item-pipeline.html
SQLITE_DB_PATH = "crawler_smartintern/QuickMatch.db"
ITEM_PIPELINES = {
    "crawler_smartintern.pipelines.SQLitePipeline": 300,
    "crawler_smartintern.pipelines.SmartInternDatabasePipeline": 400,
}

# Enable and configure the AutoThrottle extension (disabled by default)
# See https://docs.scrapy.org/en/latest/topics/autothrottle.html
# AUTOTHROTTLE_ENABLED = True
# The initial download delay
# AUTOTHROTTLE_START_DELAY = 5
# The maximum download delay to be set in case of high latencies
# AUTOTHROTTLE_MAX_DELAY = 60
# The average number of requests Scrapy should be sending in parallel to
# each remote server
# AUTOTHROTTLE_TARGET_CONCURRENCY = 1.0
# Enable showing throttling stats for every response received:
# AUTOTHROTTLE_DEBUG = False

# Enable and configure HTTP caching (disabled by default)
# See https://docs.scrapy.org/en/latest/topics/downloader-middleware.html#httpcache-middleware-settings
# HTTPCACHE_ENABLED = True
# HTTPCACHE_EXPIRATION_SECS = 0
# HTTPCACHE_DIR = "httpcache"
# HTTPCACHE_IGNORE_HTTP_CODES = []
# HTTPCACHE_STORAGE = "scrapy.extensions.httpcache.FilesystemCacheStorage"

# Set settings whose default value is deprecated to a future-proof value
FEED_EXPORT_ENCODING = "utf-8"
