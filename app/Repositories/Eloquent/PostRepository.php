<?php

namespace App\Repositories\Eloquent;

use App\Models\Post;
use App\Repositories\Contracts\PostRepository as PostContract;
use Carbon\Carbon;

class PostRepository extends BaseRepository implements PostContract
{
    public function __construct(Post $post)
    {
        $this->model = $post;
    }

    public function pending($slug)
    {
        return $this->model->whereSlug($slug)->whereStatus('PENDING')->firstOrFail();
    }

    public function dontReleased($slug)
    {
        return $this->model->whereSlug($slug)->where('status', '!=', 'RELEASE')->firstOrFail();
    }

    public function released($slug, $with = [])
    {
        return $this->model->with($with)->whereSlug($slug)->released()->firstOrFail();
    }

    public function firstOrFail($slug)
    {
        return Post::whereSlug($slug)->firstOrFail();
    }

    public function lastNews()
    {
        return $this->model->news()->released()->latest()->customPaginate(15, function ($news) {
            return [
                'title' => $news->title,
                'time'  => Carbon::parse($news->created_at)->format('H:i'),
                'url'   => route('posts.show', $news->slug),
            ];
        });
    }

    public function chiefChoice()
    {
    }
}
