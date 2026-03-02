<?php

namespace App\Console\Commands\Crawler;

use App\Services\NewsImportService;
use Illuminate\Console\Command;

class ImportNewsCommand extends Command
{
    protected $signature = 'news:import {--summaries : Générer les résumés IA pour les nouvelles}
                            {--dry-run : Simuler l\'import sans insérer en base}';

    protected $description = 'Importe les actualités depuis les flux RSS';

    public function __construct(private NewsImportService $newsService)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $this->info('🚀 Démarrage de l\'import des actualités...');

        $dryRun = $this->option('dry-run');
        $summaries = $this->option('summaries');

        if ($dryRun) {
            $this->warn('🔍 Mode dry-run activé');
        }

        if (! $dryRun) {
            $count = $this->newsService->importFromRss();
            $this->info("✅ {$count} actualités importées!");
        }

        if ($summaries && ! $dryRun) {
            $this->info('🤖 Génération des résumés IA...');
            $summaryCount = $this->newsService->generateAiSummaries();
            $this->info("✅ {$summaryCount} résumés générés!");
        }

        return Command::SUCCESS;
    }
}
