<?php

namespace App\Http\Controllers\External;

use App\Helpers\CartHelper;
use App\Helpers\OrderHelper;
use App\Http\Controllers\Controller;
use App\Models\Order\OrderTransaction;
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

class ExtraPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($transaction_id)
    {
        $orderTransaction = OrderTransaction::where('transaction_id', '=', $transaction_id)->where('transaction_status', '=', 'pending')->firstOrFail();
        if($orderTransaction && $orderTransaction->order){
            $order = $orderTransaction->order;
            $merchant = $order->merchant;
            $paymentMethods = PaymentMethod::enabled()->get();
            return view('external.extra_payment', compact('merchant', 'merchant', 'orderTransaction', 'order', 'paymentMethods'));
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
        $transaction_id = $request->get('transaction_id');
        $orderTransaction = OrderTransaction::where('transaction_id', '=', $transaction_id)->where('transaction_status', '=', 'pending')->first();

        if($orderTransaction && $orderTransaction->order){
            $order = $orderTransaction->order;
            $validator = Validator::make($request->all(), [
                'payment_method' => ['required', 'string', 'in:credit_card'],
            ], [
                'payment_method.in' => 'Select a valid Payment Method'
            ]);
            if ($validator->fails()) {
                return redirect(route('extra_payment.view', $transaction_id))->withErrors($validator)->withInput();
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
                        ->setCardAmount($orderTransaction->transaction_total)
                        ->setPhone($order->delivery_phone)
                        ->setEmail($order->email)
                        ->setCardAddress1($order->delivery_address)
                        ->setCardCity($order->delivery_parish)
                        ->setCardProvince($order->delivery_parish)
                        ->setCardState('ZZ')
                        //->setZip('BB27147')
                        ->setCardCountry($order->country ? strtoupper($order->country->iso3) : 'BRB');

                    $res = $payment->authorize();
                    if($res['FinalStatus'] === 'success'){
                        //'pending','pending_transaction_email','approved','rejected','refunded','correction_requested','partially_refunded','canceled'
                        $orderTransaction->transaction_status = 'approved';
                        $orderTransaction->transaction_extra = $res['orderID'];
                        $orderTransaction->transaction_info = $res;
//                        $order = OrderHelper::setStatusPaymentApproved($order);
//                        $order = OrderHelper::setStatusReadyForFetcher($order);
                        $orderTransaction->save();

                        OrderHelper::statusOrderTransactionNotification($orderTransaction);

                        DB::commit();
                        return redirect(route('extra_payment.approved', $orderTransaction->id))->withInput();
                    }else{

                    }
                    return redirect(route('extra_payment.problem', $orderTransaction->id))->withInput();

            }catch (\Throwable $exception) {
                dd($exception->getMessage());
                DB::rollBack();
            }
        }
        return redirect(route('extra_payment.view', $transaction_id))->withErrors(['error' => 'There was an error processing your order, try again'])->withInput();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function placed($order_id)
    {
        $orderTransaction = OrderTransaction::where('id', '=', $order_id)->firstOrFail();
        if($orderTransaction->order){
            $order = $orderTransaction->order;
            return view('external.extra_checkout_placed', compact('orderTransaction','order'));
        }
        abort(404);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function problem($order_id){

        $orderTransaction = OrderTransaction::where('id', '=', $order_id)->firstOrFail();
        if($orderTransaction->order){
            $order = $orderTransaction->order;
            return view('external.extra_checkout_problem', compact('orderTransaction', 'order'));
        }
        abort(404);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function approved($order_id){
        $orderTransaction = OrderTransaction::where('id', '=', $order_id)->firstOrFail();
        if($orderTransaction->order){
            $order = $orderTransaction->order;
            return view('external.extra_checkout_approved', compact('orderTransaction','order'));
        }
        abort(404);
    }
}
