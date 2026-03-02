<?php

namespace Tests\Unit\Services;

use App\Services\SqlGenerationService;
use Tests\TestCase;

class OllamaServiceTest extends TestCase
{
    private SqlGenerationService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new SqlGenerationService(
            $this->createMock(\App\Services\OllamaService::class)
        );
    }

    public function test_validate_sql_rejects_dangerous_keywords(): void
    {
        $result = $this->service->validateSqlString('DROP TABLE offers');
        $this->assertFalse($result['valid']);
        $this->assertStringContainsString('DROP', $result['error']);
    }

    public function test_validate_sql_rejects_delete(): void
    {
        $result = $this->service->validateSqlString('DELETE FROM offers');
        $this->assertFalse($result['valid']);
    }

    public function test_validate_sql_rejects_insert(): void
    {
        $result = $this->service->validateSqlString('INSERT INTO offers VALUES (1, "test")');
        $this->assertFalse($result['valid']);
    }

    public function test_validate_sql_rejects_update(): void
    {
        $result = $this->service->validateSqlString('UPDATE offers SET title = "hacked"');
        $this->assertFalse($result['valid']);
    }

    public function test_validate_sql_accepts_select_offers(): void
    {
        $result = $this->service->validateSqlString('SELECT * FROM offers WHERE id = 1');
        $this->assertTrue($result['valid']);
    }

    public function test_validate_sql_rejects_unauthorized_table(): void
    {
        $result = $this->service->validateSqlString('SELECT * FROM users');
        $this->assertFalse($result['valid']);
    }

    public function test_validate_sql_rejects_subqueries(): void
    {
        $result = $this->service->validateSqlString('SELECT * FROM offers WHERE id IN (SELECT id FROM companies)');
        $this->assertFalse($result['valid']);
    }

    public function test_validate_sql_accepts_join(): void
    {
        $result = $this->service->validateSqlString('SELECT offers.*, companies.name FROM offers JOIN companies ON offers.company_id = companies.id');
        $this->assertTrue($result['valid']);
    }

    public function test_validate_sql_rejects_union(): void
    {
        $result = $this->service->validateSqlString('SELECT * FROM offers UNION SELECT * FROM companies');
        $this->assertFalse($result['valid']);
    }

    public function test_get_allowed_tables(): void
    {
        $tables = $this->service->getAllowedTables();

        $this->assertContains('offers', $tables);
        $this->assertContains('companies', $tables);
        $this->assertContains('professions', $tables);
        $this->assertContains('formations', $tables);
    }

    public function test_get_allowed_columns(): void
    {
        $columns = $this->service->getAllowedColumns();

        $this->assertArrayHasKey('offers', $columns);
        $this->assertContains('title', $columns['offers']);
        $this->assertContains('description', $columns['offers']);
    }
}
