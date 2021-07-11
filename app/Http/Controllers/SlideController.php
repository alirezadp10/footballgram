<?php

namespace App\Http\Controllers;

use App\Http\Requests\SlideRequest;
use App\Models\Slider;
use Facades\App\Repositories\Contracts\PostRepository;

class SlideController extends Controller
{
    public function store(SlideRequest $request)
    {
        $news = PostRepository::firstOrFail($request->slug);

        $news->slider()->create([
            'first_tag'  => request('first_tag'),
            'second_tag' => request('second_tag'),
            'third_tag'  => request('third_tag'),
            'forth_tag'  => request('forth_tag'),
            'order'      => request('order'),
        ]);

        session()->flash('message.type', 'success');
        session()->flash('message.content', 'تغییرات با موفقیت انجام شد!');
        session()->flash('message.time', '15');

        return back();
    }

    public function show(Slider $slider)
    {
        $this->authorize('manage-slide-post');

        return response()->json($slider);
    }

    public function destroy(Slider $slider)
    {
        $this->authorize('manage-slide-post');

        $slider->delete();

        session()->flash('message.type', 'success');
        session()->flash('message.content', 'تغییرات با موفقیت انجام شد!');
        session()->flash('message.time', '15');

        return back();
    }
}
