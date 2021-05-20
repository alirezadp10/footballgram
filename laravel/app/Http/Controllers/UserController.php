<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Events\FollowingBroadcast;
use App\Helpers\MainHelper;
use App\Image;
use App\News;
use App\Notifications\FollowingNotification;
use App\TimeLine;
use App\User;
use App\UserContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    private $mainHelper;

    public function __construct(MainHelper $mainHelper)
    {
        $this->middleware('auth')
             ->only([
                 'index',
                 'edit',
                 'update',
                 'configuration',
                 'updatePassword',
                 'follow',
                 'allNotifications',
                 'getNotifications',
             ]);

        $this->mainHelper = $mainHelper;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

        $response['pageTitle'] = 'صفحه ی من - فوتبال گرام';
        $response['isHomePage'] = TRUE;
        $response['id'] = $user->id;
        $response['name'] = $user->name;
        $response['username'] = $user->username;
        $response['mobile'] = $user->mobile;
        $response['bio'] = $user->bio;
        $images = $user->images()->first();
        $response['avatar'] = !is_null($images) ? Storage::url($images->md) : '/images/userPhoto.png';
        $response['countPosts'] = $user->count_news + $user->count_user_contents;
        $response['countFollowers'] = $user->count_followers;
        $response['countFollowings'] = $user->count_followings;
        $response['followersURL'] = route('users.get-followers', $user->name);
        $response['followingsURL'] = route('users.get-followings', $user->name);

        $response['haveNews'] = TRUE;
        if (auth()->user()->role != 'Manager') {
            if (auth()->user()->role != 'Developer') {
                if (auth()->user()->role != 'Senior-Editor') {
                    if (auth()->user()->role != 'Chief-Editor') {
                        $response['haveNews'] = FALSE;
                    }
                }
            }
        }

        return view('user.user', compact('response'));
    }

    /**
     * Show the form for creating a new resource.
     *
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @param $name
     * @return \Illuminate\Http\Response
     */
    public function show($id, $name)
    {
        $user = User::where('name', $name)
                    ->where('id', $id)
                    ->firstOrFail();

        if ($user->id == Auth::id()) {
            return redirect()->route('users.index');
        }

        $response['pageTitle'] = "{$user->name} - فوتبال گرام ";
        $response['isHomePage'] = FALSE;
        $response['id'] = $user->id;
        $response['name'] = $user->name;
        $response['username'] = $user->username;
        $response['mobile'] = $user->mobile;
        $response['bio'] = $user->bio;
        $images = $user->images()
                       ->first();
        $response['avatar'] = !is_null($images) ? Storage::url($images->md) : '/images/userPhoto.png';
        $response['countPosts'] = $user->count_news + $user->count_user_contents;
        $response['countFollowers'] = $user->count_followers;
        $response['countFollowings'] = $user->count_followings;
        $response['followersURL'] = route('users.get-followers', $user->name);
        $response['followingsURL'] = route('users.get-followings', $user->name);
        $response['isFollowing'] = DB::table('followings')
                                     ->where('followings.follower_id', Auth::id())
                                     ->where('followings.follow_up_id', $user->id)
                                     ->count() ? TRUE : FALSE;


        $response['haveNews'] = TRUE;

        if ($user->role != 'Manager') {
            if ($user->role != 'Developer') {
                if ($user->role != 'Senior-Editor') {
                    if ($user->role != 'Chief-Editor') {
                        $response['haveNews'] = FALSE;
                    }
                }
            }
        }


        return view('user.user', compact('response'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
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
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     */
    public function destroy($id)
    {
        //
    }

    public function configuration()
    {
        $user = Auth::user();

        $response['name'] = $user->name;
        $response['username'] = $user->username;
        $response['mobile'] = $user->mobile;
        $response['email'] = $user->email;
        $response['bio'] = $user->bio;

        $images = $user->images()
                       ->first();

        $response['avatar'] = !is_null($images) ? Storage::url($images->md) : '/images/userPhoto.png';

        return view('user.configuration', compact('response'));
    }

    public function updatePassword()
    {
        $this->validate(request(), [
            'current_password' => 'required',
            'new_password'     => 'required|confirmed|min:4',
        ], [
            'current_password.required' => 'وارد کردن رمز عبور فعلی الزامی است !',
            'new_password.required'     => 'وارد کردن رمز عبور کنونی الزامی است !',
            'new_password.confirmed'    => 'لطفا در وارد کردن تکرار رمز عبور دقت فرمایید !',
            'new_password.min'          => 'رمز عبور حداقل باید ۴ حرف باشد !',
        ]);

        $user = Auth::user();

        if (!Hash::check(request('current_password'), $user->password)) {
            return response()->json([
                'status'  => 'failed',
                'message' => 'رمز عبور فعلی صحیح نیست !',
            ]);
        }

        $user->update([
            'password' => bcrypt(request('new_password')),
        ]);

        return response()->json([
            'status' => 'done',
        ]);

    }

    public function updateProfile()
    {
        $this->validate(request(), [
            'name'     => 'required',
            'username' => 'sometimes|nullable|min:4|regex:/^[A-Za-z-_0-9]+$/',
            'email'    => 'sometimes|nullable|email',
            'avatar'   => 'sometimes|nullable|base64image',
        ], [
            'name.required'  => 'وارد کردن نام الزامی است !',
            'username.min'   => 'نام کاربری حداقل باید ۴ حرف باشد !',
            'username.regex' => "نام کاربری تنها باید شامل عدد و حروف انگلیسی باشد !",
            'email.email'    => 'آدرس ایمیل معتبر نمی باشد !',
            'avatar.mimes'   => 'فرمت تصویر اشتباه است !',
            'avatar.image'   => 'فرمت تصویر اشتباه است !',
        ]);

        $user = Auth::user();

        if (request('username')) {
            $userFind = User::where('username', request('username'))
                            ->first();
            if (!is_null($userFind)) {
                if ($user->id != $userFind->id) {
                    return response()->json([
                        'status'  => 'failed',
                        'message' => 'این نام کاربری از قبل توسط فرد دیگری انتخاب شده است.',
                    ]);
                }
            }
        }

        $images = $user->images()
                       ->first();

        try {
            DB::beginTransaction();

            if (request('avatar')) {

                $original = $this->mainHelper->storeBase64Image(
                    request('avatar'),
                    'public/user/original'
                );

                $xs = $this->mainHelper->storeBase64Image(
                    request('avatar'),
                    'public/user/xs',
                    50, 50
                );

                $sm = $this->mainHelper->storeBase64Image(
                    request('avatar'),
                    'public/user/sm',
                    100, 100
                );

                $md = $this->mainHelper->storeBase64Image(
                    request('avatar'),
                    'public/user/md',
                    200, 200
                );

                $lg = $this->mainHelper->storeBase64Image(
                    request('avatar'),
                    'public/user/lg',
                    300, 300
                );

                Image::updateOrCreate([
                    'imageable_type' => 'App\User',
                    'imageable_id'   => $user->id,
                ], [
                    'imageable_type' => 'App\User',
                    'imageable_id'   => $user->id,
                    'xs'             => $xs,
                    'sm'             => $sm,
                    'md'             => $md,
                    'lg'             => $lg,
                    'original'       => $original,
                ]);

            }

            $user->update([
                'name'     => request('name'),
                'username' => request('username'),
                'email'    => request('email'),
                'bio'      => request('bio'),
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            debug($e);
            Log::error('update profile fails because of: ' . $e->getMessage() . ' on line: ' . $e->getLine() . ' in file: ' . $e->getFile());
            return response()->json([
                'message' => 'update profile fails',
            ], 500);
        }

        if (request('avatar') && !is_null($images)) {
            Storage::delete($images->original);
            Storage::delete($images->xs);
            Storage::delete($images->sm);
            Storage::delete($images->md);
            Storage::delete($images->lg);
        }

        return response()->json([
            'status' => 'done',
        ]);
    }

    public function follow()
    {
        $auth_user = Auth::user();

        $user = User::find(request('id'));

        $following = DB::table('followings')
                       ->where('followings.follower_id', $auth_user->id)
                       ->where('followings.follow_up_id', request('id'));

        try {
            DB::beginTransaction();
            if ($following->count()) {
                $following->delete();
                $auth_user->decrement('count_followings');
                $user->decrement('count_followers');
                $response['followingStatus'] = 'unFollowed';
                $type = 'unfollow';
                $context = $auth_user->name . ' دیگر شما را دنبال نمی کند.';
            } else {
                $auth_user->following()
                          ->create([
                              'follow_up_id' => request('id'),
                          ]);
                $auth_user->increment('count_followings');
                $user->increment('count_followers');
                $response['followingStatus'] = 'followed';
                $type = 'follow';
                $context = $auth_user->name . ' شما را دنبال می کند.';
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $message = "update post fails because of: {$e->getMessage()} on line : {$e->getLine()} in file: {$e->getFile()}";
            Log::error($message);
            return abort(500, $message);
        }


        $images = $auth_user->images()
                            ->first();

        $user->notify(new FollowingNotification(
            $type,
            $auth_user->id,
            route('users.show', ['id'   => $auth_user->id,
                                 'name' => $auth_user->name,
            ]),
            !is_null($images) ? Storage::url($images->sm) : '/images/userPhoto.png',
            $context
        ));


        return response()->json($response);
    }

    public function getTimeLine()
    {
        $posts = TimeLine::getForUser(Auth::id(), 9, request('time_line_received'));

        $response['timeLine'] = [];

        foreach ($posts as $post) {

            if ($post->type == "") {
                continue;
            }

            $separator = $post->secondary_title ? '؛' : '';

            $isLiked = FALSE;
            $isDisliked = FALSE;
            if ($post->type == 'App\News') {
                if (in_array(Auth::id(), explode(',', $post->users_liked_news))) {
                    $isLiked = TRUE;
                }
                if (in_array(Auth::id(), explode(',', $post->users_disliked_news))) {
                    $isDisliked = TRUE;
                }
            } else {
                if ($post->type == 'App\UserContent') {
                    if (in_array(Auth::id(), explode(',', $post->users_liked_user_content))) {
                        $isLiked = TRUE;
                    }
                    if (in_array(Auth::id(), explode(',', $post->users_disliked_user_content))) {
                        $isDisliked = TRUE;
                    }
                }
            }

            $response['timeLine'][] = [
                'id'           => $post->id,
                'type'         => $post->type == 'App\News' ? 'news' : 'user-contents',
                'title'        => "{$post->main_title} {$separator} {$post->secondary_title}",
                'mainPhoto'    => $post->image != "" ? Storage::url($post->image) : "/images/twitter.png",
                'authorName'   => $post->author_name,
                'authorUrl'    => route('users.show', [
                    'id'   => $post->author_id,
                    'name' => $post->author_name,
                ]),
                'countLike'    => $post->count_like,
                'isLiked'      => $isLiked,
                'countDislike' => $post->count_dislike,
                'isDisliked'   => $isDisliked,
                'url'          => $post->type == 'App\News' ? route('news.show', [$post->slug])
                    : route('user-contents.show', [$post->slug]),
            ];
        }

        return response()->json($response);
    }

    public function getNews()
    {
        $posts = News::with('images')
                     ->where('user_id', request('user_id'))
                     ->where('status', 'release')
                     ->orderBy('id', 'desc')
                     ->offset(request('news_received'))
                     ->take(9)
                     ->get([
                         'id',
                         'main_title',
                         'secondary_title',
                         "slug",
                     ]);

        $response['news'] = [];

        foreach ($posts as $post) {

            $images = $post['images'];
            $separator = $post->secondary_title ? '؛' : '';
            $response['news'][] = [
                'title'     => "{$post->main_title} {$separator} {$post->secondary_title}",
                'mainPhoto' => !is_null($images) ? Storage::url($images[0]['md']) : "/images/twitter.png",
                'url'       => route('news.show', [$post->slug]),
            ];
        }

        return response()->json($response);
    }

    public function getUserContents()
    {
        $userContents = UserContent::with('images')
                                   ->where('user_id', request('user_id'))
                                   ->where('status', 'release')
                                   ->orderBy('id', 'desc')
                                   ->offset(request('user_contents_received'))
                                   ->take(9)
                                   ->get([
                                       'id',
                                       'main_title',
                                       'secondary_title',
                                       "slug",
                                   ]);

        $response['userContents'] = [];

        foreach ($userContents as $userContent) {

            $images = $userContent['images'];
            $separator = $userContent->secondary_title ? '؛' : '';

            $response['userContents'][] = [
                'title'     => "{$userContent->main_title} {$separator} {$userContent->secondary_title}",
                'mainPhoto' => count($images) ? Storage::url($images[0]['md']) : "/images/twitter.png",
                'url'       => route('user-contents.show', [$userContent->slug]),
            ];
        }

        return response()->json($response);
    }

    public function getComments()
    {
        $comments = Comment::where('user_id', request('user_id'))
                           ->orderBy('id', 'desc')
                           ->offset(request('comments_received'))
                           ->take(9)
                           ->get([
                               'id',
                               'like',
                               'level',
                               'dislike',
                               'commentable_type',
                               'commentable_id',
                               'context',
                           ]);

        $response['comments'] = [];

        foreach ($comments as $comment) {

            if ($comment->commentable_type == 'App\News') {
                $news = News::find($comment->commentable_id);
                $url = url("/posts/news/{$news->slug}/#comment-{$comment->id}");
            } else {
                $userContent = UserContent::find($comment->commentable_id);
                $url = url("/posts/user-contents/{$userContent->slug}/#comment-{$comment->id}");
            }

            $isLiked = FALSE;
            if ($comment->likes->contains(Auth::id())) {
                $isLiked = TRUE;
            }
            $isDisliked = FALSE;
            if ($comment->dislikes->contains(Auth::id())) {
                $isDisliked = TRUE;
            }

            $response['comments'][] = [
                'context'    => nl2br(strip_tags($comment->context, '<a><br>')),
                'id'         => $comment->id,
                'level'      => $comment->level,
                'url'        => $url,
                'like'       => $comment->like,
                'dislike'    => $comment->dislike,
                'isLiked'    => $isLiked,
                'isDisliked' => $isDisliked,
            ];
        }

        return response()->json($response);
    }

    public function getFollowers($name)
    {
        $followers = User::getFollowers($name);

        $response['title'] = 'دنبال کننده ها';

        $response['items'] = [];

        foreach ($followers as $follower) {

            $isMe = FALSE;

            if ($follower->id == Auth::id()) {
                $isMe = TRUE;
            }

            $isFollow = FALSE;

            if (in_array(Auth::id(), explode(",", $follower->followersOfFollower))) {
                $isFollow = TRUE;
            }

            $response['items'][] = [
                'id'              => $follower->id,
                'name'            => $follower->name,
                'countFollowers'  => $follower->count_followers,
                'countFollowings' => $follower->count_followings,
                'countPosts'      => $follower->count_news + $follower->count_user_contents,
                'followersURL'    => route('users.get-followers', $follower->name),
                'followingsURL'   => route('users.get-followings', $follower->name),
                'isFollow'        => $isFollow,
                'isMe'            => $isMe,
                'username'        => $follower->username ? "@{$follower->username}" : "",
                'url'             => route('users.show', [
                    'id'   => $follower->id,
                    'name' => $follower->name,
                ]),
                'avatar'          => !is_null($follower->sm) ? Storage::url($follower->sm) : '/images/userPhoto.png',
            ];
        }

        return view('user.following', compact('response'));
    }

    public function getFollowings($name)
    {
        $followings = User::getFollowings($name);

        $response['title'] = 'دنبال شونده ها';

        foreach ($followings as $following) {

            $isMe = FALSE;

            if ($following->id == Auth::id()) {
                $isMe = TRUE;
            }

            $isFollow = FALSE;

            if (in_array(Auth::id(), explode(",", $following->followersOfFollowing))) {
                $isFollow = TRUE;
            }

            $response['items'][] = [
                'id'              => $following->id,
                'name'            => $following->name,
                'countFollowers'  => $following->count_followers,
                'countFollowings' => $following->count_followings,
                'countPosts'      => $following->count_news + $following->count_user_contents,
                'followersURL'    => route('users.get-followers', $following->name),
                'followingsURL'   => route('users.get-followings', $following->name),
                'isFollow'        => $isFollow,
                'isMe'            => $isMe,
                'username'        => $following->username ? "@{$following->username}" : "",
                'url'             => route('users.show', [
                    'id'   => $following->id,
                    'name' => $following->name,
                ]),
                'avatar'          => !is_null($following->sm) ? Storage::url($following->sm) : '/images/userPhoto.png',
            ];
        }

        return view('user.following', compact('response'));
    }

    public function getNotifications()
    {
        Auth::user()->unreadNotifications->markAsRead();

        $notifications = User::getNotifications(request('offset'), request('take'));

        $response = [];

        foreach ($notifications as $notification) {
            $data = json_decode($notification->notifications_data, TRUE);
            if ($data['meta_type'] == 'follow' || $data['meta_type'] == 'unfollow') {
                $response[] = [
                    'url'     => route('users.show', [
                        'id'   => $notification->following_id,
                        'name' => $notification->following_name,
                    ]),
                    'avatar'  => !is_null($notification->following_avatar)
                        ? Storage::url($notification->following_avatar) : '/images/userPhoto.png',
                    'title'   => $data['title'],
                    'context' => $data['context'],
                ];
            }
            if ($data['meta_type'] == 'news') {
                if (!is_null($notification->news_slug)) {
                    $response[] = [
                        'url'     => route('news.show', $notification->news_slug),
                        'avatar'  => !is_null($notification->news_user_avatar)
                            ? Storage::url($notification->news_user_avatar) : '/images/userPhoto.png',
                        'title'   => $data['title'],
                        'context' => $data['context'],
                    ];
                }
            }
            if ($data['meta_type'] == 'userContent') {
                if (!is_null($notification->user_content_slug)) {
                    $response[] = [
                        'url'     => route('user-contents.show', $notification->user_content_slug),
                        'avatar'  => !is_null($notification->user_content_user_avatar)
                            ? Storage::url($notification->user_content_user_avatar) : '/images/userPhoto.png',
                        'title'   => $data['title'],
                        'context' => $data['context'],
                    ];
                }
            }
            if ($data['meta_type'] == 'newsReply') {
                if (!is_null($notification->comment_news_slug)) {
                    $response[] = [
                        'url'     => route('news.show', $notification->comment_news_slug),
                        'avatar'  => !is_null($notification->comment_news_user_avatar)
                            ? Storage::url($notification->comment_news_user_avatar) : '/images/userPhoto.png',
                        'title'   => $data['title'],
                        'context' => $data['context'],
                    ];
                }
            }
            if ($data['meta_type'] == 'userContentReply') {
                if (!is_null($notification->comment_user_content_slug)) {
                    $response[] = [
                        'url'     => route('user-contents.show', $notification->comment_user_content_slug),
                        'avatar'  => !is_null($notification->comment_user_content_user_avatar)
                            ? Storage::url($notification->comment_user_content_user_avatar) : '/images/userPhoto.png',
                        'title'   => $data['title'],
                        'context' => $data['context'],
                    ];
                }
            }
        }

        return response()->json($response);
    }

    public function allNotifications()
    {
        return view('user.notifications');
    }
}
