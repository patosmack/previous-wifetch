<?php

namespace App\Http\Controllers\External;

use App\Helpers\CartHelper;
use App\Helpers\OrderHelper;
use App\Http\Controllers\Controller;
use App\Models\Order\Order;
use App\Models\Order\OrderItem;
use App\Models\Order\OrderItemMutator;
use App\Models\Order\PaymentMethod;
use App\Models\User\MerchantInfo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($transaction_id)
    {
//        $order = Order::where('transaction_id', '=', $transaction_id)->whereIn('status', ['waiting_for_payment', 'waiting_for_price'])->firstOrFail();
//        dd($transaction_id);
        //QNPLZ1XMXZTPWIBNKTDXHLEZYV9BVI
        $order = Order::where('transaction_id', '=', $transaction_id)->whereIn('status', ['waiting_for_payment'])->firstOrFail();
        $merchant = $order->merchant;
        $paymentMethods = PaymentMethod::enabled()->get();
        return view('external.external_payment', compact('merchant', 'merchant', 'order', 'paymentMethods'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $transaction_id = $request->get('transaction_id');
        $order = Order::where('transaction_id', '=', $transaction_id)->where('status', '=', 'waiting_for_payment')->first();

        if($order){
            $validator = Validator::make($request->all(), [
                'payment_method' => ['required', 'string', 'in:credit_card'],
            ], [
                'payment_method.in' => 'Select a valid Payment Method'
            ]);

            if ($validator->fails()) {
                return redirect(route('external_payment.view', $transaction_id))->withErrors($validator)->withInput();
            }

            DB::beginTransaction();
            try{
                    $card = $request->get('card');
                    $exp = str_pad((int)$card['expire-month'], 2, '0', STR_PAD_LEFT);
                    $cardExp = $exp . '/' . substr($card['expire-year'], -2);
                    $payment = new \PlugNPay();
                    $payment->setCardName($card['name'])
                        ->setCardNumber($card['number'])
                        ->setCardExp($cardExp)
                        ->setCardCVV($card['cvv'])
                        ->setCardAmount($order->transaction_total)
                        ->setPhone($order->delivery_phone)
                        ->setEmail($order->email)
                        ->setCardAddress1($order->delivery_address)
                        ->setCardCity($order->delivery_parish)
                        ->setCardProvince($order->delivery_parish)
                        ->setCardState('ZZ')
                        //->setZip('BB27147')
                        ->setCardCountry($order->country ? strtoupper($order->country->iso3) : 'BRB');

                    $res = $payment->authorize();
                    $order = OrderHelper::setStatusWaitingForPayment($order);
                    if($res['FinalStatus'] === 'success'){
                        //'pending','pending_transaction_email','approved','rejected','refunded','correction_requested','partially_refunded','canceled'
                        $order->transaction_status = 'approved';
                        $order->transaction_extra = $res['orderID'];
                        $order->transaction_info = json_encode($res);
                        $order = OrderHelper::setStatusPaymentApproved($order);
                        $order = OrderHelper::setStatusReadyForFetcher($order);
                        $order->save();
                        DB::commit();
                        return redirect(route('external_payment.approved', $order->id))->withInput();
                    }
                    return redirect(route('external_payment.problem', $order->id))->withInput();

            }catch (\Throwable $exception) {

                DB::rollBack();
            }
        }
        return redirect(route('external_payment.view', $transaction_id))->withErrors(['error' => 'There was an error processing your order, try again'])->withInput();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function placed($order_id)
    {
        $order = Order::where('id', '=', $order_id)->firstOrFail();
        return view('external.checkout_placed', compact('order'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function problem($order_id){
        $order = Order::where('id', '=', $order_id)->firstOrFail();
        return view('external.checkout_problem', compact('order'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function approved($order_id){
        $order = Order::where('id', '=', $order_id)->firstOrFail();
        return view('external.checkout_approved', compact('order'));
    }
}
