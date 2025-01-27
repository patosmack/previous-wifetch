@extends('frontend.app')

@section('styles')

@endsection

@section('scripts')


@endsection

@section('content')

    <div class="wrapper">
        <div class="all-product-grid">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-6 col-md-8">
                        <div class="order-placed-dt">
                            <i class="uil uil-check-circle icon-circle"></i>
                            <h2>Payment Approved</h2>
                            <p>Thank you for your order!</p>
                            <div class="delivery-address-bg">
                                <div class="title585">
                                    <div class="pln-icon"><i class="uil uil-telegram-alt"></i></div>
                                    <h4>Your order will be sent to this address</h4>
                                </div>
                                <ul class="address-placed-dt1">
{{--                                    <li><p><i class="uil uil-map-marker-alt"></i>Address :<span>{{ $order->delivery_address }}, {{ $order->delivery_parish }} - {{ $order->delivery_country }}</span></p></li>--}}
                                    <li><p><i class="uil uil-calender"></i>Delivery Date :<span>{{ $order->delivery_date ? \Carbon\Carbon::parse($order->delivery_date)->format('d/m/Y') : '' }}</span></p></li>
                                    <li><p><i class="uil uil-clock"></i>Delivery TimeFrame :<span>{{ $order->delivery_timeframe }}</span></p></li>
                                    <li><hr></li>
{{--                                    <li><p><i class="uil uil-phone-alt"></i>Phone Number :<span>{{ $order->delivery_phone }}</span></p></li>--}}
{{--                                    <li><p><i class="uil uil-envelope"></i>Email Address :<span>{{ $order->order_email }}</span></p></li>--}}
{{--                                    <li><p><i class="uil uil-card-atm"></i>Payment Method :<span>{{ $orderTransaction->paymentMethod ? $orderTransaction->paymentMethod->name : '' }}</span></p></li>--}}
                                    <li><p><i class="uil uil-card-atm"></i>Total :<span><strong style="font-size: 16px">$ {{ $orderTransaction->transaction_total }}</strong></span></p></li>
                                </ul>

{{--                                <div class="stay-invoice">--}}
{{--                                    <a href="{{ route('account.orders') }}" class="deliver-link">View Your Order</a>--}}
{{--                                </div>--}}
                                <div class="placed-bottom-dt">
                                    <span>You will receive a notification when we pickup your order</span>.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
