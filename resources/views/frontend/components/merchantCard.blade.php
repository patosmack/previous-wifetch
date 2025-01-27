@if(isset($merchantCardItem))<div class="col-lg-3 col-md-6 col-sm-4 col-6">
    <div class="product-item mb-30">
        <a href="{{ route('merchant', $merchantCardItem->friendly_url) }}" class="product-img">
            <img src="{{ asset('assets/common/ajax-loader-canvas.gif') }}"  data-src="{{ $merchantCardItem->logo ? asset($merchantCardItem->logo) : asset('assets/common/image_placeholder.png')}}" alt="{{ $merchantCardItem->name }}" class="lazy">
            @if($merchantCardItem->available_hours_count > 0)
            <div class="product-absolute-options">
                @if($merchantCardItem->is_open)
                    <span class="offer-badge-1 bg-success text-white">OPEN</span>
                @else
                    <span class="offer-badge-1 bg-danger text-white">OPENING SOON</span>
                @endif
            </div>
            @endif
            <div class="product-text-dt">
                <h4 class="max-lines">{{ $merchantCardItem->name }}</h4>
{{--                <p>{{ $merchantCardItem->products_count }} <span>Products</span></p>--}}
{{--                <hr>--}}
            </div>
        </a>
    </div>
</div>
@endif
