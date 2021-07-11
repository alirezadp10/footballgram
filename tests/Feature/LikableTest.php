<?php

namespace Tests\Feature;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LikableTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function a_post_can_be_liked()
    {
        $post = Post::factory()->released()->create();

        $this->signIn();

        $this->post(route('posts.like', ['slug' => $post->slug]));

        $this->assertTrue($post->likes->contains('id', auth()->id()));

        $this->assertEquals(1, $post->fresh()->like);

        $this->assertEquals(1, auth()->user()->fresh()->count_likes_given);
    }

    /**
     * @test
     */
    public function a_post_can_be_disliked()
    {
        $post = Post::factory()->released()->create();

        $this->signIn();

        $this->post(route('posts.dislike', ['slug' => $post->slug]));

        $this->assertTrue($post->dislikes->contains('id', auth()->id()));

        $this->assertEquals(1, $post->fresh()->dislike);

        $this->assertEquals(1, auth()->user()->fresh()->count_dislikes_given);
    }

    /**
     * @test
     */
    public function a_comment_can_be_liked()
    {
        //TODO
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function a_comment_can_be_disliked()
    {
        //TODO
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function unauthorized_user_can_not_like_or_dislike_any_post_or_comments()
    {
        //TODO
        $this->assertTrue(true);
        $post = Post::factory()->create();

        $this->post(route('posts.like', ['slug' => $post->slug]))->assertRedirect();

        $this->post(route('posts.dislike', ['slug' => $post->slug]))->assertRedirect();
    }

    /**
     * @test
     */
    public function user_can_not_like_or_dislike_own_comment()
    {
        //TODO
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function author_of_post_can_not_like_or_dislike_own_post()
    {
        $post = Post::factory()->released()->create();

        $this->actingAs($post->user)->post(route('posts.like', ['slug' => $post->slug]))->assertForbidden();

        $this->actingAs($post->user)->post(route('posts.dislike', ['slug' => $post->slug]))->assertForbidden();
    }

    /**
     * @test
     */
    public function a_post_can_not_like_more_than_one_time_from_one_user()
    {
        $post = Post::factory()->released()->create();

        $this->signIn();

        $this->post(route('posts.like', ['slug' => $post->slug]));

        $this->post(route('posts.like', ['slug' => $post->slug]));

        $this->assertEquals(1, $post->fresh()->like);
    }

    /**
     * @test
     */
    public function a_post_can_not_dislike_more_than_one_time_from_one_user()
    {
        $post = Post::factory()->released()->create();

        $this->signIn();

        $this->post(route('posts.dislike', ['slug' => $post->slug]));

        $this->post(route('posts.dislike', ['slug' => $post->slug]));

        $this->assertEquals(1, $post->fresh()->dislike);
    }

    /**
     * @test
     */
    public function a_comment_can_not_like_more_than_one_time_from_one_user()
    {
        //TODO
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function a_comment_can_not_dislike_more_than_one_time_from_one_user()
    {
        //TODO
        $this->assertTrue(true);
    }
}
