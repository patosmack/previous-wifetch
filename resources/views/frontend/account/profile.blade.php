@extends('frontend.app')

@section('content')

    <div class="wrapper">
        @include('frontend.shared.alert')

        <div class="">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 col-md-4">
                        @include('frontend.account.sidebar')
                    </div>
                    <div class="col-lg-9 col-md-8">
                        <div class="dashboard-right">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="main-title-tab">
                                        <h4><i class="uil uil-location-point"></i>Profile</h4>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12">
                                    <div class="pdpt-bg">
                                        <div class="pdpt-title">
                                            <h4>Edit your account</h4>
                                        </div>
                                        <div class="address-body pb-3 ">
                                            <div class="sign-form">
                                                <div class="sign-inner">
                                                    <div class="form-inpts checout-address-step pt-2">

                                                        <form method="POST" action="{{ route('account.profile.store') }}">
                                                            @csrf
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group mt-1">
                                                                        <label class="control-label">Name *</label>
                                                                        <div class="form-group pos_rel">
                                                                            <input id="name" type="text" class="form-control lgn_input @error('name') is-invalid @enderror" name="name" value="{{ old('name', $userAccount->name) }}" required autocomplete="name" autofocus placeholder="Your Name">
                                                                            <i class="uil uil-user-circle lgn_icon"></i>
                                                                        </div>
                                                                        @error('name')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group mt-1">
                                                                        <label class="control-label">Last name *</label>
                                                                        <div class="form-group pos_rel">
                                                                            <input id="last_name" type="text" class="form-control lgn_input @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name', $userAccount->last_name) }}" required autocomplete="last_name" placeholder="Your Last Name">
                                                                            <i class="uil uil-user-circle lgn_icon"></i>
                                                                        </div>
                                                                        @error('last_name')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                                                    </div>
                                                                </div>
                                                            </div>


                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="form-group mt-1">
                                                                        <label class="control-label">Email *</label>
                                                                        <div class="form-group pos_rel">
                                                                            <input id="email" type="email" class="form-control lgn_input @error('email') is-invalid @enderror" name="email" value="{{ old('email', $userAccount->email) }}" required autocomplete="email" placeholder="Your Email Address">
                                                                            <i class="uil uil-envelope lgn_icon"></i>
                                                                        </div>
                                                                        @error('email')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group mt-1">
                                                                        <label class="control-label">Home Phone </label>
                                                                        <div class="form-group pos_rel">
                                                                            <input id="home_phone" type="text" class="form-control lgn_input @error('home_phone') is-invalid @enderror" name="home_phone" value="{{ old('home_phone', $userAccount->home_phone) }}"  placeholder="Your Home Phone">
                                                                            <i class="uil uil-user-circle lgn_icon"></i>
                                                                        </div>
                                                                        @error('home_phone')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group mt-1">
                                                                        <label class="control-label">Mobile Phone </label>
                                                                        <div class="form-group pos_rel">
                                                                            <input id="mobile_phone" type="text" class="form-control lgn_input @error('mobile_phone') is-invalid @enderror" name="mobile_phone" value="{{ old('mobile_phone', $userAccount->mobile_phone) }}"  placeholder="Your Mobile Phone">
                                                                            <i class="uil uil-user-circle lgn_icon"></i>
                                                                        </div>
                                                                        @error('mobile_phone')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="form-group mt-5 mb-5">
                                                                <hr>
                                                            </div>

                                                            <div class="row mb-4">
                                                                <div class="col-md-12 mb-3">
                                                                    <h4>Modify your password</h4>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group mt-1">
                                                                        <label class="control-label">Password </label>
                                                                        <div class="form-group pos_rel">
                                                                            <input id="password" type="password"  class="form-control lgn_input @error('password') is-invalid @enderror" name="password" autocomplete="new-password" placeholder="Your Password">
                                                                            <i class="uil uil-padlock lgn_icon"></i>
                                                                        </div>
                                                                        @error('password')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group mt-1">
                                                                        <label class="control-label">Confirm your Password </label>
                                                                        <div class="form-group pos_rel">
                                                                            <input id="password-confirm" type="password" class="form-control lgn_input" name="password_confirmation" autocomplete="new-password" placeholder="Re enter your Password">
                                                                            <i class="uil uil-padlock lgn_icon"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12 mb-3">
                                                                    <p>Fill the Password field only if you wish to modify your password</p>
                                                                </div>
                                                            </div>
                                                            <button class="login-btn hover-btn" type="submit">Save your profile</button>
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
                </div>
            </div>
        </div>
    </div>

@endsection
