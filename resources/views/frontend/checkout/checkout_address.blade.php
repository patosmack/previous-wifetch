@extends('frontend.app')

@section('styles')
    <link href="{{ asset('css/step-wizard.css') }}" rel="stylesheet">
@endsection

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

            pickerFill($('.address_picker_selected'));

            $('.address_picker').change(function () {
                pickerFill(this);
            });


            function pickerFill(obj) {
                let old = $(obj).data('old');
                if(!old){
                    let name = $(obj).data('name');
                    let countryid = $(obj).data('countryid');
                    let parishid = $(obj).data('parishid');
                    let address = $(obj).data('address');
                    let phone = $(obj).data('phone');
                    let instructions = $(obj).data('instructions');

                    $('#name').val(name);
                    $('#address').val(address);
                    $('#phone').val(phone);
                    $('#instructions').val(instructions);

                    $("#country_id").val(countryid);
                    filterParishes();
                    setTimeout(function() {
                        $("#parish_id").val(parishid);
                    }, 5);
                }
            }

        });
    </script>

@endsection

@section('content')

    <div class="theme-Breadcrumb">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb pl-1 pl-sm-0">
                            <li class="breadcrumb-item d-none d-md-block"><a href="{{ route('categories.list') }}">Categories</a> /</li>
                            <li class="breadcrumb-item"><a href="{{ route('checkout.merchants') }}">Merchants</a> /</li>
                            <li class="breadcrumb-item">Checkout /</li>
                            <li class="breadcrumb-item active">Address</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <div class="wrapper-breadcrumb">
        @include('frontend.shared.alert')
        <div class="all-product-grid">
            <div class="container">

                <div class="row">
                    <div class="col-lg-8 col-md-7">
                        <div id="checkout_wizard" class="checkout accordion left-chck145">
                            <div class="checkout-step">
                                <div class="checkout-card" id="headingAddress">
                                    <span class="checkout-step-number">1</span>
                                    <h4 class="checkout-step-title">
                                        <button class="wizard-btn" type="button"> Delivery Address</button>
                                    </h4>
                                </div>
                                <div class="checkout-step-body">
                                    <div class="checout-address-step">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <form action="{{ route('checkout.address.store', $merchant->friendly_url) }}" method="POST">
                                                    <input type="hidden" value="{{ $merchant->id }}" name="merchant_id">
                                                    @csrf
                                                    <div class="form-group">
                                                        <div class="product-radio">
                                                            <ul class="product-now">
                                                                @foreach($userAccount->addresses as $userAddressItem)
                                                                    <li>

                                                                        <input
                                                                            class="address_picker
                                                                            @if(old('user_address_id'))
                                                                                @if(old('user_address_id') === $userAddressItem->id)
                                                                                    address_picker_selected
                                                                                @endif
                                                                            @else
                                                                                @if($userAddressItem->current)
                                                                                    address_picker_selected
                                                                                @endif
                                                                            @endif
                                                                            "
                                                                            type="radio"
                                                                            id="address-{{ $userAddressItem->id }}"
                                                                            name="user_address_id"
                                                                            data-old="{{ !!old('merchant_id') }}"
                                                                            value="{{ $userAddressItem->id }}"
                                                                            data-name="{{ ($userAddressItem->name  && strlen(trim($userAddressItem->name) )) > 0 ? $userAddressItem->name : 'Primary' }}"
                                                                            data-countryid="{{ $userAddressItem->country_id ? $userAddressItem->country_id : ( $userAddressItem->parish ? $userAddressItem->parish->country_id : '' ) }}"
                                                                            data-parishid="{{ $userAddressItem->parish ? $userAddressItem->parish->id : '' }}"
                                                                            data-address="{{ $userAddressItem->address }}"
                                                                            data-phone="{{ $userAddressItem->phone ? $userAddressItem->phone : $userAccount->phone }}"
                                                                            data-instructions="{{ $userAddressItem->instructions }}"

                                                                            @if(old('user_address_id'))
                                                                                @if(old('user_address_id') === $userAddressItem->id)
                                                                                    checked
                                                                                @endif
                                                                            @else
                                                                                @if($userAddressItem->current)
                                                                                    checked
                                                                                @endif
                                                                            @endif
                                                                        >
                                                                        <label for="address-{{ $userAddressItem->id }}">{{ ($userAddressItem->name  && strlen(trim($userAddressItem->name) )) > 0 ? $userAddressItem->name : 'Primary' }}</label>
                                                                    </li>
                                                                @endforeach
                                                                <li>
                                                                    <input
                                                                        class="address_picker"
                                                                        type="radio"
                                                                        id="address-new"
                                                                        value=""
                                                                        name="user_address_id"
                                                                        data-name=""
                                                                        data-country=""
                                                                        data-countryid=""
                                                                        data-parish=""
                                                                        data-parishid=""
                                                                        data-address=""
                                                                        data-phone=""
                                                                        data-instructions=""
                                                                    >
                                                                    <label for="address-new" class="bg-success">New address</label>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="address-fieldset">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="control-label">Name*</label>
                                                                    <input id="name" name="name" value="{{ old('name') }}" type="text" placeholder="E.g. 'Home', 'Work' or 'Office'" class="form-control input-md" required="">
                                                                    @error('name')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                                                </div>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="control-label">Phone*</label>
                                                                    <input id="phone" name="phone" value="{{ old('phone') }}" type="text" placeholder="Your contact phone number" class="form-control input-md" required="">
                                                                    @error('phone')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                                                </div>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <div class="form-group">
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
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="control-label">Parish*</label>
                                                                    <div class="ui search focus">
                                                                        <div class="ui left icon input swdh11 swdh19">
                                                                            <select class="form-control" name="parish_id" id="parish_id" data-pre_selected="{{ old('parish_id') }}" required="">
                                                                                @foreach($countries as $country)
                                                                                    @foreach($country->parishes as $parish)
                                                                                        <option value="{{ $parish->id }}" class="selectors {{ $country->iso }}" @if((int)old('parish_id') === (int)$parish->id) selected @endif>{{ $parish->name }}</option>
                                                                                    @endforeach
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    @error('parish_id')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                                                </div>
                                                            </div>

                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label class="control-label">Address*</label>
                                                                    <input id="address" name="address" value="{{ old('address') }}" type="text" placeholder="Delivery Address" class="form-control input-md" required="">
                                                                    @error('address')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                                                </div>
                                                            </div>

                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label class="control-label">Instructions</label>
                                                                    <textarea class="form-control" name="instructions" id="instructions" placeholder="Some tips to find your address location"  rows="3">{{ old('instructions') }}</textarea>
                                                                </div>
                                                            </div>


                                                            @if($merchantCart['merchant']->disclaimer && strlen($merchantCart['merchant']->disclaimer) > 0)
                                                            <div class="col-lg-12 col-md-12 mt-2">
                                                                <h6 class="mt-0 pt-0 font-weight-bold">Disclaimer</h6>
                                                                <p style="font-size: 12px">{{ $merchantCart['merchant']->disclaimer }}</p>
                                                            </div>
                                                            @endif

                                                            <div class="col-lg-12 col-md-12">
                                                                <div class="form-group">
                                                                    <div class="address-btns">
                                                                        {{--                                                                                <button class="save-btn14 hover-btn">Save</button>--}}
                                                                        <button type="submit" class="ml-auto next-btn16 hover-btn"> Next </button>
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
                    <div class="col-lg-4 col-md-5">
                        @include('frontend.checkout.checkout_sidebar')
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
