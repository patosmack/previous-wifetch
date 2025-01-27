@extends('frontend.app')


@section('scripts')
    <script>
        $(document).ready(function () {
            var allOptions = $('#parish_id option');
            var selectedOption = $('#parish_id').data('pre_selected');
            $('#parish_id option').remove();
            $('<option value="" selected disabled>Select one Country first</option>').appendTo('#parish_id');
            if(selectedOption){
                filterParishes();
            }
            $('#country_id').change(function () {
                selectedOption = null;
                filterParishes();
            });
            function filterParishes() {
                $('#parish_id option').remove()
                var classN = $('#country_id option:selected').prop('class');;
                var opts = allOptions.filter('.' + classN);
                $.each(opts, function (i, j) {
                    $(j).appendTo('#parish_id');
                });
                if(selectedOption){
                    $("#parish_id").val(selectedOption);
                }else{
                    $("#parish_id").val($("#parish_id option:first").val());
                }
            }
        });
    </script>
@endsection

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
                                        <form method="POST" action="{{ route('register.form') }}">
                                            @csrf

                                            <div class="form-title"><h6>Sign Up</h6></div>
                                            <div class="form-group mt-1">
                                                <label class="control-label">Name *</label>
                                                <div class="form-group pos_rel">
                                                    <input id="name" type="text" class="form-control lgn_input @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Your Name">
                                                    <i class="uil uil-user-circle lgn_icon"></i>
                                                </div>
                                                @error('name')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                            </div>
                                            <div class="form-group mt-1">
                                                <label class="control-label">Email *</label>
                                                <div class="form-group pos_rel">
                                                    <input id="email" type="email" class="form-control lgn_input @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Your Email Address">
                                                    <i class="uil uil-envelope lgn_icon"></i>
                                                </div>
                                                @error('email')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                            </div>

                                            <div class="form-group mt-1">
                                                <label class="control-label">Password *</label>
                                                <div class="form-group pos_rel">
                                                    <input id="password" type="password"  class="form-control lgn_input @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Your Password">
                                                    <i class="uil uil-padlock lgn_icon"></i>
                                                </div>
                                                @error('password')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                            </div>

                                            <div class="form-group mt-1">
                                                <label class="control-label">Confirm your Password *</label>
                                                <div class="form-group pos_rel">
                                                    <input id="password-confirm" type="password" class="form-control lgn_input" name="password_confirmation" required autocomplete="new-password" placeholder="Re enter your Password">
                                                    <i class="uil uil-padlock lgn_icon"></i>
                                                </div>
                                            </div>
                                            <div class="form-group mt-5 mb-5">
                                                <hr>
                                            </div>
                                            <div class="form-group mt-1">
                                                <label class="control-label">Country*</label>
                                                <div class="ui search focus">
                                                    <div class="ui left icon input swdh11 swdh19">
                                                        <select class="form-control" name="country_id" id="country_id" required="">
                                                            <option value="" selected class="empty" disabled>Select your Country</option>
                                                            @foreach($countries as $country)
                                                                <option value="{{ $country->id }}" class="{{ $country->iso }}" @if((int)old('country_id') === (int)$country->id) selected @endif>{{ $country->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                @error('country_id')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                            </div>

                                            <div class="form-group mt-1">
                                                <label class="control-label">Parish*</label>
                                                <div class="form-group pos_rel">
                                                    <div class="ui search focus">
                                                        <div class="ui left icon input swdh11 swdh19">
                                                            <select class="form-control" name="parish_id" id="parish_id" required="" data-pre_selected="{{ old('parish_id') }}">
                                                                @foreach($countries as $country)
                                                                    @foreach($country->parishes as $parish)
                                                                        <option value="{{ $parish->id }}" class="selectors {{ $country->iso }}" @if((int)old('parish_id') === (int)$parish->id) selected @endif>{{ $parish->name }}</option>
                                                                    @endforeach
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                @error('parish_id')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                            </div>

                                            <button class="login-btn hover-btn" type="submit">Sign Up Now</button>
                                        </form>
                                    </div>
                                    <div class="signup-link">
                                        <p>I have an account? - <a href="{{ route('login') }}">Sign In Now</a></p>
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
