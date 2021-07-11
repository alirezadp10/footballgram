<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePostRequest;
use App\Models\Tweet;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TweetController extends Controller
{
    public function create()
    {
        return view('post.create');
    }

    public function store(CreatePostRequest $request)
    {
        $tweet = auth()->user()->tweet()->create($request->validated());

        return redirect()->route('tweets.show', $tweet->slug);
    }

    public function show($slug)
    {
        $post = Tweet::getTweetWithComments($slug);

        if (is_null($post->id)) {
            abort(404);
        }

        $response['isLiked'] = false;
        if (Auth::check() && in_array(Auth::id(), explode(',', $post->users_liked_post))) {
            $response['isLiked'] = true;
        }

        $response['isDisliked'] = false;
        if (Auth::check() && in_array(Auth::id(), explode(',', $post->users_disliked_post))) {
            $response['isDisliked'] = true;
        }

        $post_comments = $post->comments ? json_decode($post->comments, true) : [];

        $comments = $this->postHelper->sequentialComments(collect($post_comments)
            ->sortByDesc('id')
            ->sortByDesc('like')
            ->sortByDesc('sub')
            ->toArray());

        foreach ($comments as $key => $value) {
            $comments[$key]['context'] = pack('H*', $value['context']);

            $comments[$key]['writer_photo'] = $value['writer_photo'] != '' ? Storage::url($value['writer_photo'])
                : '/images/userPhoto.png';

            $comments[$key]['isLiked'] = false;
            if (Auth::check() && in_array(Auth::id(), explode(',', $value['users_liked_comment']))) {
                $comments[$key]['isLiked'] = true;
            }

            $comments[$key]['isDisliked'] = false;
            if (Auth::check() && in_array(Auth::id(), explode(',', $value['users_disliked_comment']))) {
                $comments[$key]['isDisliked'] = true;
            }
        }

        $response['type'] = 'tweet';
        $response['id'] = $post->id;
        $response['slug'] = $post->slug;
        $response['context'] = $post->context;
        $response['like'] = $post->count_like;
        $response['dislike'] = $post->count_dislike;
        $response['countViews'] = $post->count_view;
        $response['countComments'] = $post->count_comment;
        $response['date'] = Carbon::parse($post->created_at)->diffForHumans();
        $response['authorID'] = $post->author_id;
        $response['authorName'] = $post->author_name;
        $response['authorImage'] = is_null($post->author_image) ? '/images/userPhoto.png'
            : Storage::url($post->author_image);
        $response['authorUrl'] = route('users.show', [
            'id'   => $post->author_id,
            'name' => $post->author_name,
        ]);
        $response['comments'] = $comments;

        //permissions
        $response['accessToEdit'] = false;
        $response['accessToDelete'] = false;
        $response['accessToManageChiefChoice'] = false;
        $response['accessToManageSlidePost'] = false;
        $response['accessToComposeComment'] = false;
        if (!auth()->check()) {
            Tweet::where('slug', $slug)->increment('view');

            return view('post.show', compact('response'));
        }

        $userActions = auth()->user()->userActions()->pluck('title')->toArray();

        if (in_array('edit-tweet', $userActions)) {
            $response['accessToEdit'] = true;
        }

        if ($post->author_id != Auth::id() && !in_array('edit-tweet-of-others', $userActions)) {
            $response['accessToEdit'] = false;
        }

        if (in_array('delete-tweet', $userActions)) {
            $response['accessToDelete'] = true;
        }

        if ($post->author_id != Auth::id() && !in_array('delete-tweet-of-others', $userActions)) {
            $response['accessToDelete'] = false;
        }

        if (in_array('create-comment', $userActions)) {
            $response['accessToComposeComment'] = true;
        }

        Tweet::where('slug', $slug)->increment('view');

        return view('post.show', compact('response'));
    }
}
