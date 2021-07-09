<?php

namespace App\Repositories\Eloquent;

use App\Models\Follow;
use App\Repositories\Contracts\FollowRepository as FollowContract;

class FollowRepository extends BaseRepository implements FollowContract
{
    public function __construct(Follow $follow)
    {
        $this->model = $follow;
    }
}
