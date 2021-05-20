@extends('layouts.app')
@section('header')
    <title>تنظیمات - فوتبال گرام</title>
@endsection
@section('styles')
    <link href="{{ mix('css/user-configuration.css') }}" rel="stylesheet">
@endsection
@section('content')
    <div class="container custom-container">
        <div class="row justify-content-lg-center">
            <div class="col-12">
                <div class="row"
                     style="direction: rtl">
                    <div class="col-12 col-lg-2">
                        <div class="nav flex-column nav-pills config-nav-pills"
                             id="v-pills-tab"
                             role="tablist"
                             aria-orientation="vertical">
                            <a class="nav-link active"
                               id="v-pills-profile-tab"
                               data-toggle="pill"
                               href="#v-pills-profile"
                               role="tab"
                               aria-controls="v-pills-profile"
                               aria-selected="true">ویرایش پروفایل</a>
                            <a class="nav-link"
                               id="v-pills-password-tab"
                               data-toggle="pill"
                               href="#pills-password"
                               role="tab"
                               aria-controls="pills-password"
                               aria-selected="false">تغییر رمز عبور</a>
                        </div>
                    </div>
                    <div class="col-12 col-lg-10">
                        <div class="tab-content" id="v-pills-tabContent">
                            <div class="tab-pane fade show active"
                                 id="v-pills-profile"
                                 role="tabpanel"
                                 aria-labelledby="v-pills-profile-tab">
                                <form action="{{ route('users.updateProfile') }}"
                                      enctype="multipart/form-data"
                                      method="POST"
                                      role="form"
                                      class="config-profile-form">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-12 col-lg-4">
                                                <div class="config-avatar-wrapper">
                                                    <div class="img-wrapper">
                                                        <img src="{{ $response['avatar'] }}"
                                                             id="preview-photo"
                                                             onclick="document.getElementById('input-photo').click()">
                                                    </div>
                                                    <input type="file"
                                                           class="form-control"
                                                           style="display: none"
                                                           data-validate="validate(imageValidateExtension)"
                                                           id="input-photo">
                                                    <input type="hidden"
                                                           name="avatar"
                                                           id="avatar">
                                                    <button type="button"
                                                            onclick="document.getElementById('input-photo').click()"
                                                            class="btn btn-info btn-lg btn-block">
                                                        تغییر آواتار
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="col-12 col-lg-8">
                                                <div class="form-group row">
                                                    <label for="name"
                                                           class="col-xs-12 col-lg-4 col-form-label">نام :</label>
                                                    <div class="col-xs-12 col-lg-8">
                                                        <input type="text"
                                                               class="form-control"
                                                               name="name"
                                                               id="name"
                                                               data-validate="validate(required)"
                                                               placeholder="نام خود را وارد کنید"
                                                               value="{{ $response['name'] }}">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="username"
                                                           class="col-xs-12 col-lg-4 col-form-label">نام کاربری :</label>
                                                    <div class="col-xs-12 col-lg-8">
                                                        <div class="input-group">
                                                            <input type="text"
                                                                   class="form-control"
                                                                   data-validate="validate(username,usernameLength)"
                                                                   id="username"
                                                                   placeholder="username"
                                                                   value="{{ $response['username'] }}"
                                                                   name="username"
                                                                   style="direction: ltr">
                                                            <div class="input-group-prepend">
                                                                <div class="input-group-text">@</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="mobile"
                                                           class="col-xs-12 col-lg-4 col-form-label">موبایل :</label>
                                                    <div class="col-xs-12 col-lg-8">
                                                        <input type="text"
                                                               class="form-control"
                                                               id="mobile"
                                                               name="mobile"
                                                               placeholder="شماره موبایل خود را وارد کنید"
                                                               value="{{ $response['mobile'] }}"
                                                               disabled>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="email"
                                                           class="col-xs-12 col-lg-4 col-form-label">ایمیل :</label>
                                                    <div class="col-xs-12 col-lg-8">
                                                        <input type="email"
                                                               class="form-control"
                                                               id="email"
                                                               data-validate="validate(mail-custom)"
                                                               value="{{ $response['email'] }}"
                                                               name="email"
                                                               placeholder="ایمیل خود را وارد کنید">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="bio"
                                                           class="col-xs-12 col-lg-4 col-form-label">درباره ی من
                                                                                                    :</label>
                                                    <div class="col-xs-12 col-lg-8">
                                                        <textarea class="form-control"
                                                                  id="bio"
                                                                  name="bio"
                                                                  rows="5">{{ $response['bio'] }}</textarea>
                                                    </div>
                                                </div>
                                                <button type="submit"
                                                        class="btn btn-success btn-lg config-submit-profile-button">
                                                    تایید
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane fade"
                                 id="pills-password"
                                 role="tabpanel"
                                 aria-labelledby="pills-password-tab">
                                <form action="{{ route('users.updatePassword') }}"
                                      method="POST"
                                      role="form"
                                      class="config-password-form">
                                    <div class="form-group row">
                                        <label for="currentPassword"
                                               class="col-xs-3 col-sm-2 col-form-label">رمز عبور فعلی :</label>
                                        <div class="col-xs-9 col-sm-10">
                                            <input type="password"
                                                   class="form-control"
                                                   id="currentPassword"
                                                   data-validate="validate(required)"
                                                   placeholder="رمز عبور فعلی خود را وارد کنید">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="newPassword"
                                               class="col-xs-3 col-sm-2 col-form-label">رمز عبور جدید:</label>
                                        <div class="col-xs-9 col-sm-10">
                                            <input type="password"
                                                   name="new_password"
                                                   class="form-control"
                                                   id="newPassword"
                                                   data-validate="validate(required,passwordValidation)"
                                                   placeholder="رمز عبور جدید خود را وارد کنید">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="newPasswordConfirmation"
                                               class="col-xs-3 col-sm-2 col-form-label">تکرار رمز عبور جدید :</label>
                                        <div class="col-xs-9 col-sm-10">
                                            <input type="password"
                                                   class="form-control"
                                                   id="newPasswordConfirmation"
                                                   data-validate="validate(required,confirmPasswordValidation)"
                                                   name="new_password_confirmation"
                                                   placeholder="تکرار رمز عبور جدید خود را وارد کنید">
                                        </div>
                                    </div>
                                    <button type="submit"
                                            class="btn btn-success btn-lg config-submit-password-button">
                                        تایید
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ mix('js/user-configuration.js') }}"></script>
@endsection
