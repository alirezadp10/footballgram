<?php

namespace Database\Factories;

use App\Models\Tweet;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TweetFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $tweet = Tweet::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'context' => $this->faker->text(),
            'user_id' => User::factory(),
            'status'  => 'PENDING',
            'like'    => 0,
            'dislike' => 0,
            'view'    => 0,
            'comment' => 0,
        ];
    }
}
