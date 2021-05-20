@extends('layouts.app')
@section('header')
    <title>اعلانات</title>
@endsection
@section('styles')
    <link href="{{ mix('css/notifications.css') }}" rel="stylesheet">
@endsection
@section('content')
    <div class="container custom-container">
        <div class="row justify-content-sm-center">
            <div class="col-12 col-lg-8 col-md-10 wrapper"></div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ mix('js/notifications.js') }}"></script>
@endsection
