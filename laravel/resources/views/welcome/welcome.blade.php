<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="favicon.ico">
    <title>شبکه اجتماعی ورزشی فوتبال گرام</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ mix('css/welcome.css') }}" rel="stylesheet">
</head>
<body>
@include('sections.navbar')
@include('welcome.trends')
@include('welcome.slider-lastNews-mostFollower')
@include('welcome.leagues-hotNews-chiefChoice')
@include('welcome.survey-newspapers-broadcastSchedule-video')
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
    @if($response['buttonNewPost']['canCreateNews'])
        <a href="{{ route('news.create') }}" class="button-new-news">خبر</a>
    @endif
    @if($response['buttonNewPost']['canCreateTweet'])
        <a href="{{ route('tweets.create') }}" class="button-new-tweet">توییت</a>
    @endif
    @if($response['buttonNewPost']['canCreateUserContent'])
        <a href="{{ route('user-contents.create') }}" class="button-new-user-content">کاربری</a>
    @endif
@endauth
<script src="{{ mix('js/persian-date.min.js') }}"></script>
<script src="{{ mix('js/welcome.js') }}"></script>
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