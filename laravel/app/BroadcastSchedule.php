<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BroadcastSchedule extends Model
{
    protected $table = 'broadcast_schedule';

    protected $fillable = [
        'id',
        'broadcast_channel',
        'host',
        'guest',
        'datetime',
        'created_at',
        'updated_at',
    ];

    public static function schedule()
    {
        return
            DB::table('broadcast_schedule')
              ->where('datetime', '>', Carbon::today())
              ->orderBy('datetime')
              ->get();
    }

}
