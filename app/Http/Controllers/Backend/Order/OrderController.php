<?php

namespace App\Http\Controllers\Backend\Order;

use App\Helpers\Helper;
use App\Helpers\OrderHelper;
use App\Http\Controllers\Controller;
use App\Models\Merchant\PrivateCategory;
use App\Models\Merchant\Product;
use App\Models\Order\Order;
use App\Models\Order\OrderTransaction;
use App\Models\Order\PaymentMethod;
use App\Models\User\MerchantInfo;
use Box\Spout\Common\Entity\Style\Color;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request as Input;
use Illuminate\Support\Str;


class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $status = Input::get('status', null);
        $allowed_status = OrderHelper::getAvailableStatus();
        return view('backend.order.orders', compact('status', 'allowed_status'));
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
        return view('backend.order.order', compact('status', 'allowed_status', 'merchant', 'order'));
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
            'orders.id',
            'orders.created_at',
            'orders.order_name',
            'orders.order_email',
            'orders.delivery_phone',
            'merchant_infos.name',
            'countries.name',
            'parishes.name',
            'orders.status',
            'orders.transaction_status',
            'orders.delivery_date',
            'orders.delivery_timeframe',
            'orders.rating',
            'actions',
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

        $itemsQuery = Order::select(
            DB::raw('orders.id as id,orders.rating as rating, orders.order_name, orders.order_email, orders.delivery_phone, merchant_infos.name as merchant_name, countries.name as country_name, parishes.name as parish_name, orders.status, orders.transaction_status as transaction_status, orders.delivery_date, orders.delivery_timeframe, orders.created_at' )
        )->leftjoin('merchant_infos', 'merchant_infos.id', '=', 'orders.merchant_id')
            ->leftjoin('countries', 'countries.id', '=', 'orders.delivery_country_id')
            ->leftjoin('parishes', 'parishes.id', '=', 'orders.delivery_parish_id')
            ->offset($start)
            ->limit($limit)
            ->orderBy($sort_order,$dir);

        $totalDataQuery = Order::select(DB::raw('orders.id as id'))->leftjoin('merchant_infos', 'merchant_infos.id', '=', 'orders.merchant_id')
            ->leftjoin('countries', 'countries.id', '=', 'orders.delivery_country_id')
            ->leftjoin('parishes', 'parishes.id', '=', 'orders.delivery_parish_id');

        if($status){
            $itemsQuery = $itemsQuery->where('orders.status', '=', $status);
            $totalDataQuery = $totalDataQuery->where('orders.status', '=', $status);
        }

        if(!empty($searchQuery)){
            $search = str_replace('-', ' ', $searchQuery);
            $itemsQuery = $this->buildMerchantSearchQuery($itemsQuery, $search);
            $totalDataQuery = $this->buildMerchantSearchQuery($totalDataQuery, $search);
        }

        $totalData = $totalDataQuery->count();
        $items = $itemsQuery->get();

        foreach ($items as $item){

            $transaction_status = 'Pending';
            switch ($item->transaction_status){
                case 'approved':
                    $transaction_status = 'Approved';
                    break;
                case 'rejected':
                    $transaction_status = 'Rejected';
                    break;
                case 'refunded':
                    $transaction_status = 'Refunded';
                    break;
                case 'canceled':
                    $transaction_status = 'Cancelled';
                    break;
                case 'pending_transaction_email':
                    $transaction_status = 'Pending';
                    break;
                case 'correction_requested':
                    $transaction_status = 'Correction Requested';
                    break;
                case 'partially_refunded':
                    $transaction_status = 'Partialy Refunded';
                    break;
            }

            $data[] =  [
                'id' => [
                    'id' => $item->id,
                    'value' => $item->id ?: '',
                ],
                'order_name' => [
                    'id' => $item->id,
                    'value' => $item->order_name ?: '',
                ],
                'order_email' => [
                    'id' => $item->id,
                    'value' => $item->order_email ?: '',
                ],
                'delivery_phone' => [
                    'id' => $item->id,
                    'value' => $item->delivery_phone ?: '',
                ],
                'merchant_name' => [
                    'id' => $item->id,
                    'value' => $item->merchant_name,
                ],
                'country' => [
                    'id' => $item->id,
                    'value' => $item->country_name ?: '',
                ],
                'parish' => [
                    'id' => $item->id,
                    'value' => $item->parish_name ?: '',
                ],
                'transaction_status' => [
                    'id' => $item->id,
                    'value' => $transaction_status,
                ],
                'status' => [
                    'id' => $item->id,
                    'value' => $item->status,
                ],
                'delivery_date' => [
                    'id' => $item->id,
                    'value' => $item->delivery_date,
                ],
                'delivery_timeframe' => [
                    'id' => $item->id,
                    'value' => $item->delivery_timeframe,
                ],
                'created_at' => [
                    'id' => $item->id,
                    'value' => $item->created_at->format('Y-m-d h:i'),
                ],
                'rating' => [
                    'id' => $item->id,
                    'value' => $item->rating ?: '-',
                ],

                'actions' => route('backend.order.show', $item->id),
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
        $allowed_targets = ['status'];
        $merchant_info_id = $request->get('id');
        $target = $request->get('target');
        $value = $request->get('value');
        $errorMessage = null;

        if(in_array($target, $allowed_targets)){
            $order = Order::find($merchant_info_id);
            if($order){
                if($target === 'status'){
                    $value = strtolower((string)$value);

                    $allowed_status = OrderHelper::getAvailableStatus();
                    if(in_array($value, $allowed_status)){
                        try{
                            OrderHelper::valdateStatus($order, $value);
                            OrderHelper::setStatus($order, $value);
                            $order->status = $value;
                            if($order->save()){
                                return response()->json(['code' => 200, 'message' => 'Saved']);
                            }else{
                                $errorMessage = 'Status not saved';
                            }
                        }catch (\Exception $exception){
                            $errorMessage = $exception->getMessage();
                        }
                    }else{
                        $errorMessage = 'Only allowed values are permitted';
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
     * Update
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $order_id)
    {
        $order = Order::where('id', '=', $order_id)->first();
        if(!$order){
            return redirect()->back()->withErrors(['error' => 'The order could not be found'])->withInput();
        }
        $success = false;
        $target = $request->get('target');
        if($target === 'save_order_total'){
            $transaction_total = (double)$request->get('transaction_total');
            $transaction_shipping = (double)$request->get('transaction_shipping');
            $transaction_handling_cost = (double)$request->get('transaction_handling_cost');

            if(($transaction_total + $transaction_shipping + $transaction_handling_cost) > 0){
                $order = OrderHelper::setStatusWaitingForPayment($order);
                $order->transaction_total = $transaction_total + $transaction_shipping + $transaction_handling_cost;
                $order->transaction_shipping = $transaction_shipping;
                $order->transaction_handling_cost = $transaction_handling_cost;
                $success = $order->save();
            }else{
                return redirect()->back()->withErrors(['error' => 'The transaction total + shipping + handling could not be less or equal to 0'])->withInput();
            }
        }
        if($success){
            return redirect(route('backend.order.show', $order->id))->with(['success' => 'The order was saved successfully']);
        }
        return redirect()->back()->withErrors(['error' => 'The task could not be processed, try again'])->withInput();



//        $allowed_targets = ['status'];
//        $merchant_info_id = $request->get('id');
//        $target = $request->get('target');
//        $value = $request->get('value');
//        $errorMessage = null;
//
//        if(in_array($target, $allowed_targets)){
//            $order = Order::find($merchant_info_id);
//            if($order){
//                if($target === 'status'){
//                    $value = strtolower((string)$value);
//
//                    $allowed_status = OrderHelper::getAvailableStatus();
//                    if(in_array($value, $allowed_status)){
//                        try{
//                            OrderHelper::valdateStatus($order, $value);
//                            $order->status = $value;
//                            if($order->save()){
//                                return response()->json(['code' => 200, 'message' => 'Saved']);
//                            }else{
//                                $errorMessage = 'Status not saved';
//                            }
//                        }catch (\Exception $exception){
//                            $errorMessage = $exception->getMessage();
//                        }
//                    }else{
//                        $errorMessage = 'Only allowed values are permitted';
//                    }
//                }else{
//                    $errorMessage = 'Invalid Target';
//                }
//            }else{
//                $errorMessage = 'Merchant not found';
//            }
//        }else{
//            $errorMessage = 'Target not allowed';
//        }
//
//        return response()->json([
//            'code'      =>  404,
//            'message'   =>  $errorMessage ?: 'Unhandled Error'
//        ], 404);

    }

    /**
     * Update
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function requestMoreMoney(Request $request, $order_id)
    {
        $order = Order::where('id', '=', $order_id)->first();
        if(!$order){
            return redirect()->back()->withErrors(['error' => 'The order could not be found'])->withInput();
        }
        $orderTransaction = new OrderTransaction();
        $transaction_total = (double)$request->get('transaction_total');
        $transaction_description = $request->get('transaction_description');

        if(!$transaction_description){
            return redirect()->back()->withErrors(['error' => 'The amount to request description is required '])->withInput();
        }

        $success = false;
        if($transaction_total > 0 ){
            $transaction_id = strtoupper(Str::random(30));
            //$orderTransaction = OrderHelper::setStatusWaitingForPayment($order);
            $orderTransaction->order_id = $order->id;
            $orderTransaction->transaction_id = $transaction_id;
            $orderTransaction->transaction_total = $transaction_total;
            $orderTransaction->transaction_shipping = 0;
            $orderTransaction->transaction_handling_cost = 0;
            $orderTransaction->transaction_description = $transaction_description;
            $orderTransaction->transaction_status = 'pending';
            if($orderTransaction->save()){
                OrderHelper::statusOrderTransactionNotification($orderTransaction);
                $success = true;
            }
        }else{
            return redirect()->back()->withErrors(['error' => 'The amount to request could not be less or equal to 0'])->withInput();
        }
        if($success){

            return redirect(route('backend.order.show', $order->id))->with(['success' => 'The new money request was created successfully']);
        }
        return redirect()->back()->withErrors(['error' => 'The task could not be processed, try again'])->withInput();

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
                $query->orWhere('orders.id', 'LIKE', "%{$term}%")

                    ->orwhere('orders.order_name', 'LIKE', "%{$term}%")
                    ->orwhere('orders.order_email', 'LIKE', "%{$term}%")
                    ->orwhere('orders.delivery_address', 'LIKE', "%{$term}%")
                    ->orwhere('orders.delivery_phone', 'LIKE', "%{$term}%")
                    ->orWhereRaw("DATE_FORMAT(delivery_date, '%Y-%m-%d') LIKE '%{$term}%'")
//                    ->orwhere('orders.delivery_date', 'LIKE', "%{$term}%")
                    ->orwhere('orders.delivery_timeframe', 'LIKE', "%{$term}%")
                    ->orwhere('orders.status', 'LIKE', "%{$status_term}%")
                    ->orwhere('countries.name', 'LIKE', "%{$term}%")
                    ->orwhere('parishes.name', 'LIKE', "%{$term}%")
                    ->orwhere('merchant_infos.name', 'LIKE', "%{$term}%");
            });
        }
        return $query;
    }

    /**
     * Export Order List
     */

    public function exportOrders(){
        $fileName = 'WIFETCH-Orders';
        $fileName = ucwords(Helper::slug($fileName)) . '.xlsx';
        $writer = WriterEntityFactory::createXLSXWriter();
        $writer->openToBrowser($fileName);
//        if(!File::isDirectory(storage_path('file_downloader'))){
//            File::makeDirectory(storage_path('file_downloader'), 0755, true, true);
//        }

        // $writer->openToFile(storage_path('file_downloader/' . $fileName));

        $sheet = $writer->getCurrentSheet();
        $sheet->setName('Merchants');

        $headers = [
            'Order ID',
            'User ID',
            'Merchant Name',
            'Customer Name',
            'Customer Last Name',
            'Customer Email',
            'Customer Home Phone',
            'Customer Mobile Phone',
            'Order Commentary',
            'Order Status',
            'Delivery Country',
            'Delivery Parish',
            'Delivery Address',
            'Delivery Phone',
            'Delivery Instructions',
            'Delivery Date',
            'Delivery TimeFrame',
            'Transaction Total',
            'Transaction Shipping Cost',
            'Transaction Ecommerce Fee',
            'Transaction Status',
            'Transaction Payment ID',
            'Created At',
        ];

        $itemHeaders = [
            '',
            '',
            'Product Name',
            'Product Price',
            'Product Quantity',
            'Product Extras',
        ];

        $headerStyle = (new StyleBuilder())
            ->setFontBold()
            ->setFontSize(12)
            ->setFontColor(Color::rgb(0, 0, 0))
            ->setShouldWrapText(true)
            ->setBackgroundColor(Color::rgb(243, 228, 78))
            ->build();

        $rowFromValues = WriterEntityFactory::createRowFromArray($headers, $headerStyle);
        $writer->addRow($rowFromValues);

        $orders  = Order::with(['items', 'merchant'])->get();

        $rowData = [];
        foreach ($orders as $order){
            $flat = [
                $order->id,
                $order->user_id,
                $order->merchant ? $order->merchant->name : '',
                $order->order_name ?: '',
                $order->order_last_name ?: '',
                $order->order_email ?: '',
                $order->order_home_phone ?: '',
                $order->order_mobile_phone ?: '',
                $order->order_comment ?: '',
                ucfirst(str_replace('_', ' ', $order->status)),
                $order->delivery_country,
                $order->delivery_parish,
                $order->delivery_address,
                $order->delivery_phone,
                $order->delivery_instructions,
                $order->delivery_date,
                $order->delivery_timeframe,
                $order->transaction_total,
                $order->transaction_shipping,
                $order->transaction_handling_cost,
                ucfirst(str_replace('_', ' ', $order->transaction_status)),
                $order->transaction_extra,
                $order->created_at ? $order->created_at->format('Y-m-d h:i') : '',
            ];

            $rowData[] = WriterEntityFactory::createRowFromArray($flat);

            $rowData[] = WriterEntityFactory::createRowFromArray([]);

            $rowData[] = WriterEntityFactory::createRowFromArray($itemHeaders, $headerStyle);

            foreach ($order->items as $item){

                $itemHeaders = [
                    '',
                    '',
                    'Product Name',
                    'Product Price',
                    'Product Quantity',
                    'Product Extras',
                ];



                $extras = '';
                foreach($item->mutators as $orderItemMutator){
                    if($extras === ''){
                        $extras = $orderItemMutator->name;
                    }else{
                        $extras .= ' / ' . $orderItemMutator->name;
                    }

                }

                $itemRowData = [
                    '',
                    '',
                    '',
                    $item->name,
                    $item->price,
                    $item->quantity,
                    $extras,
                ];
                $rowData[] = WriterEntityFactory::createRowFromArray($itemRowData);
            }
            $rowData[] = WriterEntityFactory::createRowFromArray([]);
        }

        if(count($rowData) > 0){
            $writer->addRows($rowData);
        }
        $writer->close();

//        $file = File::get(storage_path('file_downloader/' . $fileName));
//        $response = Response::make($file, 200);
//        $response->header('Content-Type', 'application/xlsx');
//        return $response;
    }

}

