<?php

namespace App\Http\Controllers\Frontend\Checkout;


use App\Helpers\CartHelper;
use App\Helpers\Helper;
use App\Helpers\OrderHelper;
use App\Http\Controllers\Controller;
use App\Models\Order\Cart;
use App\Models\Order\Discount;
use App\Models\Order\Order;
use App\Models\Order\OrderItem;
use App\Models\Order\OrderItemMutator;
use App\Models\Order\PaymentMethod;
use App\Models\Order\Timeframe;
use App\Models\User\MerchantInfo;
use App\Models\User\User;
use App\Models\User\UserAddress;
use App\Notifications\Order\OrderPlacedNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

use PnP;

class CheckoutControrller extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function merchants ()
    {
        Session::remove('open_cart');
        Session::remove('open_cart_no_animation');
        $user = Auth::user();
        $cartContent = CartHelper::getUserCartContent($user);
        $merchantCount = count($cartContent['merchants']);
        if($merchantCount > 0){
            if($merchantCount > 1){
                return view('frontend.checkout.checkout_merchants');
            }
            $merchant_key = array_keys($cartContent['merchants'])[0];
            $friendly_url = $cartContent['merchants'][$merchant_key]['merchant']->friendly_url;
            return redirect(route('checkout.address', $friendly_url));
        }
        abort(404);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function address($friendly_url)
    {
        Session::remove('open_cart');
        Session::remove('open_cart_no_animation');
        $merchant = MerchantInfo::available()->where('friendly_url', '=', $friendly_url)->firstOrFail();
        $user = User::with('addresses')->find(Auth::id());
        $cartContent = CartHelper::getUserCartContent($user);
        $merchantCart = null;
        if(count($cartContent['merchants']) > 0){
            if(array_key_exists($merchant->id, $cartContent['merchants'])){
                $merchantCart = $cartContent['merchants'][$merchant->id];
                return view('frontend.checkout.checkout_address', compact('merchant', 'merchantCart'));
            }
        }
        return redirect(route('home'));
//        abort(404);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function addressStore(Request $request, $friendly_url)
    {
//        return redirect()->back()->withInput();
        $user = Auth::user();
        $cart = CartHelper::getCart($user);
        if($cart){
            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:255'],
                'phone' => ['required', 'string', 'max:255'],
                'address' => ['required', 'string'],
                'country_id' => ['required', 'string', 'max:255', 'exists:countries,id'],
                'parish_id' => ['required', 'string', 'max:255', 'exists:parishes,id'],
            ], [
                'country_id.exists' => 'Select a valid Country',
                'parish_id.exists' => 'Select a valid Parish'
            ]);

            if ($validator->fails()) {
                return redirect(route('checkout.address', $friendly_url))->withErrors($validator)->withInput();
            }

            DB::beginTransaction();
            try{
                DB::table('user_addresses')->where('user_id', '=', $user->id)->where('current', '=', 1)->update(['current' => 0]);
                $userAddress = UserAddress::where('id', '=', $request->get('user_address_id'))->where('user_id', '=', $user->id)->first();
                if(!$userAddress){
                    $userAddress = new UserAddress();
                    $userAddress->user_id = $user->id;
                    $userAddress->enabled = 1;
                }
                $userAddress->name = $request->get('name');
                $userAddress->parish_id = $request->get('parish_id');
                $userAddress->country_id = $request->get('country_id');
                $userAddress->address = $request->get('address');
                $userAddress->phone = $request->get('phone');
                $userAddress->instructions = $request->get('instructions');
                $userAddress->current = 1;
                if($userAddress->save()){
                    $cart->user_address_id = $userAddress->id;
                    $cart->save();
                    DB::commit();
                }
                return redirect(route('checkout.timeframe', $friendly_url));
            }catch (\Throwable $exception){

            }
            DB::rollBack();
        }
        return redirect(route('checkout.address', $friendly_url))->withInput();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function timeframe($friendly_url)
    {
        Session::remove('open_cart');
        Session::remove('open_cart_no_animation');
        $merchant = MerchantInfo::available()->where('friendly_url', '=', $friendly_url)->firstOrFail();
        $user = User::with('addresses')->find(Auth::id());
        $cartContent = CartHelper::getUserCartContent($user);
        $merchantCart = null;
        if(count($cartContent['merchants']) > 0){
            if(array_key_exists($merchant->id, $cartContent['merchants'])){
                $merchantCart = $cartContent['merchants'][$merchant->id];
                $timeframes = Timeframe::enabled()->sortedByOrder()->get();
                $dates = [];
                for($i = 0; $i <= 7; $i++){
                    $dates[] = Carbon::now()->addDays($i);
                }
                return view('frontend.checkout.checkout_timeframe', compact('merchant', 'merchantCart', 'timeframes', 'dates'));
            }
        }
        abort(404);

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function timeframeStore(Request $request, $friendly_url)
    {
        $user = Auth::user();
        $cart = CartHelper::getCart($user);
        if($cart){
            $validator = Validator::make($request->all(), [
                'date' => ['required', 'numeric', 'min:0', 'max:7'],
                'timeframe' => ['required', 'string', 'max:255'],
            ]);

            if ($validator->fails()) {
                return redirect(route('checkout.timeframe', $friendly_url))->withErrors($validator)->withInput();
            }
            $delivery_date = Carbon::now()->addDays((int)$request->get('date', 0));
            $cart->delivery_date = $delivery_date;
            $cart->timeframe = $request->get('timeframe');
            if($cart->save()){
                return redirect(route('checkout.pay', $friendly_url));
            }
        }
        return redirect(route('checkout.timeframe', $friendly_url))->withInput();

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function pay($friendly_url)
    {




//        $payment->setCardAmount(50.232);
//        try{
//            $res = $payment->authorizePrev('2020081006414232001');
//            dd($res);
//        }catch (\Throwable $exception){
//            dd($exception->getMessage());
//        }

//        $p = new PnP([
//            'orderID' => 1,
//        ]);
//        $p->auth(
//            array(
//                'card-number' => '4111111111111111',
//                'card-name'   => 'cardtest',
//                'card-amount' => '100.23',
//                'card-exp'    => '11/09',
//                'ship-name'   => 'cardtest',
//                'card-cvv'      => '123',
//            )
//        );
//
//        dd( $p );

        Session::remove('open_cart');
        Session::remove('open_cart_no_animation');
        $merchant = MerchantInfo::available()->where('friendly_url', '=', $friendly_url)->firstOrFail();
        $user = User::with('addresses')->find(Auth::id());
        $cartContent = CartHelper::getUserCartContent($user);
        $merchantCart = null;
        if(count($cartContent['merchants']) > 0){
            if(array_key_exists($merchant->id, $cartContent['merchants'])){
                $merchantCart = $cartContent['merchants'][$merchant->id];
                $paymentMethods = PaymentMethod::enabled()->get();
                return view('frontend.checkout.checkout_pay', compact('merchant', 'merchantCart', 'paymentMethods'));
            }
        }
        abort(404);

    }




    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function payStore(Request $request, $friendly_url)
    {

        $user = Auth::user();
        $cart = CartHelper::getCart($user);

        if($cart){
            $validator = Validator::make($request->all(), [
                'payment_method' => ['required', 'string', 'in:credit_card'],
                'merchant_id' => ['required', 'numeric', 'min:1', 'exists:merchant_infos,id'],
            ], [
                'merchant_id.exists' => 'Select a valid Merchant',
                'payment_method.in' => 'Select a valid Payment Method'
            ]);

            if ($validator->fails()) {
                return redirect(route('checkout.pay', $friendly_url))->withErrors($validator)->withInput();
            }

            $payment_method_slug = $request->get('payment_method');
            $merchant_id = $request->get('merchant_id');
            $comment = $request->get('comment');

            $userAddress = $user->addresses()->current()->enabled(1)->first();
            if(!$userAddress || ($userAddress && (!$userAddress->country || !$userAddress->parish))){
                return redirect(route('checkout.pay', $friendly_url))->withErrors(['error' => 'Your address is not valid'])->withInput();
            }
            $paymentMethod = PaymentMethod::where('slug', '=', $payment_method_slug)->enabled()->first();
            if(!$paymentMethod){
                return redirect(route('checkout.pay', $friendly_url))->withErrors(['error' => 'Your payment method is not valid'])->withInput();
            }

            $merchant = MerchantInfo::available()->where('id', '=', $merchant_id)->first();
            if(!$merchant){
                return redirect(route('checkout.pay', $friendly_url))->withErrors(['error' => 'Your merchant is not valid'])->withInput();
            }

            $cartContent = CartHelper::getUserCartContent($user);
            $merchantCart = null;
            $discount = $cartContent['discount'];
            if(count($cartContent['merchants']) > 0){
                if(array_key_exists($merchant->id, $cartContent['merchants'])){
                    $merchantCart = $cartContent['merchants'][$merchant->id];
                }
            }
            if(!$merchantCart){
                return redirect(route('checkout.pay', $friendly_url))->withErrors(['error' => 'Your cart is not valid'])->withInput();
            }

            //$total = Helper::applyDiscount($total, $discount->rate ?? 0);

            $country_id = $userAddress->country->id;
            $parish_id = $userAddress->parish->id;

            DB::beginTransaction();

            try{

                $transaction_id = strtoupper(Str::random(30));

                $data = [
                    'user_id' => $user->id,
                    'user_address_id' => $userAddress->id,
                    'order_name' => $user->name,
                    'order_last_name' => $user->last_name,
                    'order_email' => $user->email,
                    'order_home_phone' => $user->home_phone,
                    'order_mobile_phone' => $user->mobile_phone,
                    'order_comment' => $comment,

                    'discount_id' => $discount ? $discount->id : null,

                    'merchant_id' => $merchant->id,

                    'delivery_country_id' => $userAddress->country->id,
                    'delivery_country' => $userAddress->country->name,
                    'delivery_parish_id' => $userAddress->parish->id,
                    'delivery_parish' => $userAddress->parish->name,
                    'delivery_address' => $userAddress->address,
                    'delivery_secondary_address' => $userAddress->secondary_address,
                    'delivery_lat' => $userAddress->lat,
                    'delivery_lon' => $userAddress->lon,
                    'delivery_phone' => $userAddress->phone,
                    'delivery_instructions' => $userAddress->instructions,
                    'delivery_date' => $cart->delivery_date ?: Carbon::now(),

                    //'delivery_timeframe_id' => $cart->timeframe_id,
                    'delivery_timeframe' => $cart->timeframe ?  $cart->timeframe : '',

                    'status' => 'pending',

                    'transaction_status' => 'pending_transaction_email',
                    'transaction_payment_method_id' => $paymentMethod->id,
                    'transaction_id' => $transaction_id,
                    'transaction_shipping' => $merchantCart['delivery_fee'],
                    'transaction_handling_cost' => $merchantCart['service_fee'],
                    'transaction_total' => $merchantCart['total'],
                ];

                if(array_key_exists('custom_items', $merchantCart) && is_array($merchantCart['custom_items']) && count($merchantCart['custom_items']) > 0){
                    $data['custom_product_request'] = $merchantCart['custom_items'];
                }else{
                    $data['custom_product_request'] = null;
                }

                $order = Order::create($data);
                foreach ($merchantCart['items'] as $merchantCartItem){
                    $orderItem = new OrderItem();
                    $orderItem->order_id = $order->id;
                    $orderItem->product_id = $merchantCartItem->product_id;
                    $orderItem->name = $merchantCartItem->product ? $merchantCartItem->product->name : '';
                    $orderItem->price = $merchantCartItem->orderPrice;
                    $orderItem->quantity = $merchantCartItem->quantity;
                    if($orderItem->save()){
                        foreach ($merchantCartItem->mutators as $merchantCartItemMutator) {
                            $orderItemMutator = new OrderItemMutator();
                            $orderItemMutator->order_item_id = $orderItem->id;
                            $orderItemMutator->product_mutator_id = $merchantCartItemMutator->product_mutator_id;
                            $orderItemMutator->name = $merchantCartItemMutator->mutator ? $merchantCartItemMutator->mutator->name : '';
                            $orderItemMutator->extra_price = $merchantCartItemMutator->mutator ? $merchantCartItemMutator->mutator->extra_price : 0;
                            $orderItemMutator->quantity = $merchantCartItemMutator->quantity;
                            $orderItemMutator->save();
                        }
                    }
                    $merchantCartItem->delete();
                }




                $cart->comment = null;
                $cart->delivery_date = null;
                $cart->timeframe_id = null;
                $cart->discount_id = null;
                $cart->save();

                $tmpCart = CartHelper::getCart($user);
                if($tmpCart){
                    if(count($tmpCart->items) === 0){
                        $cart->delete();
                    }
                }

                if(!$merchantCart['needsPriceConfirmation']){

                    if($merchantCart['total'] <= 0 && $discount && !$discount->is_percentage){
                        $order->transaction_status = 'approved';
                        $order->transaction_extra = $order->id . ' - Approved with discount bigger than amount to pay';
                        $order = OrderHelper::setStatusPaymentApproved($order);
                        $order = OrderHelper::setStatusReadyForFetcher($order);
                        $order->save();
                        DB::commit();
                        return redirect(route('checkout.approved', $order->id))->withInput();

                    }else{


                        $card = $request->get('card');
                        $exp = str_pad((int)$card['expire-month'], 2, '0', STR_PAD_LEFT);
                        $cardExp = $exp . '/' . substr($card['expire-year'], -2);


                        /**
                         * Anti Clicking problem solver
                         */

                        $cardKey = 'payment-request-' . md5(implode('-',$card));
                        $storedKeyExpiration = session($cardKey);
                        if ($storedKeyExpiration) {
                            $storedKeyExpirationDate = Carbon::parse($storedKeyExpiration);
                            if(Carbon::now()->gte($storedKeyExpirationDate)){
                                session([$cardKey => null]);
                                Session::save();
                            }else{
                                return redirect(route('checkout.pay', $friendly_url))->withErrors(['error' => 'Your credit card just requested a payment, please wait a few seconds to retry'])->withInput();
                            }
                        }
                        $keyExpiration = Carbon::now()->addSeconds(15)->format('Y-m-d H:i:s');
                        session([$cardKey => $keyExpiration]);
                        Session::save();

                        /**
                         * END Anti Clicking problem solver
                         */

                        $payment = new \PlugNPay();
                        $payment->setCardName($card['name'])
                            ->setCardNumber($card['number'])
                            ->setCardExp($cardExp)
                            ->setCardCVV($card['cvv'])
                            ->setCardAmount($merchantCart['total'])
                            ->setPhone($userAddress->phone)
                            ->setEmail($user->email)
                            ->setCardAddress1($userAddress->address)
                            ->setCardCity($userAddress->parish->name)
                            ->setCardProvince($userAddress->parish->name)
                            ->setCardState('ZZ')
                            //->setZip('BB27147')
                            ->setCardCountry(strtoupper($userAddress->country->iso3));

                        $res = $payment->authorize();
                        //$order = OrderHelper::setStatusWaitingForPayment($order);
                        if($res['FinalStatus'] === 'success'){
                            $order->transaction_status = 'approved';
                            $order->transaction_extra = $res['orderID'];
                            $order->transaction_info = $res;
                            $order = OrderHelper::setStatusPaymentApproved($order);
                            $order = OrderHelper::setStatusReadyForFetcher($order);
                            $order->save();
                            DB::commit();
                            return redirect(route('checkout.approved', $order->id))->withInput();
                        }
                        return redirect(route('checkout.problem', $order->id))->withInput();

                    }
                }else{

                    $order = OrderHelper::setStatusWaitingForPrice($order);
                    $order->save();

                    DB::commit();
                    return redirect(route('checkout.placed', $order->id))->withInput();
                }

            }catch (\Throwable $exception) {
                DB::rollBack();
                dd($exception->getMessage());
            }
        }
        return redirect(route('checkout.pay', $friendly_url))->withErrors(['error' => 'There was an error processing your order, try again'])->withInput();

    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function applyDiscount(Request $request)
    {
        $user = Auth::user();
        $cart = CartHelper::getCart($user);
        if($cart){
            $validator = Validator::make($request->all(), [
                'code' => ['required', 'string', 'exists:discounts,code'],
            ], [
                'code.exists' => 'The code does not exist',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            $code = $request->get('code');
            $discount = Discount::where('code', '=', $code)->first();
            if((int)$discount->enabled == 0){
                return redirect()->back()->withErrors(['error' => 'The code does not exist'])->withInput();
            }
            $tmpDiscountCartCount = Cart::where('discount_id', '=', $discount->id)->count();
            $tmpDiscountOrderCount = Order::where('discount_id', '=', $discount->id)->count();
            if($discount->consumable === 1){
                if($tmpDiscountCartCount || $tmpDiscountOrderCount){
                    return redirect()->back()->withErrors(['error' => 'The discount code entered has already been used'])->withInput();
                }
            }
            $cart->discount_id = $discount->id;
            if($cart->save()){
                return redirect()->back()->with(['success' => 'The discount code was applied correctly'])->withInput();
            }
        }
        return redirect()->back()->withErrors(['error' => 'There was an error processing your order, try again'])->withInput();

    }




//
//    /**
//     * Display a listing of the resource.
//     *
//     * @return \Illuminate\Http\Response
//     */
//    public function payStore(Request $request, $friendly_url)
//    {
//
//        dd($request->all());
//
//        $user = Auth::user();
//        $cart = CartHelper::getCart($user);
//        if($cart){
//            $validator = Validator::make($request->all(), [
//                'payment_method' => ['required', 'string', 'in:credit_card'],
//                'merchant_id' => ['required', 'numeric', 'min:1', 'exists:merchant_infos,id'],
//            ], [
//                'merchant_id.exists' => 'Select a valid Merchant',
//                'payment_method.in' => 'Select a valid Payment Method'
//            ]);
//
//            if ($validator->fails()) {
//                return redirect(route('checkout.pay', $friendly_url))->withErrors($validator)->withInput();
//            }
//
//            $payment_method_slug = $request->get('payment_method');
//            $merchant_id = $request->get('merchant_id');
//            $comment = $request->get('comment');
//
//            $userAddress = $user->addresses()->current()->enabled(1)->first();
//            if(!$userAddress || ($userAddress && (!$userAddress->country || !$userAddress->parish))){
//                return redirect(route('checkout.pay', $friendly_url))->withErrors(['error' => 'Your address is not valid'])->withInput();
//            }
//            $paymentMethod = PaymentMethod::where('slug', '=', $payment_method_slug)->enabled()->first();
//            if(!$paymentMethod){
//                return redirect(route('checkout.pay', $friendly_url))->withErrors(['error' => 'Your payment method is not valid'])->withInput();
//            }
//
//            $merchant = MerchantInfo::available()->where('id', '=', $merchant_id)->first();
//            if(!$merchant){
//                return redirect(route('checkout.pay', $friendly_url))->withErrors(['error' => 'Your merchant is not valid'])->withInput();
//            }
//
//            $cartContent = CartHelper::getUserCartContent($user);
//            $merchantCart = null;
//            if(count($cartContent['merchants']) > 0){
//                if(array_key_exists($merchant->id, $cartContent['merchants'])){
//                    $merchantCart = $cartContent['merchants'][$merchant->id];
//                }
//            }
//            if(!$merchantCart){
//                return redirect(route('checkout.pay', $friendly_url))->withErrors(['error' => 'Your cart is not valid'])->withInput();
//            }
//
//            $country_id = $userAddress->country->id;
//            $parish_id = $userAddress->parish->id;
//
//            DB::beginTransaction();
//
//            try{
//
//                $transaction_id = Str::random(20);
//
//                $data = [
//                    'user_id' => $user->id,
//                    'user_address_id' => $userAddress->id,
//                    'order_name' => $user->name,
//                    'order_last_name' => $user->last_name,
//                    'order_email' => $user->email,
//                    'order_home_phone' => $user->home_phone,
//                    'order_mobile_phone' => $user->mobile_phone,
//                    'order_comment' => $comment,
//
//                    'merchant_id' => $merchant->id,
//
//                    'delivery_country_id' => $userAddress->country->id,
//                    'delivery_country' => $userAddress->country->name,
//                    'delivery_parish_id' => $userAddress->parish->id,
//                    'delivery_parish' => $userAddress->parish->name,
//                    'delivery_address' => $userAddress->address,
//                    'delivery_secondary_address' => $userAddress->secondary_address,
//                    'delivery_lat' => $userAddress->lat,
//                    'delivery_lon' => $userAddress->lon,
//                    'delivery_phone' => $userAddress->phone,
//                    'delivery_instructions' => $userAddress->instructions,
//                    'delivery_date' => $cart->delivery_date ?: Carbon::now(),
//
//                    'delivery_timeframe_id' => $cart->timeframe_id,
//                    'delivery_timeframe' => $cart->timeframe ?  $cart->timeframe->name : '',
//
//                    'order_status' => 'pending',
//
//                    'transaction_status' => 'pending_transaction_email',
//                    'transaction_payment_method_id' => $paymentMethod->id,
//                    'transaction_id' => $transaction_id,
//                    'transaction_total' => $merchantCart['total'],
//                ];
//
//
//                $order = Order::create($data);
//                foreach ($merchantCart['items'] as $merchantCartItem){
//                    $orderItem = new OrderItem();
//                    $orderItem->order_id = $order->id;
//                    $orderItem->product_id = $merchantCartItem->product_id;
//                    $orderItem->name = $merchantCartItem->product ? $merchantCartItem->product->name : '';
//                    $orderItem->price = $merchantCartItem->orderPrice;
//                    $orderItem->quantity = $merchantCartItem->quantity;
//                    if($orderItem->save()){
//                        foreach ($merchantCartItem->mutators as $merchantCartItemMutator) {
//                            $orderItemMutator = new OrderItemMutator();
//                            $orderItemMutator->order_item_id = $orderItem->id;
//                            $orderItemMutator->product_mutator_id = $merchantCartItemMutator->product_mutator_id;
//                            $orderItemMutator->name = $merchantCartItemMutator->mutator ? $merchantCartItemMutator->mutator->name : '';
//                            $orderItemMutator->extra_price = $merchantCartItemMutator->mutator ? $merchantCartItemMutator->mutator->extra_price : 0;
//                            $orderItemMutator->quantity = $merchantCartItemMutator->quantity;
//                            $orderItemMutator->save();
//                        }
//                    }
//                    $merchantCartItem->delete();
//                }
//
//                $cart->comment = null;
//                $cart->delivery_date = null;
//                $cart->timeframe_id = null;
//                $cart->save();
//
//                $tmpCart = CartHelper::getCart($user);
//                if($tmpCart){
//                    if(count($tmpCart->items) === 0){
//                        $cart->delete();
//                    }
//                }
//
//
////                $payment = new \PlugNPay();
////
////                $payment->setCardName('cardtest')
////                    ->setCardNumber('4111111111111111')
////                    ->setCardExp('11/20')
////                    ->setCardCVV('123')
////                    ->setCardAmount(100.23)
////                    ->setPhone('3515929601')
////                    ->setEmail('patosmack@gmail.com')
////                    ->setCardAddress1('Industrial Estate')
////                    ->setCardCity('Nesfield')
////                    ->setCardState('ZZ')
////                    ->setZip('BB27147')
////                    ->setCardCountry('BBD');
////                try{
////                    $res = $payment->authorize();
////                    dd($res);
////                }catch (\Throwable $exception){
////                    dd($exception->getMessage());
////                }
//
//                $details = [
//                    'greeting' => 'Hi ' . $user->name,
//                    'body' => 'Your order was placed successfully',
//                    'thanks' => 'Thank you for using ' . env('APP_NAME'),
//                    'actionText' => 'View your orders',
//                    'actionURL' => route('account.orders'),
//                    'order_id' => $order->id,
//                ];
//                //$user->notify(new OrderPlacedNotification($details));
//
//                DB::commit();
//
//                return redirect(route('checkout.placed', $order->id))->withInput();
//
//            }catch (\Throwable $exception) {
//                DB::rollBack();
//            }
//        }
//        return redirect(route('checkout.pay', $friendly_url))->withErrors(['error' => 'There was an error processing your order, try again'])->withInput();
//
//    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function placed($order_id)
    {
        $user = Auth::user();
        $order = Order::where('user_id', '=', $user->id)->where('id', '=', $order_id)->firstOrFail();
        return view('frontend.checkout.checkout_placed', compact('order'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function problem($order_id){
        //$user = Auth::user();
        //$order = Order::where('id', '=', $order_id)->firstOrFail();
//        $order = Order::where('user_id', '=', $user->id)->where('id', '=', $order_id)->firstOrFail();
        return view('frontend.checkout.checkout_problem');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function approved($order_id){
        $user = Auth::user();
        $order = Order::where('user_id', '=', $user->id)->where('id', '=', $order_id)->firstOrFail();
        return view('frontend.checkout.checkout_approved', compact('order'));
    }


}

