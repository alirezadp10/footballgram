<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Fixture extends Model
{
    use HasFactory;

    protected $fillable = [
        'host',
        'guest',
        'host_point',
        'guest_point',
        'match_type',
        'odd_even',
        'round',
        'period',
        'final',
        'datetime',
        'season',
    ];

    public function competition(): BelongsTo
    {
        return $this->belongsTo(Competition::class);
    }
}
