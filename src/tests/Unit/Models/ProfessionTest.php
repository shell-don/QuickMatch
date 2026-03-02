<?php

namespace Tests\Unit\Models;

use App\Models\Company;
use App\Models\Formation;
use App\Models\Offer;
use App\Models\Profession;
use App\Models\Skill;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfessionTest extends TestCase
{
    use RefreshDatabase;

    public function test_profession_can_be_created(): void
    {
        $profession = Profession::create([
            'name' => 'Développeur Data',
            'description' => 'Analyse de données',
            'rome_code' => 'M1805',
        ]);

        $this->assertDatabaseHas('professions', [
            'name' => 'Développeur Data',
            'rome_code' => 'M1805',
        ]);
    }

    public function test_profession_has_many_offers(): void
    {
        $profession = Profession::create(['name' => 'Dev Web']);
        $company = Company::create(['name' => 'Company']);

        Offer::create([
            'title' => 'Stage',
            'company_id' => $company->id,
            'profession_id' => $profession->id,
        ]);

        $this->assertCount(1, $profession->offers);
    }

    public function test_profession_has_skills(): void
    {
        $profession = Profession::create(['name' => 'Data Scientist']);
        $skill = Skill::create(['name' => 'Python']);

        $profession->skills()->attach($skill);

        $this->assertCount(1, $profession->skills);
    }

    public function test_profession_has_formations(): void
    {
        $profession = Profession::create(['name' => 'Dev Frontend']);
        $formation = Formation::create(['title' => 'Formation React']);

        $profession->formations()->attach($formation);

        $this->assertCount(1, $profession->formations);
    }
}
