<?php

namespace App\Services\IndexPage\Pipes;

use App\Models\Slider;
use Closure;

class SliderContentsPipe
{
    public function handle($data,Closure $next)
    {
        $data['slider'] = Slider::with('post')->orderBy('order')->get()->map(function ($slide) {
            return [
                'title'          => $slide->post->title,
                'mainTitle'      => $slide->post->main_title,
                'secondaryTitle' => $slide->post->secondary_title,
                'firstTag'       => !$slide->tags[0] ? NULL : "#{$slide->tags[0]}",
                'firstTagURL'    => !$slide->tags[0] ? NULL : route('tags.show',$slide->tags[0]),
                'secondTag'      => !$slide->tags[1] ? NULL : "#{$slide->tags[1]}",
                'secondTagURL'   => !$slide->tags[1] ? NULL : route('tags.show',$slide->tags[1]),
                'thirdTag'       => !$slide->tags[2] ? NULL : "#{$slide->tags[2]}",
                'thirdTagURL'    => !$slide->tags[2] ? NULL : route('tags.show',$slide->tags[2]),
                'forthTag'       => !$slide->tags[3] ? NULL : "#{$slide->tags[3]}",
                'forthTagURL'    => !$slide->tags[3] ? NULL : route('tags.show',$slide->tags[3]),
                'image'          => $slide->post->image,
                'newsURL'        => route('posts.show',$slide->post->slug),
            ];
        });

        return $next($data);
    }
}
