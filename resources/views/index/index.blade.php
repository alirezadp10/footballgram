<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description"
          content="">
    <meta name="author"
          content="">
    <link rel="icon"
          href="favicon.ico">
    <title>شبکه اجتماعی ورزشی فوتبال گرام</title>
    <meta name="csrf-token"
          content="{{ csrf_token() }}">
    <link href="{{ mix('css/index.css') }}"
          rel="stylesheet">
</head>
<body>
@include('sections.navbar')
@include('index.trends')
@include('index.slider-lastNews-mostFollower')
@include('index.leagues-hotNews-chiefChoice')
@include('index.survey-newspapers-broadcastSchedule-video')
@include('sections.instagram-post')
@include('sections.footer')
@auth
    <a data-toggle="tooltip"
       style="cursor: pointer"
       data-placement="top"
       data-status="collapse"
       class="button-new-post"
    >
        <i class="fas fa-plus"></i>
    </a>
    @if($can_create_news)
        <a href="{{ route('news.create') }}"
           class="button-new-news">خبر</a>
    @endif
    @if($can_create_tweet)
        <a href="{{ route('tweets.create') }}"
           class="button-new-tweet">توییت</a>
    @endif
    @if($can_create_user_content)
        <a href="{{ route('user-contents.create') }}"
           class="button-new-user-content">کاربری</a>
    @endif
@endauth
<script src="{{ mix('js/persian-date.min.js') }}"></script>
<script src="{{ mix('js/index.js') }}"></script>
@if(session('message'))
    <script>
        window.alertify.set("notifier", "position", "bottom-left");
        window.alertify.notify(
            "{!! session('message.content') !!}",
            "{{ session('message.type') }}",
            {{ session('message.time') }}
        );
    </script>
    {{ session()->forget('message') }}
@endif
</body>
</html>
