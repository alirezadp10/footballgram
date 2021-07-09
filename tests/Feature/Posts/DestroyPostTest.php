<?php

namespace Tests\Feature\Posts;

use App\Events\DetectTagsEvent;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DestroyPostTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function guests_can_not_delete_a_post()
    {
        $post = Post::factory()->released()->create();

        $this->delete(route('posts.destroy',$post->slug))->assertRedirect('login');
    }

    /**
     * @test
     */
    public function only_users_with_delete_post_ability_can_delete_a_post()
    {
        $this->signIn();

        $post = Post::factory()->released()->create();

        $this->delete(route('posts.destroy',$post->slug))->assertForbidden();
    }

    /**
     * @test
     */
    public function wrong_post_slug_must_return_not_found()
    {
        $this->signIn()->ability('delete-news');

        $this->delete(route('posts.destroy','foobar'))->assertNotFound();
    }

    /**
     * @test
     */
    public function when_post_deleted_its_tags_should_not_exist_anymore()
    {
        $this->signIn()->ability('delete-news');

        $post = Post::factory(['context' => '#Official: #Juventus signed with #Ronaldo.'])->released()->create();

        event(new DetectTagsEvent($post));

        $this->delete(route('posts.destroy',$post->slug));

        $this->assertDatabaseMissing('tags',[
            'name'  => 'Official',
            'count' => '1',
        ])->assertDatabaseMissing('tags',[
            'name'  => 'Juventus',
            'count' => '1',
        ])->assertDatabaseMissing('tags',[
            'name'  => 'Ronaldo',
            'count' => '1',
        ]);
    }

    /**
     * @test
     */
    public function when_post_deleted_its_comments_should_not_exist_anymore()
    {
        $this->signIn()->ability('delete-news');

        $post = Post::factory()->hasComments()->released()->create();

        $this->delete(route('posts.destroy',$post->slug));

        $this->assertEmpty(Comment::all());
    }

    /**
     * @test
     */
    public function when_post_deleted_author_stats_must_be_effected()
    {
        $this->signIn()->ability('delete-news');

        $post = Post::factory()->hasComments(5)->hasLikes(4)->hasDislikes(3)->released()->create();

        $this->delete(route('posts.destroy',$post->slug));

        $post->refresh();

        $this->assertEquals(0,$post->user->count_post);

        $this->assertEquals(0,$post->user->count_likes_given);

        $this->assertEquals(0,$post->user->count_dislikes_given);

        $this->assertEquals(0,$post->user->count_comments_given);
    }
}
