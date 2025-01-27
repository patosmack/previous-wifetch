@extends('frontend.app')

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
                                    <div class="main-title-tab d-flex flex-row justify-content-between align-items-center">
                                        <h4 class="d-flex"><i class="uil uil-box"></i>Order Detail</h4>
                                        <a href="{{ route('backend.order.list') }}" class="deliver-link m-0">Go Back</a>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12">
                                    @if($order->merchant)
                                            <div class="pdpt-bg">
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
                                                                <h5 class="black pb-0 mb-0">{{ $orderItem->quantity }} x {{ $orderItem->name }} @if($orderItem->price > 0) - <span class="fs-1_3">${{ $orderItem->price }} </span>@endif</h5>
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


                                                    @if($order->status === 'waiting_for_price')
                                                        <hr>
                                                        <div class="col-md-12">
                                                            <div class="pdpt-title">
                                                                <h3 class="text-danger">This order needs price confirmation</h3>

                                                                @if($order->transaction_total > 0)
                                                                    <h5>Current transaction amount <small>(From items with price)</small></h5>
                                                                    <h6 class="border-0 pt-0 pb-1"><strong>Subtotal: <span class="pl-2">${{ $order->transaction_total - $order->transaction_shipping - $order->transaction_handling_cost }}</span></strong></h6>
                                                                    @if($order->transaction_shipping > 0 )<h6 class="border-0 pt-0 pb-1"><strong>Shipping: <span class="pl-2">${{ $order->transaction_shipping }}</span></strong></h6>@endif
                                                                    @if($order->transaction_handling_cost > 0)<h6 class="border-0 pt-0 pb-1"><strong>Handling: <span class="pl-2">${{ $order->transaction_handling_cost }}</span></strong></h6>@endif
                                                                    <h6 class="pt-3 pb-1 mb-4"><strong>Order total: <span class="pl-2">${{ $order->transaction_total }}</span></strong></h6>
                                                                @endif

                                                                <form method="POST" action="{{ route('backend.order.update', $order->id) }}">
                                                                    <input type="hidden" value="save_order_total" name="target" readonly>
                                                                    @csrf
                                                                    <div class="row">
                                                                        <div class="col-md-4">
                                                                            <div class="form-group mt-1">
                                                                                <label class="control-label">Order Total *</label>
                                                                                <div class="form-group pos_rel">
                                                                                    <input id="transaction_total" type="text" class="form-control lgn_input @error('transaction_total') is-invalid @enderror" name="transaction_total" value="{{ old('transaction_total') }}" required placeholder="E.g: $12.95">
                                                                                    <i class="uil uil-dollar-sign lgn_icon"></i>
                                                                                </div>
                                                                                @error('transaction_total')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group mt-1">
                                                                                <label class="control-label">Shipping Total *</label>
                                                                                <div class="form-group pos_rel">
                                                                                    <input id="transaction_shipping" type="text" class="form-control lgn_input @error('transaction_shipping') is-invalid @enderror" name="transaction_shipping" value="{{ old('transaction_shipping') }}" required placeholder="E.g: $2.10">
                                                                                    <i class="uil uil-dollar-sign lgn_icon"></i>
                                                                                </div>
                                                                                @error('transaction_shipping')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group mt-1">
                                                                                <label class="control-label">Handling Total *</label>
                                                                                <div class="form-group pos_rel">
                                                                                    <input id="transaction_handling_cost" type="text" class="form-control lgn_input @error('transaction_handling_cost') is-invalid @enderror" name="transaction_handling_cost" value="{{ old('transaction_handling_cost') }}" required placeholder="E.g: $3.23">
                                                                                    <i class="uil uil-dollar-sign lgn_icon"></i>
                                                                                </div>
                                                                                @error('transaction_handling_cost')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-12">
                                                                            <button class="login-btn hover-btn" type="submit">Save order total</button>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>


                                                    @elseif($order->status === 'waiting_for_payment')
                                                        <hr>
                                                        <form method="POST" action="{{ route('backend.order.update', $order->id) }}">
                                                            <div class="order-cta">
                                                                <input type="hidden" value="send_payment_email" name="target" readonly>
                                                                @csrf
                                                                <div class="delivery-man text-center w-100">
                                                                    <h3 class="text-danger text-center w-100">This order is awaiting payment</h3>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    @endif

                                                    <hr>
                                                    <div class="pdpt-title">
                                                        <h4>Contact Information</h4>
                                                        <h4>
                                                            {{ $order->order_name }} {{ $order->order_last_name }}<br><br>
                                                            <span class="font-weight-normal"><a href="tel:{{ $order->delivery_phone }}" class="text-dark"> <i class="fa fa-phone mr-2"></i> {{ $order->delivery_phone }}</a></span><br>
                                                            <span class="font-weight-normal"><a href="mailto:{{ $order->order_email }}" class="text-dark"><i class="fa fa-envelope mr-2"></i> {{ $order->order_email }}</a></span><br>
                                                        </h4>
                                                    </div>

                                                    <hr>
                                                    <div class="pdpt-title">
                                                        <h4>Delivery Address</h4>
                                                        <h4>
                                                            {{ $order->delivery_address }}, {{ $order->delivery_parish }} - {{ $order->delivery_country }}<br>
                                                            <small><strong>{{ $order->delivery_date ? \Carbon\Carbon::parse($order->delivery_date)->format('d/m/Y') : '' }}</strong></small><br>
                                                            <small>{{ $order->delivery_timeframe }}</small>
                                                        </h4>
                                                    </div>

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


{{--                                                    {"FinalStatus":"success","IPaddress":"162.158.62.235","MStatus":"success","User-Agent":"","acct_code4":"pnpremote.cgi","accttype":"credit","amountcharged":"84.00","auth-code":"789355","auth-msg":" 00: ","auth_date":"20200904","authtype":"authonly","avs-code":"","card-address1":"Barbados Golf Club","card-amount":"84.00","card-city":"Christ Church","card-country":"BRB","card-exp":"11\/24","card-name":"lily dash","card-state":"ZZ","card-type":"MSTR","client":"WiFetch","currency":"bbd","cvvresp":"","dontsndmail":"yes","easycart":"0","elapsedTimePurchase":"3","email":"lily@wifetch.com","ipaddress":"162.158.62.235","merchant":"wifetchinc","merchfraudlev":"","mode":"auth","orderID":"2020090415382203695","paymethod":"credit","phone":"2462625075","publisher-name":"wifetchinc","receiptcc":"XXXXXXXXXXXX6345","refnumber":"090400000008","resp-code":"00","shipinfo":"0","shipname":"wifetchinc","sresp":"A","success":"yes","MErrMsg":"00: ","a":"b\n"}--}}

                                                    <div class="total-dt">
                                                        <div class="main-total-cart">
                                                            <h2>Transaction Information</h2>
                                                        </div>
                                                    </div>

                                                    <div class="pdpt-title">
                                                        <h4>
                                                            <span><strong>Status</strong>
                                                            @if(!$order->transaction_status || $order->transaction_status === 'pending' || $order->transaction_status === 'pending_transaction_email')
                                                                    Pending Payment
                                                                @else
                                                                    @if($order->transaction_status === 'approved')
                                                                    <span class="text-success">{{ ucfirst(str_replace('_', ' ', $order->transaction_status)) }}</span>
                                                                        @else
                                                                        <span>{{ ucfirst(str_replace('_', ' ', $order->transaction_status)) }}</span>
                                                                    @endif
                                                                @endif
                                                            </span><br><br>
                                                        @if($order->transaction_status && $order->transaction_status !== 'pending' && $order->transaction_status !== 'pending_transaction_email')
                                                                <small><strong>Transaction ID</strong> {{ $order->transaction_extra }}</small><br>
                                                                @if($order->transaction_info && is_array($order->transaction_info))
                                                                    @if(array_key_exists('card-name', $order->transaction_info))
                                                                        <small><strong>Card Holder</strong> {{ $order->transaction_info['card-name'] }}</small><br>
                                                                    @endif
                                                                    @if(array_key_exists('receiptcc', $order->transaction_info))
                                                                        <small><strong>Card Number</strong> {{ $order->transaction_info['receiptcc'] }}</small><br>
                                                                    @endif
                                                                    @if(array_key_exists('card-type', $order->transaction_info))
                                                                        <small><strong>Card Type</strong> {{ $order->transaction_info['card-type'] }}</small><br>
                                                                    @endif
                                                                    @if(array_key_exists('orderID', $order->transaction_info))
                                                                        <small><strong>Order ID</strong> {{ $order->transaction_info['orderID'] }}</small><br>
                                                                    @endif
                                                                @endif
                                                        @endif
                                                        </h4>
                                                    </div>


                                                    <div class="track-order">
                                                        @if($order->status === 'waiting_for_price')

                                                        @elseif($order->status === 'waiting_for_payment')

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






                                                    @if($order->transaction_status && $order->transaction_status !== 'pending' && $order->transaction_status !== 'pending_transaction_email')


                                                    <div class="total-dt mb-3">
                                                        <div class="main-total-cart">
                                                            <h2>Money Request</h2>
                                                        </div>
                                                    </div>

                                                    <div class="col-12 pt-3">
                                                        <div class="row">
                                                            <div class="col-md-5">
                                                                <div class="pdpt-title">
                                                                    <h5>Create new Request</h5>
                                                                    <form method="POST" action="{{ route('backend.order.requestMoreMoney', $order->id) }}">
                                                                        <input type="hidden" value="request_more_money" name="target" readonly>
                                                                        @csrf
                                                                        <div class="row">
                                                                            <div class="col-md-12">
                                                                                <div class="form-group mt-1">
                                                                                    <label class="control-label">Amount to request *</label>
                                                                                    <div class="form-group pos_rel">
                                                                                        <input id="transaction_total" type="text" class="form-control lgn_input @error('transaction_total') is-invalid @enderror" name="transaction_total" value="{{ old('transaction_total') }}" required placeholder="E.g: $12.95">
                                                                                        <i class="uil uil-dollar-sign lgn_icon"></i>
                                                                                    </div>
                                                                                    @error('transaction_total')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-12">
                                                                                <div class="form-group mt-1">
                                                                                    <label class="control-label">Description *</label>
                                                                                    <div class="form-group pos_rel">
                                                                                        <textarea id="transaction_description" class="form-control @error('transaction_description') is-invalid @enderror" name="transaction_description" required placeholder="E.g: Extra products requested by the client">{{ old('transaction_description') }}</textarea>
                                                                                    </div>
                                                                                    @error('transaction_description')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-12">
                                                                                <button class="login-btn hover-btn" type="submit">Send Request</button>
                                                                            </div>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="pdpt-title">
                                                                    <h5>Requested Money</h5>
                                                                    <ul>
                                                                        @if(count($order->transactions) === 0)
                                                                            <li>
                                                                                <strong>This order does not have any money request yet</strong>
                                                                            </li>
                                                                        @else
                                                                            @foreach($order->transactions as $order_transaction)

                                                                                <li>
                                                                                    <h5 class="ml-3 mb-1"><strong>Transaction ID:</strong> {{ $order_transaction->transaction_id }}</h5>
                                                                                    <h5 class="ml-3 mt-0 mb-3"><strong>Transaction Internal ID:</strong> {{ $order_transaction->id }}</h5>
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







                                                    @if($order->rating && $order->rating > 0)

                                                        <div class="total-dt mb-3 mt-4">
                                                            <hr>
                                                            <div class="main-total-cart pt-2">
                                                                <h2>Order Review</h2>
                                                            </div>
                                                        </div>

                                                            <div class="order-cta">
                                                                <div class="delivery-man">
                                                                    <h3>This order got a score of: <strong>{{ $order->rating }} stars</strong></h3>
                                                                </div>
                                                            </div>

                                                        @if($order->message)
                                                            <div class="order-cta mt-0 pt-0">
                                                                <div class="delivery-man">
                                                                    <h4>Message: <span class="font-weight-normal">{{ $order->message }}</span></h4>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
