<?php

namespace App\Services\IndexPage\Pipes;

use App\Models\ChiefChoice;
use Closure;

class ChiefChoicesPipe
{
    public function handle($data, Closure $next)
    {
        $data['chiefChoices'] = ChiefChoice::with('post')->orderBy('order')->get()->map(function ($chiefChoice) {
            return [
                'title' => $chiefChoice->post->title,
                'image' => $chiefChoice->post->image,
                'url'   => route('posts.show', $chiefChoice->post->slug),
            ];
        });

        return $next($data);
    }
}
