<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Taggable extends Model{

    protected $fillable = [
        'taggable_type',
        'taggable_id'
    ];

    public function user()
    {
        return $this->belongsTo(Tag::class,'tag_id');
    }

}