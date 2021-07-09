<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use App\Notifications\FollowingNotification;
use App\Notifications\MentionNotification;
use App\Notifications\NewPostNotification;
use App\Notifications\ReplyNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function users_should_can_see_their_notifications()
    {
        $this->signIn();

        auth()->user()->notifyNow(new NewPostNotification($post = Post::factory()->released()->create()),['database']);

        auth()->user()->notifyNow(new FollowingNotification($follower = User::factory()->create(),'follow'),['database']);

        auth()->user()->notifyNow(new FollowingNotification($unFollower = User::factory()->create(),'unfollow'),['database']);

        auth()->user()->notifyNow(new MentionNotification($comment = Comment::factory()->create()),['database']);

        auth()->user()->notifyNow(new ReplyNotification($reply = Comment::factory()->create()),['database']);

        $response = $this->get(route('users.notifications'));

        $response->assertExactJson([
            [
                'url'     => route('posts.show',$post->slug),
                'image'   => $post->user->image,
                'message' => sprintf("%s %s",$post->user->name,"مطلب جدید ارسال کرده است."),
            ],
            [
                'url'     => route('users.show',$follower->username),
                'image'   => $follower->image,
                'message' => sprintf("%s %s",$follower->name,'شما را دنبال می کند.'),
            ],
            [
                'url'     => route('users.show',$unFollower->username),
                'image'   => $unFollower->image,
                'message' => sprintf("%s %s",$unFollower->name,'دیگر شما را دنبال نمی کند.'),
            ],
            [
                'url'     => route('posts.show',$comment->commentable->slug),
                'image'   => $comment->user->image,
                'message' => sprintf("%s %s",$comment->user->name,"شما را منشن کرده است."),
            ],
            [
                'url'     => sprintf("%s#comment-%s",route('posts.show',$reply->commentable->slug),$reply->id),
                'image'   => $reply->user->image,
                'message' => sprintf("%s %s",$reply->user->name,"کامنت شما را پاسخ داده است:"),
            ],
        ]);
    }
}
