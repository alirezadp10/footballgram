<?php

namespace Database\Factories;

use App\Models\BroadcastChannel;
use App\Models\BroadcastSchedule;
use Illuminate\Database\Eloquent\Factories\Factory;

class BroadcastScheduleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BroadcastSchedule::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'host'                 => $this->faker->randomElement(['barcelona','realMadrid','arsenal']),
            'guest'                => $this->faker->randomElement(['manUnited','manCity','chelsea']),
            'datetime'             => $this->faker->dateTimeBetween('now','+2 days'),
            'broadcast_channel_id' => BroadcastChannel::factory(),
        ];
    }
}
