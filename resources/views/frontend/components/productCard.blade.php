@if(isset($productCardItem))
<div class="col-lg-4 col-md-6" id="productItem{{ $productCardItem->id }}">
    <div class="product-item mb-4 mb-sm-4">
        <a href="{{ route('merchant.product', ['merchant_friendly_url' => ($productCardItem->merchant ? $productCardItem->merchant->friendly_url : 'wifetch-merchant'), 'product_friendly_url' => $productCardItem->friendly_url]) }}" class="product-img">
            <img src="{{ asset('assets/common/ajax-loader-canvas.gif') }}"  data-src="{{ $productCardItem->image ? asset($productCardItem->image) : asset('assets/common/image_placeholder.png')}}" alt="{{ $productCardItem->name }}" class="lazy">
            @if($productCardItem->hasDiscount)
                <div class="product-absolute-options">
                    <span class="offer-badge-1">{{ $productCardItem->discount }}% off</span>
                </div>
            @endif
        </a>
        <div class="product-text-dt">
            <a href="{{ route('merchant.product', ['merchant_friendly_url' => ($productCardItem->merchant ? $productCardItem->merchant->friendly_url : 'wifetch-merchant'), 'product_friendly_url' => $productCardItem->friendly_url]) }}" class="product-img">
{{--                @if($productCardItem->availableStock > 0)--}}
{{--                    <p class="text-success">In Stock</p>--}}
{{--                @else--}}
{{--                    <p class="text-danger font-weight-bold">Not Available</p>--}}
{{--                @endif--}}
                <h4 class="max-lines">{{ $productCardItem->name }}</h4>
                @if($productCardItem->sellPrice && $productCardItem->sellPrice > 0)
                    <div class="product-price">
                        $ {{ $productCardItem->formattedSellPrice }}
                        @if($productCardItem->hasDiscount)<span>${{ $productCardItem->formattedOriginalPrice }}</span>@endif
                    </div>
                @else
                    <p class="font-weight-bold">Price needs<span>Confirmation</span></p>
                @endif
            </a>
            <hr>
            @if($productCardItem->mutators_count === 0)
                <form  method="POST" action="{{ route('checkout.add_to_cart') }}">
                    @if(isset($openCartOnComplete))
                        <input type="hidden" value="@if($openCartOnComplete)  {{ 1 }}@else {{ 0 }}@endif" name="open_cart_on_complete">
                    @else
                        <input type="hidden" value="1" name="open_cart_on_complete">
                    @endif
                    <input type="hidden" value="{{ $productCardItem->id }}" name="product_id">
                    @csrf
                    <div class="qty-cart">
                        <div class="quantity buttons_added">
                            <input type="button" value="-" class="minus minus-btn">
                            <input type="number" step="1" name="quantity" value="1" class="input-text qty text">
                            <input type="button" value="+" class="plus plus-btn">
                        </div>
                        <input type="submit" value="Add" class="cart-icon-btn">
                    </div>
                </form>
            @else
                <a href="{{ route('merchant.product', ['merchant_friendly_url' => ($productCardItem->merchant ? $productCardItem->merchant->friendly_url : 'wifetch-merchant'), 'product_friendly_url' => $productCardItem->friendly_url]) }}" class="cart-icon-btn"><strong>View More Options</strong></a>
            @endif
        </div>

    </div>
</div>
@endif
