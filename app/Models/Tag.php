<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Class Tag
 * @package App\Models
 * @mixin Builder
 */
class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'count',
    ];

    public function posts()
    {
        return $this->morphedByMany(Post::class,'taggable')->withTimestamps();
    }

    public function tweets()
    {
        return $this->morphedByMany(Tweet::class,'taggable')->withTimestamps();
    }

    public function comments()
    {
        return $this->morphedByMany(Comment::class,'taggable')->withTimestamps();
    }
}
