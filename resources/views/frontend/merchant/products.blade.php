@extends('frontend.app')

@section('scripts')
    <script src="{{ asset('js/jquery.jscroll.min.js') }}"></script>
    <script>
        $('ul.pagination').hide();
        $(function() {
            $('.infinite-scroll').jscroll({
                autoTrigger: true,
                loadingHtml: '<img class="center-block" style="margin: 0 auto" src="{{ asset('assets/common/ajax-loader.gif') }}" alt="Loading..." />',
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



    <div class="theme-Breadcrumb">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb pl-1 pl-sm-0">
                            <li class="breadcrumb-item d-none d-md-block"><a href="{{ route('categories.list') }}">Categories</a> /</li>
                            @if($merchant->category)
                                <li class="breadcrumb-item"><a href="{{ route('merchants.by_category', $merchant->category->friendly_url) }}">{{ $merchant->category->name }}</a> /</li>
                            @endif
                            <li class="breadcrumb-item active d-none d-sm-inline" aria-current="page">{{ $merchant->name }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="wrapper-breadcrumb">

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

                            <div class="col-12 d-block d-sm-none pb-4">
                                <h1>{{ $merchant->name }}</h1>
                            </div>

                            <div class="row pb-2">
                                <div class="col-sm-8 offset-sm-2 col-6 offset-3 text-center">
                                    <img src="{{ $merchant->logo ? asset($merchant->logo) : asset('assets/common/image_placeholder.png')}}" alt="{{ $merchant->name }}" class="img-fluid">
                                </div>
                                <div class="col-12">
                                    <form action="{{ route('merchant', $merchant->friendly_url) }}" method="GET">
                                        @if($private_category)
                                            <input type="hidden" name="private_category" value="{{ $private_category }}">
                                        @endif
                                        @if($sort)
                                            <input type="hidden" name="sort" value="{{ $sort }}">
                                        @endif
                                        <div class="ui search ml-1 mr-1">
                                            <div class="ui left icon input swdh10">
                                                <input class="prompt srch10" type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search for products.." autofocus>
                                                <i class='uil uil-search-alt icon icon1'></i>
                                            </div>
                                        </div>
                                    </form>
                                </div>


                                @if(count($merchant->privateCategories) > 0)
                                    <div class="col-12 d-block d-sm-none mt-4">
                                        <label>Select Category</label>
                                        <select class="form-control select_target_to_link">
                                            <option value="{{ route('merchant', ['friendly_url' => $merchant->friendly_url,'sort' => $sort]) }}" @if((int)$private_category === 0) selected @endif>View All</option>
                                            @foreach($merchant->privateCategories as $privateCategoryItem)
                                                <option value="{{ route('merchant', ['friendly_url' => $merchant->friendly_url,'sort' => $sort, 'private_category' => $privateCategoryItem->id]) }}" @if((int)$private_category === (int)$privateCategoryItem->id) selected @endif>{{ $privateCategoryItem->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                                <div class="col-12 d-block d-sm-none mt-4">
                                    <select id="sort_target_mobile" class="form-control select_target_to_link">

                                        <option value="{{ route('merchant', ['friendly_url' => $merchant->friendly_url,'sort' => '-price', 'private_category' => $private_category]) }}" @if($sort === '-price') selected @endif>Price - Low to High</option>
                                        <option value="{{ route('merchant', ['friendly_url' => $merchant->friendly_url,'sort' => 'price', 'private_category' => $private_category]) }}" @if($sort === 'price') selected @endif>Price - High to Low</option>
                                        <option value="{{ route('merchant', ['friendly_url' => $merchant->friendly_url,'sort' => 'name', 'private_category' => $private_category]) }}" @if($sort === 'name') selected @endif>Alphabetical</option>
                                        <option value="{{ route('merchant', ['friendly_url' => $merchant->friendly_url,'sort' => '-discount', 'private_category' => $private_category]) }}" @if($sort === '-discount') selected @endif>% Off - Low to High</option>
                                        <option value="{{ route('merchant', ['friendly_url' => $merchant->friendly_url,'sort' => 'discount', 'private_category' => $private_category]) }}" @if($sort === 'discount') selected @endif>% Off - High to Low</option>
                                    </select>
                                </div>
                            </div>
                            @if(count($merchant->privateCategories) > 0)
                                <hr class="d-none d-sm-block">
                                <div class="dashboard-left-links d-none d-sm-block">
                                    @foreach($merchant->privateCategories as $privateCategoryItem)
                                    <a href="{{ route('merchant', ['friendly_url' => $merchant->friendly_url,'sort' => $sort, 'private_category' => $privateCategoryItem->id]) }}" class="user-item @if((int)$private_category === (int)$privateCategoryItem->id) active @endif">{{ $privateCategoryItem->name }}</a>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-9 col-md-8">
                        <div class="product-top-dt mb-3">
                            <div class="product-left-title mt-3 mt-sm-0">
                                <h1 class="d-none d-sm-block">{{ $merchant->name }}</h1>
                                @if(count($availableHoursCollection))
                                <div class="availableHours">
                                    <button class="btn btn-sm @if($availableHours['is_open']) btn-success @else btn-danger @endif dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        @if($availableHours['is_open']) WE ARE OPEN @else OPENING SOON @endif
                                    </button>
                                    @if(count($availableHours['availability']) > 0)
                                    <div class="dropdown-menu scrollable">
                                        @foreach($availableHours['availability'] as $availableHourItem)
                                            <div class="dropdown-item">
                                                <small><strong>{{ $availableHourItem['day'] }}</strong></small>
                                            </div>
                                            @foreach($availableHourItem['hours'] as $availableHourItemHour)
                                                <div class="dropdown-item">
                                                    <small>From <strong>{{ $availableHourItemHour['from'] }}</strong> to <strong>{{ $availableHourItemHour['to'] }}</strong></small>
                                                </div>
                                            @endforeach
                                            <div class="dropdown-divider"></div>
                                        @endforeach
                                    </div>
                                    @endif
                                </div>
                                @endif
                                @if(count($merchantProducts) === 0 && strlen($search ?? '') > 0)
                                    <h4>No products matching your filters</h4>
                                    <a href="{{ route('merchant', ['friendly_url' => $merchant->friendly_url,'sort' => $sort]) }}" class="show-more-btn hover-btn pt-1 pb-1 pl-3 pr-3">Remove filters</a>
                                @endif
                            </div>
                            @if($merchant->cover_image)
                                <img src="{{ asset($merchant->cover_image) }}" alt="" class="cover-image img-fluid mt-3 mb-3">
                            @endif
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
                                            <a href="{{ route('merchant', ['friendly_url' => $merchant->friendly_url,'sort' => '-price', 'private_category' => $private_category]) }}">Price - Low to High</a>
                                        </div>
                                        <div class="item">
                                            <a href="{{ route('merchant' ,['friendly_url' => $merchant->friendly_url,'sort' => 'price', 'private_category' => $private_category]) }}">Price - High to Low</a>
                                        </div>
                                        <div class="item">
                                            <a href="{{ route('merchant', ['friendly_url' => $merchant->friendly_url,'sort' => 'name', 'private_category' => $private_category]) }}">Alphabetical</a>
                                        </div>
                                        <div class="item">
                                            <a href="{{ route('merchant', ['friendly_url' => $merchant->friendly_url,'sort' => '-discount', 'private_category' => $private_category]) }}">% Off - Low to High</a>
                                        </div>
                                        <div class="item">
                                            <a href="{{ route('merchant', ['friendly_url' => $merchant->friendly_url,'sort' => 'discount', 'private_category' => $private_category]) }}">% Off - High to Low</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($merchant->allow_custom_items)
                            <div class="product-top-dt mb-3">
                                <form action="{{ route('checkout.add_custom_to_cart') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="merchant_id" value="{{ $merchant->id }}">
                                    <hr>
                                    <div class="d-flex align-items-center">
                                        <label for="own_products" class="text-nowrap mr-3 mb-0"><strong>Add your own products</strong></label>
                                        <input type="text" class="form-control" id="own_products" value="" name="custom_item" placeholder="Write what you need..">
                                        <button type="submit" class="btn btn-dark pl-3 pr-3 ml-3 text-nowrap">Add to cart</button>
                                    </div>
                                </form>
                                <hr>
                            </div>
                        @endif

                        <div class="product-list-view infinite-scroll">
                            <div class="row">
                                @foreach($merchantProducts as $merchantProductItem)
                                    @if($merchantProductItem->friendly_url)
                                        @include('frontend.components.productCard', ['productCardItem' => $merchantProductItem, 'openCartOnComplete' => false])
                                    @endif
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
