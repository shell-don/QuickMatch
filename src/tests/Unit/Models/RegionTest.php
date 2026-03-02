<?php

namespace Tests\Unit\Models;

use App\Models\Company;
use App\Models\Offer;
use App\Models\Region;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegionTest extends TestCase
{
    use RefreshDatabase;

    public function test_region_can_be_created(): void
    {
        $region = Region::create([
            'name' => 'Occitanie',
            'code' => 'OCC',
            'department' => 'Haute-Garonne',
            'postal_code' => '31000',
        ]);

        $this->assertDatabaseHas('regions', [
            'name' => 'Occitanie',
            'code' => 'OCC',
        ]);
    }

    public function test_region_has_many_companies(): void
    {
        $region = Region::create([
            'name' => 'Île-de-France',
            'code' => 'IDF',
        ]);

        Company::create([
            'name' => 'Test Company',
            'region_id' => $region->id,
        ]);

        $this->assertCount(1, $region->companies);
    }

    public function test_region_has_many_offers(): void
    {
        $region = Region::create([
            'name' => 'Bretagne',
            'code' => 'BRE',
        ]);

        $company = Company::create(['name' => 'Company']);

        Offer::create([
            'title' => 'Stage Dev',
            'company_id' => $company->id,
            'region_id' => $region->id,
        ]);

        $this->assertCount(1, $region->offers);
    }

    public function test_region_has_many_formations(): void
    {
        $region = Region::create([
            'name' => 'Auvergne-Rhône-Alpes',
            'code' => 'ARA',
        ]);

        $region->formations()->create([
            'title' => 'Formation Test',
        ]);

        $this->assertCount(1, $region->formations);
    }

    public function test_region_has_many_users(): void
    {
        $region = Region::create([
            'name' => 'Nouvelle-Aquitaine',
            'code' => 'NAQ',
        ]);

        $region->users()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $this->assertCount(1, $region->users);
    }
}
