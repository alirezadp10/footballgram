<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Image extends Model{

    protected $fillable = [
        'original',
        'xs',
        'sm',
        'md',
        'lg',
        'xl',
        'imageable_type',
        'imageable_id'
    ];

    public function imageable()
    {
        return $this->morphTo();
    }

    public function getXsAttribute($value)
    {
        return Storage::url($value);
    }

}
