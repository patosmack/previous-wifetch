<?php

namespace App\Http\Controllers\Frontend\Auth;

use App\Helpers\CartHelper;
use App\Http\Controllers\Controller;
use App\Models\User\User;
use App\Models\User\UserAddress;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        return view('frontend.auth.register');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'country_id' => ['required', 'string', 'max:255', 'exists:countries,id'],
            'parish_id' => ['required', 'string', 'max:255', 'exists:parishes,id'],
        ], [
            'country_id.exists' => 'Select a valid Country',
            'parish_id.exists' => 'Select a valid Parish'
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User\User
     */
    protected function create(array $data)
    {
        /*
        * Create default Address
        */
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $unsessionedCart = CartHelper::getCart();
        if($unsessionedCart){
            $unsessionedCart->user_id = $user->id;
            $unsessionedCart->user_token = null;
            $unsessionedCart->save();
            if($unsessionedCart && $unsessionedCart->items && count($unsessionedCart->items) > 0){
                $cartWithItems = true;
            }
            if($cartWithItems){
                Session::flash('open_cart_no_animation', true);
            }
        }

        if($this->createDefaultAddress($user, $data)){
            return $user;
        }
        return null;
    }

    /**
     * Create a new user instance after a valid registration.
     * @param  User  $user
     * @param  array  $data
     * @return bool
     */
    protected function createDefaultAddress(User $user, array $data){
        $userAddress = new UserAddress();
        $userAddress->name = 'Primary';
        $userAddress->user_id = $user->id;
        $userAddress->parish_id = $data['parish_id'];
        $userAddress->country_id = $data['country_id'];
        $userAddress->current = 1;
        $userAddress->enabled = 1;
        return $userAddress->save();
    }
}
