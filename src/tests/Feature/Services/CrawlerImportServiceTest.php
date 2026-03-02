<?php

namespace Tests\Feature\Services;

use App\Services\CrawlerImportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CrawlerImportServiceTest extends TestCase
{
    use RefreshDatabase;

    private CrawlerImportService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new CrawlerImportService;
    }

    public function test_detect_offer_type_stage(): void
    {
        $reflection = new \ReflectionClass($this->service);
        $method = $reflection->getMethod('detectOfferType');
        $method->setAccessible(true);

        $this->assertEquals('stage', $method->invoke($this->service, 'Stage développeur'));
        $this->assertEquals('stage', $method->invoke($this->service, 'Internship'));
    }

    public function test_detect_offer_type_alternance(): void
    {
        $reflection = new \ReflectionClass($this->service);
        $method = $reflection->getMethod('detectOfferType');
        $method->setAccessible(true);

        $this->assertEquals('alternance', $method->invoke($this->service, 'Alternance'));
        $this->assertEquals('alternance', $method->invoke($this->service, 'Contrat d\'alternance'));
    }

    public function test_detect_offer_type_cdi(): void
    {
        $reflection = new \ReflectionClass($this->service);
        $method = $reflection->getMethod('detectOfferType');
        $method->setAccessible(true);

        $this->assertEquals('cdi', $method->invoke($this->service, 'CDI Développeur'));
    }

    public function test_detect_offer_type_cdd(): void
    {
        $reflection = new \ReflectionClass($this->service);
        $method = $reflection->getMethod('detectOfferType');
        $method->setAccessible(true);

        $this->assertEquals('cdd', $method->invoke($this->service, 'CDD'));
    }

    public function test_detect_source_hellowork(): void
    {
        $reflection = new \ReflectionClass($this->service);
        $method = $reflection->getMethod('detectSource');
        $method->setAccessible(true);

        $this->assertEquals('hellowork', $method->invoke($this->service, 'https://www.hellowork.com/emploi'));
    }

    public function test_detect_source_indeed(): void
    {
        $reflection = new \ReflectionClass($this->service);
        $method = $reflection->getMethod('detectSource');
        $method->setAccessible(true);

        $this->assertEquals('indeed', $method->invoke($this->service, 'https://fr.indeed.com/job'));
    }

    public function test_detect_source_unknown(): void
    {
        $reflection = new \ReflectionClass($this->service);
        $method = $reflection->getMethod('detectSource');
        $method->setAccessible(true);

        $this->assertEquals('unknown', $method->invoke($this->service, null));
    }

    public function test_detect_industry_technology(): void
    {
        $reflection = new \ReflectionClass($this->service);
        $method = $reflection->getMethod('detectIndustry');
        $method->setAccessible(true);

        $this->assertEquals('Technology', $method->invoke($this->service, '["python", "javascript"]'));
    }

    public function test_detect_industry_data(): void
    {
        $reflection = new \ReflectionClass($this->service);
        $method = $reflection->getMethod('detectIndustry');
        $method->setAccessible(true);

        $this->assertEquals('Data', $method->invoke($this->service, '["machine learning", "analytics"]'));
    }

    public function test_detect_industry_marketing(): void
    {
        $reflection = new \ReflectionClass($this->service);
        $method = $reflection->getMethod('detectIndustry');
        $method->setAccessible(true);

        $this->assertEquals('Marketing', $method->invoke($this->service, '["seo", "marketing"]'));
    }

    public function test_extract_location_toulouse(): void
    {
        $reflection = new \ReflectionClass($this->service);
        $method = $reflection->getMethod('extractLocation');
        $method->setAccessible(true);

        $this->assertEquals('Toulouse', $method->invoke($this->service, 'https://www.hellowork.com/fr-fr/emploi/toulouse'));
    }

    public function test_extract_location_paris(): void
    {
        $reflection = new \ReflectionClass($this->service);
        $method = $reflection->getMethod('extractLocation');
        $method->setAccessible(true);

        $this->assertEquals('Paris', $method->invoke($this->service, 'https://www.hellowork.com/fr-fr/emploi/paris'));
    }

    public function test_extract_location_unknown(): void
    {
        $reflection = new \ReflectionClass($this->service);
        $method = $reflection->getMethod('extractLocation');
        $method->setAccessible(true);

        $this->assertNull($method->invoke($this->service, null));
    }
}
