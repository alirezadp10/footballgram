<?php

namespace App\Observers;

use App\Models\Like;
use App\Models\User;

class LikeObserver
{
    public function created(Like $like)
    {
        $like->pivotParent->increment('like');
        $like->pivotParent->user->increment('count_likes_given');
        User::find($like->user_id)->increment('count_likes_given');
    }

    public function deleted(Like $like)
    {
        $like->pivotParent->decrement('like');
        $like->pivotParent->user->decrement('count_likes_given');
        User::find($like->user_id)->decrement('count_likes_given');
    }
}
