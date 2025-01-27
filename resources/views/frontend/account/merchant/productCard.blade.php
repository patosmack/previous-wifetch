@if(isset($productCardItem))<div class="col-lg-4 col-md-6 mb-2 mt-2" id="productItem{{ $productCardItem->id }}">
    <div class="product-item mb-4 mb-sm-4">
        <a href="{{ route('account.merchant.product', ['merchant_id' => ($productCardItem->merchant ? $productCardItem->merchant->id : '0'), 'product_id' => $productCardItem->id]) }}" class="product-img">
            <img src="{{ asset('assets/common/ajax-loader-canvas.gif') }}"  data-src="{{ $productCardItem->image ? asset($productCardItem->image) : asset('assets/common/image_placeholder.png')}}" alt="{{ $productCardItem->name }}" class="lazy">
            @if($productCardItem->hasDiscount)
                <div class="product-absolute-options">
                    <span class="offer-badge-1">{{ $productCardItem->discount }}% off</span>
                </div>
            @endif
        </a>
            <div class="product-text-dt">
                <a href="{{ route('account.merchant.product', ['merchant_id' => ($productCardItem->merchant ? $productCardItem->merchant->id : '0'), 'product_id' => $productCardItem->id]) }}" class="product-img">
{{--                    @if($productCardItem->availableStock > 0)--}}
{{--                        <p class="text-success">In Stock</p>--}}
{{--                    @else--}}
{{--                        <p class="text-danger font-weight-bold">Not Available</p>--}}
{{--                    @endif--}}
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
                <a href="{{ route('account.merchant.product', ['merchant_id' => ($productCardItem->merchant ? $productCardItem->merchant->id : '0'), 'product_id' => $productCardItem->id]) }}" class="cart-icon-btn">Edit</a>
            </div>

    </div>
</div>
@endif
