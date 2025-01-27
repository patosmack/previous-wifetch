<?php

namespace App\Http\Controllers\Frontend\Auth;

use App\Helpers\CartHelper;
use App\Http\Controllers\Controller;
use App\Models\Order\CartItem;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('frontend.auth.login');
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        $cartWithItems = false;
        $unsessionedCart = CartHelper::getCart();
        $userCart = CartHelper::getCart($user);
        if($unsessionedCart){
            if(!$userCart){
                $unsessionedCart->user_id = $user->id;
                $unsessionedCart->user_token = null;
                $unsessionedCart->save();
                $cartWithItems = true;
            }else{
                DB::beginTransaction();
                $error = false;
                foreach ($unsessionedCart->items as $item){
                    $cartItem = CartItem::where('cart_id', '=', $userCart->id)->where('product_id', '=', $item->product_id)->first();
                    if($cartItem){
                        $cartItem->quantity += $item->quantity;
                        if(!$item->delete()){
                            $error = true;
                        }
                        if(!$cartItem->save()){
                            $error = true;
                        }
                    }else{
                        $item->cart_id = $userCart->id;
                        if(!$item->save()){
                            $error = true;
                        }
                    }
                }
                if(!$error){
                    $cartWithItems = true;
                    DB::commit();
                }else{
                    DB::rollBack();
                }

            }
        }else{
            if($userCart && $userCart->items && count($userCart->items) > 0){
                $cartWithItems = true;
            }
        }
        if($cartWithItems){
            Session::flash('open_cart_no_animation', true);
        }
    }
}
