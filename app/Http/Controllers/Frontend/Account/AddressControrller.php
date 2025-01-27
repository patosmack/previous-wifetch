<?php

namespace App\Http\Controllers\Frontend\Account;


use App\Http\Controllers\Controller;
use App\Models\User\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AddressControrller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('frontend.account.addresses');
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
            'phone' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string'],
            'country_id' => ['required', 'string', 'max:255', 'exists:countries,id'],
            'parish_id' => ['required', 'string', 'max:255', 'exists:parishes,id'],
        ], [
            'country_id.exists' => 'Select a valid Country',
            'parish_id.exists' => 'Select a valid Parish'
        ]);

        if ($validator->fails()) {
            return redirect(route('account.addresses'))->withErrors($validator)->withInput();
        }

        $userAddress = UserAddress::where('id', '=', $request->get('user_address_id'))->where('user_id', '=', $user->id)->first();
        if(!$userAddress){
            DB::table('user_addresses')->where('user_id', '=', $user->id)->where('current', '=', 1)->update(['current' => 0]);
            $userAddress = new UserAddress();
            $userAddress->user_id = $user->id;
            $userAddress->enabled = 1;
            $userAddress->current = 1;
        }
        $userAddress->name = $request->get('name');
        $userAddress->parish_id = $request->get('parish_id');
        $userAddress->country_id = $request->get('country_id');
        $userAddress->address = $request->get('address');
        $userAddress->phone = $request->get('phone');
        $userAddress->instructions = $request->get('instructions');

        if($userAddress->save()){
            return redirect(route('account.addresses'));
        }
        return redirect(route('account.addresses'))->withErrors(['error' => 'Your address could not be saved'])->withInput();
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
        $userAddress = UserAddress::where('id', '=',$id)->where('user_id', '=', $user->id)->first();
        if($userAddress){
            if($userAddress->delete()){
                return redirect(route('account.addresses'));
            }
        }
        return redirect(route('account.addresses'))->withErrors(['error' => 'Your address could not be deleted'])->withInput();
    }
}
