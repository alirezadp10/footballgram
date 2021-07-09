<?php

namespace App\Models;

use App\Support\Traits\ImageableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BroadcastChannel extends Model
{
    use HasFactory,ImageableTrait;

    protected $fillable = [
        'name',
        'image',
    ];

    public function schedule(): HasMany
    {
        return $this->hasMany(BroadcastSchedule::class);
    }
}
