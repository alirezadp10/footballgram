<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CreateTag
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $name;
    public $type;
    public $id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($name, $type, $id)
    {
        $this->name = $name;
        $this->type = $type;
        $this->id   = $id;
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
