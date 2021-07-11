<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use App\Notifications\MentionNotification;
use App\Notifications\ReplyNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class CommentableTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Notification::fake();
    }

    /**
     * @test
     */
    public function a_comment_can_be_posted()
    {
        $this->signIn()->ability('create-comment');

        $post = Post::factory()->create();

        $comment = Comment::factory()->raw();

        $comment['post_id'] = $post->id;

        $this->post(route('posts.comment', $comment))->assertRedirect("/posts/{$post->slug}#comment-1");

        $this->assertDatabaseHas('comments', [
            'context'          => $comment['context'],
            'user_id'          => auth()->id(),
            'commentable_id'   => $post->id,
            'commentable_type' => get_class($post),
        ]);
    }

    /**
     * @test
     */
    public function a_comment_must_have_context()
    {
        $this->signIn()->ability('create-comment');

        $post = Post::factory()->create();

        $comment = Comment::factory(['context' => ''])->raw();

        $comment['post_id'] = $post->id;

        $this->post(route('posts.comment', $comment))->assertSessionHasErrors('context');
    }

    /**
     * @test
     */
    public function a_comment_must_have_post_id()
    {
        $this->signIn()->ability('create-comment');

        $comment = Comment::factory()->raw();

        $this->post(route('posts.comment', $comment))->assertSessionHasErrors('post_id');
    }

    /**
     * @test
     */
    public function parent_id_must_be_exist_in_comments_id()
    {
        $this->signIn()->ability('create-comment');

        $post = Post::factory()->create();

        $comment = Comment::factory(['parent_id' => 999])->raw();

        $comment['post_id'] = $post->id;

        $this->post(route('posts.comment', $comment))->assertSessionHasErrors('parent_id');
    }

    /**
     * @test
     */
    public function guest_can_not_send_comment()
    {
        $post = Post::factory()->create();

        $comment = Comment::factory()->raw();

        $comment['post_id'] = $post->id;

        $this->post(route('posts.comment', $comment))->assertRedirect('login');
    }

    /**
     * @test
     */
    public function only_users_who_have_right_permission_can_send_comment()
    {
        $this->signIn();

        $post = Post::factory()->create();

        $comment = Comment::factory()->raw();

        $comment['post_id'] = $post->id;

        $this->post(route('posts.comment', $comment))->assertForbidden();
    }

    /**
     * @test
     */
    public function when_user_sent_comment_user_count_comments_taken_must_be_increased()
    {
        $this->signIn()->ability('create-comment');

        $post = Post::factory()->create();

        $comment = Comment::factory()->raw();

        $comment['post_id'] = $post->id;

        $this->post(route('posts.comment', $comment));

        $this->assertEquals(1, auth()->user()->fresh()->count_comments_taken);
    }

    /**
     * @test
     */
    public function when_comment_sent_author_count_comments_given_must_be_increased()
    {
        $this->signIn()->ability('create-comment');

        $post = Post::factory()->create();

        $comment = Comment::factory()->raw();

        $comment['post_id'] = $post->id;

        $this->post(route('posts.comment', $comment));

        $this->assertEquals(1, $post->user->count_comments_given);
    }

    /**
     * @test
     */
    public function when_comment_sent_on_post_count_of_model_comments_must_be_increased()
    {
        $this->signIn()->ability('create-comment');

        $post = Post::factory()->create();

        $comment = Comment::factory()->raw();

        $comment['post_id'] = $post->id;

        $this->post(route('posts.comment', $comment));

        $this->assertEquals(1, $post->fresh()->comment);
    }

    /**
     * @test
     */
    public function when_reply_sent_it_must_notify_parent_comment()
    {
        $this->signIn()->ability('create-comment');

        $post = Post::factory()->has(Comment::factory())->create();

        $reply = Comment::factory(['parent_id' => 1])->raw();

        $reply['post_id'] = $post->id;

        $this->post(route('posts.comment', $reply));

        Notification::assertSentTo($post->comments->first()->user, ReplyNotification::class);
    }

    /**
     * @test
     */
    public function when_comment_sent_its_tags_must_be_detected()
    {
        $this->signIn()->ability('create-comment');

        $post = Post::factory()->create();

        $comment = Comment::factory([
            'context' => 'this is #example #comment for detecting hashtags',
        ])->raw();

        $comment['post_id'] = $post->id;

        $this->post(route('posts.comment', $comment));

        $this->assertEquals("this is <a href='/tags/example' class='hashtag'>#example</a> <a href='/tags/comment' class='hashtag'>#comment</a> for detecting hashtags", Comment::first()->context);

        $this->assertDatabaseHas('tags', [
            'name'  => 'comment',
            'count' => '1',
        ])->assertDatabaseHas('tags', [
            'name'  => 'example',
            'count' => '1',
        ]);
    }

    /**
     * @test
     */
    public function when_comment_sent_its_mentions_must_be_detected()
    {
        $this->signIn()->ability('create-comment');

        User::factory(['username' => 'alirezadp10'])->create();

        $post = Post::factory()->create();

        $comment = Comment::factory([
            'context' => 'this is @alirezadp10 comment for @salam',
        ])->raw();

        $comment['post_id'] = $post->id;

        $this->post(route('posts.comment', $comment));

        $this->assertEquals("this is <a href='/users/alirezadp10' class='mention'>@alirezadp10</a> comment for @salam", Comment::first()->context);
    }

    /**
     * @test
     */
    public function when_user_mentioned_comment_must_be_notified()
    {
        $this->signIn()->ability('create-comment');

        $notifiable = User::factory(['username' => 'alirezadp10'])->create();

        $post = Post::factory()->create();

        $comment = Comment::factory([
            'context' => 'this is @alirezadp10',
        ])->raw();

        $comment['post_id'] = $post->id;

        $this->post(route('posts.comment', $comment));

        Notification::assertSentTo($notifiable, MentionNotification::class);
    }
}
