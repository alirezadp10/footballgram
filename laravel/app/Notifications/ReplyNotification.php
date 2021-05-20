<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class ReplyNotification extends Notification
{
    private $type;
    private $comment_id;
    private $url;
    private $avatar;
    private $title;
    private $context;

    /**
     * Create a new notification instance.
     *
     * @param $type
     * @param $comment_id
     * @param $url
     * @param $avatar
     * @param $context
     * @param $title
     */
    public function __construct($type, $comment_id, $url, $avatar, $title, $context)
    {
        $this->comment_id = $comment_id;
        $this->type = $type;
        $this->url = $url;
        $this->avatar = $avatar;
        $this->context = $context;
        $this->title = $title;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array
     */
    public function via()
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
    public function toBroadcast()
    {
        return new BroadcastMessage([
            'notification_type' => $this->type,
            'url'               => $this->url,
            'avatar'            => $this->avatar,
            'title'             => $this->title,
            'context'           => $this->context,
        ]);
    }

    /**
     * Store in database representation of the notification.
     *
     * @return array
     */
    public function toDatabase()
    {
        return [
            'meta_id' => $this->comment_id,
            'meta_type'    => $this->type,
            'title'   => $this->title,
            'context' => $this->context,
        ];
    }
}
