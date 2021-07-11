<?php

namespace App\Services\IndexPage\Pipes;

use App\Models\Post;
use Closure;
use Illuminate\Support\Carbon;

class HotNewsPipe
{
    public function handle($data, Closure $next)
    {
        $data['hotNews'] = Post::released()
                               ->news()
                               ->where('created_at', '>=', Carbon::now()->subDays(3))
                               ->latest('view')
                               ->take(15)
                               ->get()
                               ->map(function ($news) {
                                   return [
                                       'title' => $news->title,
                                       'time'  => Carbon::parse($news->created_at)->format('H:i'),
                                       'url'   => route('posts.show', $news->slug),
                                   ];
                               });

        return $next($data);
    }
}
