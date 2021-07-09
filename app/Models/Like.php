<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\MorphPivot;

class Like extends MorphPivot
{
    protected $table = 'likes';
}
