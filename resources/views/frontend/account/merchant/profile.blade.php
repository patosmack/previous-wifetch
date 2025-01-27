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

            filterParishes();
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
        @include('frontend.shared.alert')

        <div class="">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 col-md-4">
                        @include('frontend.account.merchant.sidebar')
                    </div>
                    <div class="col-lg-9 col-md-8">
                        <div class="dashboard-right">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="main-title-tab">
                                        <h4><i class="uil uil-home"></i>Shop Information <small class="float-right" style="font-size: 11px"><strong>Status:</strong> {{ trans('status.' . $merchant->status) }}</small></h4>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12">
                                    <form method="POST" action="{{ route('account.merchant.profile.store') }}" enctype="multipart/form-data">
                                        <input type="hidden" name="merchant_id" id="merchant_id" value="{{ $merchant->id }}" readonly required>
                                        @csrf
                                        <div class="pdpt-bg">
                                            <div class="pdpt-title">
                                                <h4>General Information</h4>
                                            </div>
                                            <div class="address-body">
                                                <div class="sign-form">
                                                    <div class="sign-inner">
                                                        <div class="form-inpts checout-address-step pt-2">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="form-group mt-1">
                                                                        <label class="control-label">Name *</label>
                                                                        <div class="form-group pos_rel">
                                                                            <input id="name" type="text" class="form-control lgn_input @error('name') is-invalid @enderror" name="name" value="{{ old('name', $merchant->name) }}" required autocomplete="name" autofocus placeholder="Business Name">
                                                                            <i class="uil uil-home lgn_icon"></i>
                                                                        </div>
                                                                        @error('name')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                                                    </div>
                                                                </div>


                                                                <div class="col-md-6">
                                                                    <div class="form-group mt-1">
                                                                        <label class="control-label">Country*</label>
                                                                        <div class="ui search focus">
                                                                            <div class="ui left icon input swdh11 swdh19">
                                                                                <select class="form-control" name="country_id" id="country_id" required="">
                                                                                    <option value="" selected class="empty" disabled>Select Business Country</option>
                                                                                    @foreach($countries as $country)
                                                                                        <option value="{{ $country->id }}" class="{{ $country->iso }}" @if((int)old('country_id', $merchant->country_id) === (int)$country->id) selected @endif>{{ $country->name }}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        @error('country_id')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group mt-1">
                                                                        <label class="control-label">Parish*</label>
                                                                        <div class="ui search focus">
                                                                            <div class="ui left icon input swdh11 swdh19">
                                                                                <select class="form-control" name="parish_id" id="parish_id" data-pre_selected="{{ old('parish_id', $merchant->parish_id) }}" required="">
                                                                                    @foreach($countries as $country)
                                                                                        @foreach($country->parishes as $parish)
                                                                                            <option value="{{ $parish->id }}" class="selectors {{ $country->iso }}" @if((int)old('parish_id', $merchant->parish_id) === (int)$parish->id) selected @endif>{{ $parish->name }}</option>
                                                                                        @endforeach
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        @error('parish_id')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group mt-1">
                                                                        <label class="control-label">Address</label>
                                                                        <div class="form-group pos_rel">
                                                                            <input id="address" type="text" class="form-control lgn_input @error('address') is-invalid @enderror" name="address" value="{{ old('address', $merchant->address) }}" placeholder="Business Address">
                                                                            <i class="uil uil-map-marker lgn_icon"></i>
                                                                        </div>
                                                                        @error('address')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <div class="form-group mt-1">
                                                                        <label class="control-label">Public Phone </label>
                                                                        <div class="form-group pos_rel">
                                                                            <input id="phone" type="text" class="form-control lgn_input @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone', $merchant->phone) }}" placeholder="Business Phone">
                                                                            <i class="uil uil-phone lgn_icon"></i>
                                                                        </div>
                                                                        @error('phone')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <div class="form-group mt-1">
                                                                        <label class="control-label">Public Email </label>
                                                                        <div class="form-group pos_rel">
                                                                            <input id="email" type="email" class="form-control lgn_input @error('email') is-invalid @enderror" name="email" value="{{ old('email', $merchant->email) }}" placeholder="Business Email">
                                                                            <i class="uil uil-envelope lgn_icon"></i>
                                                                        </div>
                                                                        @error('email')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <div class="form-group mt-1">
                                                                        <label class="control-label">Allow Users to request unlisted products?</label>
                                                                        <div class="form-check">
                                                                            <input type="checkbox" name="allow_custom_items" value="yes" @if(old('allow_custom_items', $merchant->allow_custom_items)) checked @endif class="form-check-input" id="allow_custom_items">
                                                                            <label class="form-check-label" for="allow_custom_items">Yes, allow it</label>
                                                                        </div>
                                                                        @error('email')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-12">
                                                                    <hr>
                                                                </div>

                                                                <div class="col-md-12">
                                                                    <div class="form-group mt-1">
                                                                        <div class="field">
                                                                            <label class="control-label">Business Disclaimer</label>
                                                                            <textarea rows="6" class="form-control" id="disclaimer" name="disclaimer" placeholder="Add a business disclaimer">{{ old('disclaimer', $merchant->disclaimer) }}</textarea>
                                                                        </div>
                                                                        @error('disclaimer')<span class="invalid-feedback pt-2" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <div class="form-group mt-1 mb-2">
                                                                        <img src="{{ $merchant->logo ? asset($merchant->logo) : asset('assets/common/image_placeholder.png')}}" alt="{{ $merchant->name }}" class="img-fluid" width="130">
                                                                        <label for="attachment" class="control-label">@if($merchant->logo )Edit business Logo @else Upload business logo* @endif</label>
                                                                    </div>
                                                                    <input type="file" class="form-control-file" name="attachment" id="attachment">
                                                                    @error('attachment')<span class="invalid-feedback pt-2" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group mt-1">
                                                                        <div class="field">
                                                                            <label class="control-label">Business Description</label>
                                                                            <textarea rows="6" class="form-control" id="description" name="description" placeholder="Please describe the nature of the business">{{ old('description', $merchant->description) }}</textarea>
                                                                        </div>
                                                                        @error('description')<span class="invalid-feedback pt-2" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <hr>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="pdpt-title">
                                                <h4>Contact Information</h4>
                                            </div>
                                            <div class="address-body">
                                                <div class="sign-form">
                                                    <div class="sign-inner">
                                                        <div class="form-inpts checout-address-step pt-2">
                                                            <div class="row">

                                                                <div class="col-md-12">
                                                                    <div class="form-group mt-1">
                                                                        <label class="control-label">Contact Name </label>
                                                                        <div class="form-group pos_rel">
                                                                            <input id="contact_name" type="text" class="form-control lgn_input @error('contact_name') is-invalid @enderror" name="contact_name" value="{{ old('contact_name', $merchant->contact_name) }}" placeholder="Contact Name">
                                                                            <i class="uil uil-user-circle lgn_icon"></i>
                                                                        </div>
                                                                        @error('contact_name')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-12">
                                                                    <div class="form-group mt-1">
                                                                        <label class="control-label">Contact Email </label>
                                                                        <div class="form-group pos_rel">
                                                                            <input id="contact_email" type="email" class="form-control lgn_input @error('contact_email') is-invalid @enderror" name="contact_email" value="{{ old('contact_email', $merchant->contact_email) }}" placeholder="Contact Email">
                                                                            <i class="uil uil-envelope lgn_icon"></i>
                                                                        </div>
                                                                        @error('contact_email')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-12">
                                                                    <div class="form-group mt-1">
                                                                        <label class="control-label">Contact Phone </label>
                                                                        <div class="form-group pos_rel">
                                                                            <input id="contact_phone" type="text" class="form-control lgn_input @error('contact_phone') is-invalid @enderror" name="contact_phone" value="{{ old('contact_phone', $merchant->contact_phone) }}" placeholder="Contact Phone">
                                                                            <i class="uil uil-phone lgn_icon"></i>
                                                                        </div>
                                                                        @error('contact_phone')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <hr>
                                                                </div>
                                                                <div class="col-md-12 text-center mb-4">
                                                                    <button class="next-btn16 hover-btn mt-3" type="submit" data-btntext-sending="Sending...">Save</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
