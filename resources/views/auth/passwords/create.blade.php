@extends('layouts.app')
@section('styles')
    <link href="{{ mix('css/create-password.css') }}" rel="stylesheet">
@endsection
@section('content')
    <div class="container store-password-form">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header text-right">انتخاب گذر واژه</div>
                    <div class="card-body">
                        <form method="POST"
                              action="{{ route('password.store') }}"
                              class="form">
                            @csrf

                            <div class="form-group row">
                                <label for="password"
                                       class="col-md-4 col-form-label text-md-left text-right">رمز
                                                                                               عبور</label>
                                <div class="col-md-6">
                                    <input id="password"
                                           type="password"
                                           class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                           data-validate="validate(required,password)"
                                           name="password">
                                    @if ($errors->has('password'))
                                        <span class="invalid-feedback text-right">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password-confirm"
                                       class="col-md-4 col-form-label text-md-left text-right">تکرار
                                                                                               رمز
                                                                                               عبور</label>
                                <div class="col-md-6">
                                    <input id="password-confirm"
                                           type="password"
                                           class="form-control"
                                           data-validate="validate(required,confirm-password)"
                                           name="password_confirmation">
                                </div>
                            </div>

                            <div class="form-group row mb-0 justify-content-around">
                                <div class="col-md-4 offset-sm-3 offset-lg-5 text-right text-md-left">
                                    <button type="submit"
                                            class="btn btn-primary">
                                        تایید
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer text-right">
                        <small class="text-muted">رمز عبور شما به صورت پیش فرض ۱۲۳۴۵۶ می باشد</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ mix('js/create-password.js') }}"></script>
@endsection
