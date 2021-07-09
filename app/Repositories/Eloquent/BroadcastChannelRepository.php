<?php

namespace App\Repositories\Eloquent;

use App\Models\BroadcastChannel;
use App\Repositories\Contracts\BroadcastScheduleRepository as BroadcastScheduleContract;

class BroadcastChannelRepository extends BaseRepository implements BroadcastScheduleContract
{
    public function __construct(BroadcastChannel $broadcastChannel)
    {
        $this->model = $broadcastChannel;
    }

    public function firstOrFail($name)
    {
        return $this->model->whereName($name)->firstOrFail();
    }
}
