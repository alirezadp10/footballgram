<?php

namespace Tests\Feature\Posts;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShowPostTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function post_can_be_shown()
    {
        $post = Post::factory()->released()->create();

        $this->get(route('posts.show',['slug' => $post->slug]))->assertOk()->assertViewHas([
            'post.id',
            'post.main_title',
            'post.secondary_title',
            'post.context',
            'post.slug',
            'post.user.name',
            'post.image',
            'post.comment',
            'post.like',
            'post.dislike',
            'post.view',
            'post.created_at',
            'permissions.update_post',
            'permissions.delete_post',
            'permissions.manage_chief_choice',
            'permissions.manage_slide_post',
            'permissions.create_comment',
        ]);
    }

    /**
     * @test
     */
    public function post_with_not_released_status_should_not_be_shown()
    {
        $post = Post::factory()->create();

        $this->get(route('posts.show',['slug' => $post->slug]))->assertNotFound();
    }

    /**
     * @test
     */
    public function if_user_was_author_of_post_he_can_see_edit_post_button()
    {
        $this->signIn();

        $post = Post::factory(['user_id' => auth()->id()])->released()->create();

        $this->get(route('posts.show',['slug' => $post->slug]))->assertViewHas([
            'permissions.update_post' => true,
        ]);
    }

    /**
     * @test
     */
    public function if_user_has_update_post_permission_he_can_see_edit_post_button()
    {
        $this->signIn()->ability('edit-news');

        $post = Post::factory()->released()->create();

        $this->get(route('posts.show',['slug' => $post->slug]))->assertViewHas([
            'permissions.update_post' => true,
        ]);
    }

    /**
     * @test
     */
    public function if_user_was_author_of_post_he_can_see_delete_post_button()
    {
        $this->signIn();

        $post = Post::factory(['user_id' => auth()->id()])->released()->create();

        $this->get(route('posts.show',['slug' => $post->slug]))->assertViewHas([
            'permissions.delete_post' => true,
        ]);
    }

    /**
     * @test
     */
    public function if_user_has_delete_post_permission_he_can_see_delete_post_button()
    {
        $this->signIn();

        $post = Post::factory()->released()->create();

        $this->get(route('posts.show',['slug' => $post->slug]))->assertViewHas([
            'permissions.delete_post' => false,
        ]);

        $this->signIn()->ability('delete-news');

        $this->get(route('posts.show',['slug' => $post->slug]))->assertViewHas([
            'permissions.delete_post' => true,
        ]);
    }

    /**
     * @test
     */
    public function if_user_has_chief_choice_permission_he_can_see_manage_chief_choice_button()
    {
        $this->signIn();

        $post = Post::factory()->released()->create();

        $this->get(route('posts.show',['slug' => $post->slug]))->assertViewHas([
            'permissions.manage_chief_choice' => false,
        ]);

        $this->signIn()->ability('manage-chief-choice');

        $this->get(route('posts.show',['slug' => $post->slug]))->assertViewHas([
            'permissions.manage_chief_choice' => true,
        ]);
    }

    /**
     * @test
     */
    public function if_user_has_chief_choice_permission_he_can_see_manage_slide_post_button()
    {
        $this->signIn();

        $post = Post::factory()->released()->create();

        $this->get(route('posts.show',['slug' => $post->slug]))->assertViewHas([
            'permissions.manage_slide_post' => false,
        ]);

        $this->signIn()->ability('manage-slide-post');

        $this->get(route('posts.show',['slug' => $post->slug]))->assertViewHas([
            'permissions.manage_slide_post' => true,
        ]);
    }

    /**
     * @test
     */
    public function if_user_has_chief_choice_permission_he_can_try_create_comment()
    {
        $this->signIn();

        $post = Post::factory()->released()->create();

        $this->get(route('posts.show',['slug' => $post->slug]))->assertViewHas([
            'permissions.create_comment' => false,
        ]);

        $this->signIn()->ability('create-comment');

        $this->get(route('posts.show',['slug' => $post->slug]))->assertViewHas([
            'permissions.create_comment' => true,
        ]);
    }

    /**
     * @test
     */
    public function when_post_be_shown_view_counts_must_be_increase()
    {
        $post = Post::factory()->released()->create();

        $this->get(route('posts.show',['slug' => $post->slug]));

        $this->assertEquals(1,$post->fresh()->view);
    }
}
