<?php

namespace App\Models;

use App\Support\Traits\ImageableTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Class User.
 *
 * @property static id
 * @property static followings
 * @property static followers
 * @property static name
 * @property static email
 * @property static password
 * @property static bio
 * @property static mobile
 * @property static username
 * @property static score
 * @property static count_news
 * @property static count_user_contents
 * @property static count_likes_given
 * @property static count_dislikes_given
 * @property static count_comments_taken
 * @property static count_comments_given
 * @property static count_followers
 * @property static count_followings
 * @property static instagram_id
 * @property static telegram_id
 * @mixin Builder
 */
class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;
    use ImageableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'image',
        'bio',
        'mobile',
        'username',
        'score',
        'count_posts',
        'count_likes_given',
        'count_dislikes_given',
        'count_comments_taken',
        'count_comments_given',
        'count_followers',
        'count_followings',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function abilities(): BelongsToMany
    {
        return $this->belongsToMany(Ability::class, 'users_abilities', 'user_id', 'ability_id');
    }

    //-----------------------------------------------------------

    public function follow(User $user): void
    {
        $this->followings()->firstOr(function () use ($user) {
            $this->followings()->attach([
                'follow_up_id' => $user->id,
            ]);
        });
    }

    public function unfollow(User $user): void
    {
        $this->followings()->detach([
            'follow_up_id' => $user->id,
        ]);
    }

    public function followings(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'followers', 'follower_id', 'follow_up_id')
                    ->using(Follow::class)
                    ->withTimestamps();
    }

    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'followers', 'follow_up_id', 'follower_id')
                    ->using(Follow::class)
                    ->withTimestamps();
    }

    //-----------------------------------------------------------

    public function like($model): void
    {
        $model->likes()->attach($this);
    }

    public function unlike($model): void
    {
        $model->likes()->detach($this);
    }

    public function dislike($model): void
    {
        $model->dislikes()->attach($this);
    }

    public function undislike($model): void
    {
        $model->dislikes()->detach($this);
    }

    public function postLiked(): MorphToMany
    {
        return $this->morphedByMany(Post::class, 'likable', 'likes');
    }

    public function tweetsLiked(): MorphToMany
    {
        return $this->morphedByMany(Tweet::class, 'likable', 'likes');
    }

    public function commentsLiked(): MorphToMany
    {
        return $this->morphedByMany(Comment::class, 'likable', 'likes');
    }

    public function postDisliked(): MorphToMany
    {
        return $this->morphedByMany(Post::class, 'dislikable', 'dislikes');
    }

    public function tweetsDisliked(): MorphToMany
    {
        return $this->morphedByMany(Tweet::class, 'dislikable', 'dislikes');
    }

    public function commentsDisliked(): MorphToMany
    {
        return $this->morphedByMany(Comment::class, 'dislikable', 'dislikes');
    }

    //-----------------------------------------------------------

    public function postTimeline(): HasManyThrough
    {
        return $this->hasManyThrough(Post::class, Follow::class, 'follower_id', 'user_id', 'id', 'follow_up_id');
    }

    public function tweetTimeline(): HasManyThrough
    {
        return $this->hasManyThrough(Tweet::class, Follow::class, 'follower_id', 'user_id', 'id', 'follow_up_id');
    }

    //-----------------------------------------------------------

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function tweets(): HasMany
    {
        return $this->hasMany(Tweet::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    //-----------------------------------------------------------

    public function survey(): HasMany
    {
        return $this->hasMany(Survey::class);
    }

    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }
}
