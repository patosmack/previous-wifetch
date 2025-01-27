@if(isset($cart) && array_key_exists('merchants', $cart) && is_array($cart['merchants']) &&  count($cart['merchants']) > 0)
    <div class="bs-canvas bs-canvas-right position-fixed bg-cart h-100">
        <div class="bs-canvas-header side-cart-header p-3 ">
            <div class="d-inline-block  main-cart-title">My Cart <span>({{ count($cart['cart']->items) }} Items)</span></div>
            <button type="button" class="bs-canvas-close close" aria-label="Close"><i class="uil uil-multiply"></i></button>
        </div>
        <div class="bs-canvas-body">
            @foreach($cart['merchants'] as $cartMerchantItem)
            <div>
                <div class="cart-top-total">
                    <div class="cart-total-dil">
                        <a href="{{ route('merchant', $cartMerchantItem['merchant']->friendly_url) }}">
                        <div class="cart-top-title">
                            @if($cartMerchantItem['merchant']->logo)<img src="{{ asset($cartMerchantItem['merchant']->logo) }}" alt="{{ $cartMerchantItem['merchant']->name }}" width="50" class="cart-top-logo">@endif
                            <h4 class="mt-0 mb-0"><strong>{{ $cartMerchantItem['merchant']->name }}</strong></h4>
                        </div>
                        </a>
                        @if($cartMerchantItem['needsPriceConfirmation'])
                            <p class="text-right"><span><small>Need Confirmation</small></span></p>
                        @else
                            <span>$ {{ $cartMerchantItem['total'] }}</span>
                        @endif
                    </div>
                    @if($cartMerchantItem['merchant']->delivery_fee > 0)
                    <div class="cart-total-dil pt-2">
                        <h4>Delivery Charges</h4>
                        <span>$ {{ $cartMerchantItem['merchant']->delivery_fee }}</span>
                    </div>
                    @endif
{{--                    @if($cartMerchantItem['delivery_fee'] > 0)--}}
{{--                        <div class="cart-total-dil pt-2">--}}
{{--                            <h4>Delivery Fee</h4>--}}
{{--                            <span>$ {{ $cartMerchantItem['delivery_fee'] }}</span>--}}
{{--                        </div>--}}
{{--                    @endif--}}
{{--                    @if($cartMerchantItem['service_fee'] > 0)--}}
{{--                        <div class="cart-total-dil pt-2">--}}
{{--                            <h4>Service Fee</h4>--}}
{{--                            <span>$ {{ $cartMerchantItem['service_fee']- }}</span>--}}
{{--                        </div>--}}
{{--                    @endif--}}
                </div>
                <div class="side-cart-items">
                    @foreach($cartMerchantItem['items'] as $cartMerchantItemsItem)
                    <div class="cart-item">
                        <div class="cart-product-img">
                            <img src="{{ $cartMerchantItemsItem->product->image ? asset($cartMerchantItemsItem->product->image) : asset('assets/common/image_placeholder.png')}}" alt="{{ $cartMerchantItemsItem->product->name }}">
                            @if($cartMerchantItemsItem->product->hasDiscount)
                                <div class="offer-badge">{{ $cartMerchantItemsItem->product->discount }}% OFF</div>
                            @endif
                        </div>
                        <div class="cart-text">
                            <h4 style="width: 80%">{{ $cartMerchantItemsItem->product->name }}</h4>
                            <div class="qty-group">
                                x {{ $cartMerchantItemsItem->quantity }}
                                @if($cartMerchantItemsItem->orderPrice > 0)
                                <div class="cart-item-price">$ {{ $cartMerchantItemsItem->orderPrice }} @if($cartMerchantItemsItem->product->hasDiscount)<span>$ {{ $cartMerchantItemsItem->originalOrderPrice }}</span>@endif</div>
                                @else
                                    <div class="cart-item-price"><small style="font-size: 11px">Price Needs Confirmation</small></div>
                                @endif
                            </div>
                            <div class="cart-mutators">
                                @foreach($cartMerchantItemsItem->mutators as $cartMerchantItemsItemMutator)
                                    <span class="badge badge-dark pl-2 pr-2"><small style="font-size: 11px">{{ $cartMerchantItemsItemMutator->mutator->group->name }}: {{ $cartMerchantItemsItemMutator->mutator->name }}</small></span>
                                @endforeach
                            </div>
                            <form method="POST" action="{{ route('checkout.destroy_cart_item', $cartMerchantItemsItem->id) }}">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="open_cart" value="1">
                                <button type="submit" class="cart-close-btn"><i class="uil uil-multiply"></i></button>
                            </form>

                        </div>
                    </div>
                    @endforeach


                    @if(array_key_exists('custom_items', $cartMerchantItem))
                        @foreach($cartMerchantItem['custom_items'] as $cartMerchantCustomItem)
                            <div class="cart-item">
                                <div class="cart-text">
                                    <h4 style="width: 80%">{{ $cartMerchantCustomItem }}</h4>
                                    <div class="qty-group">
                                        <div class="cart-item-price"><small style="font-size: 11px">Price Needs Confirmation</small></div>
                                    </div>
                                    <form method="POST" action="{{ route('checkout.destroy_cart_custom_item', $cartMerchantItem['merchant']->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="open_cart" value="1">
                                        <input type="hidden" name="custom_item" value="{{ $cartMerchantCustomItem }}">
                                        <button type="submit" class="cart-close-btn"><i class="uil uil-multiply"></i></button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    @endif

                </div>
            </div>
            @endforeach
        </div>
        <div class="bs-canvas-footer">
{{--            @if($cart['totalSavings'] > 0 || $cart['discount'])--}}
{{--                    <div class="main-total-cart d-flex justify-content-between align-items-center">--}}
{{--                        <div class="d-flex flex-column">--}}
{{--                            <h2>Total Saving</h2>--}}
{{--                            @if($cart['discount'] && $cart['discount']->rate > 0)--}}
{{--                            <small><span>Code:</span> <strong>{{ $cart['discount']->code }}</strong></small>--}}
{{--                            @endif--}}
{{--                        </div>--}}
{{--                        <span>$ {{ $cart['totalSavings'] }}  @if($cart['discount'] && $cart['discount']->rate > 0) <small><strong>/ {{ $cart['discount']->rate }} % OFF</strong></small> @endif</span>--}}
{{--                    </div>--}}
{{--            @endif--}}
            <div class="main-total-cart">
                <h2>Total</h2>
                @if($cart['total'] > 0)
                    <span>$ {{ $cart['total'] }}</span>
                @else
                    @if($cart['discount'] && !$cart['discount']->is_percentage)
                        <span class="text-info">Free</span>
                    @else
                        <span class="text-danger">Needs confirmation</span>
                    @endif
                @endif
            </div>
            @if($cart['cartNeedsConfirmation'])
                <small class="main-total-cart-legend">Some products in your cart needs price confirmation</small>
            @endif
            <div class="checkout-cart">
{{--                <a href="#" class="promo-code">Have a promocode?</a>--}}
                <a href="{{ route('checkout.merchants') }}" class="cart-checkout-btn hover-btn">Proceed to Checkout</a>
            </div>
        </div>
    </div>
@else
    <div class="bs-canvas bs-canvas-right position-fixed bg-cart h-100">
        <div class="bs-canvas-header side-cart-header p-3 ">
            <div class="d-inline-block  main-cart-title">My Cart <span>(0 Items)</span></div>
            <button type="button" class="bs-canvas-close close" aria-label="Close"><i class="uil uil-multiply"></i></button>
        </div>
        <div class="bs-canvas-body">
            <div>
                <div class="cart-top-total">
                    <div class="cart-total-dil">
                        <h4 class="text-center w-100">
                            <strong>You have no products in your cart</strong>
                        </h4>
                    </div>
                    <div class="cart-total-dil pt-2">
                        <a href="{{ route('categories.list') }}" class="cart-checkout-btn w-100 text-center">Find products from our categories</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endif
