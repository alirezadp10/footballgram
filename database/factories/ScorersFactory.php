<?php

namespace Database\Factories;

use App\Models\Competition;
use App\Models\Scorers;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScorersFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Scorers::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'competition_id' => Competition::factory(),
            'club'           => $this->faker->word,
            'name'           => $this->faker->word,
            'count_scores'   => $this->faker->randomDigit,
            'count_assists'  => $this->faker->randomDigit,
            'season'         => $this->faker->date('Y'),
        ];
    }
}
