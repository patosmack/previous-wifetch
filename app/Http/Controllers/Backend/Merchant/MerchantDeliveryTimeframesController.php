<?php

namespace App\Http\Controllers\Backend\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Merchant\DeliveryTimeframe;
use App\Models\Merchant\PrivateCategory;
use App\Models\Merchant\Product;
use App\Models\User\MerchantInfo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MerchantDeliveryTimeframesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $merchant = MerchantInfo::with('deliveryTimeframes')->where('id', '=', $id)->firstOrFail();
        return view('backend.merchant.delivery_timeframes', compact('merchant'));
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $merchant_id = $request->get('merchant_id');
        $merchant = MerchantInfo::where('id', '=', $merchant_id)->first();
        if($merchant){
            $open_time = $request->get('open_time');
            $close_time = $request->get('close_time');
            $name = $open_time . ' - ' . $close_time;

            $deliveryTimeframe = new DeliveryTimeframe();
            $deliveryTimeframe->merchant_info_id = $merchant_id;
            $deliveryTimeframe->name = $name;
            if($deliveryTimeframe->save()){
                return redirect()->back()->with(['success' => 'The Delivery Timeframe was created successfully'])->withInput();
            }
        }
        return redirect()->back()->withErrors(['error' => 'There was a problem generating the business delivery timeframe'])->withInput();

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deliveryTimeframe = DeliveryTimeframe::where('id', '=', $id)->first();
        if($deliveryTimeframe){
            $merchant = MerchantInfo::where('id', '=', $deliveryTimeframe->merchant_info_id)->first();
            if($merchant){
                if($deliveryTimeframe->delete()){
                    return redirect(route('backend.merchant.delivery_timeframes', $merchant->id));
                }
            }
        }
        return redirect()->back()->withErrors(['error' => 'There was a problem removing the business delivery timeframe']);

    }


}
