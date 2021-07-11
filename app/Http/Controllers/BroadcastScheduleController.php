<?php

namespace App\Http\Controllers;

use App\Http\Requests\BroadcastScheduleRequest;
use Facades\App\Repositories\Contracts\BroadcastChannelRepository;
use Facades\App\Repositories\Contracts\BroadcastScheduleRepository;
use Morilog\Jalali\Jalalian;

class BroadcastScheduleController extends Controller
{
    public function store(BroadcastScheduleRequest $request)
    {
        $channel = BroadcastChannelRepository::findOrFail($request->broadcast_channel_id);

        $broadcast = $channel->schedule()->create($request->validated());

        return response()->json([
            'message' => 'با موفقیت انجام شد',
            'data'    => [
                'id'       => $broadcast->id,
                'host'     => $broadcast->host,
                'guest'    => $broadcast->guest,
                'datetime' => $broadcast->datetime,
                'time'     => Jalalian::forge($broadcast->datetime)->format('l ، j F  - ساعت: H:i'),
                'image'    => $channel->image,
                'alt'      => $channel->name,
            ],
        ], 201);
    }

    public function update(BroadcastScheduleRequest $request, $id)
    {
        $schedule = BroadcastScheduleRepository::find($id);

        $schedule->fill($request->validated());

        $channel = BroadcastChannelRepository::findOrFail($request->broadcast_channel_id);

        $schedule->broadcast_channel_id = $channel->id;

        $schedule->save();

        return response()->json([
            'message' => 'با موفقیت انجام شد',
            'data'    => [
                'id'       => $schedule->id,
                'host'     => $schedule->host,
                'guest'    => $schedule->guest,
                'datetime' => $schedule->datetime,
                'time'     => Jalalian::forge($schedule->datetime)->format('l ، j F  - ساعت: H:i'),
                'image'    => $channel->image,
                'alt'      => $channel->name,
            ],
        ]);
    }

    public function destroy($id)
    {
        $this->authorize('manage-broadcast-schedule');

        BroadcastScheduleRepository::find($id)->delete();

        return response()->json([
            'message' => 'با موفقیت انجام شد',
        ]);
    }
}
