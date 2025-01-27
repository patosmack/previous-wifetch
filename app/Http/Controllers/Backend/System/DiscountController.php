<?php

namespace App\Http\Controllers\Backend\System;

use App\Helpers\OrderHelper;
use App\Http\Controllers\Controller;
use App\Models\Order\Discount;
use App\Models\Order\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request as Input;
use Illuminate\Support\Facades\Validator;

class DiscountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $status = Input::get('status', null);
        $allowed_status = ['enabled', 'disabled'];
        return view('backend.system.discounts', compact('status', 'allowed_status'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($order_id)
    {
        $order = Order::where('id', '=', $order_id)->firstOrFail();
        $merchant = $order->merchant;
        $status = Input::get('status', null);
        $allowed_status = OrderHelper::getAvailableStatus();
        return view('backend.discount.order', compact('status', 'allowed_status', 'merchant', 'order'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function datatable(Request $request, $status = null){

        $data = [];
        $totalData = 0;
        $totalFiltered = 0;

        $columns = [
            'id',
            'code',
            'rate',
            'is_percentage',
//            'consumable',
//            'quantity',
            'enabled',
            'status',
        ];

        $limit = $request->input('length', 40);
        $start = $request->input('start', 0);

        $sort_order = $columns[1];
        $dir = 'DESC';

        try{
            $sort_order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');
        }catch (\Throwable $exception){

        }
        $searchQuery = $request->input('search.value');

        $itemsQuery = Discount::select(
            DB::raw('discounts.id as id, discounts.code , discounts.is_percentage, discounts.rate, discounts.consumable, discounts.quantity, discounts.enabled, carts.id as cart_id, orders.id as order_id' )
        )
            ->leftjoin('orders', 'discounts.id', '=', 'orders.discount_id')
            ->leftjoin('carts', 'discounts.id', '=', 'carts.discount_id')
            ->offset($start)
            ->limit($limit)
            ->orderBy($sort_order,$dir);

        $totalDataQuery = Discount::select(DB::raw('discounts.id as id'))
            ->leftjoin('orders', 'discounts.id', '=', 'orders.discount_id')
            ->leftjoin('carts', 'discounts.id', '=', 'carts.discount_id');
//
//        if($status){
//            $itemsQuery = $itemsQuery->where('orders.status', '=', $status);
//            $totalDataQuery = $totalDataQuery->where('orders.status', '=', $status);
//        }

        if(!empty($searchQuery)){
            $search = str_replace('-', ' ', $searchQuery);
            $itemsQuery = $this->buildMerchantSearchQuery($itemsQuery, $search);
            $totalDataQuery = $this->buildMerchantSearchQuery($totalDataQuery, $search);
        }

        $totalData = $totalDataQuery->count();
        $items = $itemsQuery->get();

        foreach ($items as $item){
            $data[] =  [
                'id' => [
                    'id' => $item->id,
                    'value' => $item->id ?: '',
                ],
                'code' => [
                    'id' => $item->id,
                    'value' => $item->code ?: '',
                ],
                'rate' => [
                    'id' => $item->id,
                    'value' => $item->rate ?: '',
                ],
                'is_percentage' => [
                    'id' => $item->id,
                    'value' => $item->is_percentage,
                ],
                'quantity' => [
                    'id' => $item->id,
                    'value' => $item->quantity ?: 0,
                ],
                'consumable' => [
                    'id' => $item->id,
                    'value' => $item->consumable,
                ],
                'enabled' => [
                    'id' => $item->id,
                    'value' => $item->enabled,
                ],
                'status' => [
                    'id' => $item->id,
                    'status' => !($item->cart_id || $item->order_id),
                    'value' => ($item->cart_id || $item->order_id) ? 'Used' : 'Available',
                ],
            ];
        }

        $json_data = array(
            "draw"            => (int)$request->input('draw'),
            "recordsTotal"    => $totalData,
            "recordsFiltered" => $totalData - $totalFiltered,
            "data"            => $data,
            'allowed_status'      => OrderHelper::getAvailableStatus(),
            "sorts"            => $sort_order,
        );
        return response()->json($json_data);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function easyEdit(Request $request)
    {
        $allowed_targets = ['code', 'rate', 'consumable', 'quantity', 'enabled', 'is_percentage'];
        $discount_id = $request->get('id');
        $target = $request->get('target');
        $value = $request->get('value');
        $errorMessage = null;


        if(in_array($target, $allowed_targets)){
            $discount = Discount::find($discount_id);
            if($discount){
                if($target === 'code'){
                    if($value !== ''){
                        if(!Discount::where('code', '=', $value)->where('id', '!=', $discount->id)->first()){
                            $discount->code = $value;
                            if($discount->save()){
                                return response()->json(['code' => 200, 'message' => 'Saved']);
                            }else{
                                $errorMessage = 'Code not saved';
                            }
                        }else{
                            $errorMessage = 'Code already exists';
                        }
                    }else{
                        $errorMessage = 'Empty code';
                    }
                }else if($target === 'rate') {
                    $value = (double)$value;
                    if ($value >= 0) {
                        $discount->rate = $value;
                        if ($discount->save()) {
                            return response()->json(['code' => 200, 'message' => 'Saved']);
                        } else {
                            $errorMessage = 'Rate not saved';
                        }
                    } else {
                        $errorMessage = 'Rate between 0 ~ 100';
                    }
                }else if($target === 'consumable') {
                    $value = (int)$value;
                    if ($value === 0 || $value === 1) {
                        $discount->consumable = $value;
                        if ($discount->save()) {
                            return response()->json(['code' => 200, 'message' => 'Saved']);
                        } else {
                            $errorMessage = 'One Time use status not saved';
                        }
                    } else {
                        $errorMessage = 'Only enabled or disabled';
                    }
                }else if($target === 'quantity') {
                    $value = (int)$value;
                    if ($value > 0) {
                        $discount->quantity = $value;
                        if ($discount->save()) {
                            return response()->json(['code' => 200, 'message' => 'Saved']);
                        } else {
                            $errorMessage = 'Quantity not saved';
                        }
                    } else {
                        $errorMessage = 'Quantity bigger than 0';
                    }
                }else if($target === 'enabled') {
                    $value = (int)$value;
                    if ($value === 0 || $value === 1) {
                        $discount->enabled = $value;
                        if ($discount->save()) {
                            return response()->json(['code' => 200, 'message' => 'Saved']);
                        } else {
                            $errorMessage = 'Status not saved';
                        }
                    } else {
                        $errorMessage = 'Only enabled or disabled';
                    }
                }else if($target === 'is_percentage') {
                    $value = (int)$value;
                    if ($value === 0 || $value === 1) {
                        $discount->is_percentage = $value;
                        if ($discount->save()) {
                            return response()->json(['code' => 200, 'message' => 'Saved']);
                        } else {
                            $errorMessage = 'Status not saved';
                        }
                    } else {
                        $errorMessage = 'Only enabled or disabled';
                    }
                }else{
                    $errorMessage = 'Invalid Target';
                }
            }else{
                $errorMessage = 'Merchant not found';
            }
        }else{
            $errorMessage = 'Target not allowed';
        }

        return response()->json([
            'code'      =>  404,
            'message'   =>  $errorMessage ?: 'Unhandled Error'
        ], 404);

    }

    /**
     * store
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => ['required', 'string', 'max:255', 'unique:discounts'],
            'rate' => ['required', 'numeric', 'min:0'],
            'quantity' => ['required', 'integer', 'min:0'],
            'consumable' => ['required', 'integer', 'min:0', 'max:1'],
            'is_percentage' => ['required', 'integer', 'min:0', 'max:1'],
            'enabled' => ['required', 'integer', 'min:0', 'max:1'],
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $discount = Discount::create($request->all());
        if($discount){
            return redirect()->back()->with(['success' => 'The discount was created successfully'])->withInput();
        }
        return redirect()->back()->withErrors(['error' => 'The discount could not be created, try again'])->withInput();
    }

    /**
     * Build Merchant Product Filters
     */

    private function buildMerchantSearchQuery($query, $searchTerm){
        $keyword = trim(str_replace('*', '', $searchTerm));

        if(strlen($keyword) > 0){
            $term = trim(preg_replace('/\s+/', ' ', $keyword));
            $status_term = trim(strtolower(str_replace(' ', '_', $term)));
            $query->where(function ($query) use ($term, $status_term){

                $query->orWhere('discounts.rate', 'LIKE', "%{$term}%")
                    ->orwhere('discounts.code', 'LIKE', "%{$term}%");

//                $query->orWhere('discounts.rate', 'LIKE', "%{$term}%")
//                    ->orwhere('discounts.code', 'LIKE', "%{$term}%")
//                    ->orwhere('orders.delivery_phone', 'LIKE', "%{$term}%")
//                    ->orWhereRaw("DATE_FORMAT(delivery_date, '%Y-%m-%d') LIKE '%{$term}%'")
////                    ->orwhere('orders.delivery_date', 'LIKE', "%{$term}%")
//                    ->orwhere('orders.delivery_timeframe', 'LIKE', "%{$term}%")
//                    ->orwhere('orders.status', 'LIKE', "%{$status_term}%")
//                    ->orwhere('countries.name', 'LIKE', "%{$term}%")
//                    ->orwhere('parishes.name', 'LIKE', "%{$term}%")
//                    ->orwhere('merchant_infos.name', 'LIKE', "%{$term}%");
            });
        }
        return $query;
    }
}

