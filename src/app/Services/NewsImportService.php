<?php

namespace App\Services;

use App\Models\News;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NewsImportService
{
    private array $rssFeeds = [
        [
            'name' => 'France Travail - Actualités',
            'url' => 'https://www.francetravail.fr/cms/rss/actu.xml',
            'category' => 'emploi',
        ],
    ];

    public function importFromRss(): int
    {
        $count = 0;

        foreach ($this->rssFeeds as $feed) {
            try {
                $response = Http::timeout(30)->get($feed['url']);

                if (! $response->successful()) {
                    Log::warning("Failed to fetch RSS: {$feed['url']}");

                    continue;
                }

                $xml = simplexml_load_string($response->body());

                if (! $xml || ! isset($xml->channel->item)) {
                    continue;
                }

                foreach ($xml->channel->item as $item) {
                    if ($this->importNewsItem($item, $feed)) {
                        $count++;
                    }
                }
            } catch (\Exception $e) {
                Log::error("Error importing RSS {$feed['url']}: ".$e->getMessage());
            }
        }

        return $count;
    }

    private function importNewsItem(\SimpleXMLElement $item, array $feed): bool
    {
        try {
            $title = trim((string) $item->title);
            $link = trim((string) $item->link);
            $description = trim((string) $item->description);
            $pubDate = isset($item->pubDate) ? Carbon::parse((string) $item->pubDate) : now();

            if (empty($title) || empty($link)) {
                return false;
            }

            $news = News::updateOrCreate(
                ['source_url' => $link],
                [
                    'title' => $title,
                    'summary' => $this->cleanHtml($description),
                    'source' => $feed['name'],
                    'category' => $feed['category'],
                    'published_at' => $pubDate,
                ]
            );

            return $news->wasRecentlyCreated;
        } catch (\Exception $e) {
            Log::error('Error importing news item: '.$e->getMessage());

            return false;
        }
    }

    private function cleanHtml(string $html): string
    {
        return strip_tags(html_entity_decode($html));
    }

    public function generateAiSummaries(): int
    {
        $newsWithoutSummary = News::whereNull('ai_summary')
            ->where('published_at', '>=', now()->subWeek())
            ->get();

        $count = 0;

        foreach ($newsWithoutSummary as $news) {
            try {
                $content = $news->summary ?: $news->title;

                $news->ai_summary = $this->generateSummary($content);
                $news->save();

                $count++;
            } catch (\Exception $e) {
                Log::error("Error generating summary for news {$news->id}: ".$e->getMessage());
            }
        }

        return $count;
    }

    private function generateSummary(string $content): string
    {
        $content = substr($content, 0, 1000);

        return "Résumé automatique: {$content}";
    }
}
