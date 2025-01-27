@extends('frontend.app')

@section('scripts')



    <script>

        $(document).ready(function() {

            $('#quantity').change(function() {
                calculateFullPrice();
            });

            $('.mutatorSelector').change(function() {
                calculateFullPrice();
            });

            $('.mutatorQty').change(function() {
                let parentTarget = $(this).data('parenttarget');
                let priceElement = $(this).data('pricetarget');
                let extra_price = $(parentTarget).data('extraprice');
                let qtyElement = $(this);
                let qty = 1;
                if($(qtyElement)){
                    let qtyDta = $(qtyElement).val();
                    if(qtyDta){
                        qty = qtyDta;
                    }
                }
                if(!$(parentTarget).checked) {
                    $(parentTarget).prop( "checked", true );
                }
                $(priceElement).html('+ $' + extra_price * qty);

                calculateFullPrice();
            });


            function calculateFullPrice() {
                let price = $('#productPrice').val();
                let originalPrice = $('#productOriginalPrice').val()
                $(".mutatorSelector").each(function() {
                    if(this.checked) {
                        let extra_price = $(this).data('extraprice');
                        let qtyElement = $(this).data('qtytarget');
                        let qty = 1;
                        if($(qtyElement)){
                            let qtyDta = $(qtyElement).val();
                            if(qtyDta){
                                qty = qtyDta;
                            }
                        }
                        price = parseFloat(price) + (parseFloat(extra_price) * parseInt(qty));
                        originalPrice = parseFloat(originalPrice) + (parseFloat(extra_price) * parseInt(qty));

                    }
                });

                let productQtyVal = $('#quantity').val();
                let productQty = 1;
                if(productQtyVal){
                    productQty = productQtyVal;
                }
                $('#finalPrice').html('$ ' + parseFloat(price * parseInt(productQty)).toFixed(2));
                $('#originalFinalPrice').html('$ ' + parseFloat(originalPrice * parseInt(productQty)).toFixed(2));
            }

        });



        {{--$('ul.pagination').hide();--}}
        {{--$(function() {--}}
        {{--    $('.infinite-scroll').jscroll({--}}
        {{--        autoTrigger: true,--}}
        {{--        loadingHtml: '<img class="center-block" src="{{ asset('assets/common/ajax-loader.gif') }}" alt="Loading..." />',--}}
        {{--        padding: 800,--}}
        {{--        nextSelector: '.pagination li.active + li a',--}}
        {{--        contentSelector: 'div.infinite-scroll',--}}
        {{--        callback: function() {--}}
        {{--            $('ul.pagination').remove();--}}
        {{--            $('.lazy').Lazy()--}}
        {{--        }--}}
        {{--    });--}}
        {{--});--}}
    </script>
@endsection

@section('content')

    <div class="theme-Breadcrumb">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb pl-1 pl-sm-0">
                            <li class="breadcrumb-item d-none d-md-block"><a href="{{ route('categories.list') }}">Categories</a> /</li>
                            @if($product->merchant)
                                @if($product->merchant->category)
                                    <li class="breadcrumb-item"><a href="{{ route('merchants.by_category', $product->merchant->category->friendly_url) }}">{{ $product->merchant->category->name }}</a> /</li>
                                @endif
                                <li class="breadcrumb-item"><a href="{{ route('merchant', $product->merchant->friendly_url) }}">{{ $product->merchant->name }}</a> /</li>
                            @endif
                            <li class="breadcrumb-item active d-none d-md-block" aria-current="page">{{ $product->name }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="wrapper-breadcrumb">
        @include('frontend.shared.alert')
        <div class="all-product-grid">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="product-dt-view">
                            <div class="row">
                                <div class="col-lg-4 col-md-4">
                                    <img src="{{ $product->image ? asset($product->image) : asset('assets/common/image_placeholder.png')}}" alt="{{ $product->name }}" class="img-fluid">
                                </div>
                                <div class="col-lg-8 col-md-8">
                                    <div class="product-dt-right">
                                        @if($product->merchant)
                                            <div class="d-flex flex-row align-items-center">
                                                <img src="{{ $product->merchant->logo ? asset($product->merchant->logo) : asset('assets/common/image_placeholder.png')}}" alt="{{ $product->merchant->name }}" width="50">
                                                <h4 class="mt-0 mb-0"><a href="{{ route('merchant', $product->merchant->friendly_url) }}">{{ $product->merchant->name }}</a></h4>
                                            </div>
                                        @endif
                                        <h2>{{ $product->name }}</h2>
                                        <form  method="POST" action="{{ route('checkout.add_to_cart') }}">
                                            @csrf
                                            <input type="hidden" value="{{ $product->id }}" name="product_id" id="product_id">
                                            <input type="hidden" value="{{ $product->sellPrice }}" name="basePrice" id="productPrice" disabled>
                                            <input type="hidden" value="{{ $product->originalPrice }}" name="originalPrice" id="productOriginalPrice" disabled>
                                            <div class="no-stock">
                                                <p class="pd-no">Product No.<span>{{ str_pad($product->id, 8, '0', STR_PAD_LEFT) }}</span></p>
                                                @if($product->always_on_stock)
                                                    <p class="stock-qty">Available<span>(In Stock)</span></p>
                                                @endif
                                            </div>

                                            @if($product->mutators_count > 0)
                                                @foreach($product->mutatorGroups as $mutatorGroupItem)
                                                    @if(count($mutatorGroupItem->mutators) > 0)
                                                    <div class="product-group-dt mt-2 gray-border">
                                                        <h6>{{ $mutatorGroupItem->name }}</h6>
                                                        @if($mutatorGroupItem->choice_mode === 'single')
                                                            <div class="customRadioContainer">
                                                                <div class="customRadio">
                                                                    @foreach($mutatorGroupItem->mutators as $mutatorGroupMutatorItem)
                                                                        <div class="customRadioItem primary">
                                                                            <input type="radio"
                                                                                   data-pricetarget="#mutatorItemPrice-{{ $mutatorGroupMutatorItem->id }}"
                                                                                   data-extraprice="{{ $mutatorGroupMutatorItem->extra_price }}"
                                                                                   data-qtytarget="#mutatorItemQty-{{ $mutatorGroupMutatorItem->id }}"
                                                                                   name="single_mutator[{{ $mutatorGroupItem->id }}]"
                                                                                   value="{{ $mutatorGroupMutatorItem->id }}"
                                                                                   @if($mutatorGroupItem->mutators->first()->id === $mutatorGroupMutatorItem->id) checked @endif
                                                                                   id="mutatorItem-{{ $mutatorGroupMutatorItem->id }}"
                                                                                   class="mutatorSelector"
                                                                            />
                                                                            <label for="mutatorItem-{{ $mutatorGroupMutatorItem->id }}">
                                                                                {{ $mutatorGroupMutatorItem->name }}
                                                                            </label>
                                                                            <div class="rightSide">
                                                                                <p class="price" id="mutatorItemPrice-{{ $mutatorGroupMutatorItem->id }}">+ ${{ $mutatorGroupMutatorItem->extra_price }}</p>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        @elseif($mutatorGroupItem->choice_mode === 'multiple')

                                                            <div class="customRadioContainer">
                                                                <div class="customRadio">
                                                                    @foreach($mutatorGroupItem->mutators as $mutatorGroupMutatorItem)
                                                                        <div class="customRadioItem primary @if($mutatorGroupItem->allow_quantity_selector) withQuantityPicker @endif">
                                                                            <input type="checkbox"
                                                                                   data-pricetarget="#mutatorItemPrice-{{ $mutatorGroupMutatorItem->id }}"
                                                                                   data-extraprice="{{ $mutatorGroupMutatorItem->extra_price }}"
                                                                                   data-qtytarget="#mutatorItemQty-{{ $mutatorGroupMutatorItem->id }}"
                                                                                   name="multiple_mutator[{{ $mutatorGroupMutatorItem->id }}]"
                                                                                   value="{{ $mutatorGroupMutatorItem->id }}"
                                                                                   id="mutatorItem-{{ $mutatorGroupMutatorItem->id }}"
                                                                                   class="mutatorSelector"
                                                                            />
                                                                            <label class="@if($mutatorGroupItem->allow_quantity_selector) withQuantityPicker @endif" for="mutatorItem-{{ $mutatorGroupMutatorItem->id }}">
                                                                                {{ $mutatorGroupMutatorItem->name }}
                                                                            </label>
                                                                            <div class="rightSide @if($mutatorGroupItem->allow_quantity_selector) withQuantityPicker @endif">
                                                                                <p class="price mr-3 @if($mutatorGroupItem->allow_quantity_selector) withQuantityPicker @endif" id="mutatorItemPrice-{{ $mutatorGroupMutatorItem->id }}">+ ${{ $mutatorGroupMutatorItem->extra_price }}</p>
                                                                                @if($mutatorGroupItem->allow_quantity_selector)
                                                                                    <div class="qty-product">
                                                                                        <div class="quantity buttons_added">
                                                                                            <input type="button" value="-" class="minus minus-btn"/>
                                                                                            <input
                                                                                                type="number"
                                                                                                step="1"
                                                                                                name="multiple_mutator_quantity[{{ $mutatorGroupMutatorItem->id }}]"
                                                                                                value="1"
                                                                                                min="1"
                                                                                                class="mutatorQty input-text qty text"
                                                                                                data-parenttarget="#mutatorItem-{{ $mutatorGroupMutatorItem->id }}"
                                                                                                data-pricetarget="#mutatorItemPrice-{{ $mutatorGroupMutatorItem->id }}"
                                                                                                id="mutatorItemQty-{{ $mutatorGroupMutatorItem->id }}"
                                                                                            />
                                                                                            <input type="button" value="+" class="plus plus-btn"/>
                                                                                        </div>
                                                                                    </div>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    @endif
                                                @endforeach
                                            @endif
                                            <p class="pp-descp">
                                                {{ $product->description }}
                                            </p>

                                            <div class="product-group-dt">
                                                <ul>
                                                    @if($product->formattedOriginalPrice > 0)
                                                        @if($product->hasDiscount)
                                                            <li><div class="main-price color-discount">Price<span id="finalPrice">$ {{ $product->formattedSellPrice }}</span> <span class="itm-badge">{{ $product->discount }} % OFF</span></div></li>
                                                            <li><div class="main-price color-gray"><del><span class="color-gray"><small id="originalFinalPrice">$ {{ $product->formattedOriginalPrice }}</small></span></del></div></li>
                                                            @if($product->mutators_count > 0)
                                                                <p><small>Discounts are applied to the base price excluding combinations</small></p>
                                                            @endif
                                                        @else
                                                            <li><div class="main-price">Price<span id="finalPrice">$ {{ $product->formattedSellPrice }}</span></div></li>
                                                        @endif
                                                    @else
                                                        <li>
                                                            <div class="main-price">Price needs Confirmation</div>
                                                            <p class="pt-3">After we confirm the order total, you will receive a notification including your summary and payment instructions</p>
                                                        </li>
                                                    @endif

                                                </ul>
                                                <ul class="gty-wish-share">
                                                    <li>
                                                        <div class="qty-product">
                                                            <div class="quantity buttons_added">
                                                                <input type="button" value="-" class="minus minus-btn">
                                                                <input
                                                                    type="number"
                                                                    step="1"
                                                                    min="1"
                                                                    name="quantity"
                                                                    value="1"
                                                                    class="input-text qty text"
                                                                    id="quantity"
                                                                >
                                                                <input type="button" value="+" class="plus plus-btn">
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                                <ul class="ordr-crt-share">
                                                    <li><button class="add-cart-btn hover-btn d-block"><i class="uil uil-shopping-cart-alt"></i>Add to Cart</button></li>
{{--                                                    <li><button class="order-btn hover-btn">Order Now</button></li>--}}
                                                </ul>
                                            </div>
                                        </form>
                                        <div class="pdp-details">
                                            <ul>
                                                <li>
                                                    <div class="pdp-group-dt">
                                                        <div class="pdp-icon"><i class="uil uil-usd-circle"></i></div>
                                                        <div class="pdp-text-dt">
                                                            <span>Prices</span>
                                                            <p>All prices are in Barbados dollars</p>
                                                        </div>
                                                    </div>
                                                </li>
{{--                                                @if($product->merchant)--}}
{{--                                                    <li>--}}
{{--                                                        <div class="pdp-group-dt">--}}
{{--                                                            <div class="pdp-text-dt">--}}
{{--                                                                @if($product->merchant->phone)--}}
{{--                                                                    <p><strong>Phone</strong></p>--}}
{{--                                                                    <p><i class="fa fa-phone mr-2"></i>{{ $product->merchant->phone }}</p>--}}
{{--                                                                @endif--}}
{{--                                                                @if($product->merchant->email)--}}
{{--                                                                    <p><strong>Email</strong></p>--}}
{{--                                                                    <p><i class="fa fa-envelope mr-2"></i>{{ $product->merchant->email }}</p>--}}
{{--                                                                @endif--}}
{{--                                                                @if($product->merchant->address)--}}
{{--                                                                    <p><strong>Address</strong></p>--}}
{{--                                                                    <p>--}}
{{--                                                                        <i class="fa fa-map-marker mr-2"></i>--}}
{{--                                                                        {{ $product->merchant->address }}--}}
{{--                                                                    </p>--}}
{{--                                                                @endif--}}
{{--                                                            </div>--}}
{{--                                                        </div>--}}
{{--                                                    </li>--}}
{{--                                                @endif--}}
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{--                <div class="row">--}}
                {{--                    <div class="col-lg-4 col-md-12">--}}
                {{--                        <div class="pdpt-bg">--}}
                {{--                            <div class="pdpt-title">--}}
                {{--                                <h4>More Like This</h4>--}}
                {{--                            </div>--}}
                {{--                            <div class="pdpt-body scrollstyle_4">--}}
                {{--                                <div class="cart-item border_radius">--}}
                {{--                                    <a href="http://themelthemes.net/html-items/theme_supermarket_demo/single_product_view.html" class="cart-product-img">--}}
                {{--                                        <img src="images/product/img-6.jpg" alt="">--}}
                {{--                                        <div class="offer-badge">4% OFF</div>--}}
                {{--                                    </a>--}}
                {{--                                    <div class="cart-text">--}}
                {{--                                        <h4>Product Title Here</h4>--}}
                {{--                                        <div class="cart-radio">--}}
                {{--                                            <ul class="kggrm-now">--}}
                {{--                                                <li>--}}
                {{--                                                    <input type="radio" id="k1" name="cart1">--}}
                {{--                                                    <label for="k1">0.50</label>--}}
                {{--                                                </li>--}}
                {{--                                                <li>--}}
                {{--                                                    <input type="radio" id="k2" name="cart1">--}}
                {{--                                                    <label for="k2">1kg</label>--}}
                {{--                                                </li>--}}
                {{--                                                <li>--}}
                {{--                                                    <input type="radio" id="k3" name="cart1">--}}
                {{--                                                    <label for="k3">2kg</label>--}}
                {{--                                                </li>--}}
                {{--                                                <li>--}}
                {{--                                                    <input type="radio" id="k4" name="cart1">--}}
                {{--                                                    <label for="k4">3kg</label>--}}
                {{--                                                </li>--}}
                {{--                                            </ul>--}}
                {{--                                        </div>--}}
                {{--                                        <div class="qty-group">--}}
                {{--                                            <div class="quantity buttons_added">--}}
                {{--                                                <input type="button" value="-" class="minus minus-btn">--}}
                {{--                                                <input type="number" step="1" name="quantity" value="1" class="input-text qty text">--}}
                {{--                                                <input type="button" value="+" class="plus plus-btn">--}}
                {{--                                            </div>--}}
                {{--                                            <div class="cart-item-price">$12 <span>$15</span></div>--}}
                {{--                                        </div>--}}
                {{--                                    </div>--}}
                {{--                                </div>--}}
                {{--                                <div class="cart-item border_radius">--}}
                {{--                                    <a href="http://themelthemes.net/html-items/theme_supermarket_demo/single_product_view.html" class="cart-product-img">--}}
                {{--                                        <img src="images/product/img-2.jpg" alt="">--}}
                {{--                                        <div class="offer-badge">6% OFF</div>--}}
                {{--                                    </a>--}}
                {{--                                    <div class="cart-text">--}}
                {{--                                        <h4>Product Title Here</h4>--}}
                {{--                                        <div class="cart-radio">--}}
                {{--                                            <ul class="kggrm-now">--}}
                {{--                                                <li>--}}
                {{--                                                    <input type="radio" id="k5" name="cart2">--}}
                {{--                                                    <label for="k5">0.50</label>--}}
                {{--                                                </li>--}}
                {{--                                                <li>--}}
                {{--                                                    <input type="radio" id="k6" name="cart2">--}}
                {{--                                                    <label for="k6">1kg</label>--}}
                {{--                                                </li>--}}
                {{--                                                <li>--}}
                {{--                                                    <input type="radio" id="k7" name="cart2">--}}
                {{--                                                    <label for="k7">2kg</label>--}}
                {{--                                                </li>--}}
                {{--                                            </ul>--}}
                {{--                                        </div>--}}
                {{--                                        <div class="qty-group">--}}
                {{--                                            <div class="quantity buttons_added">--}}
                {{--                                                <input type="button" value="-" class="minus minus-btn">--}}
                {{--                                                <input type="number" step="1" name="quantity" value="1" class="input-text qty text">--}}
                {{--                                                <input type="button" value="+" class="plus plus-btn">--}}
                {{--                                            </div>--}}
                {{--                                            <div class="cart-item-price">$24 <span>$30</span></div>--}}
                {{--                                        </div>--}}
                {{--                                    </div>--}}
                {{--                                </div>--}}
                {{--                                <div class="cart-item border_radius">--}}
                {{--                                    <a href="http://themelthemes.net/html-items/theme_supermarket_demo/single_product_view.html" class="cart-product-img">--}}
                {{--                                        <img src="images/product/img-5.jpg" alt="">--}}
                {{--                                    </a>--}}
                {{--                                    <div class="cart-text">--}}
                {{--                                        <h4>Product Title Here</h4>--}}
                {{--                                        <div class="cart-radio">--}}
                {{--                                            <ul class="kggrm-now">--}}
                {{--                                                <li>--}}
                {{--                                                    <input type="radio" id="k8" name="cart3">--}}
                {{--                                                    <label for="k8">0.50</label>--}}
                {{--                                                </li>--}}
                {{--                                                <li>--}}
                {{--                                                    <input type="radio" id="k9" name="cart3">--}}
                {{--                                                    <label for="k9">1kg</label>--}}
                {{--                                                </li>--}}
                {{--                                                <li>--}}
                {{--                                                    <input type="radio" id="k10" name="cart3">--}}
                {{--                                                    <label for="k10">1.50kg</label>--}}
                {{--                                                </li>--}}
                {{--                                            </ul>--}}
                {{--                                        </div>--}}
                {{--                                        <div class="qty-group">--}}
                {{--                                            <div class="quantity buttons_added">--}}
                {{--                                                <input type="button" value="-" class="minus minus-btn">--}}
                {{--                                                <input type="number" step="1" name="quantity" value="1" class="input-text qty text">--}}
                {{--                                                <input type="button" value="+" class="plus plus-btn">--}}
                {{--                                            </div>--}}
                {{--                                            <div class="cart-item-price">$15</div>--}}
                {{--                                        </div>--}}
                {{--                                    </div>--}}
                {{--                                </div>--}}
                {{--                            </div>--}}
                {{--                        </div>--}}
                {{--                    </div>--}}
                {{--                    <div class="col-lg-8 col-md-12">--}}
                {{--                        <div class="pdpt-bg">--}}
                {{--                            <div class="pdpt-title">--}}
                {{--                                <h4>Product Details</h4>--}}
                {{--                            </div>--}}
                {{--                            <div class="pdpt-body scrollstyle_4">--}}
                {{--                                <div class="pdct-dts-1">--}}
                {{--                                    <div class="pdct-dt-step">--}}
                {{--                                        <h4>Description</h4>--}}
                {{--                                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque posuere nunc in condimentum maximus. Integer interdum sem sollicitudin, porttitor felis in, mollis quam. Nunc gravida erat eu arcu interdum eleifend. Cras tortor velit, dignissim sit amet hendrerit sed, ultricies eget est. Donec eget urna sed metus dignissim cursus.</p>--}}
                {{--                                    </div>--}}
                {{--                                    <div class="pdct-dt-step">--}}
                {{--                                        <h4>Benefits</h4>--}}
                {{--                                        <div class="product_attr">--}}
                {{--                                            Aliquam nec nulla accumsan, accumsan nisl in, rhoncus sapien.<br>--}}
                {{--                                            In mollis lorem a porta congue.<br>--}}
                {{--                                            Sed quis neque sit amet nulla maximus dignissim id mollis urna.<br>--}}
                {{--                                            Cras non libero at lorem laoreet finibus vel et turpis.<br>--}}
                {{--                                            Mauris maximus ligula at sem lobortis congue.<br>--}}
                {{--                                        </div>--}}
                {{--                                    </div>--}}
                {{--                                    <div class="pdct-dt-step">--}}
                {{--                                        <h4>How to Use</h4>--}}
                {{--                                        <div class="product_attr">--}}
                {{--                                            The peeled, orange segments can be added to the daily fruit bowl, and its juice is a refreshing drink.--}}
                {{--                                        </div>--}}
                {{--                                    </div>--}}
                {{--                                    <div class="pdct-dt-step">--}}
                {{--                                        <h4>Seller</h4>--}}
                {{--                                        <div class="product_attr">--}}
                {{--                                            themelthemes Pvt Ltd, Sks Nagar, Near Mbd Mall, Ludhana, 141001--}}
                {{--                                        </div>--}}
                {{--                                    </div>--}}
                {{--                                    <div class="pdct-dt-step">--}}
                {{--                                        <h4>Disclaimer</h4>--}}
                {{--                                        <p>Phasellus efficitur eu ligula consequat ornare. Nam et nisl eget magna aliquam consectetur. Aliquam quis tristique lacus. Donec eget nibh et quam maximus rutrum eget ut ipsum. Nam fringilla metus id dui sollicitudin, sit amet maximus sapien malesuada.</p>--}}
                {{--                                    </div>--}}
                {{--                                </div>--}}
                {{--                            </div>--}}
                {{--                        </div>--}}
                {{--                    </div>--}}
                {{--                </div>--}}
            </div>
        </div>
        <!-- Featured Products Start -->
    {{--        <div class="section145">--}}
    {{--            <div class="container">--}}
    {{--                <div class="row">--}}
    {{--                    <div class="col-md-12">--}}
    {{--                        <div class="main-title-tt">--}}
    {{--                            <div class="main-title-left">--}}
    {{--                                <span>For You</span>--}}
    {{--                                <h2>Top Featured Products</h2>--}}
    {{--                            </div>--}}
    {{--                            <a href="#" class="see-more-btn">See All</a>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                    <div class="col-md-12">--}}
    {{--                        <div class="owl-carousel featured-slider owl-theme">--}}
    {{--                            <div class="item">--}}
    {{--                                <div class="product-item">--}}
    {{--                                    <a href="http://themelthemes.net/html-items/theme_supermarket_demo/single_product_view.html" class="product-img">--}}
    {{--                                        <img src="images/product/img-1.jpg" alt="">--}}
    {{--                                        <div class="product-absolute-options">--}}
    {{--                                            <span class="offer-badge-1">6% off</span>--}}
    {{--                                            <span class="like-icon" title="wishlist"></span>--}}
    {{--                                        </div>--}}
    {{--                                    </a>--}}
    {{--                                    <div class="product-text-dt">--}}
    {{--                                        <p>Available<span>(In Stock)</span></p>--}}
    {{--                                        <h4>Product Title Here</h4>--}}
    {{--                                        <div class="product-price">$12 <span>$15</span></div>--}}
    {{--                                        <div class="qty-cart">--}}
    {{--                                            <div class="quantity buttons_added">--}}
    {{--                                                <input type="button" value="-" class="minus minus-btn">--}}
    {{--                                                <input type="number" step="1" name="quantity" value="1" class="input-text qty text">--}}
    {{--                                                <input type="button" value="+" class="plus plus-btn">--}}
    {{--                                            </div>--}}
    {{--                                            <span class="cart-icon"><i class="uil uil-shopping-cart-alt"></i></span>--}}
    {{--                                        </div>--}}
    {{--                                    </div>--}}
    {{--                                </div>--}}
    {{--                            </div>--}}
    {{--                            <div class="item">--}}
    {{--                                <div class="product-item">--}}
    {{--                                    <a href="http://themelthemes.net/html-items/theme_supermarket_demo/single_product_view.html" class="product-img">--}}
    {{--                                        <img src="images/product/img-2.jpg" alt="">--}}
    {{--                                        <div class="product-absolute-options">--}}
    {{--                                            <span class="offer-badge-1">2% off</span>--}}
    {{--                                            <span class="like-icon" title="wishlist"></span>--}}
    {{--                                        </div>--}}
    {{--                                    </a>--}}
    {{--                                    <div class="product-text-dt">--}}
    {{--                                        <p>Available<span>(In Stock)</span></p>--}}
    {{--                                        <h4>Product Title Here</h4>--}}
    {{--                                        <div class="product-price">$10 <span>$13</span></div>--}}
    {{--                                        <div class="qty-cart">--}}
    {{--                                            <div class="quantity buttons_added">--}}
    {{--                                                <input type="button" value="-" class="minus minus-btn">--}}
    {{--                                                <input type="number" step="1" name="quantity" value="1" class="input-text qty text">--}}
    {{--                                                <input type="button" value="+" class="plus plus-btn">--}}
    {{--                                            </div>--}}
    {{--                                            <span class="cart-icon"><i class="uil uil-shopping-cart-alt"></i></span>--}}
    {{--                                        </div>--}}
    {{--                                    </div>--}}
    {{--                                </div>--}}
    {{--                            </div>--}}
    {{--                            <div class="item">--}}
    {{--                                <div class="product-item">--}}
    {{--                                    <a href="http://themelthemes.net/html-items/theme_supermarket_demo/single_product_view.html" class="product-img">--}}
    {{--                                        <img src="images/product/img-3.jpg" alt="">--}}
    {{--                                        <div class="product-absolute-options">--}}
    {{--                                            <span class="offer-badge-1">5% off</span>--}}
    {{--                                            <span class="like-icon" title="wishlist"></span>--}}
    {{--                                        </div>--}}
    {{--                                    </a>--}}
    {{--                                    <div class="product-text-dt">--}}
    {{--                                        <p>Available<span>(In Stock)</span></p>--}}
    {{--                                        <h4>Product Title Here</h4>--}}
    {{--                                        <div class="product-price">$5 <span>$8</span></div>--}}
    {{--                                        <div class="qty-cart">--}}
    {{--                                            <div class="quantity buttons_added">--}}
    {{--                                                <input type="button" value="-" class="minus minus-btn">--}}
    {{--                                                <input type="number" step="1" name="quantity" value="1" class="input-text qty text">--}}
    {{--                                                <input type="button" value="+" class="plus plus-btn">--}}
    {{--                                            </div>--}}
    {{--                                            <span class="cart-icon"><i class="uil uil-shopping-cart-alt"></i></span>--}}
    {{--                                        </div>--}}
    {{--                                    </div>--}}
    {{--                                </div>--}}
    {{--                            </div>--}}
    {{--                            <div class="item">--}}
    {{--                                <div class="product-item">--}}
    {{--                                    <a href="http://themelthemes.net/html-items/theme_supermarket_demo/single_product_view.html" class="product-img">--}}
    {{--                                        <img src="images/product/img-4.jpg" alt="">--}}
    {{--                                        <div class="product-absolute-options">--}}
    {{--                                            <span class="offer-badge-1">3% off</span>--}}
    {{--                                            <span class="like-icon" title="wishlist"></span>--}}
    {{--                                        </div>--}}
    {{--                                    </a>--}}
    {{--                                    <div class="product-text-dt">--}}
    {{--                                        <p>Available<span>(In Stock)</span></p>--}}
    {{--                                        <h4>Product Title Here</h4>--}}
    {{--                                        <div class="product-price">$15 <span>$20</span></div>--}}
    {{--                                        <div class="qty-cart">--}}
    {{--                                            <div class="quantity buttons_added">--}}
    {{--                                                <input type="button" value="-" class="minus minus-btn">--}}
    {{--                                                <input type="number" step="1" name="quantity" value="1" class="input-text qty text">--}}
    {{--                                                <input type="button" value="+" class="plus plus-btn">--}}
    {{--                                            </div>--}}
    {{--                                            <span class="cart-icon"><i class="uil uil-shopping-cart-alt"></i></span>--}}
    {{--                                        </div>--}}
    {{--                                    </div>--}}
    {{--                                </div>--}}
    {{--                            </div>--}}
    {{--                            <div class="item">--}}
    {{--                                <div class="product-item">--}}
    {{--                                    <a href="http://themelthemes.net/html-items/theme_supermarket_demo/single_product_view.html" class="product-img">--}}
    {{--                                        <img src="images/product/img-5.jpg" alt="">--}}
    {{--                                        <div class="product-absolute-options">--}}
    {{--                                            <span class="offer-badge-1">2% off</span>--}}
    {{--                                            <span class="like-icon" title="wishlist"></span>--}}
    {{--                                        </div>--}}
    {{--                                    </a>--}}
    {{--                                    <div class="product-text-dt">--}}
    {{--                                        <p>Available<span>(In Stock)</span></p>--}}
    {{--                                        <h4>Product Title Here</h4>--}}
    {{--                                        <div class="product-price">$9 <span>$10</span></div>--}}
    {{--                                        <div class="qty-cart">--}}
    {{--                                            <div class="quantity buttons_added">--}}
    {{--                                                <input type="button" value="-" class="minus minus-btn">--}}
    {{--                                                <input type="number" step="1" name="quantity" value="1" class="input-text qty text">--}}
    {{--                                                <input type="button" value="+" class="plus plus-btn">--}}
    {{--                                            </div>--}}
    {{--                                            <span class="cart-icon"><i class="uil uil-shopping-cart-alt"></i></span>--}}
    {{--                                        </div>--}}
    {{--                                    </div>--}}
    {{--                                </div>--}}
    {{--                            </div>--}}
    {{--                            <div class="item">--}}
    {{--                                <div class="product-item">--}}
    {{--                                    <a href="http://themelthemes.net/html-items/theme_supermarket_demo/single_product_view.html" class="product-img">--}}
    {{--                                        <img src="images/product/img-6.jpg" alt="">--}}
    {{--                                        <div class="product-absolute-options">--}}
    {{--                                            <span class="offer-badge-1">2% off</span>--}}
    {{--                                            <span class="like-icon" title="wishlist"></span>--}}
    {{--                                        </div>--}}
    {{--                                    </a>--}}
    {{--                                    <div class="product-text-dt">--}}
    {{--                                        <p>Available<span>(In Stock)</span></p>--}}
    {{--                                        <h4>Product Title Here</h4>--}}
    {{--                                        <div class="product-price">$7 <span>$8</span></div>--}}
    {{--                                        <div class="qty-cart">--}}
    {{--                                            <div class="quantity buttons_added">--}}
    {{--                                                <input type="button" value="-" class="minus minus-btn">--}}
    {{--                                                <input type="number" step="1" name="quantity" value="1" class="input-text qty text">--}}
    {{--                                                <input type="button" value="+" class="plus plus-btn">--}}
    {{--                                            </div>--}}
    {{--                                            <span class="cart-icon"><i class="uil uil-shopping-cart-alt"></i></span>--}}
    {{--                                        </div>--}}
    {{--                                    </div>--}}
    {{--                                </div>--}}
    {{--                            </div>--}}
    {{--                            <div class="item">--}}
    {{--                                <div class="product-item">--}}
    {{--                                    <a href="http://themelthemes.net/html-items/theme_supermarket_demo/single_product_view.html" class="product-img">--}}
    {{--                                        <img src="images/product/img-7.jpg" alt="">--}}
    {{--                                        <div class="product-absolute-options">--}}
    {{--                                            <span class="offer-badge-1">1% off</span>--}}
    {{--                                            <span class="like-icon" title="wishlist"></span>--}}
    {{--                                        </div>--}}
    {{--                                    </a>--}}
    {{--                                    <div class="product-text-dt">--}}
    {{--                                        <p>Available<span>(In Stock)</span></p>--}}
    {{--                                        <h4>Product Title Here</h4>--}}
    {{--                                        <div class="product-price">$6.95 <span>$7</span></div>--}}
    {{--                                        <div class="qty-cart">--}}
    {{--                                            <div class="quantity buttons_added">--}}
    {{--                                                <input type="button" value="-" class="minus minus-btn">--}}
    {{--                                                <input type="number" step="1" name="quantity" value="1" class="input-text qty text">--}}
    {{--                                                <input type="button" value="+" class="plus plus-btn">--}}
    {{--                                            </div>--}}
    {{--                                            <span class="cart-icon"><i class="uil uil-shopping-cart-alt"></i></span>--}}
    {{--                                        </div>--}}
    {{--                                    </div>--}}
    {{--                                </div>--}}
    {{--                            </div>--}}
    {{--                            <div class="item">--}}
    {{--                                <div class="product-item">--}}
    {{--                                    <a href="http://themelthemes.net/html-items/theme_supermarket_demo/single_product_view.html" class="product-img">--}}
    {{--                                        <img src="images/product/img-8.jpg" alt="">--}}
    {{--                                        <div class="product-absolute-options">--}}
    {{--                                            <span class="offer-badge-1">3% off</span>--}}
    {{--                                            <span class="like-icon" title="wishlist"></span>--}}
    {{--                                        </div>--}}
    {{--                                    </a>--}}
    {{--                                    <div class="product-text-dt">--}}
    {{--                                        <p>Available<span>(In Stock)</span></p>--}}
    {{--                                        <h4>Product Title Here</h4>--}}
    {{--                                        <div class="product-price">$8 <span>$10</span></div>--}}
    {{--                                        <div class="qty-cart">--}}
    {{--                                            <div class="quantity buttons_added">--}}
    {{--                                                <input type="button" value="-" class="minus minus-btn">--}}
    {{--                                                <input type="number" step="1" name="quantity" value="1" class="input-text qty text">--}}
    {{--                                                <input type="button" value="+" class="plus plus-btn">--}}
    {{--                                            </div>--}}
    {{--                                            <span class="cart-icon"><i class="uil uil-shopping-cart-alt"></i></span>--}}
    {{--                                        </div>--}}
    {{--                                    </div>--}}
    {{--                                </div>--}}
    {{--                            </div>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--        </div>--}}
    <!-- Featured Products End -->
    </div>

@endsection
