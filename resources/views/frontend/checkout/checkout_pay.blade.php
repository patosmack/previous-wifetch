@extends('frontend.app')

@section('styles')

    <link href="{{ asset('css/step-wizard.css') }}" rel="stylesheet">
@endsection

@section('scripts')

    <script>
        $(document).ready(function () {


            showPaymentMethod($('.payment_method_selected'));

            $('input[name="payment_method"]').on('click', function () {
                showPaymentMethod($(this));
            });

            function showPaymentMethod(obj){
                obj.prop('checked', true);
                var $value = obj.data('target');
                $('.return-departure-dts').hide();
                $('[data-method="' + $value + '"]').show();
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
                            <li class="breadcrumb-item d-none d-md-block"><a href="{{ route('checkout.address', $merchantCart['merchant']->friendly_url) }}">Address</a> /</li>
                            <li class="breadcrumb-item d-none d-md-block"><a href="{{ route('checkout.timeframe', $merchantCart['merchant']->friendly_url) }}">TimeFrame</a> /</li>
                            <li class="breadcrumb-item active">Pay</li>

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
                                <div class="checkout-card" id="headingPayment">
                                    <span class="checkout-step-number">3</span>
                                    <h4 class="checkout-step-title">
                                        <button class="wizard-btn">Payment</button>
                                    </h4>
                                </div>
                                <div class="checkout-step-body">
                                    <div class="payment_method-checkout">
                                        <form action="{{ route('checkout.pay.store', $merchant->friendly_url) }}" method="POST">
                                            <input type="hidden" value="{{ $merchant->id }}" name="merchant_id">
                                            @csrf
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="rpt100">

                                                        <ul class="radio--group-inline-container_1 @if(count($paymentMethods) === 1) d-none @endif">
                                                            @foreach($paymentMethods as $paymentMethodItem)
                                                            <li>
                                                                <div class="radio-item_1">
                                                                    <input id="{{ $paymentMethodItem->slug }}" value="{{ $paymentMethodItem->slug }}" data-target="{{ $paymentMethodItem->slug }}" name="payment_method" type="radio" class="payment_method_selected">
                                                                    <label for="{{ $paymentMethodItem->slug }}" class="radio-label_1">{{ $paymentMethodItem->name }}</label>
                                                                </div>
                                                            </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                    @foreach($paymentMethods as $paymentMethodItem)
                                                    <div class="form-group return-departure-dts" data-method="{{ $paymentMethodItem->slug }}">

                                                        @if($merchantCart['needsPriceConfirmation'])
                                                            <div class="row">
                                                                <div class="col-lg-12">
                                                                    <div class="pymnt_title">
                                                                        {!! $paymentMethodItem->description !!}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @else

                                                            @if($merchantCart['total'] <= 0 && $cart['discount'] && !$cart['discount']->is_percentage)
                                                                <div class="row">
                                                                    <div class="col-lg-12">
                                                                        <div class="pymnt_title">
                                                                            <h4>Your order is <span class="text-success">FREE</span></h4>
                                                                            <h5>Place your order to complete the transaction</h5>
                                                                            <p>As soon as we process your ordcer, we will send an email with the details</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @else

                                                            <div class="row">
                                                                <div class="col-lg-6">
                                                                    <div class="form-group mt-1">
                                                                        <label class="control-label">Holder Name*</label>
                                                                        <div class="ui search focus">
                                                                            <div class="ui left icon input swdh11 swdh19">
                                                                                <input class="prompt srch_explore" type="text" name="card[name]" value="" id="card[name]" required="" maxlength="39" placeholder="Holder Name">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6">
                                                                    <div class="form-group mt-1">
                                                                        <label class="control-label">Card Number*</label>
                                                                        <div class="ui search focus">
                                                                            <div class="ui left icon input swdh11 swdh19">
                                                                                <input class="prompt srch_explore" type="text" name="card[number]" value="" id="card[number]" required="" maxlength="16" placeholder="Card Number">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-4">
                                                                    <div class="form-group mt-1">
                                                                        <label class="control-label">Expiration Month*</label>
                                                                        <select class="ui fluid search dropdown form-dropdown" name="card[expire-month]" required>
                                                                            <option value="" disabled selected>Select Month</option>
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
                                                                        <select class="ui fluid search dropdown form-dropdown" name="card[expire-year]" required>
                                                                            <option value="" disabled selected>Select Year</option>
                                                                            @for($i = date('Y') ; $i < date('Y') + 50; $i++)
                                                                                <option value="{{ $i }}">{{ $i }}</option>
                                                                            @endfor
                                                                        </select>
{{--                                                                        <div class="ui search focus">--}}
{{--                                                                            <div class="ui left icon input swdh11 swdh19">--}}
{{--                                                                                <input class="prompt srch_explore" type="text" name="card[expire-year]" maxlength="4" placeholder="Year">--}}
{{--                                                                            </div>--}}
{{--                                                                        </div>--}}
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-4">
                                                                    <div class="form-group mt-1">
                                                                        <label class="control-label">CVV*</label>
                                                                        <div class="ui search focus">
                                                                            <div class="ui left icon input swdh11 swdh19">
                                                                                <input class="prompt srch_explore" name="card[cvv]" maxlength="4" placeholder="CVV">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            @endif
                                                        @endif
                                                    </div>
                                                    @endforeach
                                                </div>
                                                <div class="col-md-12">
                                                    <hr>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="control-label">Order Comments</label>
                                                        <textarea class="form-control" name="comment" id="comment" placeholder="Note for the merchant"  rows="3">{{ old('comment') }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <p class="w-100 text-right">
                                                            <img src="{{ asset('assets/common/online-shopping-safety.jpg') }}" alt="" width="160">
                                                        </p>
                                                    </div>
                                                </div>

                                                @if($merchantCart['merchant']->disclaimer && strlen($merchantCart['merchant']->disclaimer) > 0)
                                                    <div class="col-lg-12 col-md-12 mt-2 mb-3">
                                                        <h6 class="mt-0 pt-0 font-weight-bold">Disclaimer</h6>
                                                        <p style="font-size: 12px">{{ $merchantCart['merchant']->disclaimer }}</p>
                                                    </div>
                                                @endif

                                                <div class="col-lg-12 col-md-12">
                                                    <div class="form-group">
                                                        <div class="address-btns">
                                                            <a href="{{ route('checkout.timeframe', $merchantCart['merchant']->friendly_url) }}" class="save-btn14 hover-btn d-none d-sm-inline">Modify your TimeFrame</a>
                                                            <a href="{{ route('checkout.timeframe', $merchantCart['merchant']->friendly_url) }}" class="save-btn14 hover-btn d-inline d-sm-none">Back</a>
                                                            <button type="submit" class="ml-auto next-btn16 hover-btn"> Place your order </button>
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
                    <div class="col-lg-4 col-md-5">
                        @include('frontend.checkout.checkout_sidebar')
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
