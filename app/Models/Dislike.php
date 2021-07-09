<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\MorphPivot;

class Dislike extends MorphPivot
{
    protected $table = 'dislikes';
}
