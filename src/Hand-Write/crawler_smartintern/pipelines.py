# Define your item pipelines here
#
# Don't forget to add your pipeline to the ITEM_PIPELINES setting
# See: https://docs.scrapy.org/en/latest/topics/item-pipeline.html


# useful for handling different item types with a single interface
# Pipelines est utile pour process la data une fois scraper
# cleansing HTML data
# validating scraped data (checking that the items contain certain fields)
# checking for duplicates (and dropping them)
# storing the scraped item in a database

# 3 fichiers à modifier, pipelines, api.php, routes zt app/JobController, settings (activé le)
import sqlite3
import json
import re
import os
from datetime import datetime
from itemadapter import ItemAdapter
from scrapy.exceptions import DropItem, NotConfigured


class SQLitePipeline:
    def __init__(self, db_path="QuickMatch.db"):
        self.db_path = db_path
        self.conn = None
        self.cursor = None

    @classmethod
    def from_crawler(cls, crawler):
        """Méthode pour récupérer les settings"""
        db_path = crawler.settings.get("SQLITE_DB_PATH", "QuickMatch.db")
        return cls(db_path=db_path)

    def open_spider(self, spider):
        """Connexion et création de la table"""
        self.conn = sqlite3.connect(self.db_path)
        self.cursor = self.conn.cursor()

        # Activer les clés étrangères
        self.cursor.execute("PRAGMA foreign_keys = ON")

        # Créer la table avec les bonnes colonnes
        self.cursor.execute(
            """
            CREATE TABLE IF NOT EXISTS offres (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                nom_offre TEXT NOT NULL,
                nom_entreprise TEXT,
                url_offre TEXT UNIQUE,
                tags TEXT,
                description_poste TEXT,
                profile_recherche TEXT,
                date_scraping TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
            """
        )

        # Créer des index pour les recherches rapides
        # ✅ CORRECTION : Utiliser les colonnes qui existent dans la table
        self.cursor.execute(
            "CREATE INDEX IF NOT EXISTS idx_nom_offre ON offres(nom_offre)"
        )
        self.cursor.execute(
            "CREATE INDEX IF NOT EXISTS idx_nom_entreprise ON offres(nom_entreprise)"
        )

        self.conn.commit()
        spider.logger.info(
            f"✅ Base de données SQLite '{self.db_path}' initialisée"
        )

    def close_spider(self, spider):
        """Fermeture de la connexion"""
        if self.conn:
            self.conn.close()
            spider.logger.info("🔒 Connexion fermée")

    def process_item(self, item, spider):
        """Insertion avec gestion d'erreurs"""
        adapter = ItemAdapter(item)

        # Validation
        if not adapter.get("nom_offre"):
            raise DropItem(f"❌ Item sans nom_offre: {item}")

        # Nettoyer les données
        nom_offre = adapter.get("nom_offre", "").strip()
        nom_entreprise = adapter.get("nom_entreprise", "").strip()
        url_offre = adapter.get("url_offre", "").strip()

        # Convertir tags (liste) en JSON string
        tags = adapter.get("tags", [])
        tags_json = json.dumps(tags, ensure_ascii=False) if tags else "[]"

        description_poste = adapter.get("description_poste", "").strip()
        profile_recherche = adapter.get("profile_recherche", "").strip()

        try:
            # ✅ CORRECTION : Correspondre les colonnes et valeurs
            self.cursor.execute(
                """
                INSERT OR IGNORE INTO offres 
                (nom_offre, nom_entreprise, url_offre, tags, description_poste, profile_recherche)
                VALUES (?, ?, ?, ?, ?, ?)
                """,
                (
                    nom_offre,
                    nom_entreprise,
                    url_offre,
                    tags_json,
                    description_poste,
                    profile_recherche,
                ),
            )

            # Commit immédiat (ou par batch si tu veux optimiser)
            if self.cursor.rowcount > 0:
                self.conn.commit()
                spider.logger.debug(f"✅ Sauvegardé: {nom_offre}")
            else:
                spider.logger.debug(
                    f"⚠️ Doublon ignoré: {nom_offre} ({url_offre})"
                )

        except sqlite3.Error as e:
            spider.logger.error(f"❌ Erreur SQLite: {e} - Item: {nom_offre}")
            self.conn.rollback()
            raise DropItem(f"Erreur BDD: {e}")

        return item


class SmartInternDatabasePipeline:
    """Pipeline qui insère les données dans la base SmartIntern"""
    
    def __init__(self):
        # Chemin vers la base de données SmartIntern
        db_path = os.path.join(
            os.path.dirname(os.path.dirname(__file__)),
            'SmartIntern',
            'database',
            'database.sqlite'
        )
        # Convertir chemin relatif en absolu
        db_path = os.path.abspath(db_path)
        self.db_path = db_path
        self.conn = None
        self.cursor = None
    
    @classmethod
    def from_crawler(cls, crawler):
        return cls()
    
    def open_spider(self, spider):
        spider.logger.info(f"📂 Connexion à SmartIntern DB: {self.db_path}")
        self.conn = sqlite3.connect(self.db_path)
        self.cursor = self.conn.cursor()
        self.cursor.execute("PRAGMA foreign_keys = ON")
        spider.logger.info("✅ Connexion SmartIntern établie")
    
    def close_spider(self, spider):
        if self.conn:
            self.conn.close()
            spider.logger.info("🔒 Connexion SmartIntern fermée")
    
    def process_item(self, item, spider):
        adapter = ItemAdapter(item)
        
        # Parser les tags
        tags = adapter.get("tags", [])
        
        city, region = self._parse_location(tags)
        contract_type = self._parse_type(tags)
        salary_min, salary_max = self._parse_salary(tags)
        is_remote = self._parse_remote(tags)
        level = self._parse_level(tags)
        industry = self._parse_industry(tags)
        
        # Company
        company_id = self._get_or_create_company(
            adapter.get("nom_entreprise"), 
            industry
        )
        
        # Region
        region_id = self._get_or_create_region(region)
        
        # Vérifier si l'offre existe déjà
        url_offre = adapter.get("url_offre", "").strip()
        if not url_offre:
            spider.logger.debug("⚠️ Pas d'URL, offre ignorée")
            return item
        
        self.cursor.execute(
            "SELECT id FROM offers WHERE source_url = ?",
            (url_offre,)
        )
        if self.cursor.fetchone():
            spider.logger.debug(f"⚠️ Offre déjà existante: {url_offre}")
            return item
        
        # Insérer l'offre
        try:
            self.cursor.execute("""
                INSERT INTO offers (
                    title, description, requirements, type, status,
                    salary_min, salary_max, is_remote,
                    source_url, source, company_id, region_id,
                    created_at, updated_at
                ) VALUES (?, ?, ?, ?, 'active', ?, ?, ?, ?, 'hellowork', ?, ?, ?, ?)
            """, (
                adapter.get("nom_offre"),
                adapter.get("description_poste", ""),
                adapter.get("profile_recherche", ""),
                contract_type,
                salary_min,
                salary_max,
                is_remote,
                url_offre,
                company_id,
                region_id,
                datetime.now(),
                datetime.now()
            ))
            
            self.conn.commit()
            spider.logger.info(f"✅ Inserted: {adapter.get('nom_offre')}")
            
        except sqlite3.Error as e:
            spider.logger.error(f"❌ Erreur insert: {e}")
            self.conn.rollback()
        
        return item
    
    def _parse_location(self, tags):
        """Parse 'Toulouse - 31' -> city='Toulouse', region='Haute-Garonne'"""
        if not tags:
            return None, None
        
        for tag in tags:
            match = re.match(r'^(.+) - (\d{2})$', tag)
            if match:
                city = match.group(1).strip()
                dept_map = {
                    '31': 'Haute-Garonne', '75': 'Paris', '69': 'Lyon',
                    '13': 'Bouches-du-Rhône', '92': 'Hauts-de-Seine',
                    '33': 'Gironde', '59': 'Nord', '44': 'Loire-Atlantique'
                }
                region = dept_map.get(match.group(2))
                return city, region
        
        return None, None
    
    def _parse_type(self, tags):
        """Parse 'CDI' -> 'cdi'"""
        type_map = {'CDI': 'cdi', 'CDD': 'cdd', 'Stage': 'stage', 'Alternance': 'alternance'}
        for tag in tags:
            if tag in type_map:
                return type_map[tag]
        return 'stage'
    
    def _parse_salary(self, tags):
        """Parse '30 000 - 40 000 € / an' -> 30000, 40000"""
        for tag in tags:
            # matcher "30 000 - 40 000" ou "30000 - 40000"
            match = re.search(r'(\d+)\s?000.*?(\d+)\s?000', tag.replace(' ', ''))
            if match:
                return int(match.group(1)) * 1000, int(match.group(2)) * 1000
        return None, None
    
    def _parse_remote(self, tags):
        """Parse 'Télétravail partiel' -> True"""
        if not tags:
            return False
        return any('téléravail' in tag.lower() for tag in tags if tag)
    
    def _parse_level(self, tags):
        """Parse 'Bac +2' -> 'Bac+2'"""
        for tag in tags:
            if 'bac' in tag.lower():
                return tag.replace(' ', '+')
        return None
    
    def _parse_industry(self, tags):
        """Parse industry from tags"""
        skip_words = ['CDI', 'CDD', 'Stage', 'Alternance', 'téléravail', 'Exp.']
        for tag in tags:
            if tag not in skip_words and not re.match(r'.*\d+.*', tag) and len(tag) > 3:
                return tag
        return None
    
    def _get_or_create_company(self, name, industry):
        if not name:
            return None
        self.cursor.execute("SELECT id FROM companies WHERE name = ?", (name,))
        result = self.cursor.fetchone()
        if result:
            return result[0]
        try:
            self.cursor.execute(
                "INSERT INTO companies (name, industry, created_at, updated_at) VALUES (?, ?, ?, ?)",
                (name, industry, datetime.now(), datetime.now())
            )
            self.conn.commit()
            return self.cursor.lastrowid
        except sqlite3.Error:
            return None
    
    def _get_or_create_region(self, name):
        if not name:
            return None
        self.cursor.execute("SELECT id FROM regions WHERE name = ?", (name,))
        result = self.cursor.fetchone()
        return result[0] if result else None


class CrawlerSmartinternPipeline:
    def process_item(self, item, spider):
        return item
