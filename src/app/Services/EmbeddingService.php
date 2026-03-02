<?php

namespace App\Services;

class EmbeddingService
{
    private OllamaService $ollama;

    public function __construct(OllamaService $ollama)
    {
        $this->ollama = $ollama;
    }

    public function generate(string $text): ?array
    {
        $text = $this->prepareText($text);

        return $this->ollama->generateEmbedding($text);
    }

    public function generateBatch(array $texts): array
    {
        $embeddings = [];

        foreach ($texts as $text) {
            $embedding = $this->generate($text);
            $embeddings[] = $embedding ?? array_fill(0, 768, 0);
        }

        return $embeddings;
    }

    private function prepareText(string $text): string
    {
        $text = preg_replace('/\s+/', ' ', $text);
        $text = trim($text);

        if (strlen($text) > 8000) {
            $text = substr($text, 0, 8000);
        }

        return $text;
    }

    public function cosineSimilarity(array $a, array $b): float
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

    public function euclideanDistance(array $a, array $b): float
    {
        if (count($a) !== count($b)) {
            return -1;
        }

        $sum = 0;
        for ($i = 0; $i < count($a); $i++) {
            $sum += pow($a[$i] - $b[$i], 2);
        }

        return sqrt($sum);
    }
}
