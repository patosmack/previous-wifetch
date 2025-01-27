<div class="pdpt-bg mt-0">
    <div class="pdpt-title">
        <div class="cart-top-title pt-3">
            @if($merchantCart['merchant']->logo)<img src="{{ asset($merchantCart['merchant']->logo) }}" alt="{{ $merchantCart['merchant']->name }}" width="50" class="cart-top-logo ml-2 mr-2">@endif
            <h3 class="mt-0 mb-0"><strong>{{ $merchantCart['merchant']->name }}</strong></h3>
        </div>
        @if($merchantCart['merchant']->disclaimer && strlen($merchantCart['merchant']->disclaimer) > 0)
            <hr class="pt-1 pb-1">
            <h4 class="mt-0 pt-0">Disclaimer</h4>
            <p>{{ $merchantCart['merchant']->disclaimer }}</p>
        @endif
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

            @if(array_key_exists('custom_items', $merchantCart))
                @foreach($merchantCart['custom_items'] as $cartMerchantCustomItem)
                    <div class="cart-item">
                        <div class="cart-text">
                            <h4 style="width: 80%">{{ $cartMerchantCustomItem }}</h4>
                            <div class="qty-group">
                                <div class="cart-item-price"><small style="font-size: 11px">Price Needs Confirmation</small></div>
                            </div>
                            <form method="POST" action="{{ route('checkout.destroy_cart_custom_item', $merchantCart['merchant']->id) }}">
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
    @if($cart['discount'] && $cart['discount']->rate > 0)
        <hr>
        <div class="main-total-cart">
            <div class="d-flex flex-column">
                <h2>Discount</h2>
                <small>Code: <strong>{{ $cart['discount']->code }}</strong></small>
            </div>
            @if($cart['discount']->is_percentage)
                <span>{{ $cart['discount']->rate }} % OFF</span>
            @else
                <span>$ {{ $cart['discount']->rate }} OFF</span>
            @endif
        </div>
    @endif
    @if($merchantCart['totalSavings'] > 0)
        <div class="main-total-cart">
            <h2>Total Saving</h2>
            <span>$ {{ $merchantCart['totalSavings'] }}</span>
        </div>
    @endif
    @if($merchantCart['delivery_fee'] > 0)
        <div class="main-total-cart">
            <h2>Delivery Fee</h2>
            <span>$ {{ $merchantCart['delivery_fee'] }}</span>
        </div>
    @endif
    @if($merchantCart['service_fee'] > 0)
        <div class="main-total-cart">
            <h2>Service Fee</h2>
            <span>$ {{ $merchantCart['service_fee'] }}</span>
        </div>
    @endif
    <div class="main-total-cart">
        <h2>Total</h2>
        @if($merchantCart['total'] > 0)
            <span>$ {{ $merchantCart['total'] }}</span>
        @else
            @if(!$merchantCart['needsPriceConfirmation'] && $cart['discount'] && !$cart['discount']->is_percentage)
                <span class="text-info">Free</span>
            @else
                <span class="text-danger">Needs confirmation</span>
            @endif
        @endif
    </div>
    @if($merchantCart['needsPriceConfirmation'])
        <div class="payment-secure">
            <small>Some products in your cart needs price confirmation</small>
        </div>
    @endif

    <div class="payment-secure">
        <i class="uil uil-padlock"></i>Secure checkout
    </div>

</div>
@if(!$cart['discount'])
    <a href="#" class="promo-link45 " data-toggle="modal" data-target="#promo_discount_modal" data-selected="private_category-new">Have a Discount Code?</a>
@else
    <a href="#" class="promo-link45 " data-toggle="modal" data-target="#promo_discount_modal" data-selected="private_category-new">Modify your Discount Code</a>
@endif
<div id="promo_discount_modal" class="header-cate-model main-theme-model modal fade" tabindex="-1" role="dialog" aria-modal="false">
    <div class="modal-dialog category-area" role="document">
        <div class="category-area-inner">
            <div class="modal-header">
                <button type="button" class="close btn-close" data-dismiss="modal" aria-label="Close">
                    <i class="uil uil-multiply"></i>
                </button>
            </div>
            <div class="category-model-content modal-content">
                <div class="cate-header">
                    @if(!$cart['discount'])
                        <h4>Have a Discount Code?</h4>
                    @else
                        <h4>Modify your Discount Code</h4>
                    @endif
                </div>
                <div class="add-address-form">
                    <div class="checout-private_category-step">
                        <div class="row">
                            <div class="col-lg-12">
                                <form action="{{ route('checkout.apply.discount') }}" method="POST">
                                    @csrf
                                    <div class="address-fieldset">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="control-label">Enter your discount Code</label>
                                                    @if(!$cart['discount'])
                                                    <input id="code" name="code" value="{{ old('code' ) }}" type="text" placeholder="Add your code here" class="form-control input-md" required="">
                                                    @else
                                                        <input id="code" name="code" value="{{ old('code', $cart['discount']->code) }}" type="text" placeholder="Add your code here" class="form-control input-md" required="">
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12">
                                                <div class="form-group">
                                                    <div class="address-btns">
                                                        <button type="submit" class="ml-auto next-btn16 hover-btn"> Apply discount </button>
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
</div>

{{--<div class="checkout-safety-alerts">--}}
{{--    <p><i class="uil uil-sync"></i>100% Replacement Guarantee</p>--}}
{{--    <p><i class="uil uil-check-square"></i>100% Genuine Products</p>--}}
{{--    <p><i class="uil uil-shield-check"></i>Secure Payments</p>--}}
{{--</div>--}}
