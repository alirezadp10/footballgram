@extends('layouts.app')
@section('styles')
    <link href="{{ mix('css/login.css') }}" rel="stylesheet">
@endsection
@section('content')
    <div class="container login-form">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="card">
                    <div class="card-header">{{ __('Login') }}</div>
                    <div class="card-body">
                        <form method="POST"
                              action="{{ route('login') }}"
                              class="form">
                            @csrf
                            <div class="form-group row">
                                <label for="email"
                                       class="col-sm-4 col-form-label text-md-left text-right">{{ __('E-Mail') }}</label>
                                <div class="col-md-6">
                                    <input id="email"
                                           type="email"
                                           class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                           name="email"
                                           value="{{ old('email') }}"
                                           data-validate="validate(required,email)"
                                           autofocus>
                                    @if ($errors->has('email'))
                                        <span class="invalid-feedback text-right">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password"
                                       class="col-md-4 col-form-label text-md-left text-right">{{ __('Password') }}</label>

                                <div class="col-md-6">
                                    <input id="password"
                                           type="password"
                                           class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                           name="password"
                                           data-validate="validate(required)">

                                    @if ($errors->has('password'))
                                        <span class="invalid-feedback text-right">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row justify-content-around">
                                <div class="col-md-6 offset-md-5 offset-lg-5 remember-me text-right text-md-left">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox"
                                                   name="remember" {{ old('remember') ? 'checked' : '' }}> {{ __('Remember Me') }}
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row mb-0 justify-content-around">
                                <div class="col-md-7 offset-md-1 offset-lg-0 login-form-submit text-right text-md-left">
                                    <button type="submit"
                                            class="btn btn-primary">
                                        {{ __('Sign-in') }}
                                    </button>

                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer">
                        <span style="float: right; line-height: 37px; font-weight: bold">
                            {{ __('Sign-in with your other account:') }}
                        </span>
                        <span class="social-login">
                            <a href="/login/telegram">
                                <i class="fab fa-telegram"
                                   data-toggle="tooltip"
                                   data-placement="top"
                                   title="{{ __('Via Telegram') }}"
                                   style="color: #34acde"
                                   aria-hidden="true">
                                </i>
                            </a>
                            <a href="/login/instagram">
                                <i class="fab fa-instagram"
                                   data-toggle="tooltip"
                                   data-placement="top"
                                   title="{{ __('Via Instagram') }}"
                                   style="color: #c65aa4"
                                   aria-hidden="true">
                                </i>
                            </a>
                            <a href="/login/twitter">
                                <i class="fab fa-twitter"
                                   data-toggle="tooltip"
                                   data-placement="top"
                                   title="{{ __('Soon') }} {{ __('Via Twitter') }}"
                                   style="color: #1cb7eb"
                                   aria-hidden="true">
                                </i>
                            </a>
                            <a href="/login/google">
                                <i class="fab fa-google"
                                   data-toggle="tooltip"
                                   data-placement="top"
                                   title="{{ __('Soon') }} {{ __('Via Google') }}"
                                   style="color: red"
                                   aria-hidden="true">
                                </i>
                            </a>
                        </span>
                        <small class="text-muted social-login-helper">
                            {{ __('In this way, your image and other public information are easily recorded in our system') }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ mix('js/login.js') }}"></script>
@endsection
