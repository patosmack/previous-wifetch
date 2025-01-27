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

            $('.address_picker').change(function () {

                let name = $(this).data('name');
                let countryid = $(this).data('countryid');
                let parishid = $(this).data('parishid');
                let address = $(this).data('address');
                let phone = $(this).data('phone');
                let instructions = $(this).data('instructions');

                $('#name').val(name);
                $('#address').val(address);
                $('#phone').val(phone);
                $('#instructions').val(instructions);

                $("#country_id").val(countryid);
                filterParishes();
                setTimeout(function() {
                    $("#parish_id").val(parishid);
                }, 5);


            });
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
                            <li class="breadcrumb-item d-none d-md-block"><a href="{{ route('checkout.merchants') }}">Merchants</a> /</li>
                            <li class="breadcrumb-item active" aria-current="page">Checkout</li>

                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    @if(!env('APP_DEBUG'))
        <div class="wrapper-breadcrumb">
            @include('frontend.shared.alert')
        </div>
    @else

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
                                            <button class="wizard-btn collapsed" type="button" data-toggle="collapse" data-target="#collapseAddress" aria-expanded="true" aria-controls="collapseAddress"> Delivery Address</button>
                                        </h4>
                                    </div>
                                    <div id="collapseAddress" class="collapse show" aria-labelledby="headingAddress" data-parent="#checkout_wizard">
                                        <div class="checkout-step-body">
                                            <div class="checout-address-step">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <form class="">
                                                            <!-- Multiple Radios (inline) -->
                                                            <div class="form-group">
                                                                <div class="product-radio">
                                                                    <ul class="product-now">
                                                                        @foreach($userAccount->addresses as $userAddressItem)
                                                                        <li>
                                                                            <input
                                                                                class="address_picker"
                                                                                type="radio"
                                                                                id="address-{{ $userAddressItem->id }}"
                                                                                name="user_address_id"
                                                                                value="{{ $userAddressItem->id }}"
                                                                                data-name="{{ ($userAddressItem->name  && strlen(trim($userAddressItem->name) )) > 0 ? $userAddressItem->name : 'Primary' }}"
                                                                                data-countryid="{{ $userAddressItem->country_id ? $userAddressItem->country_id : ( $userAddressItem->parish ? $userAddressItem->parish->country_id : '' ) }}"
                                                                                data-parishid="{{ $userAddressItem->parish ? $userAddressItem->parish->id : '' }}"
                                                                                data-address="{{ $userAddressItem->address }}"
                                                                                data-phone="{{ $userAddressItem->phone }}"
                                                                                data-instructions="{{ $userAddressItem->instructions }}"
                                                                                @if(old('address_id', ($userAddressItem->current ? $userAddressItem->id : null)) === $userAddressItem->id) checked @endif
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
                                                                            <input id="name" name="name" value="{{ old('name') }}" type="text" placeholder="E.g. 'Home', 'Work' or 'Office'" class="form-control input-md" required="" >
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="control-label">Phone*</label>
                                                                            <input id="phone" name="phone" value="{{ old('phone') }}" type="text" placeholder="Your contact phone number" class="form-control input-md" required="">
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
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
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
                                                                    </div>

                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label class="control-label">Address*</label>
                                                                            <input id="address" name="address" value="{{ old('address') }}" type="text" placeholder="Delivery Address" class="form-control input-md" required="">
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label class="control-label">Instructions</label>
                                                                            <textarea class="form-control" name="instructions" id="instructions" placeholder="Some tips to find your address location"  rows="3">{{ old('instructions') }}</textarea>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-lg-12 col-md-12">
                                                                        <div class="form-group">
                                                                            <div class="address-btns">
{{--                                                                                <button class="save-btn14 hover-btn">Save</button>--}}
                                                                                <a class="collapsed ml-auto next-btn16 hover-btn" role="button" data-toggle="collapse" data-parent="#checkout_wizard" href="#collapseThree"> Next </a>
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
                                <div class="checkout-step">
                                    <div class="checkout-card" id="headingThree">
                                        <span class="checkout-step-number">2</span>
                                        <h4 class="checkout-step-title">
                                            <button class="wizard-btn collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree"> Delivery Time & Date </button>
                                        </h4>
                                    </div>
                                    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#checkout_wizard">
                                        <div class="checkout-step-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="control-label">Select Date and Time*</label>
                                                        <div class="date-slider-group">
                                                            <div class="owl-carousel date-slider owl-theme">
                                                                <div class="item">
                                                                    <div class="date-now">
                                                                        <input type="radio" id="dd1" name="address1" checked="">
                                                                        <label for="dd1">Today</label>
                                                                    </div>
                                                                </div>
                                                                <div class="item">
                                                                    <div class="date-now">
                                                                        <input type="radio" id="dd2" name="address1">
                                                                        <label for="dd2">Tomorrow</label>
                                                                    </div>
                                                                </div>
                                                                <div class="item">
                                                                    <div class="date-now">
                                                                        <input type="radio" id="dd3" name="address1">
                                                                        <label for="dd3">10 May 2020</label>
                                                                    </div>
                                                                </div>
                                                                <div class="item">
                                                                    <div class="date-now">
                                                                        <input type="radio" id="dd4" name="address1">
                                                                        <label for="dd4">11 May 2020</label>
                                                                    </div>
                                                                </div>
                                                                <div class="item">
                                                                    <div class="date-now">
                                                                        <input type="radio" id="dd5" name="address1">
                                                                        <label for="dd5">12 May 2020</label>
                                                                    </div>
                                                                </div>
                                                                <div class="item">
                                                                    <div class="date-now">
                                                                        <input type="radio" id="dd6" name="address1">
                                                                        <label for="dd6">13 May 2020</label>
                                                                    </div>
                                                                </div>
                                                                <div class="item">
                                                                    <div class="date-now">
                                                                        <input type="radio" id="dd7" name="address1">
                                                                        <label for="dd7">14 May 2020</label>
                                                                    </div>
                                                                </div>
                                                                <div class="item">
                                                                    <div class="date-now">
                                                                        <input type="radio" id="dd8" name="address1">
                                                                        <label for="dd8">15 May 2020</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="time-radio">
                                                            <div class="ui form">
                                                                <div class="grouped fields">
                                                                    <div class="field">
                                                                        <div class="ui radio checkbox chck-rdio">
                                                                            <input type="radio" name="timeframe" value="1" checked="" tabindex="0" class="hidden">
                                                                            <label>8.00AM - 12.00AM</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="field">
                                                                        <div class="ui radio checkbox chck-rdio">
                                                                            <input type="radio"name="timeframe" value="2" tabindex="0" class="hidden">
                                                                            <label>12.00AM - 18.00PM</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <a class="collapsed next-btn16 hover-btn" role="button" data-toggle="collapse"  href="#collapseFour"> Proccess to payment </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="checkout-step">
                                    <div class="checkout-card" id="headingFour">
                                        <span class="checkout-step-number">3</span>
                                        <h4 class="checkout-step-title">
                                            <button class="wizard-btn collapsed" type="button" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">Payment</button>
                                        </h4>
                                    </div>
                                    <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#checkout_wizard">
                                        <div class="checkout-step-body">
                                            <div class="payment_method-checkout">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="rpt100">
                                                            <ul class="radio--group-inline-container_1">
                                                                <li>
                                                                    <div class="radio-item_1">
                                                                        <input id="cashondelivery1" value="cashondelivery" name="paymentmethod" type="radio" data-minimum="50.0">
                                                                        <label for="cashondelivery1" class="radio-label_1">Cash on Delivery</label>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <div class="radio-item_1">
                                                                        <input id="card1" value="card" name="paymentmethod" type="radio" data-minimum="50.0">
                                                                        <label  for="card1" class="radio-label_1">Credit / Debit Card</label>
                                                                    </div>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <div class="form-group return-departure-dts" data-method="cashondelivery">
                                                            <div class="row">
                                                                <div class="col-lg-12">
                                                                    <div class="pymnt_title">
                                                                        <h4>Cash on Delivery</h4>
                                                                        <p>Cash on Delivery will not be available if your order value exceeds $10.</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group return-departure-dts" data-method="card">
                                                            <div class="row">
                                                                <div class="col-lg-12">
                                                                    <div class="pymnt_title mb-4">
                                                                        <h4>Credit / Debit Card</h4>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6">
                                                                    <div class="form-group mt-1">
                                                                        <label class="control-label">Holder Name*</label>
                                                                        <div class="ui search focus">
                                                                            <div class="ui left icon input swdh11 swdh19">
                                                                                <input class="prompt srch_explore" type="text" name="holdername" value="" id="holder[name]" required="" maxlength="64" placeholder="Holder Name">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6">
                                                                    <div class="form-group mt-1">
                                                                        <label class="control-label">Card Number*</label>
                                                                        <div class="ui search focus">
                                                                            <div class="ui left icon input swdh11 swdh19">
                                                                                <input class="prompt srch_explore" type="text" name="cardnumber" value="" id="card[number]" required="" maxlength="64" placeholder="Card Number">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-4">
                                                                    <div class="form-group mt-1">
                                                                        <label class="control-label">Expiration Month*</label>
                                                                        <select class="ui fluid search dropdown form-dropdown" name="card[expire-month]">
                                                                            <option value="">Month</option>
                                                                            <option value="1">January</option>
                                                                            <option value="2">February</option>
                                                                            <option value="3">March</option>
                                                                            <option value="4">April</option>
                                                                            <option value="5">May</option>
                                                                            <option value="6">June</option>
                                                                            <option value="7">July</option>
                                                                            <option value="8">August</option>
                                                                            <option value="9">September</option>
                                                                            <option value="10">October</option>
                                                                            <option value="11">November</option>
                                                                            <option value="12">December</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-4">
                                                                    <div class="form-group mt-1">
                                                                        <label class="control-label">Expiration Year*</label>
                                                                        <div class="ui search focus">
                                                                            <div class="ui left icon input swdh11 swdh19">
                                                                                <input class="prompt srch_explore" type="text" name="card[expire-year]" maxlength="4" placeholder="Year">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-4">
                                                                    <div class="form-group mt-1">
                                                                        <label class="control-label">CVV*</label>
                                                                        <div class="ui search focus">
                                                                            <div class="ui left icon input swdh11 swdh19">
                                                                                <input class="prompt srch_explore" name="card[cvc]" maxlength="3" placeholder="CVV">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <a href="#" class="next-btn16 hover-btn">Place Order</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-5">
                            <div class="pdpt-bg mt-0">
                                <div class="pdpt-title">
                                    <div class="cart-top-title pt-3">
                                        @if($merchantCart['merchant']->logo)<img src="{{ asset($merchantCart['merchant']->logo) }}" alt="{{ $merchantCart['merchant']->name }}" width="50" class="cart-top-logo ml-2 mr-2">@endif
                                        <h3 class="mt-0 mb-0"><strong>{{ $merchantCart['merchant']->name }}</strong></h3>
                                    </div>
                                    <hr class="pt-1 pb-1">
                                    <h4 class="mt-0 pt-0">Order Summary</h4>
                                </div>
                                <div class="right-cart-dt-body">
                                    @foreach($merchantCart['items'] as $merchantCartItems)
                                        <div class="cart-item border_radius">
                                            <div class="cart-product-img">
                                                <img src="{{ asset('assets/common/ajax-loader-canvas.gif') }}"  data-src="{{ $merchantCartItems->product->image ? asset($merchantCartItems->product->image) : asset('assets/common/image_placeholder.png')}}" alt="{{ $merchantCartItems->product->name }}" class="lazy">
                                                @if($merchantCartItems->product->hasDiscount)
                                                    <div class="offer-badge">{{ $merchantCartItems->product->discount }}% OFF</div>
                                                @endif
                                            </div>
                                            <div class="cart-text">
                                                <h4 style="width: 80%">{{ $merchantCartItems->product->name }}</h4>
                                                <div class="qty-group">
                                                    x {{ $merchantCartItems->quantity }}
                                                    @if($merchantCartItems->orderPrice > 0)
                                                        <div class="cart-item-price">$ {{ $merchantCartItems->orderPrice }} @if($merchantCartItems->product->hasDiscount)<span>$ {{ $merchantCartItems->originalOrderPrice }}</span>@endif</div>
                                                    @else
                                                        <div class="cart-item-price"><small style="font-size: 11px">Price Needs Confirmation</small></div>
                                                    @endif
                                                </div>
                                                <div class="cart-mutators">
                                                    @foreach($merchantCartItems->mutators as $merchantCartItemsMutator)
                                                        <span class="badge badge-dark pl-2 pr-2"><small style="font-size: 11px">{{ $merchantCartItemsMutator->mutator->group->name }}: {{ $merchantCartItemsMutator->mutator->name }}</small></span>
                                                    @endforeach
                                                </div>
                                                <form method="POST" action="{{ route('checkout.destroy_cart_item', $merchantCartItems->id) }}">
                                                    <input type="hidden" name="open_cart" value="0">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="cart-close-btn"><i class="uil uil-multiply"></i></button>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                @if($merchantCart['totalSavings'] > 0)
                                    <div class="main-total-cart">
                                        <h2>Total Saving</h2>
                                        <span>$ {{ $merchantCart['totalSavings'] }}</span>
                                    </div>
                                @endif
                                <div class="main-total-cart">
                                    <h2>Total</h2>
                                    @if($merchantCart['total'] > 0)
                                        <span>$ {{ $merchantCart['total'] }}</span>
                                    @else
                                        <span class="text-danger">Needs confirmation</span>
                                    @endif
                                </div>
                                @if($merchantCart['needsPriceConfirmation'])
                                    <div class="payment-secure">
                                        <small>Some products in your cart needs price confirmation</small>
                                    </div>
                                @endif
{{--                                <div class="payment-secure">--}}
{{--                                    <a href="#" class="cart-checkout-btn hover-btn">Place Order</a>--}}
{{--                                </div>--}}






                                <div class="payment-secure">
                                    <i class="uil uil-padlock"></i>Secure checkout
                                </div>
                            </div>
                            <a href="#" class="promo-link45">Have a promocode?</a>
                            <div class="checkout-safety-alerts">
                                <p><i class="uil uil-sync"></i>100% Replacement Guarantee</p>
                                <p><i class="uil uil-check-square"></i>100% Genuine Products</p>
                                <p><i class="uil uil-shield-check"></i>Secure Payments</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

@endsection
