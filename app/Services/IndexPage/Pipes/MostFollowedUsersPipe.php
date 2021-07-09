<?php

namespace App\Services\IndexPage\Pipes;

use App\Models\User;
use Closure;

class MostFollowedUsersPipe
{
    public function handle($data,Closure $next)
    {
        $data['mostFollowedUsers'] = User::latest('count_followers')->take(5)->get()->map(function ($user) {
            return [
                'name'            => $user->name,
                'countFollowers'  => $user->count_followers,
                'countFollowings' => $user->count_followings,
                'countPosts'      => $user->count_user_contents + $user->count_news,
                'image'           => $user->image,
                'url'             => route('users.show',$user->username),
            ];
        });

        return $next($data);
    }
}
