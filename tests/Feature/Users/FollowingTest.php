<?php

namespace Tests\Feature\Users;

use App\Models\User;
use App\Notifications\FollowingNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class FollowingTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function guests_can_not_follow_any_others()
    {
        $user = User::factory()->create();
        $this->post(route('users.follow',[
            'username' => $user->username,
        ]))->assertRedirect('login');
    }

    /**
     * @test
     */
    public function follow_request_must_have_valid_username()
    {
        $this->signIn();
        $this->post(route('users.follow',[
            'username' => "foobar",
        ]))->assertNotFound();
    }

    /**
     * @test
     */
    public function follow_request_should_toggleable_for_following_and_unfollowing()
    {
        $this->signIn();
        $auth = auth()->user();
        $user = User::factory()->create();

        $this->post(route('users.follow',[
            'username' => $user->username,
        ]));
        $this->assertDatabaseHas('followers',[
            'follower_id'  => auth()->id(),
            'follow_up_id' => $user->id,
        ]);

        $this->actingAs($auth->fresh())->post(route('users.follow',[
            'username' => $user->username,
        ]));
        $this->assertDatabaseMissing('followers',[
            'follower_id'  => auth()->id(),
            'follow_up_id' => $user->id,
        ]);

        $this->actingAs($auth->fresh())->post(route('users.follow',[
            'username' => $user->username,
        ]));
        $this->assertDatabaseHas('followers',[
            'follower_id'  => auth()->id(),
            'follow_up_id' => $user->id,
        ]);
    }

    /**
     * @test
     */
    public function user_notified_when_followed()
    {
        Notification::fake();

        $this->signIn();

        $user = User::factory()->create();

        $this->post(route('users.follow',[
            'username' => $user->username,
        ]));

        Notification::assertSentTo($user,FollowingNotification::class);
    }

    /**
     * @test
     */
    public function user_notified_when_unfollowed()
    {
        Notification::fake();

        $this->signIn();

        $user = User::factory()->create();

        auth()->user()->follow($user);

        $this->post(route('users.follow',[
            'username' => $user->username,
        ]));

        Notification::assertSentTo($user,FollowingNotification::class);
    }
}
