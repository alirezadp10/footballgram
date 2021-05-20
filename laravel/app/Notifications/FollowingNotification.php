<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class FollowingNotification extends Notification
{
    private $type;
    private $user_id;
    private $url;
    private $avatar;
    private $context;

    /**
     * Create a new notification instance.
     *
     * @param $type
     * @param $url
     * @param $avatar
     * @param $context
     * @param $user_id
     */
    public function __construct($type, $user_id, $url, $avatar, $context)
    {
        $this->user_id = $user_id;
        $this->type = $type;
        $this->url = $url;
        $this->avatar = $avatar;
        $this->context = $context;
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
            'title'             => '',
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
            'meta_id' => $this->user_id,
            'meta_type'    => $this->type,
            'title'   => '',
            'context' => $this->context,
        ];
    }
}
