<?php

namespace App\Services\IndexPage\Pipes;

use App\Models\Post;
use Closure;
use Illuminate\Support\Carbon;

class LastNewsPipe
{
    public function handle($data,Closure $next)
    {
        $data['lastNews'] = Post::news()
                                ->released()
                                ->latest()
                                ->take(15)
                                ->get()
                                ->map(function ($news) {
                                    return [
                                        'title' => $news->title,
                                        'time'  => Carbon::parse($news->created_at)->format('H:i'),
                                        'url'   => route('posts.show',$news->slug),
                                    ];
                                });

        return $next($data);
    }
}
