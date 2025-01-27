<?php

namespace App\Http\Controllers\Frontend\Account;


use App\Http\Controllers\Controller;
use App\Models\User\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileControrller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('frontend.account.profile');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => 'unique:users,email,'.$user->id.',id',
        ]);
        if ($validator->fails()) {
            return redirect(route('account.profile'))->withErrors($validator)->withInput();
        }
        $user->email = $request->get('email');
        $user->name = $request->get('name');
        $user->last_name = $request->get('last_name');
        $user->home_phone = $request->get('home_phone');
        $user->mobile_phone = $request->get('mobile_phone');
        if($password = $request->get('password')){
            if(strlen($password) < 8){
                return redirect(route('account.profile'))->withErrors(['error' => 'Your password should have at least 8 characters'])->withInput();
            }
            if($password === $request->get('password')){
                $user->password =  Hash::make($password);
            }else{
                return redirect(route('account.profile'))->withErrors(['error' => 'Your password and confirmation do not match'])->withInput();
            }
        }
        if($user->save()){
            return redirect(route('account.profile'));
        }
        return redirect(route('account.profile'))->withErrors(['error' => 'Your address could not be saved'])->withInput();
    }

}
