<?php

namespace App\Support\Traits;

use Illuminate\Support\Str;

trait ImageableTrait
{
    public function setImageAttribute($value): void
    {
        $this->attributes['image'] = $value->store('images/'.Str::kebab(class_basename($this)), 'public');
    }

    public function getImageAttribute($value): string
    {
        return $value ?: 'images/'.Str::kebab(class_basename($this)).'.png';
    }
}
