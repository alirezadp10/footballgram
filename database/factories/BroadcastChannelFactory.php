<?php

namespace Database\Factories;

use App\Models\BroadcastChannel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class BroadcastChannelFactory extends Factory
{
    public function __construct($count = NULL,?Collection $states = NULL,?Collection $has = NULL,?Collection $for = NULL,?Collection $afterMaking = NULL,?Collection $afterCreating = NULL,$connection = NULL)
    {
        parent::__construct($count,$states,$has,$for,$afterMaking,$afterCreating,$connection);

        Storage::fake("public");
    }

    protected $model = BroadcastChannel::class;

    public function definition()
    {
        return [
            'name'  => $this->faker->randomElement(['tv3','varzesh','lenz','aio','tva','anten']),
            'image' => UploadedFile::fake()->image('image.jpg'),
        ];
    }
}
