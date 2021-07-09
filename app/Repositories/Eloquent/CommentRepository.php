<?php

namespace App\Repositories\Eloquent;

use App\Models\Comment;
use App\Repositories\Contracts\CommentRepository as CommentContract;

class CommentRepository extends BaseRepository implements CommentContract
{
    public function __construct(Comment $comment)
    {
        $this->model = $comment;
    }
}
