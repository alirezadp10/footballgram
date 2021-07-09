<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChiefChoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'order',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class)->news();
    }
}
