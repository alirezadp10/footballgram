<?php

namespace App\Repositories\Eloquent;

use App\Models\Scorers;
use App\Repositories\Contracts\ScorerRepository as ScorerContract;

class ScorerRepository extends BaseRepository implements ScorerContract
{
    public function __construct(Scorers $scorers)
    {
        $this->model = $scorers;
    }
}
