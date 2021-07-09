<?php

namespace App\Models;

use App\Support\Traits\ImageableTrait;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property mixed id
 * @property mixed context
 * @property mixed user_id
 * @mixin Builder
 */
class Post extends Model
{
    use HasFactory,SoftDeletes,Sluggable,ImageableTrait;

    protected $fillable = [
        'slug',
        'main_title',
        'secondary_title',
        'context',
        'image',
        'status',
        'like',
        'dislike',
        'view',
        'comment',
        'type',
    ];

    public function getTitleAttribute(): string
    {
        $separator = $this->secondary_title ? '؛ ' : '';

        return sprintf("%s%s%s",$this->main_title,$separator,$this->secondary_title);
    }

    public function scopeNews($query)
    {
        return $query->whereType('NEWS');
    }

    public function scopeUserContent($query)
    {
        return $query->whereType('USER_CONTENT');
    }

    public function scopeReleased($query)
    {
        return $query->whereStatus('RELEASED');
    }

    public function scopeDrafted($query)
    {
        return $query->whereStatus('DRAFTED');
    }

    public function scopePending($query)
    {
        return $query->whereStatus('PENDING');
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'main_title',
            ],
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class,'commentable');
    }

    public function likes(): MorphToMany
    {
        return $this->morphToMany(User::class,'likable','likes')->using(Like::class);
    }

    public function dislikes(): MorphToMany
    {
        return $this->morphToMany(User::class,'dislikable','dislikes')->using(Dislike::class);
    }

    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class,'taggable')->withTimestamps();
    }

    public function chiefChoice()
    {
        return $this->hasOne(ChiefChoice::class);
    }

    public function slider()
    {
        return $this->hasOne(Slider::class);
    }
}
