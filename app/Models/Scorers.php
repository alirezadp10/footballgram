<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Scorers extends Model
{
    use HasFactory;

    protected $fillable = [
        'club',
        'name',
        'count_scores',
        'count_assists',
        'season',
    ];

    public function competition(): BelongsTo
    {
        return $this->belongsTo(Competition::class);
    }
}
