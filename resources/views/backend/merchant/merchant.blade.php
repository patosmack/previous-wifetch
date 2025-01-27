@extends('frontend.app')


@section('scripts')

{{--    <script>--}}
{{--        $(document).ready(function () {--}}
{{--            var allOptions = $('#parish_id option');--}}
{{--            var selectedOption = $('#parish_id').data('pre_selected');--}}
{{--            $('#parish_id option').remove();--}}
{{--            filterParishes();--}}
{{--            $('#country_id').change(function () {--}}
{{--                selectedOption = null;--}}
{{--                filterParishes();--}}
{{--            });--}}

{{--            function filterParishes() {--}}
{{--                $('#parish_id option').remove()--}}
{{--                var classN = $('#country_id option:selected').prop('class');;--}}
{{--                var opts = allOptions.filter('.' + classN);--}}
{{--                $('<option value="" selected disabled>Select one Parish</option>').appendTo('#parish_id');--}}
{{--                $.each(opts, function (i, j) {--}}
{{--                    $(j).appendTo('#parish_id');--}}
{{--                });--}}
{{--                if(selectedOption){--}}
{{--                    $("#parish_id").val(selectedOption);--}}
{{--                }else{--}}
{{--                    $("#parish_id").val($("#parish_id option:first").val());--}}
{{--                }--}}
{{--            }--}}
{{--        });--}}
{{--    </script>--}}

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

<script>

    $(document).ready(function() {




        var timer;
        var doneTypingInterval = 500;
        $(document).on( "change keyup keydown", ".dynamicEditor", function(e){
            if(e.type === 'keyup') {
                clearTimeout(timer);
                e.preventDefault();
                let obj = this;
                timer = setTimeout(function () {
                    $(obj).trigger('change');
                }, doneTypingInterval);
                return false;
            }else if(e.type === 'keydown'){
                clearTimeout(timer);
            }else{
                let target = $(this).data('target');
                let ref = $(this).data('ref');
                let message = $(this).data('message');
                let val = $(this).val();
                sendRequest(this, target, ref, val, message);
            }
        });

        function sendRequest(element, target, ref, val, message) {
            $(message).removeClass("d-none text-danger text-success text-info");
            $.ajax({
                data:  {target,id:ref, value:val},
                url:   '{{ route('backend.merchant.easy-edit') }}',
                type:  'POST',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                beforeSend: function () {
                    $(message).addClass('text-info').html('Saving').show();
                },
                success:  function (response) {
                    if(response.code === 200){
                        $(message).removeClass("text-info").addClass('text-success').html(response.message).show().delay(3000).fadeOut();
                        if($('#refresh_on_change').is(":checked")){
                            table.ajax.reload();
                        }
                    }else{
                        $(message).removeClass("text-info").addClass('text-danger').html(response.message).show().delay(3000).fadeOut();
                    }
                },
                error: function (response) {
                    $(message).removeClass("text-info").addClass('text-danger').html(response.responseJSON.message).show().delay(3000).fadeOut();
                }
            });
        }

    });
</script>
@endsection

@section('content')

    <div class="wrapper">
        @include('frontend.shared.alert')

        <div class="">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-3 col-md-4">
                        @include('backend.merchant.sidebar')

                    </div>
                    <div class="col-lg-9 col-md-8">
                        <div class="dashboard-right">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="main-title-tab">
                                        <h4>
                                            <a href="{{ route('backend.merchant.list') }}" class="text-dark"><i class="uil uil-arrow-circle-left"></i>Back to merchants</a>
                                            <small class="float-right" style="font-size: 11px"><strong>Status:</strong> {{ trans('status.' . $merchant->status) }}</small>
                                        </h4>



                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12">
                                    <form method="POST" action="{{ route('backend.merchant.update', $merchant->id) }}" enctype="multipart/form-data">
                                        @csrf
                                        <div class="pdpt-bg">
                                            <div class="pdpt-title">
                                                <h4>Merchant Status</h4>
                                            </div>

                                            <div class="address-body">
                                                <div class="sign-form">
                                                    <div class="sign-inner">
                                                        <div class="form-inpts checout-address-step pt-2">
                                                            <div class="row">
                                                                <div class="col-md-3">
                                                                    <label class="control-label">Account status</label>
                                                                    <select name="" id="" data-ref="{{ $merchant->id }}" data-target="status" data-message="#status_message" class="form-control dynamicEditor">
                                                                        <option value="pending" @if($merchant->status === 'pending') selected @endif>{{ trans('status.pending') }}</option>
                                                                        <option value="approved" @if($merchant->status === 'approved') selected @endif>{{ trans('status.approved') }}</option>
                                                                        <option value="cancelled" @if($merchant->status === 'cancelled') selected @endif >{{ trans('status.cancelled') }}</option>
                                                                        <option value="rejected" @if($merchant->status === 'rejected') selected @endif>{{ trans('status.rejected') }}</option>
                                                                    </select>
                                                                    <small id="status_message" class="d-none"></small>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <label class="control-label">Account Enabled</label>
                                                                    <select name="" id="" data-ref="{{ $merchant->id }}" data-target="enabled" data-message="#enabled_message" class="form-control dynamicEditor">
                                                                        <option value="1" @if($merchant->enabled) selected @endif>Yes</option>
                                                                        <option value="0" @if(!$merchant->enabled) selected @endif>No</option>
                                                                    </select>
                                                                    <small id="enabled_message" class="d-none"></small>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <label class="control-label">Allow Custom Items</label>
                                                                    <select name="" id="" data-ref="{{ $merchant->id }}" data-target="allow_custom_items" data-message="#allow_custom_items_message" class="form-control dynamicEditor">
                                                                        <option value="1" @if($merchant->allow_custom_items) selected @endif>Yes</option>
                                                                        <option value="0" @if(!$merchant->allow_custom_items) selected @endif>No</option>
                                                                    </select>
                                                                    <small id="allow_custom_items_message" class="d-none"></small>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <label class="control-label">Is Featured</label>
                                                                    <select name="" id="" data-ref="{{ $merchant->id }}" data-target="featured" data-message="#featured_message" class="form-control dynamicEditor">
                                                                        <option value="1" @if($merchant->featured) selected @endif>Yes</option>
                                                                        <option value="0" @if(!$merchant->featured) selected @endif>No</option>
                                                                    </select>
                                                                    <small id="featured_message" class="d-none"></small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="pdpt-title">
                                                <h4>General Information</h4>
                                            </div>
                                            <div class="address-body">
                                                <div class="sign-form">
                                                    <div class="sign-inner">
                                                        <div class="form-inpts checout-address-step pt-2">
                                                            <div class="row">
                                                                <div class="col-md-6">
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
                                                                    <label class="control-label">Category</label>
                                                                    <select name="category_id" id="" class="form-control">
                                                                        @foreach($categories as $category)
                                                                        <option value="1" @if($merchant->category_id === $category->id) selected @endif> {{ $category->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    <small id="featured_message" class="d-none"></small>
                                                                </div>

{{--                                                                <div class="col-md-6">--}}
{{--                                                                    <div class="form-group mt-1">--}}
{{--                                                                        <label class="control-label">Seo Friendly URL *</label>--}}
{{--                                                                        <div class="form-group pos_rel">--}}
{{--                                                                            <input id="name" type="text" class="form-control lgn_input @error('friendly_url') is-invalid @enderror" name="friendly_url" value="{{ old('friendly_url', $merchant->friendly_url) }}" required autocomplete="friendly_url" autofocus placeholder="Seo Friendly URL">--}}
{{--                                                                            <i class="uil uil-home lgn_icon"></i>--}}
{{--                                                                        </div>--}}
{{--                                                                        @error('friendly_url')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror--}}
{{--                                                                    </div>--}}
{{--                                                                </div>--}}


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
                                                <h4>Notification Information</h4>
                                            </div>
                                            <div class="address-body">
                                                <div class="sign-form">
                                                    <div class="sign-inner">
                                                        <div class="form-inpts checout-address-step pt-2">
                                                            <div class="row">

                                                                <div class="col-md-6">
                                                                    <div class="form-group mt-1">
                                                                        <label class="control-label">Notification Email </label>
                                                                        <div class="form-group pos_rel">
                                                                            <input id="notification_email" type="email" class="form-control lgn_input @error('notification_email') is-invalid @enderror" name="notification_email" value="{{ old('notification_email', $merchant->notification_email) }}" placeholder="Notification Email">
                                                                            <i class="uil uil-envelope lgn_icon"></i>
                                                                        </div>
                                                                        @error('notification_email')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <div class="form-group mt-1">
                                                                        <label class="control-label">Notification Phone </label>
                                                                        <div class="form-group pos_rel">
                                                                            <input id="notification_phone" type="text" class="form-control lgn_input @error('notification_phone') is-invalid @enderror" name="notification_phone" value="{{ old('notification_phone', $merchant->notification_phone) }}" placeholder="Notification Phone">
                                                                            <i class="uil uil-phone lgn_icon"></i>
                                                                        </div>
                                                                        @error('notification_phone')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="pdpt-title">
                                                <h4>Pricing</h4>
                                            </div>
                                            <div class="address-body">
                                                <div class="sign-form">
                                                    <div class="sign-inner">
                                                        <div class="form-inpts checout-address-step pt-2">
                                                            <div class="row">

                                                                <div class="col-md-6">
                                                                    <div class="form-group mt-1">
                                                                        <label class="control-label">Delivery Fee</label>
                                                                        <div class="form-group pos_rel">
                                                                            <input id="delivery_fee" type="number" step="0.01" min="0" class="form-control lgn_input @error('delivery_fee') is-invalid @enderror" name="delivery_fee" value="{{ old('delivery_fee', $merchant->delivery_fee) }}" placeholder="Flat Delivery Fee">
                                                                            <i class="uil uil-dollar-sign lgn_icon"></i>
                                                                        </div>
                                                                        @error('notification_email')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <div class="form-group mt-1">
                                                                        <label class="control-label">Service Fee </label>
                                                                        <div class="form-group pos_rel">
                                                                            <input id="service_fee" type="number" step="0.01" min="0"  class="form-control lgn_input @error('service_fee') is-invalid @enderror" name="service_fee" value="{{ old('service_fee', $merchant->service_fee) }}" placeholder="% Service Fee of the order total">
                                                                            <i class="uil uil-percentage lgn_icon"></i>
                                                                        </div>
                                                                        @error('service_fee')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
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
