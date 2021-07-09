<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentPolicy
{
    use HandlesAuthorization;

    public function __construct()
    {
        //
    }

    public function create(User $user)
    {
        return $user->abilities->contains('title','create-comment');
    }

    public function like(User $user,Comment $comment): bool
    {
        return $comment->user_id != $user->id;
    }

    public function dislike(User $user,Comment $comment): bool
    {
        return $comment->user_id != $user->id;
    }

}
