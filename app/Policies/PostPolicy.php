<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Str;

class PostPolicy
{
    use HandlesAuthorization;

    public function edit(User $user,Post $post): bool
    {
        return $post->user_id == $user->id || $user->abilities->contains('title',Str::customKebab("edit-{$post->type}"));
    }

    public function delete(User $user,Post $post): bool
    {
        return $post->user_id == $user->id || $user->abilities->contains('title',Str::customKebab("delete-{$post->type}"));
    }

    public function forceDelete(User $user,Post $post): bool
    {
        return $post->user_id == $user->id || $user->abilities->contains('title',Str::customKebab("delete-{$post->type}"));
    }

    public function like(User $user,Post $post): bool
    {
        return $post->user_id != $user->id;
    }

    public function dislike(User $user,Post $post): bool
    {
        return $post->user_id != $user->id;
    }
}
