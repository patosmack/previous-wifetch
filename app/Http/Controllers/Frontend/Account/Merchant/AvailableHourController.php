<?php

namespace App\Http\Controllers\Frontend\Account\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Merchant\AvailableHour;
use App\Models\Merchant\PrivateCategory;
use App\Models\Merchant\Product;
use App\Models\User\MerchantInfo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AvailableHourController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $user = Auth::user();
        if($user->is_merchant){
            $merchant = MerchantInfo::with('availableHours')->available()->where('user_id', '=', $user->id)->where('id', '=', $id)->firstOrFail();
            return view('frontend.account.merchant.available_hours', compact('merchant'));
        }
        abort(404);
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
        if($user->is_merchant){

            $merchant_id = $request->get('merchant_id');
            $merchant = MerchantInfo::available()->where('user_id', '=', $user->id)->where('id', '=', $merchant_id)->first();

            if($merchant){
                $day = $request->get('day');
                $open_time = $request->get('open_time');
                $close_time = $request->get('close_time');
                $error = false;
                try{
                    $open_time = Carbon::parse($open_time);
                    $open_time = $open_time->format('H:i:s');
                }catch (\Exception $exception){
                    $error = true;
                }
                try{
                    $close_time = Carbon::parse($close_time);
                    $close_time = $close_time->format('H:i:s');
                }catch (\Exception $exception){
                    $error = true;
                }
                if(!$error){
                    $availableHour = new AvailableHour();
                    $availableHour->merchant_info_id = $merchant_id;
                    $availableHour->day = $day;
                    $availableHour->open_time = $open_time;
                    $availableHour->close_time = $close_time;
                    if($availableHour->save()){
                        return redirect()->back()->with(['success' => 'The Available hour was created successfully'])->withInput();
                    }
                }
            }
            return redirect()->back()->withErrors(['error' => 'There was a problem generating the business available hour'])->withInput();
        }
        abort(404);
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

        $availableHour = AvailableHour::where('id', '=', $id)->first();

        if($availableHour){
            $merchant = MerchantInfo::where('user_id', '=', $user->id)->where('id', '=', $availableHour->merchant_info_id)->first();
            if($merchant){
                if($availableHour->delete()){
                    return redirect(route('account.merchant.available_hours', $merchant->id));
                }
            }
        }
        return redirect()->back()->withErrors(['error' => 'There was a problem removing the business available hour']);

    }


}
