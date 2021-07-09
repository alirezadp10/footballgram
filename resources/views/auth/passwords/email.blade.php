@extends('layouts.app')
@section('styles')
    <link href="{{ mix('css/email-password.css') }}" rel="stylesheet">
@endsection
@section('content')
    <div class="container reset-password-form">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header text-right">{{ __('Reset Password') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success text-right">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form method="POST"
                              class="form"
                              action="{{ route('password.email') }}">
                            @csrf

                            <div class="form-group row">
                                <label for="email"
                                       class="col-md-4 col-form-label text-md-left text-right">{{ __('E-Mail') }}</label>

                                <div class="col-md-6">
                                    <input id="email"
                                           type="email"
                                           class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                           name="email"
                                           data-validate="validate(required,email)"
                                           value="{{ old('email') }}"
                                    >

                                    @if ($errors->has('email'))
                                        <span class="invalid-feedback text-right">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row mb-0 justify-content-around">
                                <div class="col-md-6 offset-md-2 offset-lg-2 text-md-left">
                                    <button type="submit"
                                            class="btn btn-primary">
                                        {{ __('Send Password Reset Link') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ mix('js/email-password.js') }}"></script>
@endsection
