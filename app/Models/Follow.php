<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\MorphPivot;

class Follow extends MorphPivot
{
    protected $table = 'followers';

    public $incrementing = TRUE;

    protected static function boot()
    {
        parent::boot();

        self::created(function ($model) {
            User::find($model->follow_up_id)->increment('count_followers');
            User::find($model->follower_id)->increment('count_followings');
        });

        self::deleted(function ($model) {
            User::find($model->follow_up_id)->decrement('count_followers');
            User::find($model->follower_id)->decrement('count_followings');
        });
    }
}
