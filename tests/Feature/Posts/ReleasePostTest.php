<?php

namespace Tests\Feature\Posts;

use App\Models\Post;
use App\Models\User;
use App\Notifications\NewPostNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ReleasePostTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function post_can_release()
    {
        $this->signIn()->ability('create-news');

        $post = Post::factory()->create();

        $this->put(route('posts.release',['slug' => $post->slug]));

        $this->assertEquals('RELEASED',Post::find($post->id)->status);
    }

    /**
     * @test
     */
    public function only_users_with_create_post_ability_can_release_a_post()
    {
        $this->signIn();

        $post = Post::factory()->create();

        $this->put(route('posts.release',['slug' => $post->slug]))->assertForbidden();
    }

    /**
     * @test
     */
    public function tags_must_be_detect_after_post_released()
    {
        $this->signIn()->ability('create-news');

        $post = Post::factory()->create(['context' => '#Official: #Juventus signed with #Ronaldo.']);

        $this->put(route('posts.release',['slug' => $post->slug]));

        $this->assertEquals(
            "<a href='/tags/Official' class='hashtag'>#Official</a>: <a href='/tags/Juventus' class='hashtag'>#Juventus</a> signed with <a href='/tags/Ronaldo' class='hashtag'>#Ronaldo</a>.",
            $post->fresh()->context
        );

        $this->assertDatabaseHas('tags',[
            'name'  => 'Official',
            'count' => '1',
        ])->assertDatabaseHas('tags',[
            'name' => 'Juventus',
            'count' => '1',
        ])->assertDatabaseHas('tags',[
            'name'  => 'Ronaldo',
            'count' => '1',
        ]);
    }

    /**
     * @test
     */
    public function when_post_released_this_must_be_appear_in_author_followers_timeline()
    {
        $this->signIn()->ability('create-news');

        $auth = auth()->user();

        (User::factory()->create())->follow($auth);

        (User::factory()->create())->follow($auth);

        $post = Post::factory()->create(['user_id' => $auth->id]);

        $this->put(route('posts.release',['slug' => $post->slug]));

        $auth->load([
            'followers.postTimeline' => function ($timeline) use ($post) {
                $this->assertTrue($timeline->get()->contains('slug',$post->slug));
            },
        ]);
    }

    /**
     * @test
     */
    public function when_post_released_author_post_count_must_be_increase()
    {
        $this->signIn()->ability('create-news');

        $auth = auth()->user();

        $post = Post::factory()->create(['user_id' => $auth->id]);

        $this->put(route('posts.release',['slug' => $post->slug]));

        $this->assertEquals(1,$auth->fresh()->count_posts);
    }

    /**
     * @test
     */
    public function when_post_released_author_followers_must_be_notified()
    {
        Notification::fake();

        $this->signIn()->ability('create-news');

        $auth = auth()->user();

        (User::factory()->create())->follow($auth);

        (User::factory()->create())->follow($auth);

        $post = Post::factory()->create(['user_id' => $auth->id]);

        $this->put(route('posts.release',['slug' => $post->slug]));

        Notification::assertSentTo($auth->fresh()->followers,NewPostNotification::class);
    }
}
