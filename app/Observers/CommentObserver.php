<?php

namespace App\Observers;

use App\Models\Comment;

class CommentObserver
{
    public function created(Comment $comment)
    {
        $comment->user->increment('count_comments_taken');

        $comment->commentable->user->increment('count_comments_given');

        $comment->commentable->increment('comment');
    }

    public function deleted(Comment $comment)
    {
        $comment->user->decrement('count_comments_taken');

        $comment->commentable->user->decrement('count_comments_given');

        $comment->commentable->decrement('comment');
    }
}
