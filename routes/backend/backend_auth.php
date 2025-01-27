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


Route::group(['prefix' => 'categories'], function () {
    Route::get('/', 'Category\CategoryController@index')->name('backend.category.list');
    Route::post('/', 'Category\CategoryController@store')->name('backend.category.store');
    Route::get('enable-categories/{id}/toggle', 'Category\CategoryController@toggle')->name('backend.category.toggle.status');
});

Route::group(['prefix' => 'importer'], function () {
    Route::get('/{merchant_id}', 'Importer\ImportHistoryController@index')->name('backend.import.list');
    Route::post('/', 'Importer\ImportHistoryController@store')->name('backend.import.store');
    Route::get('enable-categories/{id}/toggle', 'Category\ImportHistoryController@toggle')->name('backend.import.toggle.status');
});

Route::group(['prefix' => 'banners'], function () {
    Route::get('/', 'System\BannerController@index')->name('backend.banner.list');
    Route::post('/', 'System\BannerController@store')->name('backend.banner.store');
    Route::get('enable-banners/{id}/toggle', 'System\BannerController@toggle')->name('backend.banner.toggle.status');
});

Route::group(['prefix' => 'export'], function () {
    Route::get('clients/', 'Export\ExportClientController@index')->name('backend.export.clients');
});


Route::group(['prefix' => 'discounts'], function () {
    Route::get('/', 'System\DiscountController@index')->name('backend.discount.list');
    Route::post('json/{status?}', 'System\DiscountController@datatable')->name('backend.discount.json');
    Route::get('json/{status?}', 'System\DiscountController@datatable')->name('backend.discount.json');
    Route::post('easy-edit', 'System\DiscountController@easyEdit')->name('backend.discount.easy-edit');
    Route::post('/', 'System\DiscountController@store')->name('backend.discount.store');
//    Route::get('order/{order_id}', 'Order\OrderController@show')->name('backend.order.show');

});

Route::group(['prefix' => 'orders'], function () {
    Route::get('/', 'Order\OrderController@index')->name('backend.order.list');
    Route::post('json/{status?}', 'Order\OrderController@datatable')->name('backend.order.json');
    Route::get('json/{status?}', 'Order\OrderController@datatable')->name('backend.order.json');
    Route::post('easy-edit', 'Order\OrderController@easyEdit')->name('backend.order.easy-edit');

    Route::get('order/{order_id}', 'Order\OrderController@show')->name('backend.order.show');
    Route::post('order/{order_id}', 'Order\OrderController@update')->name('backend.order.update');

    Route::post('order-request-money/{order_id}', 'Order\OrderController@requestMoreMoney')->name('backend.order.requestMoreMoney');

    Route::get('orders-export', 'Order\OrderController@exportOrders')->name('backend.orders.export');
});

Route::group(['prefix' => 'merchants'], function () {
    Route::get('/', 'Merchant\MerchantController@index')->name('backend.merchant.list');
    Route::post('json/{category_id?}', 'Merchant\MerchantController@datatable')->name('backend.merchant.json');
    Route::get('json/{category_id?}', 'Merchant\MerchantController@datatable')->name('backend.merchant.json');
    Route::post('easy-edit', 'Merchant\MerchantController@easyEdit')->name('backend.merchant.easy-edit');

    Route::get('merchant/{merchant_id}', 'Merchant\MerchantController@show')->name('backend.merchant.profile');
    Route::post('merchant/{merchant_id}', 'Merchant\MerchantController@update')->name('backend.merchant.update');

    Route::get('merchants-export', 'Merchant\MerchantController@exportMerchants')->name('backend.merchants.export');
   //Route::get('merchant/{merchant_id}/export', 'Merchant\MerchantController@export')->name('backend.merchant.export');


    Route::get('merchant-products/{id}/{private_category_id?}', 'Merchant\MerchantProductController@shop')->name('backend.merchant.products');
    Route::post('merchant-products/{id}/{private_category_id?}', 'Merchant\MerchantProductController@shopJson')->name('backend.merchant.products.json');
    Route::post('merchant-products-easy-edit', 'Merchant\MerchantProductController@shopEasyEdit')->name('backend.merchant.products.easy-edit');

    Route::get('merchant-private-categories/{id}', 'Merchant\MerchantPrivateCategoryController@index')->name('backend.merchant.private_categories');
    Route::post('merchant-private-categories', 'Merchant\MerchantPrivateCategoryController@store')->name('backend.merchant.private_categories.store');
    Route::get('merchant-private-categories/{id}/delete', 'Merchant\MerchantPrivateCategoryController@destroy')->name('backend.merchant.private_categories.delete');

    Route::get('merchant-available-hours/{id}', 'Merchant\MerchantAvailableHoursController@index')->name('backend.merchant.available_hours');
    Route::post('merchant-available-hours', 'Merchant\MerchantAvailableHoursController@store')->name('backend.merchant.available_hours.store');
    Route::get('merchant-available-hours/{id}/delete', 'Merchant\MerchantAvailableHoursController@destroy')->name('backend.merchant.available_hours.delete');

    Route::get('merchant-delivery-timeframes/{id}', 'Merchant\MerchantDeliveryTimeframesController@index')->name('backend.merchant.delivery_timeframes');
    Route::post('merchant-delivery-timeframes', 'Merchant\MerchantDeliveryTimeframesController@store')->name('backend.merchant.delivery_timeframes.store');
    Route::get('merchant-delivery-timeframes/{id}/delete', 'Merchant\MerchantDeliveryTimeframesController@destroy')->name('backend.merchant.delivery_timeframes.delete');

    Route::get('merchant-product/{merchant_id}/{product_id?}', 'Merchant\MerchantProductController@show')->name('backend.merchant.product');
    Route::post('merchant-product/{merchant_id}', 'Merchant\MerchantProductController@storeInfo')->name('backend.merchant.product.store');

    Route::post('merchant-product-variation', 'Merchant\MerchantProductMutatorGroupController@store')->name('backend.merchant.product.mutator.group.store');
    Route::delete('merchant-product-variation/delete', 'Merchant\MerchantProductMutatorGroupController@destroy')->name('backend.merchant.product.mutator.group.delete');

    Route::post('merchant-product-item-variation', 'Merchant\MerchantProductMutatorController@store')->name('backend.merchant.product.mutator.group.item.store');
    Route::delete('merchant-product-item-variation/delete', 'Merchant\MerchantProductMutatorController@destroy')->name('backend.merchant.product.mutator.group.item.delete');

});

//
//Route::group(['prefix' => 'account/merchant'], function () {
//    Route::get('shops', 'Frontend\Account\Merchant\MerchantController@index')->name('account.merchant.shops');
//
//    Route::get('shops/{id}/{private_category_id?}', 'Frontend\Account\Merchant\MerchantController@shop')->name('account.merchant.shop');
//    Route::post('shops-json/{id}/{private_category_id?}', 'Frontend\Account\Merchant\MerchantController@shopJson')->name('account.merchant.shop.json');
//    Route::post('shops-easy-edit', 'Frontend\Account\Merchant\MerchantController@shopEasyEdit')->name('account.merchant.shop.easy-edit');
//
//    Route::get('private-categories/{id}', 'Frontend\Account\Merchant\PrivateCategoryController@index')->name('account.merchant.private_categories');
//    Route::post('private-categories', 'Frontend\Account\Merchant\PrivateCategoryController@store')->name('account.merchant.private_categories.store');
//    Route::get('private-categories/{id}/delete', 'Frontend\Account\Merchant\PrivateCategoryController@destroy')->name('account.merchant.private_categories.delete');
//
//    Route::get('profile/{id}', 'Frontend\Account\Merchant\ProfileController@index')->name('account.merchant.profile');
//    Route::post('profile', 'Frontend\Account\Merchant\ProfileController@store')->name('account.merchant.profile.store');
//
//    Route::get('shops-product/{merchant_id}/{product_id?}', 'Frontend\Account\Merchant\ProductController@index')->name('account.merchant.product');
//    Route::post('shops-product/{merchant_id}', 'Frontend\Account\Merchant\ProductController@store')->name('account.merchant.product.store');
//
//    Route::post('shops-product-variation', 'Frontend\Account\Merchant\ProductMutatorGroupController@store')->name('account.merchant.product.mutator.group.store');
//    Route::delete('shops-product-variation/delete', 'Frontend\Account\Merchant\ProductMutatorGroupController@destroy')->name('account.merchant.product.mutator.group.delete');
//
//    Route::post('shops-product-item-variation', 'Frontend\Account\Merchant\ProductMutatorController@store')->name('account.merchant.product.mutator.group.item.store');
//    Route::delete('shops-product-item-variation/delete', 'Frontend\Account\Merchant\ProductMutatorController@destroy')->name('account.merchant.product.mutator.group.item.delete');
//
//});
