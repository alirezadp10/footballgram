@extends('layouts.app')
@section('header')
    <title>{{ $response['pageTitle'] }}</title>
@endsection
@section('styles')
    <link href="{{ mix('css/user-page.css') }}" rel="stylesheet">
@endsection
@section('content')
    <div class="container custom-container">
        <div class="row justify-content-lg-center">
            <div class="col-12 col-lg-12 p-0">
                <div class="user-page-main-container">
                    <div class="row">
                        <div class="col-4">
                            <div class="user-page-avatar">
                                <img data-src="{{ $response['avatar'] }}"
                                     class="lazyload">
                            </div>
                        </div>
                        <div class="col-8">
                            <div class="user-page-top">
                                <div class="user-page-username-parent">
                                    <div class="name-wrapper">
                                        <div class="user-page-username">
                                            {{ $response['name'] }}
                                        </div>
                                        <small class="text-muted"
                                               style="direction: ltr; display: inline-block;">{{ $response['username'] ? '@' . $response['username'] : ''}}</small>
                                    </div>
                                    @if($response['isHomePage'])
                                        <a href="{{ route('users.configuration') }}">
                                            <button class="btn btn-warning">{{ __('Edit Profile') }}</button>
                                        </a>
                                    @else
                                        @if($response['isFollowing'])
                                            <button id="follow-btn"
                                                    class="btn btn-danger"
                                                    data-user-id="{{$response['id']}}">{{ __('Unfollow') }}
                                            </button>
                                        @else
                                            <button id="follow-btn"
                                                    class="btn btn-primary"
                                                    data-user-id="{{$response['id']}}">{{ __('Follow') }}
                                            </button>
                                        @endif
                                    @endif
                                </div>
                                <div class="user-page-statistic hide-in-xs hide-in-sm">
                                    <span data-to-farsi
                                          class="user-page-post-count">{{ $response['countPosts'] }} {{ __('Post') }}
                                    </span>
                                    <a href="{{ $response['followersURL'] }}">
                                        <span data-to-farsi
                                              class="user-page-followers">{{ $response['countFollowers'] }}
                                            {{ __('Follower') }}
                                        </span>
                                    </a>
                                    <a href="{{ $response['followingsURL'] }}">
                                        <span data-to-farsi
                                              class="user-page-following">{{ $response['countFollowings'] }}
                                            {{ __('Following') }}
                                        </span>
                                    </a>
                                </div>
                                <div class="user-page-description hide-in-xs hide-in-sm">{{ $response['bio'] }}</div>
                            </div>
                        </div>
                        <div class="col-12 mb-2 mt-4">
                            <div class="hide-in-md hide-in-lg hide-in-xl user-page-description-sm">
                                {{ $response['bio'] }}
                            </div>
                        </div>
                        <div class="col-12 p-0 mt-3">
                            <table class="hide-in-md hide-in-lg hide-in-xl user-page-statistic-sm">
                                <tr>
                                    <td>
                                        <span data-to-farsi>
                                            {{ $response['countPosts'] }}
                                            <br>
                                            {{ __('Post') }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ $response['followersURL'] }}">
                                            <span data-to-farsi>
                                                {{ $response['countFollowers'] }}
                                                <br>
                                                {{ __('Follower') }}
                                            </span>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ $response['followingsURL'] }}">
                                            <span data-to-farsi>
                                                {{ $response['countFollowings'] }}
                                                <br>
                                                {{ __('Following') }}
                                            </span>
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 10px">
                        <div class="nav nav-tabs nav-stacked instagram-nav">
                            @if($response['isHomePage'])
                                <div class="nav-link time-line pr-0 pl-0"
                                     data-toggle="tab"
                                     href="#nav-time-line"
                                     role="tab"
                                     aria-controls="#nav-time-line"
                                     aria-selected="false">خانه
                                </div>
                            @endif
                            @if($response['haveNews'])
                                <div class="nav-link news pr-0 pl-0"
                                     data-toggle="tab"
                                     href="#nav-news"
                                     role="tab"
                                     aria-controls="#nav-news"
                                     aria-selected="false">اخبار
                                </div>
                            @endif
                            <div class="nav-link user-contents pr-0 pl-0"
                                 data-toggle="tab"
                                 href="#nav-user-contents"
                                 role="tab"
                                 aria-controls="#nav-user-contents"
                                 aria-selected="false">مطالب کاربری
                            </div>
                            <div class="nav-link comments pr-0 pl-0"
                                 data-toggle="tab"
                                 href="#nav-comments"
                                 role="tab"
                                 aria-controls="#nav-comments"
                                 aria-selected="false">نظرات
                            </div>
                        </div>
                        <div class="tab-content instagram-tab-content">
                            @if($response['isHomePage'])
                                <div class="tab-pane fade"
                                     id="nav-time-line"
                                     role="tabpanel"
                                     aria-labelledby="nav-time-line-tab">
                                    <div class="row post-wrapper"
                                         id="time-line-wrapper">
                                        <div class="col-12">
                                            <div class="card"
                                                 style="width: 100%; text-align: center; direction: rtl; padding: 50px 0;">
                                                <div class="card-body">
                                                    <div class="alert alert-light" role="alert">
                                                        <strong>هیچ پستی یافت نشد ! </strong>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 post-instagram-footer-wrapper more-time-line"
                                             style="padding: 0 10px;">
                                            <div class="post-instagram-footer">
                                                <i class="fas fa-angle-double-down"></i>&nbsp;&nbsp;
                                                                                        مطالب بیشتر
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if($response['haveNews'])
                                <div class="tab-pane fade"
                                     id="nav-news"
                                     role="tabpanel"
                                     aria-labelledby="nav-news-tab">
                                    <div class="row post-wrapper"
                                         id="news-wrapper">
                                        <div class="col-12">
                                            <div class="card"
                                                 style="width: 100%; text-align: center; direction: rtl; padding: 50px 0;">
                                                <div class="card-body">
                                                    <div class="alert alert-light" role="alert">
                                                        <strong>هیچ پستی یافت نشد ! </strong>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 post-instagram-footer-wrapper more-news"
                                             style="padding: 0 10px;">
                                            <div class="post-instagram-footer">
                                                <i class="fas fa-angle-double-down"></i>&nbsp;&nbsp;
                                                                                        مطالب بیشتر
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="tab-pane fade"
                                 id="nav-user-contents"
                                 role="tabpanel"
                                 aria-labelledby="nav-user-contents-tab">
                                <div class="row post-wrapper"
                                     id="user-contents-wrapper">
                                    <div class="col-12">
                                        <div class="card"
                                             style="width: 100%; text-align: center; direction: rtl; padding: 50px 0;">
                                            <div class="card-body">
                                                <div class="alert alert-light" role="alert">
                                                    <strong>هیچ پستی یافت نشد ! </strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 post-instagram-footer-wrapper more-user-contents"
                                         style="padding: 0 10px;">
                                        <div class="post-instagram-footer">
                                            <i class="fas fa-angle-double-down"></i>&nbsp;&nbsp;
                                                                                    مطالب بیشتر
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade"
                                 id="nav-comments"
                                 role="tabpanel"
                                 aria-labelledby="nav-comments-tab">
                                <div class="row post-wrapper"
                                     id="comments-wrapper">
                                    <div class="col-12">
                                        <div class="card"
                                             style="width: 100%; text-align: center; direction: rtl; padding: 50px 0;">
                                            <div class="card-body">
                                                <div class="alert alert-light" role="alert">
                                                    <strong>هیچ نظری یافت نشد ! </strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 post-instagram-footer-wrapper more-comments"
                                         style="padding: 0 10px;">
                                        <div class="post-instagram-footer">
                                            <i class="fas fa-angle-double-down"></i>&nbsp;&nbsp;
                                                                                    مطالب بیشتر
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        window.LARAVEL_USER_ID = "{{ $response['id'] }}";
    </script>
    <script src="{{ mix('js/user-page.js') }}"></script>
@endsection
