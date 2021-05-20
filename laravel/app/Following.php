<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Following extends Model{

    protected $fillable = [
        'follower_id',
        'follow_up_id'
    ];

    public function users()
    {
        return $this->belongsTo(User::class,'follower_id');
    }

}