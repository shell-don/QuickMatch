<?php

namespace App\Services;

use App\Models\Company;
use App\Models\Offer;
use App\Models\Region;
use App\Models\Skill;
use Illuminate\Support\Facades\Log;
use SQLite3;

class CrawlerImportService
{
    private string $crawlerPath;

    private string $dbPath;

    public function __construct()
    {
        $this->crawlerPath = base_path('crawler_smartintern');
        $this->dbPath = base_path('crawler_smartintern/QuickMatch.db');
    }

    public function runSpider(string $spiderName = 'hello_work'): int
    {
        $command = "cd {$this->crawlerPath} && scrapy crawl {$spiderName} -o /tmp/offres.json 2>&1";

        Log::info("Running spider: {$spiderName}");

        $output = shell_exec($command);

        Log::info("Spider output: {$output}");

        return 0;
    }

    public function importOffersFromDatabase(): int
    {
        $count = 0;

        if (! file_exists($this->dbPath)) {
            Log::warning("Crawler database not found: {$this->dbPath}");

            return 0;
        }

        try {
            $db = new SQLite3($this->dbPath);
            $results = $db->query('SELECT * FROM offres ORDER BY id DESC');

            while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
                if ($this->importOffer($row)) {
                    $count++;
                }
            }

            $db->close();
        } catch (\Exception $e) {
            Log::error('Error importing from SQLite: '.$e->getMessage());
        }

        return $count;
    }

    public function importFromJsonFile(string $filePath): int
    {
        if (! file_exists($filePath)) {
            Log::warning("JSON file not found: {$filePath}");

            return 0;
        }

        $content = file_get_contents($filePath);
        $offers = json_decode($content, true);

        if (empty($offers)) {
            return 0;
        }

        $count = 0;
        foreach ($offers as $offerData) {
            if ($this->importOffer($offerData)) {
                $count++;
            }
        }

        return $count;
    }

    private function importOffer(array $data): bool
    {
        try {
            $companyName = $data['nom_entreprise'] ?? 'Unknown';
            $offerTitle = $data['nom_offre'] ?? 'Unknown';
            $sourceUrl = $data['url_offre'] ?? null;
            $tags = $data['tags'] ?? '[]';
            $description = $data['description_poste'] ?? $data['profile_recherche'] ?? null;

            if (empty($offerTitle) || $offerTitle === 'Unknown') {
                return false;
            }

            $company = Company::firstOrCreate(
                ['name' => $companyName],
                [
                    'industry' => $this->detectIndustry($tags),
                    'location' => $this->extractLocation($sourceUrl),
                ]
            );

            $region = $this->detectRegion($sourceUrl);

            $offer = Offer::updateOrCreate(
                ['source_url' => $sourceUrl],
                [
                    'title' => $offerTitle,
                    'description' => $description,
                    'type' => $this->detectOfferType($offerTitle),
                    'status' => 'active',
                    'source' => $this->detectSource($sourceUrl),
                    'company_id' => $company->id,
                    'region_id' => $region?->id,
                ]
            );

            if ($offer->wasRecentlyCreated || $offer->wasChanged()) {
                $this->attachSkillsFromTags($offer, $tags);

                return true;
            }

            return false;
        } catch (\Exception $e) {
            Log::error('Error importing offer: '.$e->getMessage(), $data);

            return false;
        }
    }

    private function attachSkillsFromTags(Offer $offer, string $tagsJson): void
    {
        $tags = json_decode($tagsJson, true);

        if (empty($tags) || ! is_array($tags)) {
            return;
        }

        $skillIds = [];

        foreach ($tags as $tag) {
            if (is_string($tag)) {
                $skill = Skill::firstOrCreate(
                    ['name' => ucfirst(strtolower(trim($tag)))],
                    ['category' => 'detected']
                );
                $skillIds[] = $skill->id;
            }
        }

        if (! empty($skillIds)) {
            $offer->skills()->syncWithoutDetaching($skillIds);
        }
    }

    private function detectOfferType(string $title): string
    {
        $titleLower = strtolower($title);

        if (str_contains($titleLower, 'alternance')) {
            return 'alternance';
        }
        if (str_contains($titleLower, 'stage') || str_contains($titleLower, 'intern')) {
            return 'stage';
        }
        if (str_contains($titleLower, 'cdi')) {
            return 'cdi';
        }
        if (str_contains($titleLower, 'cdd')) {
            return 'cdd';
        }

        return 'stage';
    }

    private function detectSource(?string $url): string
    {
        if (! $url) {
            return 'unknown';
        }

        if (str_contains($url, 'hellowork')) {
            return 'hellowork';
        }
        if (str_contains($url, 'indeed')) {
            return 'indeed';
        }
        if (str_contains($url, 'jobteaser')) {
            return 'jobteaser';
        }
        if (str_contains($url, 'meformerenregion')) {
            return 'meformerenregion';
        }

        return 'unknown';
    }

    private function detectIndustry(string $tagsJson): ?string
    {
        $tags = json_decode($tagsJson, true) ?? [];

        $techKeywords = ['python', 'java', 'javascript', 'php', 'react', 'angular', 'vue', 'node'];
        $dataKeywords = ['data', 'machine learning', 'analytics', 'bi', 'sql'];
        $marketingKeywords = ['marketing', 'seo', 'communication', 'digital'];

        foreach ($tags as $tag) {
            $tagLower = strtolower($tag);
            if (in_array($tagLower, $techKeywords)) {
                return 'Technology';
            }
            if (in_array($tagLower, $dataKeywords)) {
                return 'Data';
            }
            if (in_array($tagLower, $marketingKeywords)) {
                return 'Marketing';
            }
        }

        return null;
    }

    private function detectRegion(?string $url): ?Region
    {
        if (! $url) {
            return null;
        }

        $regionPatterns = [
            'toulouse' => ['Occitanie', 'OCC', 'Haute-Garonne', '31000'],
            'paris' => ['Île-de-France', 'IDF', 'Paris', '75000'],
            'lyon' => ['Auvergne-Rhône-Alpes', 'ARA', 'Rhône', '69000'],
            'bordeaux' => ['Nouvelle-Aquitaine', 'NAQ', 'Gironde', '33000'],
            'marseille' => ['Provence-Alpes-Côte d\'Azur', 'PACA', 'Bouches-du-Rhône', '13000'],
            'montpellier' => ['Occitanie', 'OCC', 'Hérault', '34000'],
            'rennes' => ['Bretagne', 'BRE', 'Ille-et-Vilaine', '35000'],
            'lille' => ['Hauts-de-France', 'HDF', 'Nord', '59000'],
        ];

        $urlLower = strtolower($url);

        foreach ($regionPatterns as $city => $regionData) {
            if (str_contains($urlLower, $city)) {
                return Region::firstOrCreate(
                    ['code' => $regionData[1]],
                    [
                        'name' => $regionData[0],
                        'department' => $regionData[2],
                        'postal_code' => $regionData[3],
                    ]
                );
            }
        }

        return null;
    }

    private function extractLocation(?string $url): ?string
    {
        if (! $url) {
            return null;
        }

        $urlLower = strtolower($url);
        $cities = ['toulouse', 'paris', 'lyon', 'bordeaux', 'marseille', 'montpellier', 'rennes', 'lille'];

        foreach ($cities as $city) {
            if (str_contains($urlLower, $city)) {
                return ucfirst($city);
            }
        }

        return null;
    }
}
