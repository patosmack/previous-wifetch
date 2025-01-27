<header class="header clearfix">
    <div class="top-header-group">
        <div class="top-header">
            <div class="res_main_logo">
                <a href="{{ route('home') }}"><img src="{{ asset('assets/common/logo-1.svg') }}" alt=""></a>
            </div>
            <div class="main_logo pl-4" id="logo">
                <a href="{{ route('home') }}"><img src="{{ asset('assets/common/logo-yellow.svg') }}" alt=""></a>
                <a href="{{ route('home') }}"><img class="logo-inverse" src="{{ asset('assets/common/dark-logo-yellow.svg') }}" alt=""></a>
            </div>
            <div class="select_location">
                <div class="ui inline dropdown loc-title">
                    <div class="text">
                        <i class="uil uil-location-point"></i>
                        @if($userCurrentCountry)
                            {{ $userCurrentCountry->name }}
                        @else
                            Select your country
                        @endif
                    </div>

                    <i class="uil uil-angle-down icon__14"></i>
                    <div class="menu dropdown_loc" >
                        @foreach($countries as $countryItem)
                            <a href="{{ route('country.select', strtolower($countryItem->iso)) }}">
                                <div class="item channel_item" style="z-index: 99999!important;">
                                    <i class="uil uil-location-point"></i> {{ $countryItem->name }}
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="search120">
                <form action="{{ route('merchant.product.search') }}" method="GET">
                    <div class="ui search ml-1 mr-1">
                        <div class="ui left icon input swdh10">
                            <input class="prompt srch10" type="text" name="search" value="{{ isset($headerSearch) ? $headerSearch : '' }}" placeholder="Search for products..">
                            <i class='uil uil-search-alt icon icon1'></i>
                        </div>
                    </div>
                </form>

{{--                <div class="ui search">--}}
{{--                    <div class="ui left icon input swdh10">--}}
{{--                        <input class="prompt srch10" type="text" placeholder="Search for products..">--}}
{{--                        <i class='uil uil-search-alt icon icon1'></i>--}}
{{--                    </div>--}}
{{--                </div>--}}
            </div>
            <div class="header_right">
                <ul>

                    <li>
                        <a href="https://api.whatsapp.com/send?phone=12462648994&text&source&data&app_absent&lang=en" target="_blank" class="offer-link"><img src="{{ asset('assets/common/whatsapp-logo.png') }}" alt="" width="30"></a>
                    </li>
                    <li class="d-none d-sm-inline-block">
                        <a href="tel:246264-8994" class="offer-link"><i class="uil uil-phone-alt"></i>(246) 264-8994</a>
                    </li>
                    @auth()
                    <li class="ui dropdown">
                        <a href="#" class="opts_account">
                            <i class="uil uil-user-circle"></i>
                            <span class="user__name">{{ $userAccount->name }}</span>
                            <i class="uil uil-angle-down"></i>
                        </a>
                        <div class="menu dropdown_account">
                            <a href="{{ route('account.overview') }}" class="item channel_item"><i class="uil uil-apps icon__1"></i>Dashbaord</a>
                            <a href="{{ route('account.orders') }}" class="item channel_item"><i class="uil uil-box icon__1"></i>My Orders</a>
                            <a href="{{ route('account.addresses') }}" class="item channel_item"><i class="uil uil-location-point icon__1"></i>My Address</a>
                            <a href="{{ route('account.profile') }}" class="item channel_item"><i class="uil uil-user-circle icon__1"></i>Profile</a>
                            <a href="#" onclick="event.preventDefault();document.getElementById('logoutform').submit();" class="item channel_item">
                                <i class="uil uil-lock-alt icon__1"></i>Logout
                            </a>
                            <form id="logoutform" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                    @endauth
                    @guest
                        <li class="d-none d-sm-inline">
                            <a href="{{ route('login') }}" class="offer-link">
                                Login
                            </a>
                        </li>
                        <li class="d-none d-sm-inline">
                            <a href="{{ route('register') }}" class="offer-link">
                                Create an account
                            </a>
                        </li>
                        <li class="d-inline d-sm-none">
                            <a href="{{ route('login') }}" class="offer-link  w-100">
                                <i class="uil uil-user-circle"></i>
                            </a>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </div>
    <div class="sub-header-group">
        <div class="sub-header">
            <div class="ui dropdown">
                <a href="#" class="category_drop hover-btn" data-toggle="modal" data-target="#category_model" title="Categories"><i class="uil uil-apps"></i><span class="cate__icon">Select Category</span></a>
            </div>
            <nav class="navbar navbar-expand-lg navbar-light py-3">
                <div class="container-fluid">
                    <button class="navbar-toggler menu_toggle_btn" type="button" data-target="#navbarSupportedContent"><i class="uil uil-bars"></i></button>
                    <div class="collapse navbar-collapse d-flex flex-column flex-lg-row flex-xl-row justify-content-lg-end bg-dark1 p-3 p-lg-0 mt1-5 mt-lg-0 mobileMenu" id="navbarSupportedContent">
                        <ul class="navbar-nav main_nav align-self-stretch">
                            <li class="nav-item"><a href="{{ route('home') }}" class="nav-link @if(Route::currentRouteName() === 'home') active @endif" title="Home">Home</a></li>
                            @if($userAccount)
                            @if($userAccount->is_merchant)
                                <li class="nav-item"><a href="{{ route('account.merchant.shops') }}" class="nav-link @if(Route::currentRouteName() === 'account.merchant.shops') active @endif" title="My Shops">My Shops</a></li>
                            @endif
                            @if($userAccount->is_admin)
                                <li class="nav-item"><a href="{{ route('backend.merchant.list') }}" class="nav-link @if(Route::currentRouteName() === 'backend.merchant.list') active @endif" title="Merchant List">Merchant List</a></li>
                                <li class="nav-item"><a href="{{ route('backend.order.list') }}" class="nav-link @if(Route::currentRouteName() === 'backend.order.list') active @endif" title="Merchant List">Order List</a></li>
                            @endif
                                @if(!$userAccount->is_merchant)
                                    <li class="nav-item"><a href="{{ route('company.become_a_vendor') }}" class="nav-link @if(Route::currentRouteName() === 'company.become_a_vendor') active @endif" title="My Shops">Become A Merchant</a></li>
                                @endif
                            @endif

                        </ul>
                    </div>
                </div>
            </nav>
            <div class="catey__icon">
                <a href="#" class="cate__btn" data-toggle="modal" data-target="#category_model" title="Categories"><i class="uil uil-apps"></i></a>
            </div>
            <div class="header_cart order-1">
                <a href="#" class="cart__btn hover-btn pull-bs-canvas-right" title="Cart" id="cart_btn">
                    <i class="uil uil-shopping-cart-alt"></i>
                    <span>Cart</span>
                    <ins>
{{--                        @if(isset($cart) && array_key_exists('merchants', $cart) && is_array($cart['merchants']) &&  count($cart['merchants']) > 0)--}}
{{--                            {{ count($cart['cart']->items) }}--}}
{{--                        @endif--}}

                        @if(isset($cart) && array_key_exists('cartCount', $cart) && $cart['cartCount'] > 0 && array_key_exists('merchants', $cart) && is_array($cart['merchants']) &&  count($cart['merchants']) > 0)
                            {{ $cart['cartCount'] }}
                        @endif
                    </ins>
                    <i class="uil uil-angle-down"></i>
                </a>
            </div>

            <div class="search__icon order-1">
                <a href="#" class="search__btn hover-btn" data-toggle="modal" data-target="#search_model" title="Search"><i class="uil uil-search"></i></a>
            </div>
        </div>
    </div>
</header>
