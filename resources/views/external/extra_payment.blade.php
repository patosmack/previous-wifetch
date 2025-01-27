<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, shrink-to-fit=9">
    {{--    <meta name="viewport" content="width=device-width, initial-scale=1">--}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('favicon/apple-icon-57x57.png') }}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('favicon/apple-icon-60x60.png') }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('favicon/apple-icon-72x72.png') }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('favicon/apple-icon-76x76.png') }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('favicon/apple-icon-114x114.png') }}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('favicon/apple-icon-120x120.png') }}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('favicon/apple-icon-144x144.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('favicon/apple-icon-152x152.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon/apple-icon-180x180.png') }}">
    <link rel="icon" type="image/png" sizes="192x192"  href="{{ asset('favicon/android-icon-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('favicon/favicon-96x96.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('favicon/manifest.json?v=2') }}">
    <meta name="msapplication-TileColor" content="#3498db">
    <meta name="msapplication-TileImage" content="{{ asset('favicon/nms-icon-144x144.png') }}">
    <meta name="theme-color" content="#FFF400">

    <!-- Stylesheets -->
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href='{{ asset('vendor/unicons-2.0.1/css/unicons.css') }}' rel='stylesheet'>

    <link href="{{ asset('css/style.css?v='.time()) }}" rel="stylesheet">
    <link href="{{ asset('css/responsive.css?v='.time()) }}" rel="stylesheet">
    <link href="{{ asset('css/app.min.css') }}" rel="stylesheet">

    <!-- Vendor Stylesheets -->
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/OwlCarousel/assets/owl.carousel.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/OwlCarousel/assets/owl.theme.default.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/semantic/semantic.min.css') }}">
    <link rel="stylesheet" type="text/css"  href="{{ asset('css/step-wizard.css') }}" >


</head>
<body>
<a id="buttonToTop">
    <i class="uil uil-arrow-circle-up"></i>
</a>

<header class="header clearfix">
    <div class="top-header-group">
        <div class="top-header">
            <div class="res_main_logo">
                <a href="{{ route('home') }}"><img src="{{ asset('assets/common/logo-1.svg') }}" alt=""></a>
            </div>
            <div class="main_logo pl-4" id="logo">
                <a href="{{ route('home') }}"><img src="{{ asset('assets/common/logo-yellow.svg') }}" alt=""></a>
                <a href="{{ route('home') }}"><img class="logo-inverse" src="{{ asset('assets/common/dark-logo-yellow.svg') }}" alt=""></a>
            </div>
            <div class="header_right">
                <ul>
                    <li>
                        <a href="tel:246264-8994" class="offer-link"><i class="uil uil-phone-alt"></i>(246) 264-8994</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="sub-header-group">
        <div class="sub-header">
            <h4 class="w-100 text-center">Safe & Secure payment</h4>
        </div>
    </div>
</header>
<div class="wrapper-breadcrumb-external">
    @include('frontend.shared.alert')
    <div class="all-product-grid">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-md-7 ">
                    <div id="checkout_wizard" class="checkout accordion left-chck145">
                        <div class="checkout-step">
                            <div class="checkout-card" id="headingPayment">
                                <h4 class="checkout-step-title">
                                    <button class="wizard-btn">Payment Request</button>
                                </h4>
                                <p class="mt-2 mb-1"><small>Order Transaction Ref.: {{ strtoupper($order->transaction_id) }}</small></p>
                                <p class="p-0 m-0"><small>Transaction Ref.: {{ strtoupper($orderTransaction->transaction_id) }}</small></p>
                            </div>
                            <div class="checkout-step-body">
                                <div class="payment_method-checkout">
                                    <form action="{{ route('extra_payment.store', $orderTransaction->transaction_id) }}" method="POST">
                                        <input type="hidden" value="{{ $orderTransaction->transaction_id }}" name="transaction_id">
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
{{--                                                                    <div class="ui search focus">--}}
{{--                                                                        <div class="ui left icon input swdh11 swdh19">--}}
{{--                                                                            <input class="prompt srch_explore" type="text" name="card[expire-year]" maxlength="4" placeholder="Year">--}}
{{--                                                                        </div>--}}
{{--                                                                    </div>--}}
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
                                                    </div>
                                                @endforeach
                                            </div>
                                            <div class="col-md-12">
                                                <hr>
                                            </div>



                                            <div class="col-lg-12 col-md-12">
                                                <div class="form-group">
                                                    <div class="address-btns">
                                                        <button type="submit" class="ml-auto next-btn16 hover-btn"> Pay now </button>
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
                <div class="col-lg-4 col-md-5 ">
                    <div class="pdpt-bg mt-0">
                        <div class="pdpt-title">
                            <div class="cart-top-title pt-3">
                                @if($merchant->logo)<img src="{{ asset($merchant->logo) }}" alt="{{ $merchant->name }}" width="50" class="cart-top-logo ml-2 mr-2">@endif
                                <h3 class="mt-0 mb-0"><strong>{{ $merchant->name }}</strong></h3>
                            </div>
                            <hr class="pt-1 pb-1">
                            <h4 class="mt-0 pt-0">Description</h4>
                        </div>
                        <div class="right-cart-dt-body">
                            <div class="cart-item border_radius">
                                <div class="cart-text">
                                    <h4 style="width: 80%">{{ $orderTransaction->transaction_description }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="main-total-cart">
                            <h2>Amount to Pay</h2>
                            <span>$ {{ $orderTransaction->transaction_total }}</span>
                        </div>
{{--                        <div class="payment-secure">--}}
{{--                            <i class="uil uil-padlock"></i>Secure checkout--}}
{{--                        </div>--}}
                        <div class="col-md-12">
                            <div class="form-group mt-2">
                                <p class="w-100 text-center">
                                    <img src="{{ asset('assets/common/online-shopping-safety.jpg') }}" alt="" width="160">
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('frontend.shared.footer')

<script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
<script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('js/jquery-ui.min.js') }}"></script>
<script src="{{ asset('vendor/OwlCarousel/owl.carousel.js') }}"></script>
<script src="{{ asset('vendor/semantic/semantic.min.js') }}"></script>
<script src="{{ asset('js/jquery.countdown.min.js') }}"></script>
<script src="{{ asset('js/custom.js?v='.time()) }}"></script>
<script src="{{ asset('js/offset_overlay.js') }}"></script>
<script src="{{ asset('js/jquery.lazy.min.js') }}"></script>
<script src="{{ asset('js/global.js') }}"></script>
<script>
    $(document).ready(function(){
        $(window).scroll(function() {
            if ($(window).scrollTop() > 300) {
                $('#buttonToTop').addClass('show');
            } else {
                $('#buttonToTop').removeClass('show');
            }
        });
        $('#buttonToTop').on('click', function(e) {
            e.preventDefault();
            $('html, body').animate({scrollTop:0}, '300');
        });

    });
</script>
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
</body>
</html>


