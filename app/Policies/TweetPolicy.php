<?php

namespace App\Policies;

use App\Models\Tweet;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TweetPolicy
{
    use HandlesAuthorization;

    public function create(User $user)
    {
        return $user->abilities->contains('title','create-tweet');
    }

    public function update(User $user,Tweet $tweet)
    {
        return $tweet->user_id == $user->id || $user->abilities->contains('title','update-tweet');
    }

    public function delete(User $user,Tweet $tweet)
    {
        return $tweet->user_id == $user->id || $user->abilities->contains('title','delete-tweet');
    }

    public function forceDelete(User $user,Tweet $tweet)
    {
        return $tweet->user_id == $user->id || $user->abilities->contains('title','delete-tweet');
    }

    public function like(User $user,Tweet $tweet)
    {
        return $tweet->user_id != $user->id;
    }

    public function dislike(User $user,Tweet $tweet)
    {
        return $tweet->user_id != $user->id;
    }
}
