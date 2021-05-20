<?php

namespace App\Http\Controllers\Post;

use App\Helpers\PostHelper;
use App\Http\Controllers\Controller;
use App\Tweet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use function strlen;

class TweetController extends Controller
{
    private $postHelper;

    public function __construct(PostHelper $postHelper)
    {
        $this->middleware(['auth'])->only([
            'like',
            'dislike',
            'create',
            'store',
            'edit',
            'update',
            'destroy',
        ]);

        $this->middleware('throttle:20,1')
             ->only([
                 'like',
                 'dislike',
             ]);

        $this->postHelper = $postHelper;

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
        return view('post.tweet.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $context = $this->postHelper->findSharpInText(request('context'));

        try {
            DB::beginTransaction();
            $tweet = Auth::user()
                        ->tweet()
                        ->create(array_merge([
                            'context' => $context,
                            'status'  => 'RELEASE',
                        ], request()->except('context')));
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $message = "store post fails because of: {$e->getMessage()} on line : {$e->getLine()} in file: {$e->getFile()}";
            Log::error($message);
            return abort(500, $message);
        }

        return redirect()->route('tweets.show', $tweet->slug);
    }

    /**
     * Display the specified resource.
     *
     * @param $slug
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $post = Tweet::getTweetWithComments($slug);

        if (is_null($post->id)){
            abort(404);
        }

        $response['isLiked'] = FALSE;
        if (Auth::check() && in_array(Auth::id(), explode(',', $post->users_liked_post))){
            $response['isLiked'] = TRUE;
        }

        $response['isDisliked'] = FALSE;
        if (Auth::check() && in_array(Auth::id(), explode(',', $post->users_disliked_post))){
            $response['isDisliked'] = TRUE;
        }

        $post_comments = $post->comments ? json_decode($post->comments, TRUE) : [];

        $comments = $this->postHelper->sequentialComments(
            collect($post_comments)
                ->sortByDesc('id')
                ->sortByDesc('like')
                ->sortByDesc('sub')
                ->toArray()
        );

        foreach ($comments as $key => $value) {
            $comments[$key]['context']= pack("H*",$value['context']);

            $comments[$key]['writer_photo'] = $value['writer_photo'] != "" ? Storage::url($value['writer_photo']) : '/images/userPhoto.png';

            $comments[$key]['isLiked'] = FALSE;
            if (Auth::check() && in_array(Auth::id(), explode(',', $value['users_liked_comment']))){
                $comments[$key]['isLiked'] = TRUE;
            }

            $comments[$key]['isDisliked'] = FALSE;
            if (Auth::check() && in_array(Auth::id(), explode(',', $value['users_disliked_comment']))){
                $comments[$key]['isDisliked'] = TRUE;
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
        $response['authorImage'] = is_null($post->author_image) ? '/images/userPhoto.png' : Storage::url($post->author_image);
        $response['authorUrl'] = route('users.show',['id' => $post->author_id , 'name' => $post->author_name] );
        $response['comments'] = $comments;

        //permissions
        $response['accessToEdit'] = FALSE;
        $response['accessToDelete'] = FALSE;
        $response['accessToManageChiefChoice'] = FALSE;
        $response['accessToManageSlidePost'] = FALSE;
        $response['accessToComposeComment'] = FALSE;
        if (auth()->check()) {

            $userActions = auth()->user()->userActions()->pluck('title')->toArray();

            if (in_array('edit-tweet', $userActions)) {
                $response['accessToEdit'] = TRUE;
            }

            if ($post->author_id != Auth::id() && !in_array('edit-tweet-of-others', $userActions)){
                $response['accessToEdit'] = FALSE;
            }

            if (in_array('delete-tweet', $userActions)) {
                $response['accessToDelete'] = TRUE;
            }

            if ($post->author_id != Auth::id() && !in_array('delete-tweet-of-others', $userActions)){
                $response['accessToDelete'] = FALSE;
            }

            if (in_array('create-comment', $userActions)) {
                $response['accessToComposeComment'] = TRUE;
            }

        }

        Tweet::where('slug',$slug)->increment('view');

        return view('post.tweet.show', compact('response'));

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

    /**
     * Like news.
     *
     * @return \Illuminate\Http\Response
     */
    public function like()
    {
        //
    }

    /**
     * Dislike news.
     *
     * @return \Illuminate\Http\Response
     */
    public function dislike()
    {
        //
    }

}
