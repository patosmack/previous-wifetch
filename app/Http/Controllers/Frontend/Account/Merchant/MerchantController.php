<?php

namespace App\Http\Controllers\Frontend\Account\Merchant;


use App\Helpers\MerchantHelper;
use App\Http\Controllers\Controller;
use App\Models\Merchant\AvailableHour;
use App\Models\Merchant\PrivateCategory;
use App\Models\Merchant\Product;
use App\Models\User\MerchantInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request as Input;

class MerchantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        if($user->is_merchant){
            $shops = MerchantInfo::available()->where('user_id', '=', $user->id)->get();

            return view('frontend.account.merchant.shops', compact('shops'));
        }
        abort(404);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function shop($id)
    {
        $user = Auth::user();
        if($user->is_merchant){

            $search = Input::get('search', null);
            $sort = Input::get('sort', null);
            $private_category = Input::get('private_category', null);
            $merchant = MerchantInfo::with(['privateCategories' => function($query){
                $query->enabled()->orderBy('name', 'DESC');
            }])->available()->where('user_id', '=', $user->id)->where('id', '=', $id)->firstOrFail();
            return view('frontend.account.merchant.shop', compact('merchant', 'sort', 'private_category', 'search'));

        }
        abort(404);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function shopJson(Request $request, int $id, $private_category_id = null){
        $user = Auth::user();
        $data = [];
        $totalData = 0;
        $totalFiltered = 0;
        $privateCategories = [];

        if($user->is_merchant){
            $merchant = MerchantInfo::available()->where('user_id', '=', $user->id)->where('id', '=', $id)->firstOrFail();
            $privateCategoriesData = PrivateCategory::where('merchant_info_id', '=', $merchant->id)->enabled()->get()->pluck('name', 'id')->toArray();
            foreach ($privateCategoriesData as $privateCategoryId => $privateCategoryValue){
                $privateCategories[] = [
                    'id' => $privateCategoryId,
                    'name' => $privateCategoryValue,
                ];
            }
            if($merchant){
                $columns = [
                    'id',
                    'private_category_id',
                    'name',
                    'price',
                    'sell_price',
                    'discount',
                    'enabled',
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

                $totalDataQuery = Product::where('merchant_info_id', '=', $merchant->id);
                $itemsQuery = Product::with('privateCategory')
                    ->where('merchant_info_id', '=', $merchant->id)
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($sort_order,$dir);

                if($private_category_id){
                    if((int)$private_category_id === -1){
                        $itemsQuery = $itemsQuery->whereNull('private_category_id');
                        $totalDataQuery = $totalDataQuery->whereNull('private_category_id');
                    }else{
                        $itemsQuery = $itemsQuery->where('private_category_id', '=', $private_category_id);
                        $totalDataQuery = $totalDataQuery->where('private_category_id', '=', $private_category_id);
                    }
                }


                $searchQuery = $request->input('search.value');
                if(!empty($searchQuery)){
                    $search = str_replace('-', ' ', $searchQuery);
                    $itemsQuery = $this->buildMerchantProductsQuery($itemsQuery, $search, $sort_order);
                    $totalDataQuery = $this->buildMerchantProductsQuery($totalDataQuery, $search, $sort_order);
                }

                $totalData = $totalDataQuery->count();
                $items = $itemsQuery->get();

                foreach ($items as $item){
                    $data[] =  [
                        'image' => $item->image ? asset($item->image) : asset('assets/common/image_placeholder.png'),
                        'private_category' => [
                            'id' => $item->id,
                            'value' => $item->privateCategory ? $item->privateCategory->id : '',
                        ],
                        'name' => [
                            'id' => $item->id,
                            'value' => $item->name,
                        ],
                        'price' => [
                            'id' => $item->id,
                            'value' => $item->price,
                        ],
                        'sell_price' => [
                            'id' => $item->id,
                            'value' => $item->formattedSellPrice,
                        ],
                        'discount' => [
                            'id' => $item->id,
                            'value' => $item->discount,
                        ],
                        'enabled' => [
                            'id' => $item->id,
                            'value' => $item->enabled,
                        ],
                        'actions' => route('account.merchant.product', ['merchant_id' => $item->merchant_info_id, 'product_id' => $item->id]),
                    ];
                }
            }
        }
        $json_data = array(
            "draw"            => (int)$request->input('draw'),
            "recordsTotal"    => $totalData,
            "recordsFiltered" => $totalData - $totalFiltered,
            "data"            => $data,
            'categories'      => $privateCategories,
        );
        return response()->json($json_data);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function shopEasyEdit(Request $request)
    {
        $user = Auth::user();
        $allowed_targets = ['category', 'price', 'discount', 'enabled'];
        $product_id = $request->get('id');
        $target = $request->get('target');
        $value = $request->get('value');
        $errorMessage = null;
        if(in_array($target, $allowed_targets)){
            $product = Product::find($product_id);
            if($product){
                $merchant = MerchantInfo::available()->where('user_id', '=', $user->id)->where('id', '=', $product->merchant_info_id)->first();
                if($merchant){
                    if($target === 'price'){
                        $value = (double)$value;
                        if($value >= 0){
                            $product->price = $value;
                            if($product->save()){
                                return response()->json(['code' => 200, 'message' => 'Saved']);
                            }else{
                                $errorMessage = 'Price not saved';
                            }
                        }else{
                            $errorMessage = 'Price below 0';
                        }
                    }else if($target === 'discount') {
                        $value = (double)$value;
                        if ($value >= 0 && $value <= 100) {
                            $product->discount = $value;
                            if ($product->save()) {
                                return response()->json(['code' => 200, 'message' => 'Saved']);
                            } else {
                                $errorMessage = 'Discount not saved';
                            }
                        } else {
                            $errorMessage = 'Between 0 ~ 100';
                        }
                    }else if($target === 'enabled'){
                        $value = (int)$value;
                        if($value === 0 || $value === 1){
                            $product->enabled = $value;
                            if($product->save()){
                                return response()->json(['code' => 200, 'message' => 'Saved']);
                            }else{
                                $errorMessage = 'Status not saved';
                            }
                        }else{
                            $errorMessage = 'Only enabled or disabled';
                        }
                    }else if($target === 'category'){
                        if($value){
                            $privateCategory = PrivateCategory::where('merchant_info_id', '=', $merchant->id)->where('id', '=', $value)->enabled()->first();
                            if($privateCategory){
                                $product->private_category_id = $privateCategory->id;
                                if($product->save()){
                                    return response()->json(['code' => 200, 'message' => 'Saved']);
                                }else{
                                    $errorMessage = 'Category not saved';
                                }
                            }else{
                                $errorMessage = 'Category not found';
                            }
                        }else{
                            $product->private_category_id = null;
                            if($product->save()){
                                return response()->json(['code' => 200, 'message' => 'Saved']);
                            }else{
                                $errorMessage = 'Category not saved';
                            }
                        }
                    }else{
                        $errorMessage = 'Invalid Target';
                    }
                }else{
                    $errorMessage = 'Merchant not found';
                }
            }else{
                $errorMessage = 'Product not found';
            }
        }else{
            $errorMessage = 'Target not allowed';
        }

        return response()->json([
            'code'      =>  404,
            'message'   =>  $errorMessage ?: 'Unhandled Error'
        ], 404);




//
//        $availableHoursCollection = AvailableHour::where('merchant_info_id', '=', $merchant->id)->orderBy('day')->orderBy('open_time', 'ASC')->orderBy('close_time', 'ASC')->get();
//        $availableHours = MerchantHelper::buildAvailableHours($availableHoursCollection);
//
//        $merchantProductsQuery = Product::with(['merchant', 'privateCategory' => function($query){
//            $query->enabled()->orderBy('name', 'DESC');
//        }])->withCount('mutators')->where('merchant_info_id', '=', $merchant->id);

        return response()->json($user);
    }

    /**
     * Build Merchant Product Filters
     */

    private function buildMerchantProductsQuery($query, $searchTerm, $sort){
        $keyword = trim(str_replace('*', '', $searchTerm));

        if(strlen($keyword) > 0){
            $term = trim(preg_replace('/\s+/', ' ', $keyword));
            $regex = '+.*?';
            $term = str_replace(' ', $regex, $term);
            $raw_query = "`searchable` RLIKE '{$term}'";
            $query->where(function ($query) use ($term, $raw_query){
                $query->where('name', 'LIKE', "%{$term}%")->orWhereRaw($raw_query);
            });
        }

        if($sort){
            switch ($sort){
                case '-price':
                default:
                    $query->orderBy('price', 'ASC');
                    break;
                case 'price':
                    $query->orderBy('price', 'DESC');
                    break;
                case 'name':
                    $query->orderBy('name', 'ASC');
                    break;
                case '-discount':
                    $query->orderBy('discount', 'ASC');
                    break;
                case 'discount':
                    $query->orderBy('discount', 'DESC');
                    break;
            }
        }

        return $query;
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
