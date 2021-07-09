<?php

namespace Database\Factories;

use App\Models\Survey;
use Illuminate\Database\Eloquent\Factories\Factory;

class SurveyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Survey::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'question' => $this->faker->sentence,
            'options'  => [
                [
                    'title' => $this->faker->sentence,
                    'count' => 1,
                ],
                [
                    'title' => $this->faker->sentence,
                    'count' => 1,
                ],
                [
                    'title' => $this->faker->sentence,
                    'count' => 1,
                ],
                [
                    'title' => $this->faker->sentence,
                    'count' => 1,
                ],
            ],
        ];
    }
}
