@extends('layouts.app')
@section('styles')
    <link href="{{ mix('css/register.css') }}" rel="stylesheet">
@endsection
@section('content')
    <div class="container register-form">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">{{ __('Register') }}</div>
                    <div class="card-body">
                        <form method="POST"
                              action="{{ route('register') }}"
                              class="form">
                            @csrf
                            <div class="form-group row">
                                <label for="name"
                                       class="col-md-4 col-form-label text-md-left text-right">{{ __('Name') }}</label>
                                <div class="col-md-6">
                                    <input id="name"
                                           type="text"
                                           class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                           name="name"
                                           value="{{ old('name') }}"
                                           data-validate="validate(required)"
                                           autofocus>
                                    @if ($errors->has('name'))
                                        <span class="invalid-feedback text-right">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="email"
                                       class="col-md-4 col-form-label text-md-left text-right">{{ __('E-Mail') }}</label>
                                <div class="col-md-6">
                                    <input id="email"
                                           type="email"
                                           class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                           name="email"
                                           data-validate="validate(required,email)"
                                           value="{{ old('email') }}">
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
                                       class="col-md-4 col-form-label text-md-left text-right">{{ __('Confirm Password') }}</label>
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
                                        {{ __('Register') }}
                                    </button>
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
    <script src="{{ mix('js/register.js') }}"></script>
@endsection
