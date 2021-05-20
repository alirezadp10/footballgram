<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ReplyNotificationEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $comment;
    public $type;
    public $auth_user;

    /**
     * Create a new event instance.
     *
     * @param $comment
     * @param $type
     * @param $auth_user
     */
    public function __construct($comment, $type, $auth_user)
    {
        $this->comment = $comment;
        $this->type = $type;
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
