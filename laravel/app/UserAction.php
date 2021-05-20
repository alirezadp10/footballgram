<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\UserAction
 *
 * @property int $id
 * @property string $title
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $users
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserAction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserAction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserAction whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserAction whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class UserAction extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'context',
        'main_photo',
        'slug',
        'like',
        'dislike',
        'report'
    ];

    public function users()
    {
        return $this->belongsToMany(
            User::class,
            'user_actions_pivot_users',
            'user_action_id',
            'user_id'
        );
    }
}
