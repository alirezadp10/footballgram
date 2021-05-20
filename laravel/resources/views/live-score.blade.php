@extends('layouts.app')
@section('header')
    {{--<title>{{ $response['title'] }}</title>--}}
@endsection
@section('styles')
    <link href="{{ mix('css/live-score.css') }}" rel="stylesheet">
@endsection
@section('content')
    <div class="container custom-container">
    </div>
@endsection
@section('scripts')
    <script src="{{ mix('js/live-score.js') }}"></script>
@endsection
