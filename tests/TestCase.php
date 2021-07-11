<?php

namespace Tests;

use App\Models\Ability;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function signIn($user = null): static
    {
        return tap($this, function () use ($user) {
            $this->actingAs($user ?? User::factory()->create());
        });
    }

    protected function ability($ability): static
    {
        return tap($this, function () use ($ability) {
            $ability = Ability::factory(['title' => $ability])->create();
            auth()->user()->abilities()->attach($ability->id);
        });
    }
}
