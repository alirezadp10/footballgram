@extends('layouts.app')
@section('header')
    <title>#{{ $response['name'] }} - فوتبال گرام</title>
@endsection
@section('styles')
    <link href="{{ mix('css/tag.css') }}" rel="stylesheet">
@endsection
@section('content')
    <div class="container custom-container">
        <div class="row" style="margin-top: 10px;background: white;padding: 25px 10px;">
            <div class="col-12">
                <div class="post-instagram-title" style="direction: rtl">
                    #{{ $response['name'] }}
                    <span style="font-size: 17px; margin: 0 5px" data-to-farsi>
                        ( {{ $response['count'] }} )
                    </span>
                </div>
            </div>
            <div class="nav nav-tabs nav-stacked instagram-nav">
                <div class="nav-link news"
                     data-toggle="tab"
                     href="#nav-news"
                     role="tab"
                     aria-controls="#nav-news"
                     aria-selected="false">اخبار
                </div>
                <div class="nav-link user-contents"
                     data-toggle="tab"
                     href="#nav-user-contents"
                     role="tab"
                     aria-controls="#nav-user-contents"
                     aria-selected="false">مطالب کاربری
                </div>
                <div class="nav-link comments"
                     data-toggle="tab"
                     href="#nav-comments"
                     role="tab"
                     aria-controls="#nav-comments"
                     aria-selected="false">نظرات
                </div>
            </div>
            <div class="tab-content instagram-tab-content">
                <div class="tab-pane fade"
                     id="nav-news"
                     role="tabpanel"
                     aria-labelledby="nav-news-tab">
                    <div class="row"
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
                        <div class="col-12 post-instagram-footer-wrapper more-news" style="padding: 0 10px;">
                            <div class="post-instagram-footer">
                                <i class="fas fa-angle-double-down"></i>&nbsp;&nbsp;
                                                                        مطالب بیشتر
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade"
                     id="nav-user-contents"
                     role="tabpanel"
                     aria-labelledby="nav-user-contents-tab">
                    <div class="row"
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
                        <div class="col-12 post-instagram-footer-wrapper more-user-contents" style="padding: 0 10px;">
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
                    <div class="row"
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
                        <div class="col-12 post-instagram-footer-wrapper more-comments" style="padding: 0 10px;">
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
@endsection
@section('scripts')
    <script>
        window.LARAVEL_TAG_NAME = "{{ $response['name'] }}";
    </script>
    <script src="{{ mix('js/tag.js') }}"></script>
@endsection
