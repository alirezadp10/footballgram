<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Contracts\UserRepository as UserContract;
use Closure;

class UserRepository extends BaseRepository implements UserContract
{
    public function __construct(User $user)
    {
        $this->model = $user;
    }

    public function firstOrFail($username)
    {
        return $this->model->whereUsername($username)->firstOrFail();
    }

    public function postsTimeline($user)
    {
        return $user->postTimeline()->with([
            'user',
            'likes',
            'dislikes',
        ])->customPaginate(9, function ($post) {
            return [
                'id'           => $post->id,
                'title'        => $post->title,
                'image'        => $post->image,
                'countLike'    => $post->count_like,
                'isLiked'      => $post->likes->contains(auth()->user()),
                'countDislike' => $post->count_dislike,
                'isDisliked'   => $post->dislikes->contains(auth()->user()),
                'url'          => route('posts.show', $post->slug),
                'user'         => [
                    'name' => $post->user->name,
                    'url'  => route('users.show', $post->user->username),
                ],
            ];
        });
    }

    public function news($username)
    {
        return User::whereUsername($username)
                   ->firstOrFail()
                   ->posts()
                   ->news()
                   ->released()
                   ->latest()
                   ->customPaginate(9, function ($post) {
                       return [
                           'title' => $post->title,
                           'image' => $post->image,
                           'url'   => route('posts.show', $post->slug),
                       ];
                   });
    }

    public function userContents($username)
    {
        return User::whereUsername($username)
                   ->firstOrFail()
                   ->posts()
                   ->userContent()
                   ->released()
                   ->latest()
                   ->customPaginate(9, function ($post) {
                       return [
                           'title' => $post->title,
                           'image' => $post->image,
                           'url'   => route('posts.show', $post->slug),
                       ];
                   });
    }

    public function comments($username)
    {
        return User::whereUsername($username)->firstOrFail()->comments()->with([
            'commentable',
            'likes',
            'dislikes',
        ])->latest()->customPaginate(9, function ($comment) {
            return [
                'context'    => nl2br(strip_tags($comment->context, '<a><br>')),
                'id'         => $comment->id,
                'url'        => url("/posts/{$comment->commentable->slug}/#comment-{$comment->id}"),
                'like'       => $comment->like,
                'dislike'    => $comment->dislike,
                'isLiked'    => $comment->likes->contains(auth()->user()),
                'isDisliked' => $comment->dislikes->contains(auth()->user()),
            ];
        });
    }

    public function followers($username)
    {
        return $this->firstOrFail($username)->followers()->customPaginate(10, $this->followList(), 'users');
    }

    public function followings($username)
    {
        return $this->firstOrFail($username)->followings()->customPaginate(10, $this->followList(), 'users');
    }

    private function followList(): Closure
    {
        return function ($user) {
            return [
                'id'              => $user->id,
                'name'            => $user->name,
                'countFollowers'  => $user->count_followers,
                'countFollowings' => $user->count_followings,
                'countPosts'      => $user->count_news + $user->count_user_contents,
                'followersURL'    => route('users.followers', $user->username),
                'followingsURL'   => route('users.followings', $user->username),
                'isFollow'        => $user->followings->contains('id', auth()->id()),
                'isMe'            => $user->id == auth()->id(),
                'username'        => $user->username,
                'url'             => route('users.show', $user->username),
                'avatar'          => $user->image,
            ];
        };
    }
}
