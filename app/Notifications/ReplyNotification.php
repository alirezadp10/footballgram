<?php

namespace App\Notifications;

use App\Models\Comment;
use App\Models\Post;
use App\Models\Tweet;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class ReplyNotification extends Notification
{
    /**
     * Create a new notification instance.
     *
     * @param Comment $comment
     */
    public function __construct(public Comment $comment)
    {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array
     */
    public function via(): array
    {
        return [
            'broadcast',
            'database',
        ];
    }

    /**
     * Get the broadcastable representation of the notification.
     *
     * @return BroadcastMessage
     */
    public function toBroadcast(): BroadcastMessage
    {
        return new BroadcastMessage([
            'url'     => $this->url(),
            'image'   => $this->comment->user->image,
            'message' => $this->message(),
        ]);
    }

    /**
     * Store in database representation of the notification.
     *
     * @return array
     */
    public function toDatabase(): array
    {
        return [
            'url'     => $this->url(),
            'image'   => $this->comment->user->image,
            'message' => $this->message(),
        ];
    }

    /**
     * @return string
     */
    private function message(): string
    {
        return sprintf('%s %s', $this->comment->user->name, 'کامنت شما را پاسخ داده است:');
    }

    /**
     * @return string
     */
    private function url(): string
    {
        $route = [
            Post::class  => 'posts',
            Tweet::class => 'tweets',
        ];

        $post = $this->comment->commentable;

        $name = $route[get_class($post)].'.show';

        return sprintf('%s#comment-%s', route($name, $post->slug), $this->comment->id);
    }
}
