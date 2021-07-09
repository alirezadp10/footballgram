<?php

namespace Database\Seeders;

use App\Models\Ability;
use Illuminate\Database\Seeder;

class AbilitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $attributes = [
            ['title' => 'create-news'],
            ['title' => 'create-user-content'],
            ['title' => 'create-tweet'],
            ['title' => 'create-comment'],
            ['title' => 'edit-news'],
            ['title' => 'edit-user-content'],
            ['title' => 'edit-tweet'],
            ['title' => 'edit-comment'],
            ['title' => 'delete-tweet'],
            ['title' => 'delete-news'],
            ['title' => 'delete-user-content'],
            ['title' => 'delete-comment'],
            ['title' => 'manage-broadcast-schedule'],
            ['title' => 'manage-survey'],
            ['title' => 'manage-slide-post'],
            ['title' => 'manage-chief-choice'],
        ];

        Ability::factory($attributes)->create();
    }
}
