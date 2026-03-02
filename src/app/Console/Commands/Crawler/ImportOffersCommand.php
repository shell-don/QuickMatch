<?php

namespace App\Console\Commands\Crawler;

use App\Services\CrawlerImportService;
use Illuminate\Console\Command;

class ImportOffersCommand extends Command
{
    protected $signature = 'crawler:import {--spider=hello_work : Nom du spider Scrapy à exécuter}
                            {--from-json= : Chemin vers un fichier JSON à importer}
                            {--dry-run : Simuler l\'import sans insérer en base}';

    protected $description = 'Exécute le crawler et importe les offres dans la base de données';

    public function __construct(private CrawlerImportService $crawlerService)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $this->info('🚀 Démarrage de l\'import des offres...');

        $spider = $this->option('spider');
        $jsonFile = $this->option('from-json');
        $dryRun = $this->option('dry-run');

        if ($dryRun) {
            $this->warn('🔍 Mode dry-run activé - aucune donnée ne sera insérée');
        }

        if ($jsonFile) {
            $this->info("📄 Import depuis le fichier: {$jsonFile}");

            if (! $dryRun) {
                $count = $this->crawlerService->importFromJsonFile($jsonFile);
                $this->info("✅ {$count} offres importées avec succès!");
            }
        } else {
            $this->info("🕷️ Exécution du spider: {$spider}");

            if (! $dryRun) {
                $this->crawlerService->runSpider($spider);
                $this->info('📥 Import depuis la base SQLite...');
                $count = $this->crawlerService->importOffersFromDatabase();
                $this->info("✅ {$count} offres importées avec succès!");
            }
        }

        return Command::SUCCESS;
    }
}
