<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Notifications\FollowingNotification;
use Facades\App\Repositories\Contracts\UserRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function home(): View
    {
        $user = auth()->user();

        return view('user.index', compact('user'));
    }

    public function show($username): View
    {
        $user = UserRepository::firstOrFail($username);

        $isFollowing = $user->followers->contains(auth()->user());

        return view('user.index', compact('user', 'isFollowing'));
    }

    public function update(UpdateUserRequest $request, $username): JsonResponse
    {
        User::whereUsername($username)->update($request->validated());

        return response()->json([
            'status' => 'done',
        ]);
    }

    public function follow($username): JsonResponse
    {
        $user = UserRepository::firstOrFail($username);

        $auth = auth()->user();

        $type = $auth->followings->contains('id', $user->id) ? 'unfollow' : 'follow';

        $auth->$type($user);

        $user->notify(new FollowingNotification($auth, $type));

        return response()->json(compact('type'));
    }

    public function configuration(): View
    {
        $user = auth()->user();

        return view('user.configuration', compact('user'));
    }

    public function postsTimeline(): JsonResponse
    {
        $response = UserRepository::postsTimeline(auth()->user());

        return response()->json($response);
    }

    public function news($username): JsonResponse
    {
        $response = UserRepository::news($username);

        return response()->json($response);
    }

    public function userContents($username): JsonResponse
    {
        $response = UserRepository::userContents($username);

        return response()->json($response);
    }

    public function comments($username): JsonResponse
    {
        $response = UserRepository::comments($username);

        return response()->json($response);
    }

    public function followers($username): View
    {
        $response = UserRepository::followers($username);

        $response['title'] = 'دنبال کننده ها';

        return view('user.following', compact('response'));
    }

    public function followings($username): View
    {
        $response = UserRepository::followings($username);

        $response['title'] = 'دنبال شونده ها';

        return view('user.following', compact('response'));
    }

    public function notifications(): JsonResponse
    {
        auth()->user()->unreadNotifications->markAsRead();

        $response = auth()->user()->notifications->map(fn ($notification) => $notification->data);

        return response()->json($response);
    }
}
