<?php

namespace App\Notifications;


use App\Models\Post;
use App\Models\Tweet;

use Illuminate\Bus\Queueable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class NewPostNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @param Model $post
     */
    public function __construct(public Model $post)
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
            'image'   => $this->post->user->image,
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
            'image'   => $this->post->user->image,
            'message' => $this->message(),
        ];
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

        $name = $route[get_class($this->post)] . ".show";

        return route($name,$this->post['slug']);
    }

    /**
     * @return string
     */
    private function message(): string
    {
        return sprintf("%s %s",$this->post->user->name,"مطلب جدید ارسال کرده است.");
    }
}
