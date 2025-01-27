<div class="left-side-tabs">
    <div class="row pb-2">
        @if($merchant->logo)
            <div class="col-8 offset-2 text-center">
                <img src="{{ $merchant->logo ? asset($merchant->logo) : asset('assets/common/image_placeholder.png')}}" alt="{{ $merchant->name }}" class="img-fluid">
            </div>
        @endif
    </div>
    <div class="dashboard-left-links">
        @if(Route::currentRouteName() === 'account.merchant.product' && isset($product) && $product->id)
            <a href="#" class="user-item @if(Route::currentRouteName() === 'account.merchant.product') active @endif"><i class="uil uil-edit"></i>Editing Product</a>
        @endif
        <a href="{{ route('account.merchant.product', $merchant->id) }}" class="user-item @if(Route::currentRouteName() === 'account.merchant.product') @if(!isset($product) || (isset($product) && !$product->id)) active @endif @endif"><i class="uil uil-plus-circle"></i>Add new product</a>
        <a href="{{ route('account.merchant.shop', $merchant->id) }}" class="user-item @if(Route::currentRouteName() === 'account.merchant.shop') active @endif"><i class="uil uil-box"></i>Products</a>
        <a href="{{ route('account.merchant.private_categories', $merchant->id) }}" class="user-item @if(Route::currentRouteName() === 'account.merchant.private_categories') active @endif"><i class="uil uil-apps"></i>Categories</a>
            <a href="{{ route('account.merchant.available_hours', $merchant->id) }}" class="user-item @if(Route::currentRouteName() === 'account.merchant.available_hours') active @endif"><i class="uil uil-clock"></i>Available Hours</a>
            <a href="{{ route('account.merchant.delivery_timeframes', $merchant->id) }}" class="user-item @if(Route::currentRouteName() === 'account.merchant.delivery_timeframes') active @endif"><i class="uil uil-clock"></i>Delivery Timeframes</a>

        <a href="{{ route('account.merchant.profile', $merchant->id) }}" class="user-item @if(Route::currentRouteName() === 'account.merchant.profile') active @endif"><i class="uil uil-info-circle"></i>Shop Information</a>
    </div>
</div>
