<?php

namespace App\Http\Controllers\Post;

use App\Events\CreateTag;
use App\Events\DeleteTag;
use App\Events\NewPostNotificationEvent;
use App\Helpers\PostHelper;
use App\Http\Controllers\Controller;
use App\Image;
use App\User;
use App\UserContent;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UserContentController extends Controller
{
    private $postHelper;

    public function __construct (PostHelper $postHelper){
        $this->middleware(['auth'])->only([
            'like',
            'dislike',
            'create',
            'store',
            'edit',
            'update',
            'destroy',
        ]);

        $this->middleware(['CreateUserContent'])->only([
            'create',
            'store'
        ]);

        $this->middleware(['EditUserContent'])->only([
            'edit',
            'update'
        ]);

        $this->middleware(['DeleteUserContent'])->only([
            'destroy'
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
        return view('post.userContent.create');
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

            $userContent = Auth::user()
                               ->userContents()
                               ->create(array_merge([
                                   'context' => $context,
                                   'status'  => 'PENDING',
                               ], request()->except('context')));

            if (request('main_photo')) {

                $original = $this->postHelper->storeBase64Image(
                    request('main_photo'),
                    'public/post/user-content/original'
                );

                $xs = $this->postHelper->storeBase64Image(
                    request('main_photo'),
                    'public/post/user-content/xs',
                    90, 64
                );

                $sm = $this->postHelper->storeBase64Image(
                    request('main_photo'),
                    'public/post/user-content/sm',
                    180, 128
                );

                $md = $this->postHelper->storeBase64Image(
                    request('main_photo'),
                    'public/post/user-content/md',
                    450, 320
                );

                $lg = $this->postHelper->storeBase64Image(
                    request('main_photo'),
                    'public/post/user-content/lg',
                    675, 480
                );

                $xl = $this->postHelper->storeBase64Image(
                    request('main_photo'),
                    'public/post/user-content/lg',
                    900, 640
                );

                Image::create([
                    'imageable_type' => 'App\UserContent',
                    'imageable_id'   => $userContent->id,
                    'xs'             => $xs,
                    'sm'             => $sm,
                    'md'             => $md,
                    'lg'             => $lg,
                    'xl'             => $xl,
                    'original'       => $original,
                ]);

            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('store post fails because of: ' . $e->getMessage() . ' on line: ' . $e->getLine() . 'in file: ' . $e->getFile());
            return abort(500);
        }

        return redirect()->route('user-contents.preview', $userContent->slug);
    }

    /**
     * Preview a newly created resource in storage.
     *
     * @param $slug
     * @return \Illuminate\Http\Response
     */
    public function preview($slug)
    {
        $post = UserContent::with(
            'users:id,name,username',
            'images'
        )
                           ->where('slug', $slug)
                           ->firstOrFail();
        $response['isLiked'] = FALSE;
        $response['isDisliked'] = FALSE;
        $response['id'] = $post->id;
        $response['type'] = 'news';
        $response['author'] = $post->users->name;
        $response['mainTitle'] = $post->main_title;
        $response['secondaryTitle'] = $post->secondary_title;
        $response['concatTitle'] = '<small class="secondary-title">' . $post->secondary_title . '</small>' . '<br>' . $post->main_title;
        $response['mainPhoto'] = isset($post['images'][0]['original']) ? Storage::url($post['images'][0]['original'])
            : '/images/twitter.png';
        $response['context'] = $post->context;
        $response['like'] = $post->like;
        $response['dislike'] = $post->dislike;
        $response['countViews'] = $post->view;
        $response['countComments'] = $post->comment;
        $response['slug'] = $post->slug;
        $response['date'] = Carbon::parse($post->created_at)
                                  ->diffForHumans();
        $response['releaseLink'] = route('user-contents.release');
        $response['draftLink'] = route('user-contents.draft');
        return view('post.preview', compact('response'));
    }

    /**
     * release a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function release()
    {
        $post = UserContent::where('slug', request('slug'))
                           ->where('status', '!=', 'RELEASE')
                           ->firstOrFail();


        $context = $this->postHelper->findSharpInText($post->context);

        try {

            DB::beginTransaction();

            $post->update([
                'status'  => 'RELEASE',
                'context' => $context,
            ]);

            foreach (PostHelper::$tags as $tag) {
                event(new CreateTag($tag, 'App\UserContent', $post->id));
            }

            $auth_user = Auth::user()
                             ->load('images', 'followers.users');
            User::find($auth_user->id)
                ->increment('count_user_contents');
            event(new NewPostNotificationEvent($post->toArray(), 'userContent', $auth_user));

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            $message = "release post fails because of: {$e->getMessage()} on line : {$e->getLine()} in file: {$e->getFile()}";
            Log::error($message);
            return abort(500, $message);
        }

        return redirect()->route('user-contents.show', $post->slug);
    }

    /**
     * Draft a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function draft()
    {
        $post = UserContent::where('slug', request('slug'))
                           ->where('status', '!=', 'RELEASE')
                           ->firstOrFail();
        $post->update([
            'status' => 'DRAFT',
        ]);
        return redirect()->route('user-contents.show', $post->slug);
    }

    /**
     * Display the specified resource.
     *
     * @param  string $slug
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $post = UserContent::getUserContentWithComments($slug);

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

        $response['type'] = 'userContent';
        $response['id'] = $post->id;
        $response['slug'] = $post->slug;
        $response['mainTitle'] = $post->main_title;
        $response['secondaryTitle'] = $post->secondary_title;
        $response['concatTitle'] = '<small class="secondary-title">' . $response['secondaryTitle'] . '</small>' . '<br>' . $response['mainTitle'];
        $response['context'] = $post->context;
        $response['mainPhoto'] = is_null($post->main_photo) ? '/images/twitter.png' : Storage::url($post->main_photo);
        $response['like'] = $post->count_like;
        $response['dislike'] = $post->count_dislike;
        $response['countViews'] = $post->count_view;
        $response['countComments'] = $post->count_comment;
        $response['date'] = Carbon::parse($post->created_at)->diffForHumans();
        $response['authorID'] = $post->author_id;
        $response['author'] = $post->author_name;
        $response['comments'] = $comments;

        //permissions
        $response['accessToEdit'] = FALSE;
        $response['accessToDelete'] = FALSE;
        $response['accessToComposeComment'] = FALSE;
        if (auth()->check()) {

            $userActions = auth()->user()->userActions()->pluck('title')->toArray();

            if (in_array('edit-user-content', $userActions)) {
                $response['accessToEdit'] = TRUE;
            }

            if ($post->author_id != Auth::id() && !in_array('edit-user-content-of-others', $userActions)){
                $response['accessToEdit'] = FALSE;
            }

            if (in_array('delete-user-content', $userActions)) {
                $response['accessToDelete'] = TRUE;
            }

            if ($post->author_id != Auth::id() && !in_array('delete-user-content-of-others', $userActions)){
                $response['accessToDelete'] = FALSE;
            }

            if (in_array('create-comment', $userActions)) {
                $response['accessToComposeComment'] = TRUE;
            }

        }

        UserContent::where('slug',$slug)->increment('view');

        return view('post.userContent.show', compact('response'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $slug
     * @return \Illuminate\Http\Response
     */
    public function edit($slug)
    {
        $userContent = UserContent::with('images')
                                  ->where('slug', $slug)
                                  ->where('status', 'RELEASE')
                                  ->firstOrFail();

        if (auth()->id() != $userContent->user_id) {
            if (auth()->user()->role != 'Manager') {
                if (auth()->user()->role != 'Developer') {
                    if (auth()->user()->role != 'Senior-Editor') {
                        if (auth()->user()->role != 'Chief-Editor') {
                            if (auth()->user()->role != 'Author') {
                                return abort(
                                    401,
                                    'با عرض پوزش از شما، شما دسترسی لازم برای مشاهده ی این صفحه را ندارید.'
                                );
                            }
                        }
                    }
                }
            }
        }

        $response['id'] = $userContent->id;
        $response['author'] = $userContent->users->name;
        $response['slug'] = $userContent->slug;
        $response['mainTitle'] = $userContent->main_title;
        $response['secondaryTitle'] = $userContent->secondary_title;
        $response['mainPhoto'] = isset($userContent['images'][0]['lg']) ? Storage::url($userContent['images'][0]['lg'])
            : '/images/twitter.png';
        $response['context'] = $userContent->context;

        return view('post.userContent.edit', compact('response'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param $slug
     * @return \Illuminate\Http\Response
     */
    public function update($slug)
    {
        $userContent = UserContent::where('slug', $slug)
                                  ->firstOrFail();

        if (auth()->id() != $userContent->user_id) {
            if (auth()->user()->role != 'Manager') {
                if (auth()->user()->role != 'Developer') {
                    if (auth()->user()->role != 'Senior-Editor') {
                        if (auth()->user()->role != 'Chief-Editor') {
                            if (auth()->user()->role != 'Author') {
                                return abort(
                                    401,
                                    'با عرض پوزش از شما، شما دسترسی لازم برای مشاهده ی این صفحه را ندارید.'
                                );
                            }
                        }
                    }
                }
            }
        }


        event(new DeleteTag($userContent->id, 'App\UserContent'));

        $context = $this->postHelper->findSharpInText(request('context'));

        $images = $userContent->images()
                              ->first();

        try {
            DB::beginTransaction();

            UserContent::where('slug', $slug)
                       ->update(array_merge([
                           'context' => $context,
                           'status'  => 'RELEASE',
                       ], request()->except('context', '_token', '_method', 'main_photo')));

            if (request('main_photo')) {

                $original = $this->postHelper->storeBase64Image(
                    request('main_photo'),
                    'public/post/user-content/original'
                );

                $xs = $this->postHelper->storeBase64Image(
                    request('main_photo'),
                    'public/post/user-content/xs',
                    90, 64
                );

                $sm = $this->postHelper->storeBase64Image(
                    request('main_photo'),
                    'public/post/user-content/sm',
                    180, 128
                );

                $md = $this->postHelper->storeBase64Image(
                    request('main_photo'),
                    'public/post/user-content/md',
                    450, 320
                );

                $lg = $this->postHelper->storeBase64Image(
                    request('main_photo'),
                    'public/post/user-content/lg',
                    675, 480
                );

                $xl = $this->postHelper->storeBase64Image(
                    request('main_photo'),
                    'public/post/user-content/lg',
                    900, 640
                );

                Image::updateOrCreate([
                    'imageable_type' => 'App\UserContent',
                    'imageable_id'   => $userContent->id,
                ], [
                    'imageable_type' => 'App\UserContent',
                    'imageable_id'   => $userContent->id,
                    'xs'             => $xs,
                    'sm'             => $sm,
                    'md'             => $md,
                    'lg'             => $lg,
                    'xl'             => $xl,
                    'original'       => $original,
                ]);

            }

            foreach (PostHelper::$tags as $tag) {
                event(new CreateTag($tag, 'App\UserContent', $userContent->id));
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $message = "update post fails because of: {$e->getMessage()} on line : {$e->getLine()} in file: {$e->getFile()}";
            Log::error($message);
            return abort(500, $message);
        }

        if (request('main_photo') && !is_null($images)) {
            Storage::delete($images->original);
            Storage::delete($images->xs);
            Storage::delete($images->sm);
            Storage::delete($images->md);
            Storage::delete($images->lg);
            Storage::delete($images->xl);
        }

        return redirect()->route('user-contents.show', $userContent->slug);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $slug
     * @return void
     */
    public function destroy($slug)
    {
        $userContent = UserContent::where('slug', $slug)
                                  ->firstOrFail();
        if (auth()->id() != $userContent->user_id) {
            if (auth()->user()->role != 'Manager') {
                if (auth()->user()->role != 'Developer') {
                    if (auth()->user()->role != 'Senior-Editor') {
                        if (auth()->user()->role != 'Chief-Editor') {
                            if (auth()->user()->role != 'Author') {
                                return abort(
                                    401,
                                    'با عرض پوزش از شما، شما دسترسی لازم برای مشاهده ی این صفحه را ندارید.'
                                );
                            }
                        }
                    }
                }
            }
        }

        $user = User::find($userContent->user_id);


        try {
            DB::beginTransaction();
            event(new DeleteTag($userContent->id, 'App\UserContent'));
            User::find($userContent->user_id)
                ->update([
                    'count_user_contents'  => (integer)($user->count_user_contents - 1),
                    'count_likes_given'    => (integer)($user->count_likes_given - $userContent->like),
                    'count_dislikes_given' => (integer)($user->count_dislikes_given - $userContent->dislike),
                    'count_comments_given' => (integer)($user->count_comments_given - $userContent->comment),
                ]);
            $userContent->comments()
                        ->delete();
            UserContent::where('slug', $slug)
                       ->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $message = "delete post fails because of: {$e->getMessage()} on line : {$e->getLine()} in file: {$e->getFile()}";
            Log::error($message);
            return abort(500, $message);
        }
        return redirect()->route('users.index');
    }

    /**
     * Like news.
     *
     * @return \Illuminate\Http\Response
     */
    public function like()
    {
        $userContent = UserContent::with('likes', 'dislikes')
                                  ->findOrFail(request('id'));

        if ($userContent->user_id == Auth::id()) {
            $response['status'] = 'Failed';
            $response['like'] = $userContent->like;
            $response['dislike'] = $userContent->dislike;
            return response()->json($response);
        }

        try {
            DB::beginTransaction();

            if (!$userContent->likes->contains(Auth::id())) {
                $userContent->increment('like');
                Auth::user()
                    ->likes()
                    ->create([
                        'likeable_type' => 'App\UserContent',
                        'likeable_id'   => request('id'),
                    ]);
                User::find($userContent->user_id)
                    ->increment('count_likes_given');
            }

            if ($userContent->dislikes->contains(Auth::id())) {
                $userContent->decrement('dislike');
                Auth::user()
                    ->dislikes()
                    ->where('dislikeable_id', request('id'))
                    ->where('dislikeable_type', 'App\UserContent')
                    ->delete();
                User::find($userContent->user_id)
                    ->decrement('count_dislikes_given');
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('like user-content fails because of: ' . $e->getMessage() . ' on line: ' . $e->getLine() . 'in file: ' . $e->getFile());
            return response()->json([
                'message' => 'like user-content fails because of: ' . $e->getMessage() . ' on line: ' . $e->getLine() . 'in file: ' . $e->getFile(),
            ], 500);
        }

        $response['status'] = 'Done';
        $response['like'] = $userContent->like;
        $response['dislike'] = $userContent->dislike;
        return response()->json($response);

    }

    /**
     * Dislike news.
     *
     * @return \Illuminate\Http\Response
     */
    public function dislike()
    {
        $userContent = UserContent::with('likes', 'dislikes')
                                  ->findOrFail(request('id'));

        if ($userContent->user_id == Auth::id()) {
            $response['status'] = 'Failed';
            $response['like'] = $userContent->like;
            $response['dislike'] = $userContent->dislike;
            return response()->json($response);
        }

        try {
            DB::beginTransaction();

            if (!$userContent->dislikes->contains(Auth::id())) {
                $userContent->increment('dislike');
                Auth::user()
                    ->dislikes()
                    ->create([
                        'dislikeable_type' => 'App\UserContent',
                        'dislikeable_id'   => request('id'),
                    ]);
                User::find($userContent->user_id)
                    ->increment('count_dislikes_given');
            }

            if ($userContent->likes->contains(Auth::id())) {
                $userContent->decrement('like');
                Auth::user()
                    ->likes()
                    ->where('likeable_id', request('id'))
                    ->where('likeable_type', 'App\UserContent')
                    ->delete();
                User::find($userContent->user_id)
                    ->decrement('count_likes_given');
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('dislike user-content fails because of: ' . $e->getMessage() . ' on line: ' . $e->getLine() . 'in file: ' . $e->getFile());
            return response()->json([
                'message' => 'dislike user-content fails because of: ' . $e->getMessage() . ' on line: ' . $e->getLine() . 'in file: ' . $e->getFile(),
            ], 500);
        }

        $response['status'] = 'Done';
        $response['like'] = $userContent->like;
        $response['dislike'] = $userContent->dislike;
        return response()->json($response);

    }

    public function instagram(Request $request)
    {
        $userContents = UserContent::getUserContentForComposer(30,$request->offset);

        $response = [];

        foreach ($userContents as $userContent) {

            $separator = $userContent->secondary_title ? '؛' : '';

            $isLiked = FALSE;

            $isDisliked = FALSE;

            if (Auth::check() && in_array(Auth::id(), explode(',', $userContent->users_liked))){
                $isLiked = TRUE;
            }

            if (Auth::check() && in_array(Auth::id(), explode(',', $userContent->users_disliked))){
                $isDisliked = TRUE;
            }

            $response[] = [
                'id'           => $userContent->id,
                'title'        => "{$userContent->main_title} {$separator} {$userContent->secondary_title}",
                'mainPhoto'    => !is_null($userContent->image) ? Storage::url($userContent->image) : "/images/twitter.png",
                'countLike'    => $userContent->like,
                'isLiked'      => $isLiked,
                'countDislike' => $userContent->dislike,
                'isDisliked'   => $isDisliked,
                'url'          => route('user-contents.show', [$userContent->slug]),
                'authorName'   => $userContent->name,
                'authorUrl'    => route('users.show', [
                    'id'   => $userContent->author_id,
                    'name' => $userContent->name,
                ]),
            ];

        }

        return response()->json($response);
    }
}
