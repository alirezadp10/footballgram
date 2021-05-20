<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class NewPostNotification extends Notification
{
    private $type;
    private $meta_id;
    private $url;
    private $avatar;
    private $title;
    private $context;

    /**
     * Create a new notification instance.
     *
     * @param $type
     * @param $meta_id
     * @param $url
     * @param $avatar
     * @param $title
     * @param $context
     */
    public function __construct($type, $meta_id, $url, $avatar, $title, $context)
    {
        $this->type = $type;
        $this->meta_id = $meta_id;
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
//            'broadcast',
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
            'meta_id' => $this->meta_id,
            'meta_type'    => $this->type,
            'title'   => $this->title,
            'context' => $this->context,
        ];
    }
}
