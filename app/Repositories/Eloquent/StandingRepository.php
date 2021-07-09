<?php

namespace App\Repositories\Eloquent;

use App\Models\Standing;
use App\Repositories\Contracts\StandingRepository as StandingContract;

class StandingRepository extends BaseRepository implements StandingContract
{
    public function __construct(Standing $standing)
    {
        $this->model = $standing;
    }
}
