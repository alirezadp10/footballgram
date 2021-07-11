<?php

namespace Tests\Feature;

use App\Models\Competition;
use App\Models\Scorers;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ScorerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function scorers_must_be_accessible_in_index_page()
    {
        Competition::factory(['name' => 'khaligefars'])->has(Scorers::factory(['season' => 2022])->count(5))->create();

        $this->get(route('index.competition-scorers', [
            'competition' => 'khaligefars',
            'season'      => 2022,
        ]))->assertOk();
    }

    /**
     * @test
     */
    public function scorers_require_season()
    {
        $this->get(route('index.competition-scorers'))->assertSessionHasErrors('season');
    }

    /**
     * @test
     */
    public function scorers_require_competition()
    {
        $this->get(route('index.competition-scorers'))->assertSessionHasErrors('season');
    }

    /**
     * @test
     */
    public function scorers_require_valid_competition()
    {
        $this->get(route('index.competition-scorers', [
            'competition' => 'foo',
        ]))->assertSessionHasErrors('season');
    }
}
