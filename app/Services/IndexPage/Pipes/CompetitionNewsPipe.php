<?php

namespace App\Services\IndexPage\Pipes;

use App\Models\Tag;
use Closure;
use Illuminate\Support\Carbon;

class CompetitionNewsPipe
{
    public function handle($data, Closure $next)
    {
        $tag = Tag::whereName('خلیج_فارس')->firstOrFail();

        $data['competitionNews'] = $tag->posts()->latest('posts.created_at')->take(15)->get()->map(function ($news) {
            return [
                'title' => $news->title,
                'time'  => Carbon::parse($news->created_at)->format('H:i'),
                'url'   => route('posts.show', $news->slug),
            ];
        });

        return $next($data);
    }
}
