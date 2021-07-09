<?php

namespace App\Repositories\Eloquent;

use App\Models\Ability;
use App\Repositories\Contracts\AbilityRepository as AbilityContract;

class AbilityRepository extends BaseRepository implements AbilityContract
{
    public function __construct(Ability $ability)
    {
        $this->model = $ability;
    }
}
