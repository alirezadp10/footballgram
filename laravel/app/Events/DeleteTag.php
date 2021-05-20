<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DeleteTag
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $meta_id;
    public $meta_type;

    /**
     * Create a new event instance.
     *
     * @param $meta_id
     * @param $meta_type
     */
    public function __construct($meta_id, $meta_type)
    {
        $this->meta_id = $meta_id;
        $this->meta_type = $meta_type;
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
