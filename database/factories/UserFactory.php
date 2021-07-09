<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    public function __construct($count = NULL,?Collection $states = NULL,?Collection $has = NULL,?Collection $for = NULL,?Collection $afterMaking = NULL,?Collection $afterCreating = NULL,$connection = NULL)
    {
        parent::__construct($count,$states,$has,$for,$afterMaking,$afterCreating,$connection);

        Storage::fake("public");
    }

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name'           => $this->faker->name(),
            'username'       => $this->faker->unique()->userName,
            'email'          => $this->faker->unique()->safeEmail,
            'mobile'         => $this->faker->unique()->phoneNumber,
            'image'          => UploadedFile::fake()->image('image.jpg'),
            'bio'            => $this->faker->text(100),
            'password'       => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'remember_token' => Str::random(10),
        ];
    }
}
