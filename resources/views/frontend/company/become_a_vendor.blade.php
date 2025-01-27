@extends('frontend.app')

@section('styles')

@endsection

@section('scripts')

{{--    <script>--}}
{{--        $(document).ready(function () {--}}
{{--            var allOptions = $('#parish_id option');--}}
{{--            $('#parish_id option').remove();--}}
{{--            $('<option value="" selected disabled>Select one Country first</option>').appendTo('#parish_id');--}}
{{--            $('#country_id').change(function () {--}}
{{--                $('#parish_id option').remove()--}}
{{--                var classN = $('#country_id option:selected').prop('class');;--}}
{{--                var opts = allOptions.filter('.' + classN);--}}
{{--                $.each(opts, function (i, j) {--}}
{{--                    $(j).appendTo('#parish_id');--}}
{{--                });--}}
{{--                $("#parish_id").val($("#parish_id option:first").val());--}}
{{--            });--}}
{{--        });--}}
{{--    </script>--}}

<script>
    $(document).ready(function () {
        var allOptions = $('#parish_id option');
        var selectedOption = $('#parish_id').data('pre_selected');
        $('#parish_id option').remove();
        filterParishes();
        $('#country_id').change(function () {
            selectedOption = null;
            filterParishes();
        });

        function filterParishes() {
            $('#parish_id option').remove()
            var classN = $('#country_id option:selected').prop('class');;
            var opts = allOptions.filter('.' + classN);
            $('<option value="" selected disabled>Select one Parish</option>').appendTo('#parish_id');
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
        @include('frontend.shared.alert')
        <div class="all-product-grid">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 offset-md-3">
                        <div class="contact-title">
                            <h2>Become A Merchant</h2>
                            <p>Start Selling On WiFetch</p>
                        </div>

                        <div class="contact-form">
                            <form  method="POST" action="{{ route('company.become_a_vendor.store') }}" enctype="multipart/form-data">
                                @csrf

                                <div class="form-group mt-1">
                                    <label class="control-label">Category*</label>
                                    <div class="ui search focus">
                                        <div class="ui left icon input swdh11 swdh19">
                                            <select class="form-control" name="category_id" id="category_id" required="">
                                                <option value="" selected disabled >Select your Category</option>
                                                @foreach($categories as $categoryItem)
                                                    <option value="{{ $categoryItem->id }}" @if((int)old('category_id') === (int)$categoryItem->id) selected @endif>{{ $categoryItem->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <hr>

                                <div class="form-group mt-1">
                                    <label class="control-label">Business Name*</label>
                                    <div class="ui search focus">
                                        <div class="ui left icon input swdh11 swdh19">
                                            <input class="prompt srch_explore" type="text" name="name" id="name" value="{{ old('name') }}" required="" placeholder="The name of your business">
                                        </div>
                                    </div>
                                </div>


                                <div class="form-group mt-1">
                                    <label class="control-label">Business Phone Number</label>
                                    <div class="ui search focus">
                                        <div class="ui left icon input swdh11 swdh19">
                                            <input class="prompt srch_explore" type="text" name="phone" id="phone" value="{{ old('phone') }}" placeholder="Phone for contacting your Business">
                                        </div>
                                    </div>
                                </div>

                                @if(Auth::user())
                                    <div class="form-group mt-1">
                                        <label class="control-label">Business Email Address*</label>
                                        <div class="ui search focus">
                                            <div class="ui left icon input swdh11 swdh19">
                                                <input class="prompt srch_explore" type="email" name="email" id="email" value="{{ old('email') }}" required="" placeholder="Email for contacting your Business">
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <hr>
                                    <div class="form-group mt-1">
                                        <label class="control-label">Account Email Address*</label>
                                        <div class="ui search focus">
                                            <div class="ui left icon input swdh11 swdh19">
                                                <input class="prompt srch_explore" type="email" name="email" id="email" value="{{ old('email') }}" required="" placeholder="Email for contacting your Business">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mt-1">
                                        <label class="control-label">Account Password *</label>
                                        <div class="ui search focus">
                                            <div class="ui left icon input swdh11 swdh19">
                                                <input id="password" type="password"  class="form-control lgn_input @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Your Password">
                                            </div>
                                        </div>
                                        @error('password')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                    </div>

                                    <div class="form-group mt-1">
                                        <label class="control-label">Confirm your Password *</label>
                                        <div class="ui search focus">
                                            <div class="ui left icon input swdh11 swdh19">
                                                <input id="password-confirm" type="password" class="form-control lgn_input" name="password_confirmation" required autocomplete="new-password" placeholder="Re enter your Password">
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                @endif

                                <div class="form-group mt-1">
                                    <div class="field">
                                        <label class="control-label">Business Description*</label>
                                        <textarea rows="2" class="form-control" id="description" name="description" required="" placeholder="Please describe the nature of your business">{{ old('description') }}</textarea>
                                    </div>
                                </div>
                                <hr>
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
                                </div>

                                <div class="form-group mt-1">
                                    <label class="control-label">Parish*</label>
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

                                <div class="form-group mt-1">
                                    <label class="control-label">Address*</label>
                                    <div class="ui search focus">
                                        <div class="ui left icon input swdh11 swdh19">
                                            <input class="prompt srch_explore" type="text" name="address" id="address" value="{{ old('address') }}" required="" placeholder="Your Address">
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group mt-1">
                                    <label class="control-label">Internal Contact Name*</label>
                                    <div class="ui search focus">
                                        <div class="ui left icon input swdh11 swdh19">
                                            <input class="prompt srch_explore" type="text" name="contact_name" id="contact_name" value="{{ old('contact_name') }}" required="" placeholder="Name of the Contact Person">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mt-1">
                                    <label class="control-label">Internal Contact Email Address*</label>
                                    <div class="ui search focus">
                                        <div class="ui left icon input swdh11 swdh19">
                                            <input class="prompt srch_explore" type="email" name="contact_email" id="contact_email" value="{{ old('contact_email') }}" required="" placeholder="Email Address for us to contact">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mt-1">
                                    <label class="control-label">Internal Contact Phone</label>
                                    <div class="ui search focus">
                                        <div class="ui left icon input swdh11 swdh19">
                                            <input class="prompt srch_explore" type="text" name="contact_phone" id="contact_phone" value="{{ old('contact_phone') }}"  placeholder="Phone Number for us to contact">
                                        </div>
                                    </div>
                                </div>

                                <hr>

                                <div class="form-group">
                                    <label class="control-label" for="attachment">Upload your logo</label>
                                    <input type="file" class="form-control-file" id="attachment" name="attachment" accept="image/*">
                                </div>

                                <button class="next-btn16 hover-btn mt-3" type="submit" data-btntext-sending="Sending...">Submit Request</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
