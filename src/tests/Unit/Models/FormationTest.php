<?php

namespace Tests\Unit\Models;

use App\Models\Formation;
use App\Models\Region;
use App\Models\Skill;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FormationTest extends TestCase
{
    use RefreshDatabase;

    public function test_formation_can_be_created(): void
    {
        $formation = Formation::create([
            'title' => 'Master Data Science',
            'description' => 'Formation Data Science',
            'level' => 'Bac+5',
            'duration' => '2 ans',
            'school' => 'Université Toulouse',
            'city' => 'Toulouse',
            'type' => 'initial',
        ]);

        $this->assertDatabaseHas('formations', [
            'title' => 'Master Data Science',
            'level' => 'Bac+5',
        ]);
    }

    public function test_formation_belongs_to_region(): void
    {
        $region = Region::create(['name' => 'Occitanie', 'code' => 'OCC']);
        $formation = Formation::create([
            'title' => 'Formation',
            'region_id' => $region->id,
        ]);

        $this->assertEquals($region->id, $formation->region->id);
    }

    public function test_formation_has_skills(): void
    {
        $formation = Formation::create(['title' => 'Formation']);
        $skill = Skill::create(['name' => 'Machine Learning']);

        $formation->skills()->attach($skill);

        $this->assertCount(1, $formation->skills);
    }
}
