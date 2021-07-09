<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Builder
 */
class Slider extends Model
{
    use HasFactory;

    protected $table = 'slider';

    protected $fillable = [
        'order',
        'tags',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'tags' => 'array',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class)->news();
    }
}
