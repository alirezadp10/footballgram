@extends('layouts.app')
@section('header')
    <title>ایجاد خبر جدید - فوتبال گرام</title>
@endsection
@section('styles')
    <link href="{{ mix('css/create-post.css') }}" rel="stylesheet">
@endsection
@section('content')
    <div class="container custom-container">
        <!-- SmartWizard html -->
        <div id="smartwizard">
            <ul>
                <li><a href="#step-1">مرحله ی ۱<br />
                        <small>نوشتن عنوان</small>
                    </a></li>
                <li><a href="#step-2">مرحله ی ۲<br />
                        <small>انتخاب تصویر</small>
                    </a></li>
                <li><a href="#step-3">مرحله ی ۳<br />
                        <small>نوشتن متن</small>
                    </a></li>
                {{--<li><a href="#step-4">مرحله ی ۴<br /><small>دسته بندی</small></a></li>--}}
                <li><a href="#step-4">مرحله ی ۴<br />
                        <small>پیش نمایش</small>
                    </a></li>
            </ul>
            <div>
                <form action="{{ route('news.store') }}"
                      method="post"
                      class="form"
                      target="_blank"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="steps" id="step-1">
                        <div class="form-group">
                            <label for="secondary-title">بخش فرعی عنوان را بنویسید:
                                <small> (اختیاری)</small>
                            </label>
                            <input type="text"
                                   class="form-control"
                                   name="secondary_title"
                                   id="secondary-title"
                                   placeholder="برای مثال: نقدی بر انتخاب برترین بازیکن سال اروپا؛">
                        </div>
                        <div class="form-group">
                            <label for="main-title">بخش اصلی عنوان را بنویسید:
                                <small style="color: red"> (اجباری)</small>
                            </label>
                            <input type="text"
                                   class="form-control"
                                   name="main_title"
                                   data-validate="validate(required)"
                                   id="main-title"
                                   placeholder="برای مثال: چه کسی شایسته ی انتخاب برترین بازیکن اروپا بود؟">
                        </div>
                        <div class="card" style="color: grey">
                            <div class="card-body">
                                <p class="card-text">
                                <p>توصیه هایی برای بهتر دیده شدن مطلب:</p>
                                <small>
                                    <ul style="list-style: decimal">
                                        <li>در انتخاب عنوان خود از نوشتن تک کلمه و عبارات نا خوانا مانند :)))) اجتناب
                                            کنید.
                                        </li>
                                        <li>از اعلائم نگارشی مانند ویرگول ، نقطه ویرگول ، نقطه ، علامت تعجب و علامت پرسش
                                            درست و به جای خود استفاده کنید.
                                        </li>
                                    </ul>
                                </small>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="steps" id="step-2">
                        <div class="form-group">
                            <label for="main-photo">تصویر :
                                <small style="color: red"> (اجباری)</small>
                            </label>
                            <input type="file"
                                   class="form-control"
                                   data-validate="validate(required,imageValidateExtension)"
                                   id="input-photo">
                            <input type="hidden"
                                   name="main_photo"
                                   id="main_photo">
                            <img id="preview-photo"
                                 onclick="document.getElementById('input-photo').click();"
                                 src="/images/no-image.png"
                                 alt="your image"
                                 class="img-fluid" />
                            <div class="form-control btn btn-info"
                                 onclick="document.getElementById('input-photo').click();">
                                برای انتخاب تصویر اصلی کلیک کنید
                            </div>
                        </div>
                    </div>
                    <div class="steps" id="step-3">
                        <div class="form-group">
                            <label for="context">متن :
                                <small> (اختیاری)</small>
                            </label>
                            <textarea class="form-control"
                                      name="context"
                                      id="context"></textarea>
                        </div>
                    </div>
                    {{--<div class="steps" id="step-4">--}}
                    {{--<div class="form-group">--}}
                    {{--<label>دسته بندی :<small style="color: red"> (اجباری) </small></label>--}}
                    {{--<select class="form-control category"--}}
                    {{--name="category[]"--}}
                    {{--style="width: 100%;"--}}
                    {{--data-validate="validate(select-tools)"--}}
                    {{--multiple="multiple">--}}
                    {{--<option value="internal">داخلی</option>--}}
                    {{--<option value="external">خارجی</option>--}}
                    {{--<option value="calcio">سری آ</option>--}}
                    {{--<option value="bundesliga">بوندس لیگا</option>--}}
                    {{--<option value="laliga">لالیگا</option>--}}
                    {{--<option value="khaligefars">لیگ خلیج فارس</option>--}}
                    {{--<option value="loshampione">لوشامپیونه</option>--}}
                    {{--<option value="eredivisie">ارودیوژه</option>--}}
                    {{--<option value="azadegan">لیگ آزادگان</option>--}}
                    {{--<option value="premier_league">لیگ جزیره</option>--}}
                    {{--<option value="europe_champions_league">لیگ قهرمانان اروپا</option>--}}
                    {{--<option value="europe_league">لیگ اروپا</option>--}}
                    {{--<option value="asia_champions_league">لیگ قهرمانان آسیا</option>--}}
                    {{--<option value="europe_national_league">لیگ ملت های اروپا</option>--}}
                    {{--<option value="uefa_euro">جام ملت های اروپا</option>--}}
                    {{--<option value="world_cup">جام جهانی</option>--}}
                    {{--<option value="afc_asian_cup">جام ملت های آسیا</option>--}}
                    {{--<option value="stars_league">لیگ ستارگان قطر</option>--}}
                    {{--</select>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    <div class="steps" id="step-4">
                        <div class="form-group">
                            <button type="submit"
                                    class="btn btn-success">برای مشاهده ی پیش نمایش کلیک کنید
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

