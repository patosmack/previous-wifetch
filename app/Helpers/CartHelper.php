<?php

namespace App\Helpers;


use App\Models\Order\Cart;
use App\Models\User\MerchantInfo;
use App\Models\User\User;
use App\Services\Calculations\ProductPrice;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;


class CartHelper {

    public static function getUserToken(){
        $token = Session::get('sessionUserToken');
//        $token = 'j9R0edl5l6oiiwg6dcouNHupWVtvYcZtgllMZQHQGeUHMGzV3ZiZmeKWoup8oSMYtUF3NZbrakCWw5Ud';
//        Session::put('sessionUserToken', $token);
        if(!$token){
            $token = Str::random(80);
            Session::put('sessionUserToken', $token);
            Session::save();
        }
        return $token;
    }

    public static function getCart(User $user = null){
        $cartQuery = Cart::with([
            'items',
            'items.product',
            'items.product.merchant',
            'items.mutators',
            'items.mutators.mutator',
            'items.mutators.mutator.group'
        ]);
        if($user){
            $cartQuery->where('user_id', '=', $user->id);
        }else{
            $token = CartHelper::getUserToken();
            $cartQuery->where('user_token', '=', $token);
        }
        return $cartQuery->first();
    }

    public static function getUserCartContent(User $user = null){

        $cart = self::getCart($user);
        $newCart = [
            'cart' => null,
            'cartCount' => 0,
            'discount' => null,
            'total' => 0,
            'cartNeedsConfirmation' => true,
            'totalSavings' => 0,
            'merchants' => [],
            'merchantCountries' => [],
        ];

        if($cart){
            $discount = $cart->discount;
            $newCartMerchants = [];
            $merchantCountries = [];
            $total = 0;
            $totalOriginal = 0;
            $cartNeedsConfirmation = false;

            $cartCount = 0;
            foreach ($cart->items as $item){
                if($item && $item->product && $item->product->merchant){
                    $merchant = $item->product->merchant;
                    if(!array_key_exists($merchant->id, $newCartMerchants)){
                        $merchantCountries[] = $merchant->country_id;
                        $newCartMerchants[$merchant->id] = [
                            'merchant' => $merchant,
                            'needsPriceConfirmation' => false,
                            'total' => 0,
                            'totalOriginal' => 0,
                            'totalSavings' => 0,
                            'delivery_fee' => 0,
                            'service_fee' => 0,
                            'items' => []
                        ];
                    }
                    $newCartMerchants[$merchant->id]['items'][] = $item;
                    $cartCount ++;
                    $productPrice = new ProductPrice($item->product, $item->quantity);
                    if($discount){
                        $productPrice->addDiscount($discount->rate);
                    }
                    foreach ($item->mutators as $cartItemMutator){
                        $productPrice->addMutator($cartItemMutator->mutator, $cartItemMutator->quantity);
                    }
                    $item->orderPrice = $productPrice->orderPrice();
                    $item->originalOrderPrice = $productPrice->originalOrderPrice();
                    //$total += $productPrice->orderPrice();
                    $totalOriginal += $productPrice->originalOrderPrice();

                    if($productPrice->orderPrice() == 0){
                        $cartNeedsConfirmation = true;
                        $newCartMerchants[$merchant->id]['needsPriceConfirmation'] = true;
                    }

                    //dd($discount);

                    if($discount){
                        if($discount->is_percentage){
                            $totalByMerchant =  Helper::applyDiscount($productPrice->orderPrice(), $discount->rate ?? 0);
                        }else{
                            $totalByMerchant =  Helper::applyDiscount($productPrice->orderPrice(), 0);
//                            $totalByMerchant =  $productPrice->orderPrice() - $discount->rate;
//                            if($totalByMerchant < 0 ){
//                                $totalByMerchant = 0;
//                            }
                        }
                    }else{
                        $totalByMerchant =  Helper::applyDiscount($productPrice->orderPrice(), 0);
                    }


                    if($merchant->delivery_fee){
                        if($newCartMerchants[$merchant->id]['delivery_fee'] === 0){
                            $newCartMerchants[$merchant->id]['delivery_fee'] = $merchant->delivery_fee;
                            $totalByMerchant += $merchant->delivery_fee;
                        }
                    }else{
                        $newCartMerchants[$merchant->id]['delivery_fee'] = 0;
                    }

                    if($merchant->service_fee && $merchant->service_fee > 0){
//                        if($newCartMerchants[$merchant->id]['service_fee'] === 0) {
                        $serviceFee = ($merchant->service_fee * $totalByMerchant) / 100;
                        $newCartMerchants[$merchant->id]['service_fee'] += $serviceFee;
                        $totalByMerchant += $serviceFee;
                        //}
                    }else{
                        //$serviceFee = 0;
                        $newCartMerchants[$merchant->id]['service_fee'] = 0;
                    }

                    if($discount){
                        if(!$discount->is_percentage){
                            $totalByMerchant =  $totalByMerchant - $discount->rate;
                            if($totalByMerchant < 0 ){
                                $totalByMerchant = 0;
                            }
                        }
                    }


                    $total +=$totalByMerchant;

                    $newCartMerchants[$merchant->id]['total'] +=  $totalByMerchant;
                    $newCartMerchants[$merchant->id]['totalOriginal'] +=  $productPrice->originalOrderPrice();
                    $newCartMerchants[$merchant->id]['totalSavings'] =  $newCartMerchants[$merchant->id]['totalOriginal'] - $newCartMerchants[$merchant->id]['total'];

                }
            }


                if (is_array($cart->custom_product_request) && count($cart->custom_product_request) > 0) {
                    $cartNeedsConfirmation = true;
                    foreach ($cart->custom_product_request as $customMerchantId => $customItems){
                        $merchant = MerchantInfo::whereId($customMerchantId)->first();
                        if($merchant) {

                            if(is_array($customItems)){
                                if(!array_key_exists($merchant->id, $newCartMerchants)){
                                    $merchantCountries[] = $merchant->country_id;
                                    $newCartMerchants[$merchant->id] = [
                                        'merchant' => $merchant,
                                        'needsPriceConfirmation' => false,
                                        'total' => 0,
                                        'totalOriginal' => 0,
                                        'totalSavings' => 0,
                                        'delivery_fee' => 0,
                                        'service_fee' => 0,
                                        'items' => []
                                    ];
//                                if($merchant->delivery_fee){
//                                    if($newCartMerchants[$merchant->id]['delivery_fee'] === 0){
//                                        $newCartMerchants[$merchant->id]['delivery_fee'] = $merchant->delivery_fee;
//                                    }
//                                }
                                }

                                $newCartMerchants[$merchant->id]['custom_items'] = $customItems;
                                $cartCount += count($customItems);
                                $newCartMerchants[$merchant->id]['needsPriceConfirmation'] = true;
                            }

                        }
                    }
                }

//            if($discount){
//                if($discount->is_percentage){
//                    $total = Helper::applyDiscount($total, $discount->rate);
//                    //$totalByMerchant =  Helper::applyDiscount($productPrice->orderPrice(), $discount->rate ?? 0);
//                }else{
//                    $total = Helper::applyDiscount($total, $discount->rate);
//                    $totalByMerchant =  $productPrice->orderPrice() - $discount->rate;
//                    if($totalByMerchant < 0 ){
//                        $totalByMerchant = 0;
//                    }
//                }
//            }else{
//                $total = Helper::applyDiscount($total, 0);
//            }

//            $total = Helper::applyDiscount($total, $discount->rate ?? 0);
            $total = Helper::applyDiscount($total,  0);
            $newCart = [
                'cart' => $cart,
                'cartCount' => $cartCount,
                'discount' => $discount,
                'total' => $total,
                'cartNeedsConfirmation' => $cartNeedsConfirmation,
                'totalSavings' => $totalOriginal - $total,
                'merchants' => $newCartMerchants,
                'merchantCountries' => array_unique($merchantCountries),
            ];


        }
        return $newCart;

    }



}
