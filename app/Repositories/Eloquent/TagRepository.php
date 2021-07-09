<?php

namespace App\Repositories\Eloquent;

use App\Models\Tag;
use App\Repositories\Contracts\TagRepository as TagContract;
use Illuminate\Support\Carbon;

class TagRepository extends BaseRepository implements TagContract
{
    public function __construct(Tag $tag)
    {
        $this->model = $tag;
    }

    public function relatedNews($tag,$with = [])
    {
        return $this->model->whereName($tag)
                           ->firstOrFail()
                           ->posts()
                           ->news()
                           ->released()
                           ->with($with)
                           ->latest('posts.id')
                           ->customPaginate(9,count($with) ? $this->withRelations() : $this->feed());
    }

    public function relatedUserContents($tag,$with = [])
    {
        return $this->model->whereName($tag)
                           ->firstOrFail()
                           ->posts()
                           ->userContent()
                           ->released()
                           ->with($with)
                           ->latest('posts.id')
                           ->customPaginate(9,count($with) ? $this->withRelations() : $this->feed());
    }

    public function relatedComments($tag,$with = [])
    {
        return $this->model->whereName($tag)
                           ->firstOrFail()
                           ->comments()
                           ->with($with)
                           ->latest('posts.id')
                           ->customPaginate(9,function ($comment) {
                               return [
                                   'id'           => $comment->id,
                                   'countLike'    => $comment->count_like,
                                   'isLiked'      => $comment->likes->contains(auth()->id()),
                                   'countDislike' => $comment->count_dislike,
                                   'isDisliked'   => $comment->dislikes->contains(auth()->id()),
                                   'context'      => nl2br(strip_tags($comment->context,'<a><br>')),
                                   'url'          => route('posts.show',$comment->post->slug),
                                   'image'        => $comment->user->image,
                                   'authorName'   => $comment->user->name,
                                   'authorUrl'    => route('users.show',$comment->user->username),
                               ];
                           });
    }

    public function firstOrFail($tag)
    {
        return $this->model->whereName($tag)->firstOrFail();
    }

    private function withRelations()
    {
        return function ($post) {
            return [
                'id'           => $post->id,
                'type'         => $post->type,
                'title'        => $post->title,
                'image'        => $post->image,
                'countLike'    => $post->count_like,
                'isLiked'      => $post->likes->contains(auth()->id()),
                'countDislike' => $post->count_dislike,
                'isDisliked'   => $post->dislikes->contains(auth()->id()),
                'url'          => route('posts.show',$post->slug),
                'authorName'   => $post->user->name,
                'authorUrl'    => route('users.show',$post->user->username),
            ];
        };
    }

    private function feed()
    {
        return function ($news) {
            return [
                'title' => $news->title,
                'time'  => Carbon::parse($news->created_at)->format('H:i'),
                'url'   => route('posts.show',$news->slug),
            ];
        };
    }
}
