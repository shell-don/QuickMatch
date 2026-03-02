<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OllamaService
{
    private string $host;

    private string $model;

    private string $embedModel;

    private int $timeout;

    public function __construct()
    {
        $this->host = config('services.ollama.host', 'http://localhost:11434');
        $this->model = config('services.ollama.model', 'llama3');
        $this->embedModel = config('services.ollama.embed_model', 'nomic-embed-text');
        $this->timeout = config('services.ollama.timeout', 120);
    }

    public function chat(string $prompt, array $context = []): ?string
    {
        $messages = [];

        if (! empty($context)) {
            foreach ($context as $msg) {
                $messages[] = $msg;
            }
        }

        $messages[] = [
            'role' => 'user',
            'content' => $prompt,
        ];

        try {
            $response = Http::timeout($this->timeout)
                ->post("{$this->host}/api/chat", [
                    'model' => $this->model,
                    'messages' => $messages,
                    'stream' => false,
                ]);

            if ($response->successful()) {
                $data = $response->json();

                return $data['message']['content'] ?? null;
            }

            Log::error('Ollama chat error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
        } catch (\Exception $e) {
            Log::error('Ollama chat exception: '.$e->getMessage());
        }

        return null;
    }

    public function generate(string $prompt, ?string $systemPrompt = null): ?string
    {
        $payload = [
            'model' => $this->model,
            'prompt' => $prompt,
            'stream' => false,
        ];

        if ($systemPrompt) {
            $payload['system'] = $systemPrompt;
        }

        try {
            $response = Http::timeout($this->timeout)
                ->post("{$this->host}/api/generate", $payload);

            if ($response->successful()) {
                $data = $response->json();

                return $data['response'] ?? null;
            }

            Log::error('Ollama generate error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
        } catch (\Exception $e) {
            Log::error('Ollama generate exception: '.$e->getMessage());
        }

        return null;
    }

    public function generateEmbedding(string $text): ?array
    {
        try {
            $response = Http::timeout($this->timeout)
                ->post("{$this->host}/api/embeddings", [
                    'model' => $this->embedModel,
                    'prompt' => $text,
                ]);

            if ($response->successful()) {
                $data = $response->json();

                return $data['embedding'] ?? null;
            }

            Log::error('Ollama embedding error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
        } catch (\Exception $e) {
            Log::error('Ollama embedding exception: '.$e->getMessage());
        }

        return null;
    }

    public function isAvailable(): bool
    {
        try {
            $response = Http::timeout(5)
                ->get("{$this->host}/api/tags");

            return $response->successful();
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getAvailableModels(): array
    {
        try {
            $response = Http::timeout(5)
                ->get("{$this->host}/api/tags");

            if ($response->successful()) {
                $data = $response->json();

                return array_map(fn ($m) => $m['name'], $data['models'] ?? []);
            }
        } catch (\Exception $e) {
            Log::error('Ollama list models error: '.$e->getMessage());
        }

        return [];
    }

    public function setModel(string $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function getModel(): string
    {
        return $this->model;
    }
}
