<?php

namespace App\Repositories\Eloquent;

use App\Models\BroadcastSchedule;
use App\Repositories\Contracts\BroadcastChannelRepository as BroadcastChannelContract;
use Morilog\Jalali\Jalalian;

class BroadcastScheduleRepository extends BaseRepository implements BroadcastChannelContract
{
    public function __construct(BroadcastSchedule $broadcastSchedule)
    {
        $this->model = $broadcastSchedule;
    }

    public function all()
    {
        return $this->model->with('channel')
                           ->where('datetime','>',now())
                           ->orderBy('datetime')
                           ->get()
                           ->map(function ($item) {
                               return [
                                   'id'       => $item->id,
                                   'host'     => $item->host,
                                   'guest'    => $item->guest,
                                   'datetime' => $item->datetime,
                                   'time'     => Jalalian::forge($item->datetime)->format('l ، j F  - ساعت: H:i'),
                                   'image'    => $item->channel->image,
                                   'alt'      => $item->channel->name,
                               ];
                           });
    }
}
