<div class="left-side-tabs mt-0 mb-2">
    <div class="dashboard-left-links">
        <span class="user-item"><i class="uil uil-dashboard"></i><strong>Admin Area</strong></span>
        <a href="{{ route('backend.order.list') }}" class="user-item @if(Route::currentRouteName() === 'backend.order.list' || Route::currentRouteName() === 'backend.order.show') active @endif"><i class="uil uil-copy"></i>Orders</a>
        <a href="{{ route('backend.merchant.list') }}"
           class="user-item @if(Route::currentRouteName() === 'backend.merchant.list') active @endif">
            <i class="uil uil-building"></i>
            Merchants
        </a>
        <a href="{{ route('backend.discount.list') }}" class="user-item @if(Route::currentRouteName() === 'backend.discount.list') active @endif"><i class="uil uil-percentage"></i>Discounts</a>
        <a href="{{ route('backend.category.list') }}" class="user-item @if(Route::currentRouteName() === 'backend.category.list') active @endif"><i class="uil uil-apps"></i>Categories</a>
        <a href="{{ route('backend.banner.list') }}" class="user-item @if(Route::currentRouteName() === 'backend.banner.list') active @endif"><i class="uil uil-image"></i>Home Banners</a>

        <a href="#" class="user-item">
            <small>Export</small>
        </a>
        <a href="{{ route('backend.export.clients') }}" class="user-item @if(Route::currentRouteName() === 'backend.export.clients') active @endif"><i class="uil uil-user"></i>Export Clients</a>


{{--        <a href="{{ route('account.merchant.private_categories', $merchant->id) }}" class="user-item @if(Route::currentRouteName() === 'account.merchant.private_categories') active @endif"><i class="uil uil-apps"></i>Categories</a>--}}
{{--        <a href="{{ route('account.merchant.profile', $merchant->id) }}" class="user-item @if(Route::currentRouteName() === 'account.merchant.profile') active @endif"><i class="uil uil-info-circle"></i>Shop Information</a>--}}
    </div>


@if(isset($merchant) && (
    Route::currentRouteName() === 'backend.merchant.products' ||
    Route::currentRouteName() === 'backend.merchant.product' ||
    Route::currentRouteName() === 'backend.merchant.profile' ||
    Route::currentRouteName() === 'backend.merchant.available_hours' ||
    Route::currentRouteName() === 'backend.merchant.delivery_timeframes' ||
    Route::currentRouteName() === 'backend.import.list' ||
    Route::currentRouteName() === 'backend.merchant.private_categories'
    ))
    <div class="dashboard-left-links">
        <div class="d-flex align-items-center justify-content-center pt-5 pl-3 pr-3 pb-3 border-top">
            <h4 class="d-flex m-0">Editing Merchant</h4>
        </div>
        <div class="d-flex align-items-center p-4 border-bottom">
            @if($merchant->logo)
                <img src="{{ $merchant->logo ? asset($merchant->logo) : asset('assets/common/image_placeholder.png')}}" alt="{{ $merchant->name }}" class="img-fluid" width="50">
            @endif
            <h4 class="d-flex m-0 pl-2">{{ $merchant->name }}</h4>
        </div>

        @if(Route::currentRouteName() === 'backend.merchant.product' && isset($product) && $product->id)
            <a href="#" class="user-item @if(Route::currentRouteName() === 'account.merchant.product') active @endif"><i class="uil uil-edit"></i>Editing Product</a>
        @endif
        <a href="{{ route('backend.merchant.product', $merchant->id) }}" class="user-item @if(Route::currentRouteName() === 'backend.merchant.product') @if(!isset($product) || (isset($product) && !$product->id)) active @endif @endif"><i class="uil uil-plus-circle"></i>Add new product</a>
        <a href="{{ route('backend.merchant.products', $merchant->id) }}" class="user-item @if(Route::currentRouteName() === 'backend.merchant.products') active @endif"><i class="uil uil-box"></i>Products</a>
        <a href="{{ route('backend.import.list', $merchant->id) }}" class="user-item @if(Route::currentRouteName() === 'backend.import.list') active @endif"><i class="uil uil-upload"></i>Product Importer</a>
        <a href="{{ route('backend.merchant.private_categories', $merchant->id) }}" class="user-item @if(Route::currentRouteName() === 'backend.merchant.private_categories') active @endif"><i class="uil uil-apps"></i>Categories</a>
        <a href="{{ route('backend.merchant.available_hours', $merchant->id) }}" class="user-item @if(Route::currentRouteName() === 'backend.merchant.available_hours') active @endif"><i class="uil uil-clock"></i>Available Hours</a>
        <a href="{{ route('backend.merchant.delivery_timeframes', $merchant->id) }}" class="user-item @if(Route::currentRouteName() === 'backend.merchant.delivery_timeframes') active @endif"><i class="uil uil-clock"></i>Delivery Timeframes</a>
        <a href="{{ route('backend.merchant.profile', $merchant->id) }}" class="user-item @if(Route::currentRouteName() === 'backend.merchant.profile') active @endif"><i class="uil uil-info-circle"></i>Shop Information</a>
    </div>
@endif
</div>
