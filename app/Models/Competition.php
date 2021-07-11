<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property bool $timestamps
 * @mixin Builder
 */
class Competition extends Model
{
    use HasFactory;

    protected $table = 'competitions';

    public $timestamps = false;

    protected $fillable = [
        'name',
    ];

    public function standing(): HasMany
    {
        return $this->hasMany(Standing::class);
    }

    public function scorers(): HasMany
    {
        return $this->hasMany(Scorers::class);
    }

    public function fixtures(): HasMany
    {
        return $this->hasMany(Fixture::class);
    }
}
