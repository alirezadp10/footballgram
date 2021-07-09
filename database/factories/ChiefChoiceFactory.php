<?php

namespace Database\Factories;

use App\Models\ChiefChoice;
use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChiefChoiceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ChiefChoice::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'post_id' => Post::factory()->news()->released(),
            'order'   => $this->faker->randomDigit,
        ];
    }
}
