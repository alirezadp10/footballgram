@extends('layouts.app')
@section('header')
    <title>خطا!</title>
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
                        اشکال در برقراری ارتباط با سرور
                    </div>
                    @if(env('APP_DEBUG'))
                        <div class="card-body" style="direction: ltr;text-align: left;">
                            {{ $exception->getMessage() }}
                        </div>
                    @else
                        <div class="card-body" style="direction: rtl;text-align: right;">
                            با عرض پوزش از شما, در ارتباط با سرور اشکال پیش آمده است، لطفا دوباره تلاش کنید.
                        </div>
                    @endif
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
