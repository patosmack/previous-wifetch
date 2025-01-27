@extends('frontend.app')

@section('scripts')
    <script src="{{ asset('js/jquery.jscroll.min.js') }}"></script>
    <script>
        $('ul.pagination').hide();
        $(function() {
            $('.infinite-scroll').jscroll({
                autoTrigger: true,
                loadingHtml: '<img class="center-block" src="{{ asset('assets/common/ajax-loader.gif') }}" alt="Loading..." />',
                padding: 800,
                nextSelector: '.pagination li.active + li a',
                contentSelector: 'div.infinite-scroll',
                callback: function() {
                    $('ul.pagination').remove();
                    $('.lazy').Lazy()
                }
            });

            $('.select_target_to_link').on('change', function(){
                window.location = $(this).val();
            });
        });
    </script>
@endsection

@section('content')

{{--    <div class="theme-Breadcrumb">--}}
{{--        <div class="container">--}}
{{--            <div class="row">--}}
{{--                <div class="col-md-12">--}}
{{--                    <nav aria-label="breadcrumb">--}}
{{--                        <ol class="breadcrumb pl-1 pl-sm-0">--}}
{{--                            <li class="breadcrumb-item d-none d-md-block"><a href="{{ route('home') }}">Search</a> /</li>--}}
{{--                            @if($merchant->category)--}}
{{--                                <li class="breadcrumb-item"><a href="{{ route('merchants.by_category', $merchant->category->friendly_url) }}">{{ $merchant->category->name }}</a> /</li>--}}
{{--                            @endif--}}
{{--                            <li class="breadcrumb-item active d-none d-sm-inline" aria-current="page">{{ $headerSearch }}</li>--}}
{{--                        </ol>--}}
{{--                    </nav>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}

    <div class="wrapper">

        @include('frontend.shared.alert')

{{--        <div class="container-fluid p-0 d-none d-sm-block">--}}
{{--            <div class="row no-gutters">--}}
{{--                <div class="col-md-12 mt-1">--}}
{{--                    @if($merchant->category)--}}
{{--                        <img src="{{ asset($merchant->category->cover_image) }}" alt="" class="cover-image img-fluid">--}}
{{--                    @endif--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}

        <div class="all-product-grid">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 col-md-4">
                        <div class="left-side-tabs mt-0 p-3 p-sm-0">
                            <div class="row pb-2 pt-4">

                                <div class="col-12 d-block d-sm-none pb-4">
                                    <h1>Search result</h1>
                                </div>

                                <div class="col-12">
                                    <form action="{{ route('merchant.product.search', $headerSearch) }}" method="GET">
                                        @if($sort)
                                            <input type="hidden" name="sort" value="{{ $sort }}">
                                        @endif
                                        <div class="ui search ml-1 mr-1">
                                            <div class="ui left icon input swdh10">
                                                <input class="prompt srch10" type="text" name="search" value="{{ $headerSearch }}" placeholder="Search for products.." autofocus>
                                                <i class='uil uil-search-alt icon icon1'></i>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-12 d-block d-sm-none mt-4">
                                    <label for="select_merchant_target_mobile">Select Merchant</label>
                                    <select id="select_merchant_target_mobile" class="form-control select_target_to_link">
                                        @foreach($merchants as $merchantItem)
                                            <option value="{{ route('merchant', ['friendly_url' => $merchantItem->friendly_url,'sort' => $sort, 'search'=> $headerSearch]) }}" >{{ $merchantItem->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 d-block d-sm-none mt-4">
                                    <select id="sort_target_mobile" class="form-control select_target_to_link">
                                        <option value="{{ route('merchant.product.search', ['search' => $headerSearch,'sort' => '-price']) }}" @if($sort === '-price') selected @endif>Price - Low to High</option>
                                        <option value="{{ route('merchant.product.search', ['search' => $headerSearch,'sort' => 'price']) }}" @if($sort === 'price') selected @endif>Price - High to Low</option>
                                        <option value="{{ route('merchant.product.search', ['search' => $headerSearch,'sort' => 'name']) }}" @if($sort === 'name') selected @endif>Alphabetical</option>
                                        <option value="{{ route('merchant.product.search', ['search' => $headerSearch,'sort' => '-discount']) }}" @if($sort === '-discount') selected @endif>% Off - Low to High</option>
                                        <option value="{{ route('merchant.product.search', ['search' => $headerSearch,'sort' => 'discount']) }}" @if($sort === 'discount') selected @endif>% Off - High to Low</option>
                                    </select>
                                </div>
                            </div>
                            @if(count($merchants) > 0)
                                <hr class="d-none d-sm-block">
                                <div class="dashboard-left-links d-none d-sm-block">
                                    @foreach($merchants as $merchantItem)
                                    <a href="{{ route('merchant', ['friendly_url' => $merchantItem->friendly_url,'sort' => $sort, 'search'=> $headerSearch]) }}" class="user-item">
                                        <div class="d-flex flex-row align-items-center">
                                            @if($merchantItem->logo)<img src="{{ asset($merchantItem->logo) }}" alt="{{ $merchantItem->name }}" width="40" class="mr-1">@endif
                                            <h4 class="mt-0 mb-0">{{ $merchantItem->name }}</h4>
                                        </div>
                                    </a>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-9 col-md-8">
                        <div class="product-top-dt mb-3">
                            <div class="product-left-title mt-3 mt-sm-0">
                                <h1 class="d-none d-sm-block">Search result</h1>
                                @if(count($merchantProducts) === 0)
                                    <h4>No products matching your search parameter</h4>
                                @endif
                            </div>
{{--                            @if($merchant->cover_image)--}}
{{--                                <img src="{{ asset($merchant->cover_image) }}" alt="" class="cover-image img-fluid mt-3 mb-3">--}}
{{--                            @endif--}}
                            <div class="product-sort d-none d-sm-block">
                                <div class="ui selection dropdown vchrt-dropdown">
                                    <i class="dropdown icon d-icon"></i>
                                    <div class="text">
                                        @switch($sort)
                                                @case('-price')
                                                    Price - Low to High
                                                @break
                                                @case('price')
                                                    Price - High to Low
                                                @break
                                                @case('name')
                                                    Alphabetical
                                                @break
                                                @case('-discount')
                                                    % Off - Low to High
                                                @break
                                                @case('discount')
                                                    % Off - High to Low
                                                @break
                                                @default
                                                    Price - Low to High
                                        @endswitch
                                    </div>
                                    <div class="menu">
                                        <div class="item">
                                            <a href="{{ route('merchant.product.search', ['search' => $headerSearch,'sort' => '-price']) }}">Price - Low to High</a>
                                        </div>
                                        <div class="item">
                                            <a href="{{ route('merchant.product.search' ,['search' => $headerSearch,'sort' => 'price']) }}">Price - High to Low</a>
                                        </div>
                                        <div class="item">
                                            <a href="{{ route('merchant.product.search', ['search' => $headerSearch,'sort' => 'name']) }}">Alphabetical</a>
                                        </div>
                                        <div class="item">
                                            <a href="{{ route('merchant.product.search', ['search' => $headerSearch,'sort' => '-discount']) }}">% Off - Low to High</a>
                                        </div>
                                        <div class="item">
                                            <a href="{{ route('merchant.product.search', ['search' => $headerSearch,'sort' => 'discount']) }}">% Off - High to Low</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="product-list-view infinite-scroll">
                            <div class="row">
                                @foreach($merchantProducts as $merchantProductItem)
                                    @include('frontend.components.productCard', ['productCardItem' => $merchantProductItem])
                                @endforeach
                            </div>
                            {{ $merchantProducts->appends(request()->capture()->except('page'))->links() }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
