<?php

namespace Database\Factories;

use App\Models\Competition;
use App\Models\Fixture;
use Illuminate\Database\Eloquent\Factories\Factory;

class FixtureFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Fixture::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'competition_id' => Competition::factory(),
            'host'           => $this->faker->word,
            'guest'          => $this->faker->word,
            'host_point'     => $this->faker->randomDigit,
            'guest_point'    => $this->faker->randomDigit,
            'match_type'     => $this->faker->randomElement([
                'LEAGUE',
                'GROUP_STAGE',
                'PLAY_OFFS',
            ]),
            'odd_even'       => $this->faker->randomElement([
                'ODD',
                'EVEN',
            ]),
            'period'         => $this->faker->randomDigit,
            'final'          => $this->faker->boolean,
            'datetime'       => $this->faker->date(),
            'season'         => $this->faker->date('Y'),
        ];
    }
}
