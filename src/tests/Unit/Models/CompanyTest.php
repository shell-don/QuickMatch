<?php

namespace Tests\Unit\Models;

use App\Models\Company;
use App\Models\Offer;
use App\Models\Region;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CompanyTest extends TestCase
{
    use RefreshDatabase;

    public function test_company_can_be_created(): void
    {
        $company = Company::create([
            'name' => 'Tech Corp',
            'description' => 'A tech company',
            'website' => 'https://techcorp.fr',
            'industry' => 'Technology',
            'size' => 'PME',
            'location' => 'Toulouse',
        ]);

        $this->assertDatabaseHas('companies', [
            'name' => 'Tech Corp',
            'industry' => 'Technology',
        ]);
    }

    public function test_company_belongs_to_region(): void
    {
        $region = Region::create(['name' => 'Occitanie', 'code' => 'OCC']);
        $company = Company::create([
            'name' => 'Company',
            'region_id' => $region->id,
        ]);

        $this->assertEquals($region->id, $company->region->id);
    }

    public function test_company_has_many_offers(): void
    {
        $company = Company::create(['name' => 'Company']);

        Offer::create([
            'title' => 'Stage',
            'company_id' => $company->id,
        ]);

        Offer::create([
            'title' => 'Alternance',
            'company_id' => $company->id,
        ]);

        $this->assertCount(2, $company->offers);
    }

    public function test_company_has_many_users(): void
    {
        $company = Company::create(['name' => 'Company']);

        User::create([
            'name' => 'User 1',
            'email' => 'user1@example.com',
            'password' => 'password',
            'company_id' => $company->id,
            'is_company' => true,
        ]);

        User::create([
            'name' => 'User 2',
            'email' => 'user2@example.com',
            'password' => 'password',
            'company_id' => $company->id,
            'is_company' => true,
        ]);

        $this->assertCount(2, $company->users);
    }
}
