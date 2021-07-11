<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Tweet extends Model
{
    use Sluggable;

    protected $fillable = [
        'context',
        'slug',
        'like',
        'dislike',
        'view',
        'comment',
        'status',
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'method'   => function () {
                    return Str::random(20);
                },
                'onUpdate' => true,
            ],
        ];
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function likes()
    {
        return $this->morphToMany(User::class, 'likeable', 'likes')
                    ->select(['users.id']);
    }

    public function dislikes()
    {
        return $this->morphToMany(User::class, 'dislikeable', 'dislikes')
                    ->select(['users.id']);
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable')->withTimestamps();
    }
}
