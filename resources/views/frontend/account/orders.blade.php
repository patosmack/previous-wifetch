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
                                        <h4><i class="uil uil-box"></i>My Orders</h4>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12">
                                    @foreach($orders as $order)
                                        @if($order->merchant)
                                            <div class="pdpt-bg">
                                                <div class="pdpt-title">
                                                    <h6>
                                                        <strong>Delivery Address: </strong>{{ $order->delivery_address }}, {{ $order->delivery_parish }} - {{ $order->delivery_country }}<br><br>
                                                        <strong>Delivery Date: </strong><small><strong>{{ $order->delivery_date ? \Carbon\Carbon::parse($order->delivery_date)->format('d/m/Y') : '' }}</strong> - </small><small>{{ $order->delivery_timeframe }}</small><br><br>
                                                        <strong>Transaction ID: </strong><small><strong> # {{ $order->transaction_id }}</strong></small>
                                                    </h6>
                                                </div>
                                                <div class="order-body10">
                                                    <ul class="order-dtsll">
                                                        <li>
                                                            <div class="order-dt-img">
                                                                <img src="{{ $order->merchant->logo ? asset($order->merchant->logo) : asset('assets/common/image_placeholder.png')}}" alt="{{ $order->merchant->name }}" class="img-fluid">
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="order-dt47">
                                                                <h4>{{ $order->merchant->name }}</h4>
                                                                <div class="order-title">{{ count($order->items) }} Item/s</div>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                    <ul class="pl-5">
                                                        @foreach($order->items as $orderItem)
                                                            <li class="pb-1">
                                                                <p class="black pb-0 mb-0">{{ $orderItem->quantity }} x {{ $orderItem->name }}</p>
                                                                <ul class="pl-4">
                                                                    <li>
                                                                        <small>
                                                                            @foreach($orderItem->mutators as $orderItemMutator)
                                                                                / {{ $orderItemMutator->name }}
                                                                            @endforeach
                                                                        </small>
                                                                    </li>
                                                                </ul>
                                                            </li>
                                                        @endforeach
                                                            @if($order->custom_product_request && is_array($order->custom_product_request))
                                                                @foreach($order->custom_product_request as $cartMerchantCustomItem)
                                                                    <li class="pb-1">
                                                                        <p class="black pb-0 mb-0">{{ $cartMerchantCustomItem }}</p>
                                                                    </li>
                                                                @endforeach
                                                            @endif
                                                    </ul>
                                                    @if($order->discount && $order->discount->rate > 0)
                                                    <div class="total-dt mt-2">
                                                        <div class="main-total-cart border-top align-items-center">
                                                            <div class="d-flex flex-column align-items-center">
                                                                <h2>Discount</h2>
                                                                <small>Code: <strong>{{ $order->discount->code }}</strong></small>
                                                            </div>
                                                            @if($order->discount->is_percentage)
                                                                <span>{{ $order->discount->rate }} % OFF</span>
                                                            @else
                                                                <span>$ {{ $order->discount->rate }} OFF</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    @endif
                                                    <div class="total-dt">
                                                        <div class="main-total-cart">
                                                            <h2>Total</h2>
                                                            @if($order->status === 'waiting_for_price')
                                                                <span>NEEDS CONFIRMATION</span>
                                                            @else
                                                                <span>$ {{ $order->transaction_total }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="track-order">
                                                        @if($order->status === 'waiting_for_price')
                                                            <div class="order-cta">
                                                                <div class="delivery-man">
                                                                    <h3>We are processing your order, an email will be sent with the payment link</h3>
                                                                </div>
                                                            </div>
                                                        @elseif($order->status === 'waiting_for_payment')
                                                            <div class="order-cta">
                                                                <div class="delivery-man">
                                                                    <h3>Your order is awaiting payment</h3>
                                                                </div>
                                                                <div class="order-bill-slip">
                                                                    <a href="{{ route('external_payment.view', $order->transaction_id) }}" class="bill-btn5 hover-btn">Pay Now</a>
                                                                </div>
                                                            </div>
                                                        @elseif($order->status === 'canceled')
                                                            <div class="order-cta">
                                                                <div class="delivery-man">
                                                                    <h3>Your order was canceled</h3>
                                                                </div>
                                                            </div>
                                                        @elseif($order->status === 'rejected')
                                                            <div class="order-cta">
                                                                <div class="delivery-man">
                                                                    <h3>Your order was rejected</h3>
                                                                </div>
                                                            </div>
                                                        @else
                                                                <h4>Order Status</h4>
                                                                <div class="track-order">
{{--                                                                    <h4>Track your Order</h4>--}}
                                                                    <div class="bs-wizard" style="border-bottom:0;">
                                                                        <div class="bs-wizard-step {{ \App\BFF\OrderBFF::step1Status($order) }}">
                                                                            <div class="text-center bs-wizard-stepnum">Payment</div>
                                                                            <div class="progress"><div class="progress-bar"></div></div>
                                                                            <a href="#" class="bs-wizard-dot"></a>
                                                                        </div>
                                                                        <div class="bs-wizard-step {{ \App\BFF\OrderBFF::step2Status($order) }}">
                                                                            <div class="text-center bs-wizard-stepnum">Processing</div>
                                                                            <div class="progress"><div class="progress-bar"></div></div>
                                                                            <a href="#" class="bs-wizard-dot"></a>
                                                                        </div>
                                                                        <div class="bs-wizard-step {{ \App\BFF\OrderBFF::step3Status($order) }}">
                                                                            <div class="text-center bs-wizard-stepnum">Ready</div>
                                                                            <div class="progress"><div class="progress-bar"></div></div>
                                                                            <a href="#" class="bs-wizard-dot"></a>
                                                                        </div>
                                                                        <div class="bs-wizard-step {{ \App\BFF\OrderBFF::step4Status($order) }}">
                                                                            <div class="text-center bs-wizard-stepnum">In Transit</div>
                                                                            <div class="progress"><div class="progress-bar"></div></div>
                                                                            <a href="#" class="bs-wizard-dot"></a>
                                                                        </div>

                                                                        <div class="bs-wizard-step {{ \App\BFF\OrderBFF::step5Status($order) }}">
                                                                            <div class="text-center bs-wizard-stepnum">Delivered</div>
                                                                            <div class="progress"><div class="progress-bar"></div></div>
                                                                            <a href="#" class="bs-wizard-dot"></a>
                                                                        </div>
                                                                    </div>
                                                                </div>







                                                                @if(count($order->transactions) > 0)
                                                                <hr>
                                                                <div class="total-dt mb-3">
                                                                    <div class="main-total-cart">
                                                                        <h2>Aditional Payments</h2>
                                                                    </div>
                                                                </div>

                                                                <div class="col-12 pt-3">
                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            <div class="pdpt-title">
                                                                                <h5>Payment</h5>
                                                                                <ul>
                                                                                    @if(count($order->transactions) === 0)
                                                                                        <li>
                                                                                            <strong>This order does not have any money request yet</strong>
                                                                                        </li>
                                                                                    @else
                                                                                        @foreach($order->transactions as $order_transaction)

                                                                                            <li>
                                                                                                <h5 class="ml-3 mb-3"><strong>Transaction ID:</strong> {{ $order_transaction->transaction_extra }}</h5>
                                                                                                <div class="ml-5">
                                                                                                    <h6 class="pl-0 pt-0 pb-2 border-0">
                                                                                                        <strong>Status: </strong>
                                                                                                        @if(!$order_transaction->transaction_status || $order_transaction->transaction_status === 'pending' || $order_transaction->transaction_status === 'pending_transaction_email')
                                                                                                            <strong class="text-info">Pending Payment</strong>
                                                                                                        @else
                                                                                                            @if($order_transaction->transaction_status === 'approved')
                                                                                                                <strong class="text-success">{{ ucfirst(str_replace('_', ' ', $order_transaction->transaction_status)) }}</strong>
                                                                                                            @else
                                                                                                                <strong class="text-info">{{ ucfirst(str_replace('_', ' ', $order_transaction->transaction_status)) }}</strong>
                                                                                                            @endif
                                                                                                        @endif
                                                                                                    </h6>
                                                                                                    <h6 class="pl-0 pt-0 pb-2 border-0">
                                                                                                        <strong>Amount Requested:</strong>
                                                                                                        ${{ $order_transaction->transaction_total }}
                                                                                                    </h6>
                                                                                                    @if($order_transaction->transaction_info && is_array($order_transaction->transaction_info))
                                                                                                        @if(array_key_exists('card-name', $order_transaction->transaction_info))
                                                                                                            <small><strong>Card Holder</strong> {{ $order_transaction->transaction_info['card-name'] }}</small><br>
                                                                                                        @endif
                                                                                                        @if(array_key_exists('receiptcc', $order_transaction->transaction_info))
                                                                                                            <small><strong>Card Number</strong> {{ $order_transaction->transaction_info['receiptcc'] }}</small><br>
                                                                                                        @endif
                                                                                                        @if(array_key_exists('card-type', $order_transaction->transaction_info))
                                                                                                            <small><strong>Card Type</strong> {{ $order_transaction->transaction_info['card-type'] }}</small><br>
                                                                                                        @endif
                                                                                                        @if(array_key_exists('orderID', $order_transaction->transaction_info))
                                                                                                            <small><strong>Order ID</strong> {{ $order_transaction->transaction_info['orderID'] }}</small><br>
                                                                                                        @endif
                                                                                                    @endif
                                                                                                </div>
                                                                                            </li>

                                                                                        @endforeach
                                                                                    @endif

                                                                                </ul>



                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                @endif


{{--                                                            @foreach($order->logs as $orderLog)--}}
{{--                                                                <div class="order-log">--}}
{{--                                                                    <div class="order-log-text">--}}
{{--                                                                        {{ $orderLog->status }}--}}
{{--                                                                    </div>--}}
{{--                                                                    <div class="order-bill-slip">--}}
{{--                                                                        <a href="{{ $order->transaction_url }}" class="bill-btn5 hover-btn">Pay Now</a>--}}
{{--                                                                    </div>--}}
{{--                                                                </div>--}}
{{--                                                            @endforeach--}}

{{--                                                            'pending', 'waiting_for_payment', 'payment_approved', 'payment_rejected', 'payment_refunded', 'canceled', 'completed',--}}
{{--                                                            'ready_to_fetch', 'finding_fetcher', 'fetching_order_items', 'ready_for_pickup',--}}
{{--                                                            'ready_for_delivery','finding_delivery','delivery_on_place','collected_by_delivery',--}}
{{--                                                            'transit_to_pickup','transit_to_destination','near_destination','delivered'--}}



{{--                                                                @if($order->transaction_status === 'pending_transaction_email')--}}
{{--                                                                    @if($order->transaction_url)--}}
{{--                                                                        <div class="order-cta">--}}
{{--                                                                            <div class="delivery-man">--}}
{{--                                                                                Your order is awaiting payment--}}
{{--                                                                            </div>--}}
{{--                                                                            <div class="order-bill-slip">--}}
{{--                                                                                <a href="{{ $order->transaction_url }}" class="bill-btn5 hover-btn">Pay Now</a>--}}
{{--                                                                            </div>--}}
{{--                                                                        </div>--}}
{{--                                                                    @else--}}
{{--                                                                        <div class="order-cta">--}}
{{--                                                                            <div class="delivery-man">--}}
{{--                                                                                We are processing your order, an email will be sent with the payment link--}}
{{--                                                                            </div>--}}
{{--                                                                        </div>--}}
{{--                                                                    @endif--}}
{{--                                                                @endif--}}

                                                        @endif
                                                    </div>






















{{--                                                    @if($order->transaction_status === 'pending_transaction_email')--}}
{{--                                                        @if($order->transaction_url)--}}
{{--                                                            <div class="order-cta">--}}
{{--                                                                <div class="delivery-man">--}}
{{--                                                                    Your order is awaiting payment--}}
{{--                                                                </div>--}}
{{--                                                                <div class="order-bill-slip">--}}
{{--                                                                    <a href="{{ $order->transaction_url }}" class="bill-btn5 hover-btn">Pay Now</a>--}}
{{--                                                                </div>--}}
{{--                                                            </div>--}}
{{--                                                        @else--}}
{{--                                                            <div class="order-cta">--}}
{{--                                                                <div class="delivery-man">--}}
{{--                                                                    We are processing your order, an email will be sent with the payment link--}}
{{--                                                                </div>--}}
{{--                                                            </div>--}}
{{--                                                        @endif--}}
{{--                                                    @endif--}}
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
