<?php

namespace Database\Factories;

use App\Models\Competition;
use App\Models\Standing;
use Illuminate\Database\Eloquent\Factories\Factory;

class StandingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Standing::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'competition_id'   => Competition::factory(),
            'name'             => $this->faker->word,
            'position'         => $this->faker->randomDigit,
            'played'           => $this->faker->randomDigit,
            'won'              => $this->faker->randomDigit,
            'drawn'            => $this->faker->randomDigit,
            'lost'             => $this->faker->randomDigit,
            'goals_for'        => $this->faker->randomDigit,
            'goals_against'    => $this->faker->randomDigit,
            'goals_difference' => $this->faker->randomDigit,
            'points'           => $this->faker->randomDigit,
            'group'            => $this->faker->randomLetter,
            'season'           => $this->faker->date('Y'),
        ];
    }
}
