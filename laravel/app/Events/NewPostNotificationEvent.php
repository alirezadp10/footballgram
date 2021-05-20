<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewPostNotificationEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $post;
    public $type;
    public $auth_user;

    /**
     * Create a new event instance.
     *
     * @param $post
     * @param $type
     * @param $auth_user
     */
    public function __construct($post, $type, $auth_user)
    {
        $this->type = $type;
        $this->post = $post;
        $this->auth_user = $auth_user;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
