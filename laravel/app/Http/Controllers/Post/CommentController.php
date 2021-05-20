<?php

namespace App\Http\Controllers\Post;

use App\Comment;
use App\Events\CreateTag;
use App\Events\ReplyNotificationEvent;
use App\Helpers\PostHelper;
use App\Http\Controllers\Controller;
use App\News;
use App\User;
use App\UserContent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CommentController extends Controller
{
    private $postHelper;

    public function __construct(PostHelper $postHelper)
    {
        $this->middleware('auth')->only([
             'store',
             'update',
             'like',
             'dislike',
         ]);

        $this->middleware('throttle:20,1')->only([
            'like','dislike'
        ]);

        $this->middleware('CreateComment')->only([
             'store',
             'update',
         ]);

        $this->postHelper = $postHelper;
    }

    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store()
    {
        $context = $this->postHelper->findSharpInText(request('context'));

        $context = $this->postHelper->findMentionInText($context);

        try{

            DB::beginTransaction();

            $auth_user = Auth::user();

            $comment = $auth_user->comment()->create([
                'context'          => $context,
                'level'            => request('comment_level'),
                'parent'           => request('parent_id') ?: NULL,
                'commentable_id'   => $this->postHelper->farsiToDigit(request('post_id')),
                'commentable_type' => request('post_type') == 'news' ? "App\News" : "App\UserContent",
            ]);

            $auth_user->increment('count_comments_taken');

            if (request('post_type') == 'news'){
                $news = News::with('users')->where('id',request('post_id'))->get()->first();
                $news->users->increment('count_comments_given');
                $news->increment('comment');
                if (request('parent_id')) {
                    event(new ReplyNotificationEvent($comment,'news',$auth_user));
                }
            }
            else {
                $userContent = UserContent::with('users')->where('id',request('post_id'))->get()->first();
                $userContent->users->increment('count_comments_given');
                $userContent->increment('comment');
                if (request('parent_id')) {
                    event(new ReplyNotificationEvent($comment,'userContent',$auth_user));
                }
            }

            DB::commit();

        }catch (\Exception $e){
            DB::rollBack();
            Log::error('store comment fails because of: ' . $e->getMessage() . ' on line: ' . $e->getLine() . 'in file: ' . $e->getFile());
        }

        foreach (PostHelper::$tags as $tag){
            event(new CreateTag($tag, 'App\Comment',$comment->id));
        }

        if (request('post_type') == 'news') {
            $url = url("/posts/news/{$news->slug}/#comment-{$comment->id}");
        } else {
            $url = url("/posts/user-contents/{$userContent->slug}/#comment-{$comment->id}");
        }

        return redirect($url);
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update($id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }

    public function like()
    {
        $comment = Comment::with('likes','dislikes')->findOrFail(request('id'));

        if ($comment->user_id == Auth::id()){
            $response['status'] = 'Failed';
            $response['like'] = $comment->like;
            $response['dislike'] = $comment->dislike;
            return response()->json($response);
        }

        try {
            DB::beginTransaction();

            if ( ! $comment->likes->contains(Auth::id())){
                $comment->increment('like');
                Auth::user()->likes()->create([
                    'likeable_type' => 'App\Comment',
                    'likeable_id'   => request('id'),
                ]);
                User::find($comment->user_id)->increment('count_likes_given');
            }

            if ($comment->dislikes->contains(Auth::id())){
                $comment->decrement('dislike');
                Auth::user()
                    ->dislikes()
                    ->where('dislikeable_id', request('id'))
                    ->where('dislikeable_type', 'App\Comment')
                    ->delete();
                User::find($comment->user_id)->decrement('count_dislikes_given');
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('like comment fails because of: ' . $e->getMessage() . ' on line: ' . $e->getLine() . 'in file: ' . $e->getFile());
            return response()->json([
                'message' => 'like comment fails because of: ' . $e->getMessage() . ' on line: ' . $e->getLine() . 'in file: ' . $e->getFile(),
            ],500);
        }

        $response['status'] = 'Done';
        $response['like'] = $comment->like;
        $response['dislike'] = $comment->dislike;
        return response()->json($response);

    }

    public function dislike()
    {
        $comment = Comment::with('likes','dislikes')->findOrFail(request('id'));

        if ($comment->user_id == Auth::id()){
            $response['status'] = 'Failed';
            $response['like'] = $comment->like;
            $response['dislike'] = $comment->dislike;
            return response()->json($response);
        }

        try {
            DB::beginTransaction();

            if ( ! $comment->dislikes->contains(Auth::id())){
                $comment->increment('dislike');
                Auth::user()->dislikes()->create([
                    'dislikeable_type' => 'App\Comment',
                    'dislikeable_id'   => request('id'),
                ]);
                User::find($comment->user_id)->increment('count_dislikes_given');
            }

            if ($comment->likes->contains(Auth::id())){
                $comment->decrement('like');
                Auth::user()
                    ->likes()
                    ->where('likeable_id', request('id'))
                    ->where('likeable_type', 'App\Comment')
                    ->delete();
                User::find($comment->user_id)->decrement('count_likes_given');
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('dislike comment fails because of: ' . $e->getMessage() . ' on line: ' . $e->getLine() . 'in file: ' . $e->getFile());
            return response()->json([
                'message' => 'dislike comment fails because of: ' . $e->getMessage() . ' on line: ' . $e->getLine() . 'in file: ' . $e->getFile(),
            ],500);
        }

        $response['status'] = 'Done';
        $response['like'] = $comment->like;
        $response['dislike'] = $comment->dislike;
        return response()->json($response);

    }

}
