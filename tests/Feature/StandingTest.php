<?php

namespace Tests\Feature;

use App\Models\BroadcastSchedule;
use App\Models\ChiefChoice;
use App\Models\Competition;
use App\Models\Fixture;
use App\Models\Post;
use App\Models\Scorers;
use App\Models\Slider;
use App\Models\Standing;
use App\Models\Survey;
use App\Models\Tag;
use App\Models\User;
use App\Models\Vote;
use Database\Seeders\TagsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StandingTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function standing_must_be_accessible_in_index_page()
    {


        Competition::factory(['name' => 'khaligefars'])->has(Standing::factory(['season' => 2022])->count(5))->create();

        $this->get(route('index.competition-standing',[
            'competition' => 'khaligefars',
            'season'      => 2022,
        ]))->assertOk();
    }

    /**
     * @test
     */
    public function standing_require_season()
    {
        $this->get(route('index.competition-standing'))->assertSessionHasErrors('season');
    }

    /**
     * @test
     */
    public function standing_require_competition()
    {
        $this->get(route('index.competition-standing'))->assertSessionHasErrors('season');
    }

    /**
     * @test
     */
    public function standing_require_valid_competition()
    {
        $this->get(route('index.competition-standing',[
            'competition' => 'foo',
        ]))->assertSessionHasErrors('season');
    }
}
