<?php

namespace App\Repositories\Eloquent;

use App\Models\Dislike;
use App\Repositories\Contracts\DislikeRepository as DislikeContract;

class DislikeRepository extends BaseRepository implements DislikeContract
{
    public function __construct(Dislike $dislike)
    {
        $this->model = $dislike;
    }
}
