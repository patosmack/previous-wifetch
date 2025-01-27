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

                                        <form method="POST" action="{{ route('login.form') }}">
                                            @csrf
                                            <input class="d-none" type="checkbox" name="remember" id="remember" checked>

                                            <div class="form-title"><h6>Sign In</h6></div>
                                            <div class="form-group mt-1">
                                                <label class="control-label">Email *</label>
                                                <div class="form-group pos_rel">
                                                    <input id="email" type="email" class="form-control lgn_input @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Your Email">
                                                    <i class="uil uil-mobile-android-alt lgn_icon"></i>
                                                </div>
                                                @error('email')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                            </div>

                                            <div class="form-group mt-1">
                                                <label class="control-label">Password *</label>
                                                <div class="form-group pos_rel">
                                                    <input id="password" type="password" class="form-control lgn_input @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Your Password">
                                                    <i class="uil uil-padlock lgn_icon"></i>
                                                </div>
                                                @error('password')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                            </div>

                                            <div class="form-group mt-5 mb-5">
                                                <hr>
                                            </div>
                                            <button class="login-btn hover-btn" type="submit">Sign In Now</button>
                                        </form>
                                    </div>
                                    @if (Route::has('password.request'))
                                        <div class="password-forgor">
                                            <a href="{{ route('password.request') }}">Forgot Password?</a>
                                        </div>
                                    @endif

                                    <div class="signup-link">
                                        <p>Don't have an account? - <a href="{{ route('register') }}">Sign Up Now</a></p>
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
