<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function __construct($count = null, ?Collection $states = null, ?Collection $has = null, ?Collection $for = null, ?Collection $afterMaking = null, ?Collection $afterCreating = null, $connection = null)
    {
        parent::__construct($count, $states, $has, $for, $afterMaking, $afterCreating, $connection);

        Storage::fake('public');
    }

    public function definition()
    {
        return [
            'main_title'      => $this->faker->sentence,
            'secondary_title' => $this->faker->sentence,
            'context'         => $this->faker->text(),
            'image'           => UploadedFile::fake()->image('image.jpg'),
            'user_id'         => User::factory(),
            'status'          => 'PENDING',
            'type'            => 'NEWS',
            'like'            => 0,
            'dislike'         => 0,
            'view'            => 0,
            'comment'         => 0,
        ];
    }

    public function news()
    {
        return $this->state(fn () => ['type' => 'NEWS']);
    }

    public function userContent()
    {
        return $this->state(fn () => ['type' => 'USER_CONTENT']);
    }

    public function released()
    {
        return $this->state(fn () => ['status' => 'RELEASED']);
    }

    public function drafted()
    {
        return $this->state(fn () => ['status' => 'DRAFTED']);
    }
}
