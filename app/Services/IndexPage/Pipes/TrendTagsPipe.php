<?php

namespace App\Services\IndexPage\Pipes;

use App\Models\Tag;
use Closure;
use Illuminate\Support\Carbon;

class TrendTagsPipe
{
    public function handle($data, Closure $next)
    {
        $data['trends'] = Tag::select('name')
                             ->selectRaw('COUNT(taggables.id) as count')
                             ->join('taggables', 'taggables.tag_id', 'tags.id')
                             ->where('taggables.created_at', '>', Carbon::now()->subDay())
                             ->latest('count')
                             ->take(10)
                             ->groupBy('name')
                             ->get()
                             ->map(function ($trend) {
                                 return [
                                     'name'  => "#{$trend->name}",
                                     'count' => $trend->count,
                                     'url'   => route('tags.show', [$trend->name]),
                                 ];
                             });

        return $next($data);
    }
}
