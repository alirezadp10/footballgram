@extends('layouts.app')
@section('header')
    <title>ایجاد توییت جدید - فوتبال گرام</title>
@endsection
@section('styles')
    <link href="{{ mix('css/create-post.css') }}" rel="stylesheet">
@endsection
@section('content')
    <div class="container custom-container">
        <form action="{{ route('tweets.store') }}"
              method="post"
              class="form mr-2 ml-2"
              enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <textarea class="form-control"
                          rows="10"
                          name="context"
                          placeholder="متن توییت خود را بنویسید..."></textarea>
            </div>
            <button type="submit"
                    class="btn btn-success float-right">ارسال
            </button>
        </form>
    </div>
@endsection
@section('scripts')
    <script src="{{ mix('js/create-post.js') }}"></script>
@endsection

