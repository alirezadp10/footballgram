<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model{

    protected $fillable = [
        'context',
        'like',
        'parent',
        'level',
        'dislike',
        'report',
        'commentable_type',
        'commentable_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function news()
    {
        return $this->belongsTo(News::class,'commentable_id');
    }

    public function userContents()
    {
        return $this->belongsTo(UserContent::class,'commentable_id');
    }

    public function tweets()
    {
        return $this->belongsTo(Tweet::class,'commentable_id');
    }

    public function commentable()
    {
        return $this->morphTo();
    }

    public function likes()
    {
        return $this->morphToMany(User::class, 'likeable','likes')->select(['users.id']);
    }

    public function dislikes()
    {
        return $this->morphToMany(User::class, 'dislikeable','dislikes')->select(['users.id']);
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable','taggables');
    }

}