<?php

namespace App\Repositories\Eloquent;

use App\Models\Like;
use App\Repositories\Contracts\LikeRepository as LikeContract;

class LikeRepository extends BaseRepository implements LikeContract
{
    public function __construct(Like $like)
    {
        $this->model = $like;
    }
}
