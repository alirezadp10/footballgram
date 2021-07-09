<?php

namespace App\Repositories\Eloquent;

use App\Models\Tweet;
use App\Repositories\Contracts\TweetRepository as TweetContract;

class TweetRepository extends BaseRepository implements TweetContract
{
    public function __construct(Tweet $tweet)
    {
        $this->model = $tweet;
    }
}
