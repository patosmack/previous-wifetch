<?php

namespace App\Http\Controllers\Frontend\Checkout;

use App\Helpers\CartHelper;
use App\Http\Controllers\Controller;
use App\Models\Merchant\Product;
use App\Models\Order\Cart;
use App\Models\Order\CartItem;
use App\Models\Order\CartItemMutator;
use App\Models\User\MerchantInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;


class CartControrller extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeCustomItem(Request $request)
    {
        $merchant_id = $request->get('merchant_id');
        $customItem = $request->get('custom_item');
        $open_cart_on_complete = $request->get('open_cart_on_complete', false);

        if(!$customItem || strlen($customItem) <= 2){
            return redirect()->back()->withErrors('You should type at lease 3 letters to add a custom product');
        }


        if(!MerchantInfo::find($merchant_id)){
            return redirect()->back()->withErrors('The merchant could not be found');
        }



//        if($quantity <= 0){
//            return redirect()->back()->withErrors('The quantity of products cannot be less than or equal to zero');
//        }

        $user = Auth::user();

        DB::beginTransaction();
        try{
            $cartCreated = true;
            if($user){
                $cart = Cart::where('user_id', '=', $user->id)->first();
                if(!$cart){
                    $cart = new Cart();
                    $cart->user_id = $user->id;
                    $cartCreated = $cart->save();
                }
            }else{
                $token = CartHelper::getUserToken();
                $cart = Cart::where('user_token', '=', $token)->first();
                if(!$cart){
                    $cart = new Cart();
                    $cart->user_token = $token;
                    $cartCreated = $cart->save();
                }
            }
            if(!$cartCreated){
                throw new \Exception('Cart could not be created');
            }

            $originalCustomItems = $cart->custom_product_request;
            DB::commit();

            if(!$originalCustomItems || ($originalCustomItems && !array_key_exists($merchant_id, $originalCustomItems))){
                $data = [
                    $merchant_id => [
                        $customItem,
                    ]
                ];
                $cart->custom_product_request = $data;
                $cart->save();
            }else{
                if(array_key_exists($merchant_id, $originalCustomItems)){
                    $originalCustomItems[$merchant_id][] = $customItem;
                    $cart->custom_product_request = $originalCustomItems;
                    $cart->save();
                }
            }

            if($open_cart_on_complete){
                Session::flash('open_cart', true);
            }
            return redirect()->back()->with(['success' => 'The product was added successfully to your cart']);
        }catch (\Throwable $exception){
            DB::rollback();
        }
        return redirect()->back()->withErrors('The product could not be added to cart, try again');


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $product_id = $request->get('product_id');
        $quantity = (int)$request->get('quantity', 1);
        $single_mutator = $request->get('single_mutator', []);
        $multiple_mutator = $request->get('multiple_mutator', []);
        $multiple_mutator_quantity = $request->get('multiple_mutator_quantity', []);
        $open_cart_on_complete = $request->get('open_cart_on_complete', true);
        $product = Product::with([
                'merchant' => function($query){
                    $query->available();
                },
                'mutators' => function($query){
                    $query->available()->orderBy('extra_price');
                },
                'mutatorGroups' => function($query){
                    $query->available()->orderBy('choice_mode','ASC');
                },
                'mutatorGroups.mutators' => function($query){
                    $query->available()->orderBy('extra_price');
                },
            ])->available()->where('id', '=', $product_id)->first();

        if(!$product){
            return redirect()->back()->withErrors('The product you are trying to buy is not available at the moment');
        }
        if($quantity <= 0){
            return redirect()->back()->withErrors('The quantity of products cannot be less than or equal to zero');
        }

        $user = Auth::user();

        $cartContent = CartHelper::getUserCartContent($user);

        if(count($cartContent['merchantCountries']) > 0 && $product->merchant && !in_array($product->merchant->country_id, $cartContent['merchantCountries'])){
            return redirect()->back()->withErrors('Your cart has one or more products from a different country, please complete your previous order to continue');
        }
        DB::beginTransaction();
        try{
            $cartCreated = true;
            if($user){
                $cart = Cart::where('user_id', '=', $user->id)->first();
                if(!$cart){
                    $cart = new Cart();
                    $cart->user_id = $user->id;
                    $cartCreated = $cart->save();
                }
            }else{
                $token = CartHelper::getUserToken();
                $cart = Cart::where('user_token', '=', $token)->first();
                if(!$cart){
                    $cart = new Cart();
                    $cart->user_token = $token;
                    $cartCreated = $cart->save();
                }
            }
            if(!$cartCreated){
                throw new \Exception('Cart could not be created');
            }

            $cartItem = null;
            if(count($product->mutatorGroups) === 0){
                $cartItem = CartItem::where('cart_id', '=', $cart->id)->where('product_id', '=', $product->id)->first();
            }
            if(!$cartItem){
                $cartItem = new CartItem();
                $cartItem->cart_id = $cart->id;
                $cartItem->product_id = $product->id;
                $cartItem->quantity = $quantity;
            }else{
                $cartItem->quantity += $quantity;
            }
            if(!$cartItem->save()){
                throw new \Exception('Cart could not be created');
            }


            $single_mutator_needed = null;

            foreach ($product->mutatorGroups as $mutatorGroup){
                if($mutatorGroup->choice_mode === 'single'){
                    $single_mutator_needed = $mutatorGroup;
                    if(array_key_exists($mutatorGroup->id, $single_mutator)){
                        $mutator_id = (int)$single_mutator[$mutatorGroup->id];
                        foreach ($mutatorGroup->mutators as $mutator){
                            if($mutator_id === $mutator->id){
                                $this->createCartItemMutator($cartItem, $mutator, 1);
                                $single_mutator_added = true;
                            }
                        }
                    }
                }else if($mutatorGroup->choice_mode === 'multiple'){
                    $mutator_ids = $mutatorGroup->mutators->pluck('id')->toArray();
                    foreach ($multiple_mutator as $mutator_id){
                        if(in_array($mutator_id, $mutator_ids)){
                            $mutator = $mutatorGroup->mutators->find($mutator_id);
                            if(!$mutatorGroup->allow_quantity_selector){
                                $this->createCartItemMutator($cartItem, $mutator, 1);
                            }else{
                                if(array_key_exists($mutator_id, $multiple_mutator_quantity)){
                                    $mutator_quantity = (int)$multiple_mutator_quantity[$mutator_id];
                                    $this->createCartItemMutator($cartItem, $mutator, $mutator_quantity);
                                }
                            }
                        }
                    }
                }
            }

            if($single_mutator_needed){
                if(!$single_mutator_added){
                    DB::rollback();
                    return redirect()->back()->withErrors("The {$mutatorGroup->name} field is required");
                }
            }
            DB::commit();
            if($open_cart_on_complete){
                Session::flash('open_cart', true);
            }
            return redirect()->back()->with(['success' => 'The product was added successfully to your cart']);

        }catch (\Throwable $exception){
            DB::rollback();
        }
        return redirect()->back()->withErrors('The product could not be added to cart, try again');


    }

    private function createCartItemMutator($cartItem, $mutator, $quantity){
        if(!$cartItem){
            throw new \Exception('Cart Item should exists');
        }
        if(!$mutator){
            throw new \Exception('Mutator should exists');
        }
        if($quantity <=0){
            throw new \Exception('Quantity cannot be less than or equal to zero');
        }
        $cartItemMutator = new CartItemMutator();
        $cartItemMutator->cart_item_id = $cartItem->id;
        $cartItemMutator->product_mutator_id = $mutator->id;
        $cartItemMutator->quantity = $quantity;
        if(!$cartItemMutator->save()){
            throw new \Exception('Cart Item Mutator could not be created');
        }
        return $cartItemMutator;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $cart = CartHelper::getCart($user);
        if($cart) {
            $open_cart = (bool)\request()->get('open_cart', false);
            try {
                $cartItem = CartItem::where('cart_id', '=', $cart->id)->whereId($id)->first();
                $cartItem->delete();
                if ($open_cart) {
                    Session::flash('open_cart_no_animation', true);
                }
                return redirect()->back()->with('success', 'The product was removed from cart');
            } catch (\Throwable $exception) {
                return redirect()->back()->withErrors('The product you are trying to remove is not available at the moment, try again');
            }
        }
        return redirect()->back()->withErrors('The product you are trying to remove could not be found');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyCustomItem($merchant_id)
    {

        $user = Auth::user();
        $cart = CartHelper::getCart($user);
        if($cart){
            $open_cart = (bool)\request()->get('open_cart', false);
            $custom_item = (string)\request()->get('custom_item', null);
            try{

                if(is_array($cart->custom_product_request) && array_key_exists($merchant_id, $cart->custom_product_request)){
                    $custom_request = $cart->custom_product_request;
                    $items = $custom_request[$merchant_id];
                    if(is_array($items)){
                        $newItems = [];
                        foreach ($items as $item) {
                            if($item !== $custom_item){
                                $newItems[] = $item;
                            }
                        }
                        if(count($newItems) > 0){
                            $custom_request[$merchant_id] = $newItems;
                        }else{
                            unset($custom_request[$merchant_id]);
                        }

                        if(count($custom_request) > 0){
                            $cart->custom_product_request = $custom_request;
                        }else{
                            $cart->custom_product_request = null;
                        }

                        if($cart->save()){
                            if($open_cart) {
                                Session::flash('open_cart_no_animation', true);
                            }
                            return redirect()->back()->with('success', 'The product was removed from cart');
                        }
                    }
                }
            }catch (\Throwable $exception){
                dd($exception->getMessage());
                return redirect()->back()->withErrors('The product you are trying to remove is not available at the moment, try again');
            }
        }
        return redirect()->back()->withErrors('The product you are trying to remove could not be found');
    }


}
