<?php

namespace Tests\Unit\Models;

use App\Models\Company;
use App\Models\Offer;
use App\Models\User;
use App\Models\UserApplication;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserApplicationTest extends TestCase
{
    use RefreshDatabase;

    public function test_application_can_be_created(): void
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

        $application = UserApplication::create([
            'user_id' => $user->id,
            'offer_id' => $offer->id,
            'status' => 'pending',
            'cover_letter' => 'Lettre de motivation',
        ]);

        $this->assertDatabaseHas('user_applications', [
            'user_id' => $user->id,
            'offer_id' => $offer->id,
            'status' => 'pending',
        ]);
    }

    public function test_application_belongs_to_user(): void
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

        $application = UserApplication::create([
            'user_id' => $user->id,
            'offer_id' => $offer->id,
        ]);

        $this->assertEquals($user->id, $application->user->id);
    }

    public function test_application_belongs_to_offer(): void
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

        $application = UserApplication::create([
            'user_id' => $user->id,
            'offer_id' => $offer->id,
        ]);

        $this->assertEquals($offer->id, $application->offer->id);
    }

    public function test_application_status_defaults_to_pending(): void
    {
        $company = Company::create(['name' => 'Company']);
        $offer = Offer::create([
            'title' => 'Stage',
            'company_id' => $company->id,
        ]);
        $user = User::create([
            'name' => 'Student',
            'email' => 'student2@example.com',
            'password' => 'password',
        ]);

        $application = UserApplication::create([
            'user_id' => $user->id,
            'offer_id' => $offer->id,
            'status' => 'pending',
        ]);

        $this->assertEquals('pending', $application->status);
    }
}
