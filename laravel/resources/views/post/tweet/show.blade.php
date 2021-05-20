@extends('layouts.app')
@section('header')
    <title>{{ $response['slug'] }} - فوتبال گرام</title>
    <meta name="slug" content="{{$response['slug']}}" />
    <meta name="id" content="{{$response['id']}}" />
@endsection
@section('styles')
    <link href="{{ mix('css/show-tweet.css') }}" rel="stylesheet">
    @if($response['accessToComposeComment'])
        <meta name="comment" content="true">
    @endif
@endsection
@section('content')
    <div class="container post-container">
        <div class="row mb-3">
            <div class="col-12 col-lg-8_5">
                <div class="post-main-body mt-0">
                    <div class="tweet-author">
                        <a href="{{ $response['authorUrl'] }}">
                            <img src="{{ $response['authorImage'] }}">
                            {{ $response['authorName'] }} نوشته:
                        </a>
                    </div>
                    <div class="tweet-context mt-5">
                        {!! nl2br(strip_tags($response['context'],'<a><br>')) !!}
                    </div>
                    <table class="mt-4 tweet-toolbar w-75 float-left">
                        <tr>
                            <td class="tweet-copy hide-in-xs">
                                <a href="#">
                                    <i class="far fa-clone"></i>
                                </a>
                            </td>
                            <td class="tweet-view">
                                <a href="#">
                                    {{ $response['countViews'] }}&nbsp;
                                    <i class="far fa-eye"></i>
                                </a>
                            </td>
                            <td class="tweet-comment">
                                <a href="#">
                                    {{ $response['countComments'] }}&nbsp;
                                    <i class="far fa-comment"></i>
                                </a>
                            </td>
                            <td class="tweet-dislike">
                                <a href="#">
                                    {{ $response['dislike'] }}&nbsp;
                                    <i class="far fa-thumbs-down"></i>
                                </a>
                            </td>
                            <td class="tweet-like">
                                <a href="#">
                                    {{ $response['like'] }}&nbsp;
                                    <i class="far fa-thumbs-up"></i>
                                </a>
                            </td>
                        </tr>
                    </table>
                    <div class="clearfix"></div>
                </div>
                @guest
                    <div class="alert alert-info" role="alert"
                         style="margin-top: 15px; text-align: right">
                        <strong>برای ارسال نظر خود می توانید در سایت <span><a href="{{ route('register') }}">ثبت نام</a></span>
                                کنید</strong>
                    </div>
                @else
                    @if($response['accessToComposeComment'])
                        <div class="post-compose-comment">
                            <div class="post-compose-comment-container">
                                <form method="post"
                                      action="{{ route('comments.store',['post_id' => $response['id'] , 'post_type' => $response['type'] , 'comment_level' => 1 ]) }}">
                                    @csrf
                                    <textarea name="context" placeholder="نظر خود را بنویسید ..."></textarea>
                                    <button type="submit" class="btn btn-info">ارسال</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-danger" role="alert"
                             style="margin-top: 15px; text-align: right">
                            <strong>.شما امکان ارسال نظر ندارید</strong>
                        </div>
                    @endif
                @endguest
                <div class="post-comments">
                    @foreach($response['comments'] as $comment)
                        <div class="post-comment post-comment-{{ $comment['level'] }}"
                             id="comment-{{ $comment['id'] }}"
                             data-id="{{ $comment['id'] }}"
                             data-status="expand"
                             data-level="{{ $comment['level'] }}">
                            <div class="post-comment-author">
                                <a href="{{ route('users.show',['id' => $comment['writer_id'] , 'name' => $comment['writer_name']] ) }}">
                                    {{ $comment['writer_name'] }}
                                </a>
                                نوشته :
                            </div>
                            <div class="post-comment-body">
                                {!! nl2br(strip_tags($comment['context'],'<a><br>')) !!}
                            </div>
                            <img class="post-comment-user-avatar lazyload" data-src="{{ $comment['writer_photo'] }}">
                            <div class="post-comment-like-parent">
                                <span class="post-comment-like-wrapper">
                                    <button class="post-comment-like-button @if($comment['isLiked']) post-comment-liked @endif">
                                        <i class="far fa-thumbs-up"></i>
                                    </button>
                                    <span class="post-comment-like-count @if($comment['isLiked']) post-comment-liked @endif">
                                        {{ $comment['like'] }}
                                    </span>
                                </span>
                                <span class="post-comment-dislike-wrapper">
                                    <button class="post-comment-dislike-button @if($comment['isDisliked']) post-comment-disliked @endif">
                                        <i class="far fa-thumbs-down"></i>
                                    </button>
                                    <span class="post-comment-dislike-count @if($comment['isDisliked']) post-comment-disliked @endif">
                                        {{ $comment['dislike'] }}
                                    </span>
                                </span>
                                <div class="post-comment-report" data-toggle="tooltip" data-placement="top"
                                     title="گزارش" aria-hidden="true"><i class="fas fa-ban"></i></div>
                                @auth
                                    <div class="post-comment-reply" data-toggle="tooltip" data-placement="top"
                                         title="پاسخ" aria-hidden="true"><i class="fas fa-reply"></i></div>
                                @endauth
                                <div class="post-comment-toggle" data-toggle="tooltip" data-placement="top"
                                     @guest style="left: 200px" @endguest
                                     data-original-title="بستن" aria-hidden="true"><i class="fas fa-angle-down"></i>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="col-12 col-lg-3_5">
                @include('sections.post-side-block')
            </div>
        </div>
    </div>
    @include('sections.instagram-post')
    <div id="post-details"
         data-post-id="{{ $response['id'] }}"
         data-post-type="{{ $response['type'] }}"
    ></div>
@endsection
@section('scripts')
    <script src="{{ mix('js/show-tweet.js') }}"></script>
@endsection
