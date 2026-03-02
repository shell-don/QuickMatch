#!/bin/bash

echo "=== $(date) - Début du crawl quotidien ==="

# 1. Lancer le crawler HelloWork
cd ~/Documents/Informatique/AUTRES/PHP/Laravel/SmartIntern/crawler_smartintern
source venv/bin/activate
scrapy crawl hello_work2 -s LOG_FILE=crawl.log

# 2. Si nouvelles données et RAG activé
if [ $? -eq 0 ]; then
    echo "Crawl terminé, indexing..."
    cd ~/Documents/Informatique/AUTRES/PHP/Laravel/SmartIntern/smartintern-ai
    source venv/bin/activate
    python -m app.scraper.indexer
fi

echo "=== $(date) - Fin du crawl quotidien ==="
