<?php

namespace App\Http\Controllers\Post;

use App\Events\CreateTag;
use App\Events\DeleteTag;
use App\Events\NewPostNotificationEvent;
use App\Helpers\PostHelper;
use App\Http\Controllers\Controller;
use App\Image;
use App\News;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class NewsController extends Controller
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
            'destroySlide',
            'storeSlide'
        ]);

        $this->middleware(['CreateNews'])->only([
            'create',
            'store'
        ]);

        $this->middleware(['EditNews'])->only([
            'edit',
            'update'
        ]);

        $this->middleware(['DeleteNews'])->only([
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
        return view('post.news.create');
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

            $news = Auth::user()
                        ->news()
                        ->create(array_merge([
                            'context' => $context,
                            'status'  => 'PENDING',
                        ], request()->except('context')));

            $original = $this->postHelper->storeBase64Image(
                request('main_photo'),
                'public/post/news/original'
            );

            $xs = $this->postHelper->storeBase64Image(
                request('main_photo'),
                'public/post/news/xs',
                90, 64
            );

            $sm = $this->postHelper->storeBase64Image(
                request('main_photo'),
                'public/post/news/sm',
                180, 128
            );

            $md = $this->postHelper->storeBase64Image(
                request('main_photo'),
                'public/post/news/md',
                450, 320
            );

            $lg = $this->postHelper->storeBase64Image(
                request('main_photo'),
                'public/post/news/lg',
                675, 480
            );

            $xl = $this->postHelper->storeBase64Image(
                request('main_photo'),
                'public/post/news/lg',
                900, 640
            );

            Image::create([
                'imageable_type' => 'App\News',
                'imageable_id'   => $news->id,
                'xs'             => $xs,
                'sm'             => $sm,
                'md'             => $md,
                'lg'             => $lg,
                'xl'             => $xl,
                'original'       => $original,
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $message = "store post fails because of: {$e->getMessage()} on line : {$e->getLine()} in file: {$e->getFile()}";
            Log::error($message);
            return abort(500, $message);
        }

        return redirect()->route('news.preview', $news->slug);
    }

    /**
     * Preview a newly created resource in storage.
     *
     * @param $slug
     * @return \Illuminate\Http\Response
     */
    public function preview($slug)
    {
        $post = News::with(
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
        $response['mainPhoto'] = Storage::url($post['images'][0]['original']);
        $response['context'] = $post->context;
        $response['like'] = $post->like;
        $response['dislike'] = $post->dislike;
        $response['countViews'] = $post->view;
        $response['countComments'] = $post->comment;
        $response['slug'] = $post->slug;
        $response['date'] = Carbon::parse($post->created_at)
                                  ->diffForHumans();
        $response['releaseLink'] = route('news.release');
        $response['draftLink'] = route('news.draft');
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
        $news = News::where('slug', request('slug'))
                    ->where('status', '!=', 'RELEASE')
                    ->firstOrFail();

        $context = $this->postHelper->findSharpInText($news->context);

        try {

            DB::beginTransaction();

            $news->update([
                'status'  => 'RELEASE',
                'context' => $context,
            ]);

            foreach (PostHelper::$tags as $tag) {
                event(new CreateTag($tag, 'App\News', $news->id));
            }

            $auth_user = Auth::user()->load('images', 'followers.users');
            User::find($auth_user->id)->increment('count_news');
            event(new NewPostNotificationEvent($news->toArray(), 'news', $auth_user));

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            $message = "release post fails because of: {$e->getMessage()} on line : {$e->getLine()} in file: {$e->getFile()}";
            Log::error($message);
            return abort(500, $message);
        }

        return redirect()->route('news.show', $news->slug);
    }

    /**
     * Draft a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function draft()
    {
        $news = News::where('slug', request('slug'))
                    ->where('status', '!=', 'RELEASE')
                    ->firstOrFail();
        $news->update([
            'status' => 'DRAFT',
        ]);
        return redirect()->route('news.show', $news->slug);
    }

    /**
     * Display the specified resource.
     *
     * @param  string $slug
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $post = News::getNewsWithComments($slug);

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

        $response['type'] = 'news';
        $response['id'] = $post->id;
        $response['slug'] = $post->slug;
        $response['mainTitle'] = $post->main_title;
        $response['secondaryTitle'] = $post->secondary_title;
        $response['concatTitle'] = '<small class="secondary-title">' . $response['secondaryTitle'] . '</small>' . '<br>' . $response['mainTitle'];
        $response['context'] = $post->context;
        $response['mainPhoto'] = Storage::url($post->main_photo);
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
        $response['accessToManageChiefChoice'] = FALSE;
        $response['accessToManageSlidePost'] = FALSE;
        $response['accessToComposeComment'] = FALSE;
        if (auth()->check()) {

            $userActions = auth()->user()->userActions()->pluck('title')->toArray();

            if (in_array('edit-news', $userActions)) {
                $response['accessToEdit'] = TRUE;
            }

            if ($post->author_id != Auth::id() && !in_array('edit-news-of-others', $userActions)){
                $response['accessToEdit'] = FALSE;
            }

            if (in_array('delete-news', $userActions)) {
                $response['accessToDelete'] = TRUE;
            }

            if ($post->author_id != Auth::id() && !in_array('delete-news-of-others', $userActions)){
                $response['accessToDelete'] = FALSE;
            }

            if (in_array('chief-choice', $userActions)) {
                $response['accessToManageChiefChoice'] = TRUE;
            }

            if (in_array('slide-post', $userActions)) {
                $response['accessToManageSlidePost'] = TRUE;
            }

            if (in_array('create-comment', $userActions)) {
                $response['accessToComposeComment'] = TRUE;
            }

        }

        News::where('slug',$slug)->increment('view');

        return view('post.news.show', compact('response'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $slug
     * @return \Illuminate\Http\Response
     */
    public function edit($slug)
    {
        $news = News::with('images')
                    ->where('slug', $slug)
                    ->where('status', 'RELEASE')
                    ->firstOrFail();

        if (auth()->user()->role != 'Manager') {
            if (auth()->user()->role != 'Developer') {
                if (auth()->user()->role != 'Senior-Editor') {
                    if (auth()->user()->role != 'Chief-Editor') {
                        if (auth()->id() != $news->user_id) {
                            return abort(
                                401,
                                'با عرض پوزش از شما، شما دسترسی لازم برای مشاهده ی این صفحه را ندارید.'
                            );
                        }
                    }
                }
            }
        }

        $response['id'] = $news->id;
        $response['slug'] = $news->slug;
        $response['mainTitle'] = $news->main_title;
        $response['secondaryTitle'] = $news->secondary_title;
        $response['mainPhoto'] = Storage::url($news['images'][0]['lg']);
        $response['context'] = $news->context;

        return view('post.news.edit', compact('response'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $slug
     * @return \Illuminate\Http\Response
     */
    public function update($slug)
    {
        $news = News::where('slug', $slug)
                    ->firstOrFail();

        if (auth()->user()->role != 'Manager') {
            if (auth()->user()->role != 'Developer') {
                if (auth()->user()->role != 'Senior-Editor') {
                    if (auth()->user()->role != 'Chief-Editor') {
                        if (auth()->id() != $news->user_id) {
                            return abort(
                                401,
                                'با عرض پوزش از شما، شما دسترسی لازم برای مشاهده ی این صفحه را ندارید.'
                            );
                        }
                    }
                }
            }
        }

        event(new DeleteTag($news->id, 'App\News'));

        $context = $this->postHelper->findSharpInText(request('context'));

        $images = $news->images()
                       ->first();

        try {
            DB::beginTransaction();

            News::where('slug', $slug)
                ->update(array_merge([
                    'context' => $context,
                    'status'  => 'RELEASE',
                ], request()->except('context', '_token', '_method', 'main_photo')));

            if (request('main_photo')) {

                $original = $this->postHelper->storeBase64Image(
                    request('main_photo'),
                    'public/post/news/original'
                );

                $xs = $this->postHelper->storeBase64Image(
                    request('main_photo'),
                    'public/post/news/xs',
                    90, 64
                );

                $sm = $this->postHelper->storeBase64Image(
                    request('main_photo'),
                    'public/post/news/sm',
                    180, 128
                );

                $md = $this->postHelper->storeBase64Image(
                    request('main_photo'),
                    'public/post/news/md',
                    450, 320
                );

                $lg = $this->postHelper->storeBase64Image(
                    request('main_photo'),
                    'public/post/news/lg',
                    675, 480
                );

                $xl = $this->postHelper->storeBase64Image(
                    request('main_photo'),
                    'public/post/news/lg',
                    900, 640
                );

                Image::updateOrCreate([
                    'imageable_type' => 'App\News',
                    'imageable_id'   => $news->id,
                ], [
                    'imageable_type' => 'App\News',
                    'imageable_id'   => $news->id,
                    'xs'             => $xs,
                    'sm'             => $sm,
                    'md'             => $md,
                    'lg'             => $lg,
                    'xl'             => $xl,
                    'original'       => $original,
                ]);

            }

            foreach (PostHelper::$tags as $tag) {
                event(new CreateTag($tag, 'App\News', $news->id));
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

        return redirect()->route('news.show', $news->slug);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $slug
     * @return void
     */
    public function destroy($slug)
    {
        $news = News::where('slug', $slug)
                    ->firstOrFail();

        if (auth()->user()->role != 'Manager') {
            if (auth()->user()->role != 'Developer') {
                if (auth()->user()->role != 'Senior-Editor') {
                    if (auth()->user()->role != 'Chief-Editor') {
                        if (auth()->id() != $news->user_id) {
                            return abort(
                                401,
                                'با عرض پوزش از شما، شما دسترسی لازم برای مشاهده ی این صفحه را ندارید.'
                            );
                        }
                    }
                }
            }
        }

        $user = User::find($news->user_id);

        try {
            DB::beginTransaction();
            event(new DeleteTag($news->id, 'App\News'));
            User::find($news->user_id)
                ->update([
                    'count_news'           => (integer)($user->count_news - 1),
                    'count_likes_given'    => (integer)($user->count_likes_given - $news->like),
                    'count_dislikes_given' => (integer)($user->count_dislikes_given - $news->dislike),
                    'count_comments_given' => (integer)($user->count_comments_given - $news->comment),
                ]);
            $news->comments()
                 ->delete();
            News::where('slug', $slug)
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
        $news = News::with('likes', 'dislikes')
                    ->findOrFail(request('id'));

        if ($news->user_id == Auth::id()) {
            $response['status'] = 'Failed';
            $response['like'] = $news->like;
            $response['dislike'] = $news->dislike;
            return response()->json($response);
        }

        try {
            DB::beginTransaction();

            if (!$news->likes->contains(Auth::id())) {
                $news->increment('like');
                Auth::user()
                    ->likes()
                    ->create([
                        'likeable_type' => 'App\News',
                        'likeable_id'   => request('id'),
                    ]);
                User::find($news->user_id)
                    ->increment('count_likes_given');
            }

            if ($news->dislikes->contains(Auth::id())) {
                $news->decrement('dislike');
                Auth::user()
                    ->dislikes()
                    ->where('dislikeable_id', request('id'))
                    ->where('dislikeable_type', 'App\News')
                    ->delete();
                User::find($news->user_id)
                    ->decrement('count_dislikes_given');
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('like news fails because of: ' . $e->getMessage() . ' on line: ' . $e->getLine() . ' in file: ' . $e->getFile());
            return response()->json([
                'message' => 'like news fails because of: ' . $e->getMessage() . ' on line: ' . $e->getLine() . 'in file: ' . $e->getFile(),
            ], 500);
        }

        $response['status'] = 'Done';
        $response['like'] = $news->like;
        $response['dislike'] = $news->dislike;
        return response()->json($response);

    }

    /**
     * Dislike news.
     *
     * @return \Illuminate\Http\Response
     */
    public function dislike()
    {
        $news = News::with('likes', 'dislikes')
                    ->findOrFail(request('id'));

        if ($news->user_id == Auth::id()) {
            $response['status'] = 'Failed';
            $response['like'] = $news->like;
            $response['dislike'] = $news->dislike;
            return response()->json($response);
        }

        try {
            DB::beginTransaction();

            if (!$news->dislikes->contains(Auth::id())) {
                $news->increment('dislike');
                Auth::user()
                    ->dislikes()
                    ->create([
                        'dislikeable_type' => 'App\News',
                        'dislikeable_id'   => request('id'),
                    ]);
                User::find($news->user_id)
                    ->increment('count_dislikes_given');
            }

            if ($news->likes->contains(Auth::id())) {
                $news->decrement('like');
                Auth::user()
                    ->likes()
                    ->where('likeable_id', request('id'))
                    ->delete();
                Auth::user()
                    ->likes()
                    ->where('likeable_id', request('id'))
                    ->where('likeable_type', 'App\News')
                    ->delete();
                User::find($news->user_id)
                    ->decrement('count_likes_given');
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('dislike news fails because of: ' . $e->getMessage() . ' on line: ' . $e->getLine() . 'in file: ' . $e->getFile());
            return response()->json([
                'message' => 'dislike news fails because of: ' . $e->getMessage() . ' on line: ' . $e->getLine() . 'in file: ' . $e->getFile(),
            ], 500);
        }

        $response['status'] = 'Done';
        $response['like'] = $news->like;
        $response['dislike'] = $news->dislike;
        return response()->json($response);

    }

    public function storeSlide()
    {
        if ((auth()->user()->role != 'Manager' && auth()->user()->role != 'Developer') || auth()->user()->cant('slide-post')) {
            session()->flash('message.type', 'error');
            session()->flash(
                'message.content',
                'با عرض پوزش از شما، شما دسترسی لازم برای مشاهده ی این صفحه را ندارید.'
            );
            session()->flash('message.time', '15');
            return back();
        }

        $news = News::find(request('id'));

        DB::table('slider')
          ->updateOrInsert([
              'news_id' => $news->id,
          ], [
              'main_title'      => $news->main_title,
              'secondary_title' => $news->secondary_title,
              'news_id'         => $news->id,
              'slug'            => $news->slug,
              'first_tag'       => request('first_tag'),
              'second_tag'      => request('second_tag'),
              'third_tag'       => request('third_tag'),
              'forth_tag'       => request('forth_tag'),
              'order'           => request('order'),
              'created_at'      => Carbon::now(),
              'updated_at'      => Carbon::now(),
          ]);

        session()->flash('message.type', 'success');
        session()->flash(
            'message.content',
            'تغییرات با موفقیت انجام شد!'
        );
        session()->flash('message.time', '15');

        return back();
    }

    public function showSlide()
    {
        if ((auth()->user()->role != 'Manager' && auth()->user()->role != 'Developer') || auth()->user()->cant('slide-post')) {
            session()->flash('message.type', 'error');
            session()->flash(
                'message.content',
                'با عرض پوزش از شما، شما دسترسی لازم برای مشاهده ی این صفحه را ندارید.'
            );
            session()->flash('message.time', '15');
            return back();
        }

        $slide = DB::table('slider')
                   ->where([
                       'news_id' => request('id'),
                       'slug'    => request('slug'),
                   ])
                   ->get()
                   ->first();

        $response['firstTag'] = isset($slide->first_tag) ? $slide->first_tag : '';
        $response['secondTag'] = isset($slide->second_tag) ? $slide->second_tag : '';
        $response['thirdTag'] = isset($slide->third_tag) ? $slide->third_tag : '';
        $response['forthTag'] = isset($slide->forth_tag) ? $slide->forth_tag : '';
        $response['order'] = isset($slide->order) ? $slide->order : '';

        return response()->json($response);
    }

    public function destroySlide()
    {
        if ((auth()->user()->role != 'Manager' && auth()->user()->role != 'Developer') || auth()->user()->cant('slide-post')) {
            session()->flash('message.type', 'error');
            session()->flash(
                'message.content',
                'با عرض پوزش از شما، شما دسترسی لازم برای مشاهده ی این صفحه را ندارید.'
            );
            session()->flash('message.time', '15');
            return back();
        }

        DB::table('slider')
          ->where([
              'news_id' => request('id'),
              'slug'    => request('slug'),
          ])
          ->delete();

        session()->flash('message.type', 'success');
        session()->flash(
            'message.content',
            'تغییرات با موفقیت انجام شد!'
        );
        session()->flash('message.time', '15');

        return back();
    }

    public function lastNews(Request $request)
    {
        $lastNews = News::lastNews(15, $request->offset);
        $response = [];
        foreach ($lastNews as $news) {
            $separator = $news->secondaryTitle ? '؛' : '';
            $response[] = [
                'title' => "{$news->mainTitle} {$separator} {$news->secondaryTitle}",
                'time'  => Carbon::parse($news->created_at)
                                 ->format('H:i'),
                'url'   => route('news.show', [$news->slug]),
            ];
        }
        return response()->json($response);
    }

}
