<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FollowingTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function users_can_be_followed_by_others()
    {
        $ali = User::factory()->create();
        $mohammad = User::factory()->create();
        $mohammad->follow($ali);
        $this->assertDatabaseHas('followers', [
            'follower_id'  => $mohammad->id,
            'follow_up_id' => $ali->id,
        ]);
    }

    /**
     * @test
     */
    public function users_can_not_followed_by_specific_user_twice()
    {
        $ali = User::factory()->create();
        $mohammad = User::factory()->create();
        $mohammad->follow($ali);
        $mohammad->follow($ali);
        $this->assertEquals(1, $ali->followers->count());
    }

    /**
     * @test
     */
    public function users_can_be_unfollowed_by_others()
    {
        $ali = User::factory()->create();
        $mohammad = User::factory()->create();
        $mohammad->follow($ali);
        $mohammad->unfollow($ali);
        $this->assertDatabaseMissing('followers', [
            'follower_id'  => $mohammad->id,
            'follow_up_id' => $ali->id,
        ]);
    }

    /**
     * @test
     */
    public function follow_specific_user_must_increase_followings_count_of_auth()
    {
        $this->signIn();

        $auth = auth()->user();

        $user = User::factory()->create();

        $this->assertEquals(0, $auth->count_followings);

        $this->post(route('users.follow', $user->username));

        $this->assertEquals(1, $auth->fresh()->count_followings);
    }

    /**
     * @test
     */
    public function follow_specific_user_must_increase_followers_count_of_user()
    {
        $this->signIn();

        $user = User::factory()->create();

        $this->assertEquals(0, $user->count_followers);

        $this->post(route('users.follow', $user->username));

        $this->assertEquals(1, $user->fresh()->count_followers);
    }

    /**
     * @test
     */
    public function unfollow_specific_user_must_decrease_followings_count_of_auth()
    {
        $this->signIn();

        $auth = auth()->user();

        $user = User::factory()->create();

        auth()->user()->follow($user);

        $this->assertEquals(1, $auth->fresh()->count_followings);

        $this->post(route('users.follow', $user->username));

        $this->assertEquals(0, $auth->fresh()->count_followings);
    }

    /**
     * @test
     */
    public function unfollow_specific_user_must_decrease_followers_count_of_user()
    {
        $this->signIn();

        $user = User::factory()->create();

        auth()->user()->follow($user);

        $this->assertEquals(1, $user->fresh()->count_followers);

        $this->post(route('users.follow', $user->username));

        $this->assertEquals(0, $user->fresh()->count_followers);
    }
}
