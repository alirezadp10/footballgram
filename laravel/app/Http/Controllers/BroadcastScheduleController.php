<?php

namespace App\Http\Controllers;

use App\Helpers\MainHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use jDate;

class BroadcastScheduleController extends Controller
{
    private $mainHelper;

    public function __construct(MainHelper $mainHelper)
    {
        $this->mainHelper = $mainHelper;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'host'              => 'required',
            'guest'             => 'required',
            'broadcast_channel' => 'required',
            'datetime'          => 'required',
        ]);

        if (auth()->user()->cant('manage-broadcast-schedule')) {
            abort('403');
        }

        $datetime = Carbon::createFromTimestamp(substr($request->datetime, 0, 10))
                          ->format("Y-m-d H:i:s");

        $match_id = DB::table('broadcast_schedule')->insertGetId(
            array_merge($request->except('_token'), [
                'datetime' => $datetime,
            ])
        );

        return response()->json([
            'message'  => 'با موفقیت انجام شد',
            'id'       => $match_id,
            'title'    => "{$request->host} - {$request->guest}",
            'datetime' => $datetime,
            'time'     => jDate::forge($datetime)->format('l ، j F  - ساعت: H:i'),
            'host'     => $request->host,
            'guest'    => $request->guest,
            'image'    => "/images/{$request->broadcast_channel}.png",
            'alt'      => $request->broadcast_channel,
        ]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'host'              => 'required',
            'guest'             => 'required',
            'broadcast_channel' => 'required',
            'datetime'          => 'required',
        ]);

        if (auth()->user()->cant('manage-broadcast-schedule')) {
            abort('403');
        }

        $datetime = Carbon::createFromTimestamp(substr($request->datetime, 0, 10))
              ->format("Y-m-d H:i:s");

        DB::table('broadcast_schedule')->where('id',$id)->update(
            array_merge($request->except('_token','_method'), [
                'datetime' => $datetime,
            ])
        );

        return response()->json([
            'message'  => 'با موفقیت انجام شد',
            'title'    => "{$request->host} - {$request->guest}",
            'datetime' => $datetime,
            'time'     => jDate::forge($datetime)->format('l ، j F  - ساعت: H:i'),
            'host'     => $request->host,
            'guest'    => $request->guest,
            'alt'      => $request->broadcast_channel,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        if (auth()->user()->cant('manage-broadcast-schedule')) {
            abort('403');
        }
        DB::table('broadcast_schedule')->where('id',$id)->delete();
        return response()->json([
            'message' => 'با موفقیت انجام شد'
        ]);
    }
}
