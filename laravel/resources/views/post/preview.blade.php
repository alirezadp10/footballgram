@extends('layouts.app')
@section('header')
    <title xmlns="">پیش نمایش - فوتبال گرام</title>
@endsection
@section('styles')
    <link href="{{ mix('css/preview-post.css') }}" rel="stylesheet">
@endsection
@section('content')
    <div class="post-main-header">
        <div class="post-main-title">
            {!! $response['concatTitle'] !!}
        </div>
    </div>
    <div class="container custom-container">
        <div class="row" style="direction: rtl;">
            <div class="col-12 col-lg-8">
                <img src="{{ $response['mainPhoto'] }}" class="img-fluid post-main-img">
            </div>
            <div class="col-12 col-lg-4">
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
                                    <span>شماره خبر :</span>
                                </td>
                                <td data-to-farsi>شماره ی {{ $response['id'] }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" style="direction: rtl">
            <div class="col-12 col-lg-8" style="direction: ltr">
                <div class="post-main-body">
                    {!! $response['context'] !!}
                </div>
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div onclick="document.getElementById('release').submit();"
                             class="release-link">
                            <button class="btn btn-lg btn-success btn-block">منتشر شود</button>
                        </div>
                        <form id="release"
                              action="{{ $response['releaseLink'] }}"
                              method="POST"
                              style="display: none;">
                            @csrf
                            <input name="slug" value="{{ $response['slug'] }}">
                        </form>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="draft-link"
                             onclick="document.getElementById('draft').submit();">
                            <button class="btn btn-lg btn-warning btn-block">در پیش نویس ها ذخیره شود</button>
                        </div>
                        <form id="draft"
                              action="{{ $response['draftLink'] }}"
                              method="POST"
                              style="display: none;">
                            @csrf
                            <input name="slug" value="{{ $response['slug'] }}">
                        </form>
                    </div>
                    <div class="col-12">
                        <div class="card" style="text-align: center; margin-top: 20px">
                            <div class="card-body">
                                <p class="card-text">
                                    .در صورت انتخاب نشدن هیچ کدام تا یک ساعت دیگر این پیش نمایش پاک می شود
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-4">
            </div>
        </div>
    </div>
    <div id="post-details"
         data-post-id="{{ $response['id'] }}"
         data-post-type="{{ $response['type'] }}"
    ></div>
@endsection
@section('scripts')
    <script src="{{ mix('js/preview-post.js') }}"></script>
@endsection
