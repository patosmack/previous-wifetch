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
                                        <h4><i class="uil uil-apps"></i>Overview</h4>
                                    </div>
                                    <div class="welcome-text">
                                        <h2>Hello {{ trim($userAccount->name . ' ' . $userAccount->last_name) }}!</h2>
                                    </div>
                                </div>
                                @if(isset($cart) && array_key_exists('merchants', $cart) && is_array($cart['merchants']) &&  count($cart['merchants']) > 0)
                                <div class="col-lg-6 col-md-12">
                                    <div class="pdpt-bg">
                                        <div class="pdpt-title">
                                            <h4>My Cart</h4>
                                        </div>
                                        <div class="ddsh-body">
                                            <h2>You have {{ count($cart['cart']->items) }} item/s</h2>
                                            <p><small><strong>Checkout your Merchant products</strong></small></p>
                                            <ul>
                                                @foreach($cart['merchants'] as $cartMerchantItem)
                                                <li>
                                                    <a href="{{ route('checkout.address', $cartMerchantItem['merchant']->friendly_url) }}" class="small-reward-dt hover-btn">{{ $cartMerchantItem['merchant']->name }}</a>
                                                </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        <a href="{{ route('categories.list') }}" class="more-link14">Browse our categories <i class="uil uil-angle-double-right"></i></a>
                                    </div>
                                </div>
                                @else
                                    <div class="col-lg-6 col-md-12">
                                        <div class="pdpt-bg">
                                            <div class="pdpt-title">
                                                <h4>My Cart</h4>
                                            </div>
                                            <div class="ddsh-body">
                                                <h2>No items on cart</h2>
                                                <ul>
                                                    <li>
                                                        <a href="{{ route('categories.list') }}" class="small-reward-dt hover-btn">Add products and stay at home</a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <a href="{{ route('categories.list') }}" class="more-link14">Browse our categories <i class="uil uil-angle-double-right"></i></a>
                                        </div>
                                    </div>
                                @endif
                                <div class="col-lg-6 col-md-12">
                                    <div class="pdpt-bg">
                                        <div class="pdpt-title">
                                            <h4>My Latest Orders</h4>
                                        </div>
                                        <div class="ddsh-body">
                                            <ul class="order-list-145">
                                                @foreach($latest_orders as $latestOrderItem)
                                                <li>
                                                    <div class="smll-history">
                                                        <div class="order-title">#{{ str_pad($latestOrderItem->id, 8, '0', STR_PAD_LEFT) }} <span data-inverted="" data-tooltip="@foreach($latestOrderItem->items as $latestOrderItemItem){{ $latestOrderItemItem->name }} x {{ $latestOrderItemItem->quantity }} @endforeach" data-position="top center">?</span></div>
                                                        <div class="order-status">{{ trans('status.' . $latestOrderItem->status) }}</div>
                                                        <p>$ {{ $latestOrderItem->transaction_total }}</p>
                                                    </div>
                                                </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        <a href="{{ route('account.orders') }}" class="more-link14">All Orders <i class="uil uil-angle-double-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
