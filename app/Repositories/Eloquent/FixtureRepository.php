<?php

namespace App\Repositories\Eloquent;

use App\Models\Fixture;
use App\Repositories\Contracts\FixtureRepository as FixtureContract;

class FixtureRepository extends BaseRepository implements FixtureContract
{
    public function __construct(Fixture $fixture)
    {
        $this->model = $fixture;
    }
}
