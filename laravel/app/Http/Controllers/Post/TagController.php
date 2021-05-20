<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TagController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')
             ->only([
                 'create',
                 'store',
                 'edit',
                 'update',
             ]);

        $this->middleware('CreatePost')
             ->only([
                 'create',
                 'store',
                 'edit',
                 'update',
             ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return abort(404);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
    }

    /**
     * Display the specified resource.
     *
     * @param $name
     * @return \Illuminate\Http\Response
     */
    public function show($name)
    {
        $count = Tag::where('name', $name)->get(['count'])->first();
        $response['count'] = is_null($count) ? '0' : $count->count;
        $response['name'] = $name;
        return view('post.tag', compact('response'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getNews()
    {
        $posts = Tag::getNewsRelatedToTag(
            request('name'),
            request('news_received')
        );

        $response['news'] = [];

        foreach ($posts as $post) {

            $isLiked = FALSE;
            if (Auth::check() && in_array(Auth::id(), explode(',', $post->users_liked))){
                $isLiked = TRUE;
            }

            $isDisliked = FALSE;
            if (Auth::check() && in_array(Auth::id(), explode(',', $post->users_disliked))){
                $isDisliked = TRUE;
            }

            $separator = $post->secondary_title ? '؛' : '';

            $response['news'][] = [
                'id'           => $post->id,
                'type'         => 'news',
                'title'        => "{$post->main_title} {$separator} {$post->secondary_title}",
                'mainPhoto'    => !is_null($post->image) ? Storage::url($post->image) : "/images/twitter.png",
                'url'          => route('news.show', [$post->slug]),
                'authorName'   => $post->author_name,
                'authorUrl'    => route('users.show', [
                    'id'   => $post->author_id,
                    'name' => $post->author_name,
                ]),
                'countLike'    => $post->count_like,
                'isLiked'      => $isLiked,
                'countDislike' => $post->count_dislike,
                'isDisliked'   => $isDisliked,
            ];
        }

        return response()->json($response);
    }

    public function getUserContents()
    {
        $posts = Tag::getUserContentsRelatedToTag(
            request('name'),
            request('user_contents_received')
        );

        $response['userContents'] = [];

        foreach ($posts as $post) {

            $isLiked = FALSE;
            if (Auth::check() && in_array(Auth::id(), explode(',', $post->users_liked))){
                $isLiked = TRUE;
            }

            $isDisliked = FALSE;
            if (Auth::check() && in_array(Auth::id(), explode(',', $post->users_disliked))){
                $isDisliked = TRUE;
            }

            $separator = $post->secondary_title ? '؛' : '';

            $response['userContents'][] = [
                'id'           => $post->id,
                'type'         => 'user-contents',
                'title'        => "{$post->main_title} {$separator} {$post->secondary_title}",
                'mainPhoto'    => !is_null($post->image) ? Storage::url($post->image) : "/images/twitter.png",
                'url'          => route('user-contents.show', [$post->slug]),
                'authorName'   => $post->author_name,
                'authorUrl'    => route('users.show', [
                    'id'   => $post->author_id,
                    'name' => $post->author_name,
                ]),
                'countLike'    => $post->count_like,
                'isLiked'      => $isLiked,
                'countDislike' => $post->count_dislike,
                'isDisliked'   => $isDisliked,
            ];
        }

        return response()->json($response);
    }

    public function getComments()
    {
        $comments = Tag::getCommentsRelatedToTag(
            request('name'),
            request('comments_received')
        );

        $response['comments'] = [];

        foreach ($comments as $comment) {

            if ($comment->commentable_type == 'App\News') {
                $url = url("/posts/news/{$comment->news_slug}/#comment-{$comment->id}");
            } else {
                $url = url("/posts/user-contents/{$comment->user_content_slug}/#comment-{$comment->id}");
            }

            $isLiked = FALSE;
            if (Auth::check() && in_array(Auth::id(),explode(",",$comment->users_likes_this))) {
                $isLiked = TRUE;
            }

            $isDisliked = FALSE;
            if (Auth::check() && in_array(Auth::id(),explode(",",$comment->users_dislikes_this))) {
                $isDisliked = TRUE;
            }

            $response['comments'][] = [
                'url'        => $url,
                'authorUrl'  => route('users.show',['id' => $comment->author_id , 'name' => $comment->author_name]),
                'author'     => $comment->author_name,
                'context'    => nl2br(strip_tags($comment->context, '<a><br>')),
                'id'         => $comment->id,
                'level'      => $comment->level,
                'like'       => $comment->like,
                'dislike'    => $comment->dislike,
                'image'      => !is_null($comment->user_avatar) ? Storage::url($comment->user_avatar) : '/images/userPhoto.png',
                'isLiked'    => $isLiked,
                'isDisliked' => $isDisliked,
            ];
        }

        return response()->json($response);
    }


}
