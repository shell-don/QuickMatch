<?php

namespace Tests\Unit\Models;

use App\Models\Company;
use App\Models\Offer;
use App\Models\Region;
use App\Models\Skill;
use App\Models\User;
use App\Models\UserApplication;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OfferTest extends TestCase
{
    use RefreshDatabase;

    public function test_offer_can_be_created(): void
    {
        $company = Company::create(['name' => 'Company']);

        $offer = Offer::create([
            'title' => 'Stage Développeur',
            'description' => 'Description du stage',
            'type' => 'stage',
            'status' => 'active',
            'source' => 'hellowork',
            'source_url' => 'https://example.com/offer',
            'company_id' => $company->id,
        ]);

        $this->assertDatabaseHas('offers', [
            'title' => 'Stage Développeur',
            'type' => 'stage',
            'status' => 'active',
        ]);
    }

    public function test_offer_belongs_to_company(): void
    {
        $company = Company::create(['name' => 'Tech Corp']);
        $offer = Offer::create([
            'title' => 'Stage',
            'company_id' => $company->id,
        ]);

        $this->assertEquals($company->id, $offer->company->id);
    }

    public function test_offer_belongs_to_region(): void
    {
        $region = Region::create(['name' => 'Occitanie', 'code' => 'OCC']);
        $company = Company::create(['name' => 'Company']);
        $offer = Offer::create([
            'title' => 'Stage',
            'company_id' => $company->id,
            'region_id' => $region->id,
        ]);

        $this->assertEquals($region->id, $offer->region->id);
    }

    public function test_offer_has_skills(): void
    {
        $skill = Skill::create(['name' => 'Python']);
        $company = Company::create(['name' => 'Company']);
        $offer = Offer::create([
            'title' => 'Stage Data',
            'company_id' => $company->id,
        ]);

        $offer->skills()->attach($skill);

        $this->assertCount(1, $offer->skills);
    }

    public function test_offer_scope_active(): void
    {
        $company = Company::create(['name' => 'Company']);

        Offer::create([
            'title' => 'Stage Active',
            'company_id' => $company->id,
            'status' => 'active',
        ]);

        Offer::create([
            'title' => 'Stage Closed',
            'company_id' => $company->id,
            'status' => 'closed',
        ]);

        $this->assertCount(1, Offer::active()->get());
    }

    public function test_offer_can_have_applications(): void
    {
        $company = Company::create(['name' => 'Company']);
        $offer = Offer::create([
            'title' => 'Stage',
            'company_id' => $company->id,
        ]);

        $user = User::create([
            'name' => 'Student',
            'email' => 'student@example.com',
            'password' => 'password',
        ]);

        UserApplication::create([
            'user_id' => $user->id,
            'offer_id' => $offer->id,
            'status' => 'pending',
        ]);

        $this->assertCount(1, $offer->applications);
    }

    public function test_offer_is_remote(): void
    {
        $company = Company::create(['name' => 'Company']);

        $offer = Offer::create([
            'title' => 'Remote Stage',
            'company_id' => $company->id,
            'is_remote' => true,
        ]);

        $this->assertTrue($offer->is_remote);
    }
}
