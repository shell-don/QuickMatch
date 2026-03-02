<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SqlGenerationService
{
    private OllamaService $ollama;

    private array $allowedTables = [
        'offers',
        'companies',
        'professions',
        'formations',
        'skills',
        'regions',
    ];

    private array $allowedColumns = [
        'offers' => ['id', 'title', 'description', 'type', 'status', 'salary_min', 'salary_max', 'duration', 'start_date', 'is_remote', 'company_id', 'region_id', 'profession_id', 'created_at'],
        'companies' => ['id', 'name', 'description', 'industry', 'size', 'location', 'region_id', 'created_at'],
        'professions' => ['id', 'name', 'description', 'rome_code', 'created_at'],
        'formations' => ['id', 'title', 'description', 'level', 'duration', 'school', 'city', 'type', 'region_id', 'created_at'],
        'skills' => ['id', 'name', 'category', 'created_at'],
        'regions' => ['id', 'name', 'code', 'department', 'postal_code', 'created_at'],
    ];

    private array $dangerousKeywords = [
        'DROP', 'DELETE', 'TRUNCATE', 'ALTER', 'CREATE', 'INSERT', 'UPDATE',
        'GRANT', 'REVOKE', 'EXEC', 'EXECUTE', 'LOAD', 'INTO OUTFILE',
        ';--', 'UNION', 'UNION ALL', 'WAITFOR', 'DELAY',
    ];

    public function __construct(OllamaService $ollama)
    {
        $this->ollama = $ollama;
    }

    public function processQuestion(string $question): array
    {
        $schema = $this->getDatabaseSchema();
        $systemPrompt = $this->buildSystemPrompt($schema);

        $llmResponse = $this->ollama->chat($question, [
            ['role' => 'system', 'content' => $systemPrompt],
        ]);

        if (! $llmResponse) {
            return [
                'success' => false,
                'error' => 'Impossible de générer une requête SQL',
                'sql' => null,
                'results' => null,
                'reformulation' => null,
            ];
        }

        $sql = $this->extractSql($llmResponse);

        if (! $sql) {
            return [
                'success' => false,
                'error' => 'Aucune requête SQL valide trouvée dans la réponse',
                'sql' => $llmResponse,
                'results' => null,
                'reformulation' => $this->generateReformulation($question),
            ];
        }

        $validation = $this->validateSqlString($sql);

        if (! $validation['valid']) {
            return [
                'success' => false,
                'error' => $validation['error'],
                'sql' => $sql,
                'results' => null,
                'reformulation' => $this->generateReformulation($question),
            ];
        }

        $results = $this->executeSql($sql);

        if ($results === null) {
            return [
                'success' => false,
                'error' => 'Erreur lors de l\'exécution de la requête',
                'sql' => $sql,
                'results' => null,
                'reformulation' => $this->generateReformulation($question),
            ];
        }

        return [
            'success' => true,
            'error' => null,
            'sql' => $sql,
            'results' => $results,
            'reformulation' => $this->generateReformulation($question),
            'explanation' => $this->explainResults($results, $question),
        ];
    }

    private function getDatabaseSchema(): string
    {
        $schema = "Tables disponibles:\n";

        foreach ($this->allowedTables as $table) {
            $schema .= "- {$table}: ".implode(', ', $this->allowedColumns[$table])."\n";
        }

        $schema .= "\nRelations:\n";
        $schema .= "- offers.company_id -> companies.id\n";
        $schema .= "- offers.region_id -> regions.id\n";
        $schema .= "- offers.profession_id -> professions.id\n";
        $schema .= "- offers.skills (many-to-many via offer_skills)\n";
        $schema .= "- professions.skills (many-to-many via profession_skills)\n";
        $schema .= "- formations.skills (many-to-many via formation_skills)\n";
        $schema .= "- formations.region_id -> regions.id\n";

        return $schema;
    }

    private function buildSystemPrompt(string $schema): string
    {
        return <<<PROMPT
Tu es un assistant qui convertit les questions en français en requêtes SQL valides pour SQLite.

{$schema}

Instructions:
1. Analyse la question de l'utilisateur
2. Génère une requête SQL SELECT simple et sécurisée
3. Utilise uniquement les tables et colonnes listées ci-dessus
4. NE JAMAIS utiliser de mots-clés dangereux (DROP, DELETE, INSERT, UPDATE, etc.)
5. Limite les résultats à 20 maximum avec LIMIT
6. Réponds UNIQUEMENT avec la requête SQL, sans explication

Exemples:
- "Quels métiers demandent Python?" -> SELECT DISTINCT p.name FROM professions p JOIN profession_skills ps ON p.id = ps.profession_id JOIN skills s ON ps.skill_id = s.id WHERE s.name LIKE '%Python%'
- "Offres à Toulouse" -> SELECT o.* FROM offers o JOIN regions r ON o.region_id = r.id WHERE r.name LIKE '%Toulouse%' OR o.is_remote = 1
- "Formations Bac+5 en Data" -> SELECT * FROM formations WHERE level LIKE '%Bac+5%' AND (title LIKE '%Data%' OR description LIKE '%Data%')

Question:
PROMPT;
    }

    private function extractSql(string $response): ?string
    {
        $response = trim($response);

        if (preg_match('/```sql\s*(.*?)\s*```/s', $response, $matches)) {
            return trim($matches[1]);
        }

        if (preg_match('/```\s*(SELECT.*?)\s*```/s', $response, $matches)) {
            return trim($matches[1]);
        }

        if (stripos($response, 'SELECT') !== false) {
            $lines = explode("\n", $response);
            $sqlLines = [];
            $inSelect = false;

            foreach ($lines as $line) {
                if (stripos($line, 'SELECT') !== false) {
                    $inSelect = true;
                }
                if ($inSelect) {
                    $sqlLines[] = $line;
                    if (preg_match('/;\s*$/', $line)) {
                        break;
                    }
                }
            }

            $sql = implode(' ', $sqlLines);
            if (stripos($sql, 'SELECT') !== false) {
                return trim($sql);
            }
        }

        return null;
    }

    public function validateSqlString(string $sql): array
    {
        $sqlUpper = strtoupper($sql);

        foreach ($this->dangerousKeywords as $keyword) {
            if (strpos($sqlUpper, $keyword) !== false) {
                return [
                    'valid' => false,
                    'error' => "Mot-clé dangereux détecté: {$keyword}",
                ];
            }
        }

        if (stripos($sql, 'SELECT') !== 0) {
            return [
                'valid' => false,
                'error' => 'Seules les requêtes SELECT sont autorisées',
            ];
        }

        $tableMatch = preg_match('/FROM\s+(\w+)/i', $sql, $matches);
        if (! $tableMatch || ! in_array(strtolower($matches[1]), $this->allowedTables)) {
            return [
                'valid' => false,
                'error' => 'Table non autorisée',
            ];
        }

        if (preg_match_all('/\bJOIN\s+(\w+)/i', $sql, $joinMatches)) {
            foreach ($joinMatches[1] as $joinTable) {
                if (! in_array(strtolower($joinTable), $this->allowedTables)) {
                    return [
                        'valid' => false,
                        'error' => "Table de jointure non autorisée: {$joinTable}",
                    ];
                }
            }
        }

        if (preg_match('/WHERE\s+.*?\s+IN\s*\(\s*SELECT/i', $sql)) {
            return [
                'valid' => false,
                'error' => 'Sous-requêtes non autorisées',
            ];
        }

        return ['valid' => true, 'error' => null];
    }

    private function executeSql(string $sql): ?array
    {
        $validation = $this->validateSqlString($sql);
        if (! $validation['valid']) {
            return null;
        }

        try {
            if (stripos($sql, 'LIMIT') === false) {
                $sql = trim($sql).' LIMIT 20';
            }

            $results = DB::select($sql);

            return array_map(function ($row) {
                return (array) $row;
            }, $results);
        } catch (\Exception $e) {
            Log::error('SQL execution error: '.$e->getMessage(), ['sql' => $sql]);

            return null;
        }
    }

    private function generateReformulation(string $question): string
    {
        $systemPrompt = 'Tu es un assistant qui reformule les questions des utilisateurs sur les offres de stages, métiers et formations. Reformule la question de manière claire et concise.';

        $reformulation = $this->ollama->chat($question, [
            ['role' => 'system', 'content' => $systemPrompt],
        ]);

        return $reformulation ?? $question;
    }

    private function explainResults(array $results, string $question): string
    {
        if (empty($results)) {
            return 'Aucun résultat trouvé pour votre recherche.';
        }

        $count = count($results);
        $systemPrompt = "Tu es un assistant qui explique les résultats de recherche d'offres de stages. Explique simplement à l'utilisateur combien de résultats ont été trouvés et donne quelques exemples.";

        $examples = array_slice($results, 0, 3);
        $examplesText = json_encode($examples, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        $prompt = "Question: {$question}\n\nRésultats ({$count} trouvés):\n{$examplesText}\n\nDonne une brève explication en français.";

        return $this->ollama->chat($prompt, [
            ['role' => 'system', 'content' => $systemPrompt],
        ]) ?? "{$count} résultat(s) trouvé(s).";
    }

    public function getAllowedTables(): array
    {
        return $this->allowedTables;
    }

    public function getAllowedColumns(): array
    {
        return $this->allowedColumns;
    }
}
