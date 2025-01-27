<?php

namespace App\Http\Controllers\Frontend\Base;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Order\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ServiceRateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($transaction_id = null)
    {
        return  view('frontend.service_rate.service_rate', compact('transaction_id'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function response()
    {
        return  view('frontend.service_rate.service_rate_response');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'transaction_id' => ['required', 'string', 'exists:orders,transaction_id'],
            'message' => ['nullable', 'string'],
        ], [
            'order_id.exists' => 'The order to rate is not valid',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $rating = $request->get('rating');
        $transaction_id = $request->get('transaction_id');
        $message = $request->get('message');
        if($message){
            $message = Helper::stripTagsContent($message);
        }
        $order = Order::where('transaction_id', '=', $transaction_id)->first();
        if(!$order){
            return redirect()->back()->withErrors(['error' => 'The order could not be found'])->withInput();
        }
        if($order->rating && $order->rating >= 1 && $order->rating <= 5){
            return redirect()->back()->withErrors(['error' => 'The order was already reviewed'])->withInput();
        }
        $order->rating = $rating;
        $order->message = $message;
        if($order->save()){
            return redirect(route('service.rate.response'));
        }
        return redirect()->back()->withErrors(['error' => 'The order rating could not be saved'])->withInput();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
