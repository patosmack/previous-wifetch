<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Route::group(['prefix' => 'account'], function () {
    Route::get('overview', 'Frontend\Account\OverviewControrller@index')->name('account.overview');
    Route::get('addresses', 'Frontend\Account\AddressControrller@index')->name('account.addresses');
    Route::post('addresses', 'Frontend\Account\AddressControrller@store')->name('account.addresses.store');
    Route::get('addresses/{id}/delete', 'Frontend\Account\AddressControrller@destroy')->name('account.addresses.delete');
    Route::get('order', 'Frontend\Account\OrderControrller@index')->name('account.orders');
    Route::get('profile', 'Frontend\Account\ProfileControrller@index')->name('account.profile');
    Route::post('profile', 'Frontend\Account\ProfileControrller@store')->name('account.profile.store');
});


Route::group(['prefix' => 'account/merchant'], function () {
    Route::get('shops', 'Frontend\Account\Merchant\MerchantController@index')->name('account.merchant.shops');

    Route::get('shops/{id}/{private_category_id?}', 'Frontend\Account\Merchant\MerchantController@shop')->name('account.merchant.shop');
    Route::post('shops-json/{id}/{private_category_id?}', 'Frontend\Account\Merchant\MerchantController@shopJson')->name('account.merchant.shop.json');
    Route::post('shops-easy-edit', 'Frontend\Account\Merchant\MerchantController@shopEasyEdit')->name('account.merchant.shop.easy-edit');

    Route::get('private-categories/{id}', 'Frontend\Account\Merchant\PrivateCategoryController@index')->name('account.merchant.private_categories');
    Route::post('private-categories', 'Frontend\Account\Merchant\PrivateCategoryController@store')->name('account.merchant.private_categories.store');
    Route::get('private-categories/{id}/delete', 'Frontend\Account\Merchant\PrivateCategoryController@destroy')->name('account.merchant.private_categories.delete');

    Route::get('available-hours/{id}', 'Frontend\Account\Merchant\AvailableHourController@index')->name('account.merchant.available_hours');
    Route::post('available-hours', 'Frontend\Account\Merchant\AvailableHourController@store')->name('account.merchant.available_hours.store');
    Route::get('available-hours/{id}/delete', 'Frontend\Account\Merchant\AvailableHourController@destroy')->name('account.merchant.available_hours.delete');

    Route::get('delivery-timeframes/{id}', 'Frontend\Account\Merchant\DeliveryTimframeController@index')->name('account.merchant.delivery_timeframes');
    Route::post('delivery-timeframes', 'Frontend\Account\Merchant\DeliveryTimframeController@store')->name('account.merchant.delivery_timeframes.store');
    Route::get('delivery-timeframes/{id}/delete', 'Frontend\Account\Merchant\DeliveryTimframeController@destroy')->name('account.merchant.delivery_timeframes.delete');

    Route::get('profile/{id}', 'Frontend\Account\Merchant\ProfileController@index')->name('account.merchant.profile');
    Route::post('profile', 'Frontend\Account\Merchant\ProfileController@store')->name('account.merchant.profile.store');

    Route::get('shops-product/{merchant_id}/{product_id?}', 'Frontend\Account\Merchant\ProductController@index')->name('account.merchant.product');
    Route::post('shops-product/{merchant_id}', 'Frontend\Account\Merchant\ProductController@store')->name('account.merchant.product.store');

    Route::post('shops-product-variation', 'Frontend\Account\Merchant\ProductMutatorGroupController@store')->name('account.merchant.product.mutator.group.store');
    Route::delete('shops-product-variation/delete', 'Frontend\Account\Merchant\ProductMutatorGroupController@destroy')->name('account.merchant.product.mutator.group.delete');

    Route::post('shops-product-item-variation', 'Frontend\Account\Merchant\ProductMutatorController@store')->name('account.merchant.product.mutator.group.item.store');
    Route::delete('shops-product-item-variation/delete', 'Frontend\Account\Merchant\ProductMutatorController@destroy')->name('account.merchant.product.mutator.group.item.delete');

//    Route::get('addresses', 'Frontend\Account\AddressControrller@index')->name('account.addresses');
//    Route::post('addresses', 'Frontend\Account\AddressControrller@store')->name('account.addresses.store');
//    Route::get('addresses/{id}/delete', 'Frontend\Account\AddressControrller@destroy')->name('account.addresses.delete');
//    Route::get('order', 'Frontend\Account\OrderControrller@index')->name('account.orders');
//    Route::get('profile', 'Frontend\Account\ProfileControrller@index')->name('account.profile');
//    Route::post('profile', 'Frontend\Account\ProfileControrller@store')->name('account.profile.store');
});

Route::group(['prefix' => 'checkout'], function () {
    Route::get('merchants', 'Frontend\Checkout\CheckoutControrller@merchants')->name('checkout.merchants');
    Route::get('address/{friendly_url}', 'Frontend\Checkout\CheckoutControrller@address')->name('checkout.address');
    Route::post('address/{friendly_url}', 'Frontend\Checkout\CheckoutControrller@addressStore')->name('checkout.address.store');
    Route::get('timeframe/{friendly_url}', 'Frontend\Checkout\CheckoutControrller@timeframe')->name('checkout.timeframe');
    Route::post('timeframe/{friendly_url}', 'Frontend\Checkout\CheckoutControrller@timeframeStore')->name('checkout.timeframe.store');
    Route::get('pay/{friendly_url}', 'Frontend\Checkout\CheckoutControrller@pay')->name('checkout.pay');
    Route::post('pay/{friendly_url}', 'Frontend\Checkout\CheckoutControrller@payStore')->name('checkout.pay.store');

    Route::post('apply-discount', 'Frontend\Checkout\CheckoutControrller@applyDiscount')->name('checkout.apply.discount');


    Route::get('problem/{order_id}', 'Frontend\Checkout\CheckoutControrller@problem')->name('checkout.problem');
    Route::get('approved/{order_id}', 'Frontend\Checkout\CheckoutControrller@approved')->name('checkout.approved');
    Route::get('placed/{order_id}', 'Frontend\Checkout\CheckoutControrller@placed')->name('checkout.placed');

});
