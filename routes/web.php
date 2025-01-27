<?php

use App\Helpers\SMSHelper;
use App\Models\User\User;
use App\Notifications\Order\OrderPlacedNotification;
use Illuminate\Support\Facades\Notification;
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

Route::get('/notification', function () {





    $order = \App\Models\Order\Order::find(44);

    if($order){

        $merchant = $order->merchant;
        $items = [];
        foreach ($order->items as $item) {
            $items[] = [
                'name' => $item->name,
                'quantity' => $item->quantity,
                'price' => $item->price > 0 ? $item->price : '-',
            ];
        }


        $hasCustomItems = false;
        if($order->custom_product_request && is_array($order->custom_product_request) && count($order->custom_product_request) > 0){
            $hasCustomItems = true;
            foreach ($order->custom_product_request as $customItem){
                $items[] = [
                    'name' => $customItem,
                    'quantity' => '',
                    'price' => ($order->status !== 'waiting_for_payment') ? 'Needs confirmation' : '',
                ];
            }
        }


        if(count($order->items) > 0){
            $items[] = [
                'name' => 'Total',
                'quantity' => '',
                'price' => ($order->status === 'waiting_for_payment' || ($order->transaction_total > 0 && !$hasCustomItems)) ? $order->transaction_total :  'Needs confirmation',
            ];
        }

        $details = [
            'greeting' => 'Hi ' . $order->user->name ,
            'thanks' => 'Thank you for using ' . env('APP_NAME'),
            'order_id' => $order->id,
            'transaction_id' => $order->transaction_id,
            'tableHeader' => [
                'Product',
                'Qty',
                'Price'
            ],
            'tableBody' => $items
        ];

        $details['subject'] = 'Your order was delivered!';
        $details['body'] = 'Your order' . ($order ? ' from ' . strtoupper($order->merchant->name) : '') . ' has been delivered';
        $details['actionText'] = 'Rate our service';
        $details['actionURL'] =  route('service.rate', $order->transaction_id);
    }

//    if($order->user){
//        $order->user->notify(new \App\Notifications\Order\OrderPlacedNotification($details));
//    }
//
    $notification = new \App\Notifications\Order\OrderPlacedNotification($details);
    return $notification->toMail('test@example.com');
});


Route::get('/', 'Frontend\Base\HomeController@index')->name('home');

Route::get('rate-our-service/{transaction_id?}', 'Frontend\Base\ServiceRateController@index')->name('service.rate');
Route::get('rate-our-service-response', 'Frontend\Base\ServiceRateController@response')->name('service.rate.response');
Route::post('rate-our-service/', 'Frontend\Base\ServiceRateController@store')->name('service.rate.store');

Route::get('select-country/{country_iso?}', 'Frontend\Base\CountrySelectorController@selectCountry')->name('country.select');

Route::group(['prefix' => 'login'], function () {
    Route::get('/', 'Frontend\Auth\LoginController@showLoginForm')->name('login');
    Route::post('/', 'Frontend\Auth\LoginController@login')->name('login.form');
});

Route::post('logout', 'Frontend\Auth\LoginController@logout')->name('logout');

Route::group(['prefix' => 'password'], function () {
    Route::get('confirm', 'Frontend\Auth\ConfirmPasswordController@showConfirmForm')->name('password.confirm');
    Route::post('confirm', 'Frontend\Auth\ConfirmPasswordController@confirm')->name('password.confirm.form');

    Route::post('email', 'Frontend\Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    Route::get('reset', 'Frontend\Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');

    Route::post('reset', 'Frontend\Auth\ResetPasswordController@reset')->name('password.update');
    Route::get('reset/{token}', 'Frontend\Auth\ResetPasswordController@showResetForm')->name('password.reset');

    Route::post('reset', 'Frontend\Auth\ResetPasswordController@reset')->name('password.update');
    Route::get('reset/{token}', 'Frontend\Auth\ResetPasswordController@showResetForm')->name('password.reset');
});

Route::group(['prefix' => 'register'], function () {
    Route::get('/', 'Frontend\Auth\RegisterController@showRegistrationForm')->name('register');
    Route::post('/', 'Frontend\Auth\RegisterController@register')->name('register.form');
});

Route::group(['prefix' => 'company'], function () {
    Route::get('about-us', 'Frontend\Company\StaticPageController@aboutUs')->name('company.about_us');
    Route::get('privacy', 'Frontend\Company\StaticPageController@privacy')->name('company.privacy');
    Route::get('terms', 'Frontend\Company\StaticPageController@terms')->name('company.terms');
    Route::get('contact', 'Frontend\Company\ContactController@index')->name('company.contact');
    Route::post('contact', 'Frontend\Company\ContactController@store')->name('company.contact.store');
    Route::get('become-a-vendor', 'Frontend\Company\BecomeAVendorController@index')->name('company.become_a_vendor');
    Route::post('become-a-vendor', 'Frontend\Company\BecomeAVendorController@store')->name('company.become_a_vendor.store');
    Route::get('become-a-driver', 'Frontend\Company\BecomeADriverController@index')->name('company.become_a_driver');
    Route::post('become-a-driver', 'Frontend\Company\BecomeADriverController@store')->name('company.become_a_driver.store');
});

Route::get('categories/list', 'Frontend\Merchant\CategoryController@list')->name('categories.list');

Route::get('shops/{friendly_url?}', 'Frontend\Merchant\MerchantController@byCategory')->name('merchants.by_category');
Route::get('shop/{friendly_url?}', 'Frontend\Merchant\MerchantController@show')->name('merchant');
Route::get('shop/{merchant_friendly_url?}/{product_friendly_url}', 'Frontend\Merchant\ProductController@show')->name('merchant.product');

Route::get('search', 'Frontend\Merchant\MerchantController@search')->name('merchant.product.search');


Route::group(['prefix' => 'checkout'], function () {
    Route::post('add-custom-to-cart', 'Frontend\Checkout\CartControrller@storeCustomItem')->name('checkout.add_custom_to_cart');
    Route::post('add-to-cart', 'Frontend\Checkout\CartControrller@store')->name('checkout.add_to_cart');
    Route::delete('destroy-cart-item/{id}', 'Frontend\Checkout\CartControrller@destroy')->name('checkout.destroy_cart_item');

    Route::delete('destroy-cart-custom-item/{merchant_id}', 'Frontend\Checkout\CartControrller@destroyCustomItem')->name('checkout.destroy_cart_custom_item');

});


Route::group(['prefix' => 'external/payment'], function () {
    Route::get('{transaction_id}', 'External\PaymentController@index')->name('external_payment.view');
    Route::post('{transaction_id}', 'External\PaymentController@store')->name('external_payment.store');
    Route::get('/problem/{order_id}', 'External\PaymentController@problem')->name('external_payment.problem');
    Route::get('/approved/{order_id}', 'External\PaymentController@approved')->name('external_payment.approved');
    Route::get('/placed/{order_id}', 'External\PaymentController@placed')->name('external_payment.placed');
});

Route::group(['prefix' => 'external/payment-extra'], function () {
    Route::get('{transaction_id}', 'External\ExtraPaymentController@index')->name('extra_payment.view');
    Route::post('{transaction_id}', 'External\ExtraPaymentController@store')->name('extra_payment.store');
    Route::get('/problem/{order_id}', 'External\ExtraPaymentController@problem')->name('extra_payment.problem');
    Route::get('/approved/{order_id}', 'External\ExtraPaymentController@approved')->name('extra_payment.approved');
    Route::get('/placed/{order_id}', 'External\ExtraPaymentController@placed')->name('extra_payment.placed');
});


