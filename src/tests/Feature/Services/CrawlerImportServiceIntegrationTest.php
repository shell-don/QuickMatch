<?php

namespace Tests\Feature\Services;

use App\Models\Company;
use App\Models\Offer;
use App\Services\CrawlerImportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CrawlerImportServiceIntegrationTest extends TestCase
{
    use RefreshDatabase;

    private CrawlerImportService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new CrawlerImportService;
    }

    public function test_import_creates_company_if_not_exists(): void
    {
        $reflection = new \ReflectionClass($this->service);
        $method = $reflection->getMethod('importOffer');
        $method->setAccessible(true);

        $data = [
            'nom_offre' => 'Stage Développeur',
            'nom_entreprise' => 'New Company',
            'url_offre' => 'https://example.com/job/1',
            'tags' => '[]',
        ];

        $method->invoke($this->service, $data);

        $this->assertDatabaseHas('companies', ['name' => 'New Company']);
    }

    public function test_import_reuses_existing_company(): void
    {
        $company = Company::create(['name' => 'Existing Company']);

        $reflection = new \ReflectionClass($this->service);
        $method = $reflection->getMethod('importOffer');
        $method->setAccessible(true);

        $data = [
            'nom_offre' => 'Stage Développeur',
            'nom_entreprise' => 'Existing Company',
            'url_offre' => 'https://example.com/job/2',
            'tags' => '[]',
        ];

        $method->invoke($this->service, $data);

        $this->assertEquals(1, Company::count());
    }

    public function test_import_creates_region_from_url(): void
    {
        $reflection = new \ReflectionClass($this->service);
        $method = $reflection->getMethod('importOffer');
        $method->setAccessible(true);

        $data = [
            'nom_offre' => 'Stage à Toulouse',
            'nom_entreprise' => 'Company',
            'url_offre' => 'https://hellowork.com/toulouse/job/1',
            'tags' => '[]',
        ];

        $method->invoke($this->service, $data);

        $this->assertDatabaseHas('regions', ['name' => 'Occitanie']);
    }

    public function test_import_creates_offer_with_all_fields(): void
    {
        $reflection = new \ReflectionClass($this->service);
        $method = $reflection->getMethod('importOffer');
        $method->setAccessible(true);

        $data = [
            'nom_offre' => 'Stage Développeur Python',
            'nom_entreprise' => 'Tech Corp',
            'url_offre' => 'https://hellowork.com/job/123',
            'description_poste' => 'Description du poste',
            'profile_recherche' => 'Profil recherché: Bac+5',
            'tags' => '["Python", "Django"]',
        ];

        $method->invoke($this->service, $data);

        $this->assertDatabaseHas('offers', [
            'title' => 'Stage Développeur Python',
            'type' => 'stage',
            'source' => 'hellowork',
        ]);
    }

    public function test_import_skips_empty_offer_title(): void
    {
        $reflection = new \ReflectionClass($this->service);
        $method = $reflection->getMethod('importOffer');
        $method->setAccessible(true);

        $data = [
            'nom_offre' => '',
            'nom_entreprise' => 'Company',
            'url_offre' => 'https://example.com/job/1',
        ];

        $result = $method->invoke($this->service, $data);

        $this->assertFalse($result);
    }

    public function test_import_creates_skills_from_tags(): void
    {
        $reflection = new \ReflectionClass($this->service);
        $method = $reflection->getMethod('importOffer');
        $method->setAccessible(true);

        $data = [
            'nom_offre' => 'Stage Data Science',
            'nom_entreprise' => 'Company',
            'url_offre' => 'https://example.com/job/1',
            'tags' => '["Python", "Machine Learning", "SQL"]',
        ];

        $method->invoke($this->service, $data);

        $this->assertDatabaseHas('skills', ['name' => 'Python']);
        $this->assertDatabaseHas('skills', ['name' => 'Machine learning']);
        $this->assertDatabaseHas('skills', ['name' => 'Sql']);
    }

    public function test_import_associates_skills_to_offer(): void
    {
        $reflection = new \ReflectionClass($this->service);
        $method = $reflection->getMethod('importOffer');
        $method->setAccessible(true);

        $data = [
            'nom_offre' => 'Stage Dev',
            'nom_entreprise' => 'Company',
            'url_offre' => 'https://example.com/job/1',
            'tags' => '["PHP", "Laravel"]',
        ];

        $method->invoke($this->service, $data);

        $offer = Offer::first();
        $this->assertCount(2, $offer->skills);
    }

    public function test_import_detects_alternance_type(): void
    {
        $reflection = new \ReflectionClass($this->service);
        $method = $reflection->getMethod('importOffer');
        $method->setAccessible(true);

        $data = [
            'nom_offre' => 'Alternance Développeur',
            'nom_entreprise' => 'Company',
            'url_offre' => 'https://example.com/job/1',
        ];

        $method->invoke($this->service, $data);

        $this->assertDatabaseHas('offers', ['type' => 'alternance']);
    }

    public function test_import_detects_cdi_type(): void
    {
        $reflection = new \ReflectionClass($this->service);
        $method = $reflection->getMethod('importOffer');
        $method->setAccessible(true);

        $data = [
            'nom_offre' => 'CDI Développeur Full Stack',
            'nom_entreprise' => 'Company',
            'url_offre' => 'https://example.com/job/1',
        ];

        $method->invoke($this->service, $data);

        $this->assertDatabaseHas('offers', ['type' => 'cdi']);
    }

    public function test_import_updates_existing_offer_by_url(): void
    {
        $company = Company::create(['name' => 'Company']);

        Offer::create([
            'title' => 'Old Title',
            'company_id' => $company->id,
            'source_url' => 'https://example.com/job/1',
            'status' => 'active',
        ]);

        $reflection = new \ReflectionClass($this->service);
        $method = $reflection->getMethod('importOffer');
        $method->setAccessible(true);

        $data = [
            'nom_offre' => 'New Title',
            'nom_entreprise' => 'Company',
            'url_offre' => 'https://example.com/job/1',
        ];

        $method->invoke($this->service, $data);

        $this->assertEquals(1, Offer::count());
        $this->assertDatabaseHas('offers', ['title' => 'New Title']);
    }
}
