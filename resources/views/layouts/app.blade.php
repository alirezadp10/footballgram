<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('header')
    @yield('styles')
</head>
<body>
<div id="app">
    @include('sections.navbar')
    <main class="py-4 main-container">
        @yield('content')
    </main>
</div>
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
        <a href="{{ route('news.create') }}" class="button-new-news">خبر</a>
    @endif
    @if($can_create_tweet)
        <a href="{{ route('tweets.create') }}" class="button-new-tweet">توییت</a>
    @endif
    @if($can_create_user_content)
        <a href="{{ route('user-contents.create') }}" class="button-new-user-content">کاربری</a>
    @endif
@endauth
@include('sections.footer')
<!-- Scripts -->
@yield('scripts')
@if(session('message'))
    <script>
        window.alertify.set('notifier','position', 'bottom-left');
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
