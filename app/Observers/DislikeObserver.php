<?php

namespace App\Observers;

use App\Models\Dislike;
use App\Models\User;

class DislikeObserver
{
    public function created(Dislike $dislike)
    {
        $dislike->pivotParent->increment('dislike');
        $dislike->pivotParent->user->increment('count_dislikes_given');
        User::find($dislike->user_id)->increment('count_dislikes_given');
    }

    public function deleted(Dislike $dislike)
    {
        $dislike->pivotParent->decrement('dislike');
        $dislike->pivotParent->user->decrement('count_dislikes_given');
        User::find($dislike->user_id)->decrement('count_dislikes_given');
    }
}
