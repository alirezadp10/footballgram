<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BroadcastSchedule extends Model
{
    use HasFactory;

    protected $table = 'broadcast_schedule';

    protected $fillable = [
        'host',
        'guest',
        'datetime',
    ];

    public function channel(): BelongsTo
    {
        return $this->belongsTo(BroadcastChannel::class,'broadcast_channel_id');
    }
}
