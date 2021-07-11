<?php

namespace App\Http\Controllers;

use Facades\App\Repositories\Contracts\TagRepository;

class TagController extends Controller
{
    public function show($tag)
    {
        $response = TagRepository::firstOrFail($tag);

        return view('post.tag', $response);
    }

    public function news()
    {
        $response = TagRepository::relatedNews(request('name'), [
            'user',
            'likes',
            'dislikes',
        ]);

        return response()->json($response);
    }

    public function userContents()
    {
        $response = TagRepository::relatedUserContents(request('name'));

        return response()->json($response);
    }

    public function comments()
    {
        $response = TagRepository::relatedComments(request('name'));

        return response()->json($response);
    }
}
