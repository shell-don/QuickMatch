<?php

namespace App\Services;

use App\Models\News;
use Illuminate\Support\Facades\Log;

class RagService
{
    private OllamaService $ollama;

    private EmbeddingService $embedding;

    public function __construct(OllamaService $ollama, EmbeddingService $embedding)
    {
        $this->ollama = $ollama;
        $this->embedding = $embedding;
    }

    public function summarizeNews(News $news): ?string
    {
        $content = $news->summary ?: $news->content;

        if (empty($content)) {
            return null;
        }

        $systemPrompt = <<<'PROMPT'
Tu es un assistant qui synthétise des articles d'actualité sur l'emploi, la formation et les stages.
Ta mission est de créer un résumé concis (2-3 phrases) qui capture l'essence de l'article.
Le résumé doit être en français, clair et accessible à des étudiants en recherche de stage.
PROMPT;

        $prompt = "Résume cet article d'actualité:\n\n{$content}";

        $summary = $this->ollama->chat($prompt, [
            ['role' => 'system', 'content' => $systemPrompt],
        ]);

        return $summary;
    }

    public function generateSummaryForAllNews(): int
    {
        $newsList = News::whereNull('ai_summary')
            ->where('published_at', '>=', now()->subMonth())
            ->get();

        $count = 0;

        foreach ($newsList as $news) {
            try {
                $summary = $this->summarizeNews($news);

                if ($summary) {
                    $news->ai_summary = $summary;
                    $news->save();
                    $count++;
                }
            } catch (\Exception $e) {
                Log::error("Error generating summary for news {$news->id}: ".$e->getMessage());
            }
        }

        return $count;
    }

    public function findRelevantNews(string $query, int $limit = 5): array
    {
        $queryEmbedding = $this->embedding->generate($query);

        if (! $queryEmbedding) {
            return [];
        }

        $newsList = News::whereNotNull('published_at')
            ->where('published_at', '>=', now()->subMonth())
            ->orderBy('published_at', 'desc')
            ->limit(50)
            ->get();

        $relevantNews = [];

        foreach ($newsList as $news) {
            $content = $news->title.' '.($news->summary ?: '');
            $newsEmbedding = $this->embedding->generate($content);

            if ($newsEmbedding) {
                $similarity = $this->cosineSimilarity($queryEmbedding, $newsEmbedding);
                $relevantNews[] = [
                    'news' => $news,
                    'similarity' => $similarity,
                ];
            }
        }

        usort($relevantNews, function ($a, $b) {
            return $b['similarity'] <=> $a['similarity'];
        });

        return array_slice($relevantNews, 0, $limit);
    }

    public function answerQuestionAboutNews(string $question): array
    {
        $relevantNews = $this->findRelevantNews($question, 3);

        if (empty($relevantNews)) {
            return [
                'success' => false,
                'error' => 'Aucune actualité pertinente trouvée',
                'answer' => null,
            ];
        }

        $context = "Voici des actualités récentes:\n\n";

        foreach ($relevantNews as $item) {
            $news = $item['news'];
            $context .= "- {$news->published_at->format('d/m/Y')}: {$news->title}\n";
            if ($news->ai_summary) {
                $context .= "  Résumé: {$news->ai_summary}\n";
            }
            $context .= "\n";
        }

        $systemPrompt = <<<'PROMPT'
Tu es un assistant qui répond aux questions sur les actualités de l'emploi, de la formation et des stages.
Utilise le contexte fourni pour répondre de manière précise et concise.
Si tu ne trouves pas la réponse dans le contexte, dis-le clairement.
PROMPT;

        $prompt = "Question: {$question}\n\n{$context}";

        $answer = $this->ollama->chat($prompt, [
            ['role' => 'system', 'content' => $systemPrompt],
        ]);

        return [
            'success' => $answer !== null,
            'error' => $answer ? null : 'Impossible de générer une réponse',
            'answer' => $answer,
            'sources' => array_map(fn ($item) => [
                'title' => $item['news']->title,
                'url' => $item['news']->source_url,
                'date' => $item['news']->published_at?->format('d/m/Y'),
            ], $relevantNews),
        ];
    }

    private function cosineSimilarity(array $a, array $b): float
    {
        if (count($a) !== count($b) || empty($a)) {
            return 0;
        }

        $dotProduct = 0;
        $normA = 0;
        $normB = 0;

        for ($i = 0; $i < count($a); $i++) {
            $dotProduct += $a[$i] * $b[$i];
            $normA += $a[$i] * $a[$i];
            $normB += $b[$i] * $b[$i];
        }

        $normA = sqrt($normA);
        $normB = sqrt($normB);

        if ($normA === 0 || $normB === 0) {
            return 0;
        }

        return $dotProduct / ($normA * $normB);
    }
}
