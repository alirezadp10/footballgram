@extends('layouts.app')
@section('header')
    <title>{{ $response['mainTitle'] }} - فوتبال گرام</title>
    <meta name="slug" content="{{$response['slug']}}" />
    <meta name="id" content="{{$response['id']}}" />
@endsection
@section('styles')
    <link href="{{ mix('css/show-user-content.css') }}" rel="stylesheet">
    @if($response['accessToComposeComment'])
        <meta name="comment" content="true">
    @endif
@endsection
@section('content')
    <div class="post-main-header">
        <div class="post-main-title">
            {!! $response['concatTitle'] !!}
        </div>
        @auth
            @if($response['accessToEdit'] || $response['accessToDelete'])
                <i class="post-action fas fa-ellipsis-v"
                   id="post-action"
                   data-toggle="dropdown"
                   aria-haspopup="true"
                   aria-expanded="false">
                </i>
            @endif
            <div class="dropdown-menu post-action-dropdown-menu"
                 aria-labelledby="post-action">
                @if($response['accessToEdit'])
                    <a class="dropdown-item"
                       href="{{ route('user-contents.edit',$response['slug']) }}">ویرایش</a>
                @endif
                @if($response['accessToDelete'])
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item"
                       id="delete-post-btn">حذف</a>
                @endif
            </div>
            @if($response['accessToDelete'])
                <form hidden
                      id="delete-post"
                      method="post"
                      action="{{ route('user-contents.destroy',$response['slug']) }}">
                    @csrf
                    @method('delete')
                </form>
            @endif
        @endauth
    </div>
    <div class="container post-container">
        <div class="row" style="direction: rtl;">
            <div class="col-12 col-lg-8_5">
                <img src="{{ $response['mainPhoto'] }}" class="img-fluid post-main-img">
            </div>
            <div class="col-12 col-lg-3_5">
                <div class="card post-details">
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tbody>
                            <tr>
                                <td>
                                    {{--<span class="hide-in-xs"><i class="fas fa-pencil-alt"></i></span>--}}
                                    <span>نویسنده :</span>
                                </td>
                                <td>{{ $response['author'] }}</td>
                            </tr>
                            <tr>
                                <td>
                                    {{--<span class="hide-in-xs"><i class="far fa-clock"></i></span>--}}
                                    <span>زمان :</span>
                                </td>
                                <td data-to-farsi>{{ $response['date'] }}</td>
                            </tr>
                            <tr>
                                <td>
                                    {{--<span class="hide-in-xs"><i class="far fa-comment"></i></span>--}}
                                    <span>تعداد نظرات :</span>
                                </td>
                                <td data-to-farsi>{{ $response['countComments'] }} نظر</td>
                            </tr>
                            <tr>
                                <td>
                                    {{--<span class="hide-in-xs"><i class="far fa-eye"></i></span>--}}
                                    <span>تعداد بازدید :</span>
                                </td>
                                <td data-to-farsi>{{ $response['countViews'] }} بازدید</td>
                            </tr>
                            <tr>
                                <td>
                                    {{--<span class="hide-in-xs"><i class="fas fa-tags"></i></span>--}}
                                    <span>شماره مطلب :</span>
                                </td>
                                <td data-to-farsi>شماره ی {{ $response['id'] }}</td>
                            </tr>
                            <tr class="post-likes-content">
                                <td style="width: 50%">
                                    <span class="post-dislike-wrapper">
                                        <button class="post-dislike-button @if($response['isDisliked']) post-disliked @endif">
                                            <i class="far fa-thumbs-down"></i>
                                        </button>
                                        <span class="post-dislike-count @if($response['isDisliked']) post-disliked @endif">
                                            {{ $response['dislike'] }}
                                        </span>
                                    </span>
                                </td>
                                <td style="width: 50%">
                                    <span class="post-like-wrapper">
                                        <button class="post-like-button @if($response['isLiked']) post-liked @endif">
                                            <i class="far fa-thumbs-up"></i>
                                        </button>
                                        <span class="post-like-count @if($response['isLiked']) post-liked @endif">
                                            {{ $response['like'] }}
                                        </span>
                                    </span>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" style="direction: rtl">
            <div class="col-12 col-lg-8_5" style="direction: ltr">
                {{--<div class="row post-related-news-box-row">--}}
                {{--<div class="col-12 col-lg-6 post-related-news-box-col">--}}
                {{--<div class="post-related-news-box">--}}
                {{--<img src="{{ asset('storage/3.jpg') }}" style="width: 70px; height: 100%; float: right">--}}
                {{--<p>--}}
                {{--کمتر بازیکنی مانند کانسلو دیده ام ؛پاول ندود: تمام تیم های بزرگ اروپا امره جان را می--}}
                {{--خواستند؛ انتقال رونالدو نشان دهنده پیشرفت یوونتوس است--}}
                {{--</p>--}}
                {{--</div>--}}
                {{--</div>--}}
                {{--<div class="col-12 col-lg-6 post-related-news-box-col">--}}
                {{--<div class="post-related-news-box">--}}
                {{--<img src="{{ asset('storage/2.jpg') }}" style="width: 70px; height: 100%; float: right">--}}
                {{--<p>--}}
                {{--مارکا: عصبانیت شدید رئال مادرید از سکوت مودریچ و تحرکات ایجنتش در قبال پیشنهاد اینتر--}}
                {{--</p>--}}
                {{--</div>--}}
                {{--</div>--}}
                {{--</div>--}}
                @if(trim($response['context']))
                    <div class="post-main-body">
                        {!! $response['context'] !!}
                    </div>
                @endif
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
    <script src="{{ mix('js/show-user-content.js') }}"></script>
@endsection
