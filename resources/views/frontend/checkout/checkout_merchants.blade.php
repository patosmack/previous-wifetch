@extends('frontend.app')

@section('styles')

    <link href="{{ asset('css/step-wizard.css') }}" rel="stylesheet">
@endsection

@section('scripts')


@endsection

@section('content')


    <div class="wrapper">
        @include('frontend.shared.alert')
        <div class="all-product-grid mb-14">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <div class="default-title mt-4">
                            <h2>Checkout by Merchant</h2>
                        </div>
                    </div>
                    @if(isset($cart) && array_key_exists('merchants', $cart) && is_array($cart['merchants']) &&  count($cart['merchants']) > 0)
                        <hr>
                        @foreach($cart['merchants'] as $cartMerchantItem)

                            <div class="col-md-3">
                                <a href="{{ route('checkout.address', $cartMerchantItem['merchant']->friendly_url) }}" class="offers-item">
                                    <div class="offer-img">
                                        <img src="{{ asset('assets/common/ajax-loader-canvas.gif') }}"  data-src="{{ $cartMerchantItem['merchant']->logo ? asset($cartMerchantItem['merchant']->logo) : asset('assets/common/image_placeholder.png')}}" alt="{{ $cartMerchantItem['merchant']->name }}" class="lazy">
                                    </div>
                                    <div class="offers-text">
                                        <h4 class="max-lines">{{ $cartMerchantItem['merchant']->name }}</h4>
                                        <button class="cart-icon-btn-reverse w-100"> Checkout Now</button>
                                    </div>

                                </a>
                            </div>

{{--                        <div>--}}
{{--                            <div class="cart-top-total">--}}
{{--                                <div class="cart-total-dil">--}}
{{--                                    <a href="{{ route('merchant', $cartMerchantItem['merchant']->friendly_url) }}">--}}
{{--                                        <div class="cart-top-title">--}}
{{--                                            @if($cartMerchantItem['merchant']->logo)<img src="{{ asset($cartMerchantItem['merchant']->logo) }}" alt="{{ $cartMerchantItem['merchant']->name }}" width="50" class="cart-top-logo">@endif--}}
{{--                                            <h4 class="mt-0 mb-0"><strong>{{ $cartMerchantItem['merchant']->name }}</strong></h4>--}}
{{--                                        </div>--}}
{{--                                    </a>--}}
{{--                                    @if($cartMerchantItem['needsPriceConfirmation'])--}}
{{--                                        <p class="text-right"><span><small>Need Confirmation</small></span></p>--}}
{{--                                    @else--}}
{{--                                        <span>$ {{ $cartMerchantItem['total'] }}</span>--}}
{{--                                    @endif--}}
{{--                                </div>--}}
{{--                                @if($cartMerchantItem['merchant']->delivery_fee > 0)--}}
{{--                                    <div class="cart-total-dil pt-2">--}}
{{--                                        <h4>Delivery Charges</h4>--}}
{{--                                        <span>$ {{ $cartMerchantItem['merchant']->delivery_fee }}</span>--}}
{{--                                    </div>--}}
{{--                                @endif--}}
{{--                            </div>--}}
{{--                            <div class="side-cart-items">--}}
{{--                                @foreach($cartMerchantItem['items'] as $cartMerchantItemsItem)--}}
{{--                                    <div class="cart-item">--}}
{{--                                        <div class="cart-product-img">--}}
{{--                                            @if($cartMerchantItemsItem->product->image)--}}
{{--                                                <img src="{{ asset($cartMerchantItemsItem->product->image) }}" alt="{{ $cartMerchantItemsItem->product->name }}">--}}
{{--                                            @endif--}}
{{--                                            @if($cartMerchantItemsItem->product->hasDiscount)--}}
{{--                                                <div class="offer-badge">{{ $cartMerchantItemsItem->product->discount }}% OFF</div>--}}
{{--                                            @endif--}}
{{--                                        </div>--}}
{{--                                        <div class="cart-text">--}}
{{--                                            <h4 style="width: 80%">{{ $cartMerchantItemsItem->product->name }}</h4>--}}
{{--                                            <div class="qty-group">--}}
{{--                                                x {{ $cartMerchantItemsItem->quantity }}--}}

{{--                                                @if($cartMerchantItemsItem->orderPrice > 0)--}}
{{--                                                    <div class="cart-item-price">$ {{ $cartMerchantItemsItem->orderPrice }} @if($cartMerchantItemsItem->product->hasDiscount)<span>$ {{ $cartMerchantItemsItem->originalOrderPrice }}</span>@endif</div>--}}
{{--                                                @else--}}
{{--                                                    <div class="cart-item-price"><small style="font-size: 11px">Price Needs Confirmation</small></div>--}}
{{--                                                @endif--}}
{{--                                            </div>--}}
{{--                                            <div class="cart-mutators">--}}
{{--                                                @foreach($cartMerchantItemsItem->mutators as $cartMerchantItemsItemMutator)--}}
{{--                                                    <span class="badge badge-dark pl-2 pr-2"><small style="font-size: 11px">{{ $cartMerchantItemsItemMutator->mutator->group->name }}: {{ $cartMerchantItemsItemMutator->mutator->name }}</small></span>--}}
{{--                                                @endforeach--}}
{{--                                            </div>--}}
{{--                                            <form method="POST" action="{{ route('checkout.destroy_cart_item', $cartMerchantItemsItem->id) }}">--}}
{{--                                                @csrf--}}
{{--                                                @method('DELETE')--}}
{{--                                                <button type="submit" class="cart-close-btn"><i class="uil uil-multiply"></i></button>--}}
{{--                                            </form>--}}

{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                @endforeach--}}
{{--                            </div>--}}
{{--                        </div>--}}
                    @endforeach
                    @else
                        <div class="col-md-6 offset-md-3 mt-5">
                            <h3 class="text-center d-block w-100">Your cart is empty</h3>
                        </div>
                        <div class="col-md-4 offset-md-4 mt-5">
                            <a href="{{ route('categories.list') }}" class="cart-checkout-btn w-100 text-center">Find products from our categories</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
