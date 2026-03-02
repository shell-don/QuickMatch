<?php

namespace Tests\Unit\Models;

use App\Models\Company;
use App\Models\Formation;
use App\Models\Offer;
use App\Models\Profession;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SkillTest extends TestCase
{
    use RefreshDatabase;

    public function test_skill_can_be_created(): void
    {
        $skill = Skill::create([
            'name' => 'Python',
            'category' => 'programming',
        ]);

        $this->assertDatabaseHas('skills', [
            'name' => 'Python',
            'category' => 'programming',
        ]);
    }

    public function test_skill_can_be_attached_to_offer(): void
    {
        $skill = Skill::create(['name' => 'JavaScript']);
        $company = Company::create(['name' => 'Company']);
        $offer = Offer::create([
            'title' => 'Développeur JS',
            'company_id' => $company->id,
        ]);

        $offer->skills()->attach($skill);

        $this->assertCount(1, $offer->skills);
    }

    public function test_skill_can_be_attached_to_profession(): void
    {
        $skill = Skill::create(['name' => 'SQL']);
        $profession = Profession::create(['name' => 'Data Analyst']);

        $profession->skills()->attach($skill);

        $this->assertCount(1, $profession->skills);
    }

    public function test_skill_can_be_attached_to_formation(): void
    {
        $skill = Skill::create(['name' => 'React']);
        $formation = Formation::create(['title' => 'Formation React']);

        $formation->skills()->attach($skill);

        $this->assertCount(1, $formation->skills);
    }

    public function test_skill_can_be_attached_to_user(): void
    {
        $skill = Skill::create(['name' => 'Laravel']);
        $user = User::create([
            'name' => 'Dev',
            'email' => 'dev@example.com',
            'password' => 'password',
        ]);

        $user->skills()->attach($skill);

        $this->assertCount(1, $user->skills);
    }
}
