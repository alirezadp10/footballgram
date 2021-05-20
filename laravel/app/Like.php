<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Like extends Model{

    protected $fillable = [
        'likeable_type',
        'likeable_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

}