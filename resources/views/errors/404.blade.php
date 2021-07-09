@extends('layouts.app')
@section('header')
    <title>چنین صفحه ای پیدا نشد!</title>
@endsection
@section('styles')
    <link href="{{ mix('css/error.css') }}" rel="stylesheet">
@endsection
@section('content')
    <div class="container" style="text-align: right; direction: rtl">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="card">
                    <div class="card-header">
                        چنین صفحه ای پیدا نشد
                    </div>
                    <div class="card-body">
                        با عرض پوزش از شما, چنین صفحه ای در سایت وجود ندارد یا این صفحه از سایت پاک شده است.
                    </div>
                    <div class="card-footer" style="text-align: left">
                        <a href="/">
                            <button class="btn btn-warning text-white">
                                <i class="fas fa-home"></i>
                            </button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ mix('js/error.js') }}"></script>
@endsection
