@extends('frontend.app')

@section('content')

    <div class="wrapper">
        <div class="sign-inup">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-5">
                        <div class="sign-form">
                            <div class="sign-inner">
                                <div class="form-dt">
                                    <div class="form-inpts checout-address-step">


                                        <form method="POST" action="{{ route('password.email') }}">
                                            @csrf
                                            <input class="d-none" type="checkbox" name="remember" id="remember" checked>
                                            <div class="form-title"><h6>Reset your Password</h6></div>
                                            @if (session('status'))
                                                <div class="alert alert-success mt-2 mb-5" role="alert">
                                                    {{ session('status') }}
                                                </div>
                                            @endif
                                            <div class="form-group mt-1">
                                                <label class="control-label">Email *</label>
                                                <div class="form-group pos_rel">
                                                    <input id="email" type="email" class="form-control lgn_input @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Your Email">
                                                    <i class="uil uil-mobile-android-alt lgn_icon"></i>
                                                </div>
                                                @error('email')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                            </div>

                                            <div class="form-group mt-5 mb-5">
                                                <hr>
                                            </div>
                                            <button class="login-btn hover-btn" type="submit">Send Password Reset Link</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

