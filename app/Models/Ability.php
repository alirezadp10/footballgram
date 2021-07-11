<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property bool $timestamps
 * @mixin Builder
 */
class Ability extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'title',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'users_ability', 'ability_id', 'user_id');
    }
}
