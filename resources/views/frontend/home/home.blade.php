@extends('frontend.app')

@section('styles')

@endsection

@section('scripts')


@endsection

@section('content')

    <div class="wrapper">

        @include('frontend.shared.alert')
{{--        <div class="section145">--}}
{{--            <div class="container">--}}
{{--                <div class="row">--}}
{{--                    <div class="col-md-12">--}}
{{--                        <div class="main-title-tt">--}}
{{--                            <div class="main-title-left">--}}
{{--                                <span>Shop By</span>--}}
{{--                                <h2>Categories</h2>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="col-md-12">--}}
{{--                        <div class="ui search">--}}
{{--                            <div class="ui left icon input swdh10">--}}
{{--                                <input class="prompt srch10" type="text" placeholder="Search for products..">--}}
{{--                                <i class='uil uil-search-alt icon icon1'></i>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}

        @include('frontend.home.banners')

        @include('frontend.home.category_grid')

        <div class="section145">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="main-title-tt">
                            <div class="main-title-left">
                                <span>Everything you need</span>
                                <h2>Popular Products</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="owl-carousel featured-slider owl-theme">
                            @foreach($featuredProducts as $featuredProductItem)

                                <div class="product-item mb-4 mb-sm-4">
                                    <a href="{{ route('merchant.product', ['merchant_friendly_url' => ($featuredProductItem->merchant ? $featuredProductItem->merchant->friendly_url : 'wifetch-merchant'), 'product_friendly_url' => $featuredProductItem->friendly_url]) }}" class="product-img">
                                        <img src="{{ asset('assets/common/ajax-loader-canvas.gif') }}"  data-src="{{ $featuredProductItem->image ? asset($featuredProductItem->image) : asset('assets/common/image_placeholder.png')}}" alt="{{ $featuredProductItem->name }}" class="lazy">
                                        @if($featuredProductItem->hasDiscount)
                                            <div class="product-absolute-options">
                                                <span class="offer-badge-1">{{ $featuredProductItem->discount }}% off</span>
                                            </div>
                                        @endif
                                    </a>
                                    <div class="product-text-dt">
                                        <a href="{{ route('merchant.product', ['merchant_friendly_url' => ($featuredProductItem->merchant ? $featuredProductItem->merchant->friendly_url : 'wifetch-merchant'), 'product_friendly_url' => $featuredProductItem->friendly_url]) }}" class="product-img">
{{--                                            @if($featuredProductItem->availableStock > 0)--}}
{{--                                                <p class="text-success">In Stock</p>--}}
{{--                                            @else--}}
{{--                                                <p class="text-danger font-weight-bold">Not Available</p>--}}
{{--                                            @endif--}}
                                            <h4 class="max-lines">{{ $featuredProductItem->name }}</h4>
                                            @if($featuredProductItem->sellPrice && $featuredProductItem->sellPrice > 0)
                                                <div class="product-price">
                                                    $ {{ $featuredProductItem->formattedSellPrice }}
                                                    @if($featuredProductItem->hasDiscount)<span>${{ $featuredProductItem->formattedOriginalPrice }}</span>@endif
                                                </div>
                                            @else
                                                <p class="font-weight-bold">Price needs<span>Confirmation</span></p>
                                            @endif
                                        </a>
{{--                                        <hr>--}}
{{--                                        <a href="{{ route('merchant.product', ['merchant_friendly_url' => ($featuredProductItem->merchant ? $featuredProductItem->merchant->friendly_url : 'wifetch-merchant'), 'product_friendly_url' => $featuredProductItem->friendly_url]) }}" class="cart-icon-btn"><strong>View</strong></a>--}}
                                    </div>

                                </div>

{{--                                <div class="col-lg-4 col-md-6" id="productItem{{ $featuredProductItem->id }}">--}}
{{--                                    --}}
{{--                                </div>--}}

                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="section145">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="main-title-tt">
                            <div class="main-title-left">
                                <h2>Top Featured Merchants</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="owl-carousel featured-slider owl-theme">
                            @foreach($featuredMerchants as $featuredMerchantItem)
                            <div class="item">
                                <div class="product-item">
                                    <a href="{{ route('merchant', $featuredMerchantItem->friendly_url) }}" class="product-img">
                                        <img src="{{ asset('assets/common/ajax-loader-canvas.gif') }}"  data-src="{{ $featuredMerchantItem->logo ? asset($featuredMerchantItem->logo) : asset('assets/common/image_placeholder.png')}}" alt="{{ $featuredMerchantItem->name }}" class="lazy">
{{--                                        <div class="product-absolute-options">--}}
{{--                                            <span class="offer-badge-1">6% off</span>--}}
{{--                                            <span class="like-icon" title="wishlist"></span>--}}
{{--                                        </div>--}}
                                    </a>
                                    <div class="product-text-dt">
                                        <h4 class="max-lines">{{ $featuredMerchantItem->name }}</h4>
{{--                                        <p>{{ $featuredMerchantItem->products_count }} <span>Products</span></p>--}}
                                        <hr>
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
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="all-product-grid">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-md-12 mt-5 mb-5">
                        <hr>
                    </div>
                    <div class="col-lg-12 col-md-12">
                        <div class="job-des-dt142 policy-des-dt">
                            <h1 class="text-center pt-2 pt-md-4 pb-2 pb-md-4">WiFetch. We do it for you. Caribbean Marketplace</h1>
                            <p class="text-center pb-2 pb-md-4">
                                WiFetch is transforming the way goods move around in the Caribbean locally, enabling anyone to have anything delivered on-demand. Our revolutionary local Logistics platform connects customers with local Fetchers who can deliver anything from any of our partner vendors within hours. We empower communities to shop local and remotely and empower businesses through our API to offer delivery at the most economical/most efficient cost.
                            </p>
                        </div>
                        <div class="job-des-dt142 policy-des-dt text-center">
                            <h1 class="text-center pt-2 pt-md-4 pb-2 pb-md-4">WiFetch is Powered by UNDP</h1>
                            <p class="text-center pb-2 pb-4">UNDP and WiFETCH will connect businesses - who have lost customers - to buyers; it will also continue ensuring safe deliveries at home, assisting Barbadians who face special difficulties in procuring for their daily needs, by integrating hotline and volunteer services.</p>

                            <a href="https://www.bb.undp.org/content/barbados/en/home/presscenter/pressreleases/20191/digital-economy-provides-crisis-jobs-and-safe-services/" class="main-btn-border" title="Digital Economy Provides Crisis Jobs and Safe Services">
                                View Announcement
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>

@endsection
