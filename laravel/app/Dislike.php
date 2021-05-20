<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dislike extends Model{

    protected $fillable = [
        'dislikeable_type',
        'dislikeable_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

}