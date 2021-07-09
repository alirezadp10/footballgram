@extends('layouts.app')
@section('header')
    <title>ویرایش خبر - فوتبال گرام</title>
@endsection
@section('styles')
    <link href="{{ mix('css/create-post.css') }}" rel="stylesheet">
@endsection
@section('content')
    <div class="container custom-container">
        <!-- SmartWizard html -->
        <div id="smartwizard">
            <ul>
                <li><a href="#step-1">مرحله ی ۱<br /><small>نوشتن عنوان</small></a></li>
                <li><a href="#step-2">مرحله ی ۲<br /><small>انتخاب تصویر</small></a></li>
                <li><a href="#step-3">مرحله ی ۳<br /><small>نوشتن متن</small></a></li>
                <li><a href="#step-4">مرحله ی ۴<br /><small>به روز رسانی</small></a></li>
            </ul>
            <div>
                <form action="{{ route('news.update',$response['slug']) }}"
                      method="post"
                      class="form"
                      enctype="multipart/form-data">
                    @csrf
                    @method('put')
                    <div class="steps" id="step-1">
                        <div class="form-group">
                            <label for="secondary-title">بخش فرعی عنوان را بنویسید:<small> (اختیاری) </small></label>
                            <input type="text"
                                   class="form-control"
                                   name="secondary_title"
                                   id="secondary-title"
                                   value="{{ $response['secondaryTitle'] }}"
                                   placeholder="برای مثال: نقدی بر انتخاب برترین بازیکن سال اروپا؛">
                        </div>
                        <div class="form-group">
                            <label for="main-title">بخش اصلی عنوان را بنویسید:<small style="color: red"> (اجباری) </small></label>
                            <input type="text"
                                   class="form-control"
                                   name="main_title"
                                   data-validate="validate(required)"
                                   id="main-title"
                                   value="{{ $response['mainTitle'] }}"
                                   placeholder="برای مثال: چه کسی شایسته ی انتخاب برترین بازیکن اروپا بود؟">
                        </div>
                        <div class="card" style="color: grey">
                            <div class="card-body">
                                <p class="card-text">
                                <p>توصیه هایی برای بهتر دیده شدن مطلب:</p>
                                <small>
                                    <ul style="list-style: decimal">
                                        <li>در انتخاب عنوان خود از نوشتن تک کلمه و عبارات نا خوانا مانند :)))) اجتناب کنید.</li>
                                        <li>از اعلائم نگارشی مانند ویرگول ، نقطه ویرگول ، نقطه ، علامت تعجب و علامت پرسش درست و به جای خود استفاده کنید.</li>
                                    </ul>
                                </small>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="steps" id="step-2">
                        <div class="form-group">
                            <label for="main-photo">تصویر :<small style="color: red"> (اجباری) </small></label>
                            <input type="file"
                                   class="form-control"
                                   data-validate="validate(imageValidateExtension)"
                                   id="input-photo">
                            <input type="hidden"
                                   name="main_photo"
                                   id="main_photo">
                            <img id="preview-photo"
                                 onclick="document.getElementById('input-photo').click();"
                                 src="{{ $response['mainPhoto'] }}"
                                 alt="your image"
                                 class="img-fluid"/>
                            <div class="form-control btn btn-info"
                                 onclick="document.getElementById('input-photo').click();">
                                برای انتخاب تصویر اصلی کلیک کنید
                            </div>
                        </div>
                    </div>
                    <div class="steps" id="step-3">
                        <div class="form-group">
                            <label for="context">متن :<small> (اختیاری) </small></label>
                            <textarea class="form-control"
                                      name="context"
                                      id="context">{!! $response['context'] !!}</textarea>
                        </div>
                    </div>
                    <div class="steps" id="step-4">
                        <div class="form-group">
                            <button type="submit"
                                    class="btn btn-success">برای به روز رسانی خبر کلیک کنید
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ mix('js/create-post.js') }}"></script>
@endsection

