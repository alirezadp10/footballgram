<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'context',
        'like',
        'parent_id',
        'user_id',
        'dislike',
        'report',
    ];

    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function likes(): MorphToMany
    {
        return $this->morphToMany(User::class, 'likable', 'likes')->using(Like::class);
    }

    public function dislikes(): MorphToMany
    {
        return $this->morphToMany(User::class, 'dislikable', 'dislikes')->using(Dislike::class);
    }

    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable')->withTimestamps();
    }
}
