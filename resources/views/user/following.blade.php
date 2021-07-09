@extends('layouts.app')
@section('header')
    <title>{{ $response['title'] }}</title>
@endsection
@section('styles')
    <link href="{{ mix('css/following.css') }}" rel="stylesheet">
@endsection
@section('content')
    <div class="container custom-container">
        <div class="row justify-content-lg-center">
            <div class="col-lg-8 wrapper">
                @foreach($response->items()['users'] as $user)
                    <div class="card mb-2">
                        <div class="card-body text-right">
                            <a href="{{ $user['url'] }}"
                               class="card-link">
                                <div class="avatar-border">
                                    <img data-src="{{ $user['avatar'] }}"
                                         class="img-fluid lazyload">
                                </div>
                                <span class="mr-4 name">
                                    <small class="text-muted">{{ $user['username'] }}&nbsp;</small>
                                    <h4>{{ $user['name'] }}&nbsp;</h4>
                                </span>
                            </a>
                            <span class="mr-5 hide-in-xs hide-in-sm">
                                <a href="{{ $user['followersURL'] }}">
                                    <small data-to-farsi
                                           class="text-muted">
                                        {{ $user['countFollowers'] }} {{ __('Follower') }}
                                    </small>
                                </a>
                            </span>
                            <span class="mr-2 hide-in-xs hide-in-sm">
                                <a href="{{ $user['followingsURL'] }}">
                                    <small data-to-farsi class="text-muted">
                                        {{ $user['countFollowings'] }} {{ __('Following') }}
                                    </small>
                                </a>
                            </span>
                            @if(!$user['isMe'])
                                <button class="btn float-left follow-btn align-middle @if($user['isFollow']) btn-danger @else btn-primary @endif"
                                        data-user-id="{{ $user['id'] }}">
                                    @if($user['isFollow'])
                                        {{ __('Unfollow') }}
                                    @else
                                        {{ __('Follow') }}
                                    @endif
                                </button>
                            @else
                                <br>
                            @endif
                            <div class="p-0">
                                <table class="hide-in-md hide-in-lg hide-in-xl statistic-sm">
                                    <tr>
                                        <td>
                                            <span data-to-farsi>
                                                {{ $user['countPosts'] }}
                                                <br>
                                                {{ __('Post') }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ $user['followersURL'] }}">
                                                <span data-to-farsi>
                                                    {{ $user['countFollowers'] }}
                                                    <br>
                                                    {{ __('Follower') }}
                                                </span>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ $user['followingsURL'] }}">
                                                <span data-to-farsi>
                                                    {{ $user['countFollowings'] }}
                                                    <br>
                                                    {{ __('Following') }}
                                                </span>
                                            </a>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ mix('js/following.js') }}"></script>
@endsection
