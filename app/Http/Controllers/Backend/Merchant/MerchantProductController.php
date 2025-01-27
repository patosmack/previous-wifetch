<?php

namespace App\Http\Controllers\Backend\Merchant;

use App\Helpers\Helper;
use App\Helpers\ProductHelper;
use App\Http\Controllers\Controller;
use App\Models\Merchant\PrivateCategory;
use App\Models\Merchant\Product;
use App\Models\User\MerchantInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Request as Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class MerchantProductController extends Controller
{

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function shop($id)
    {
        $merchant = MerchantInfo::with(['privateCategories' => function($query){
            $query->enabled()->orderBy('name', 'DESC');
        }])->where('id', '=', $id)->firstOrFail();
        $search = Input::get('search', null);
        $sort = Input::get('sort', null);
        $private_category = Input::get('private_category', null);
        return view('backend.merchant.products', compact('merchant', 'sort', 'private_category', 'search'));

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function shopJson(Request $request, int $id, $private_category_id = null){
        $data = [];

        $totalFiltered = 0;
        $privateCategories = [];

        $merchant = MerchantInfo::where('id', '=', $id)->firstOrFail();
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
                'featured',
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
                    'featured' => [
                        'id' => $item->id,
                        'value' => $item->featured,
                    ],
                    'actions' => route('backend.merchant.product', ['merchant_id' => $item->merchant_info_id, 'product_id' => $item->id]),
                ];
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
        $allowed_targets = ['category', 'price', 'discount', 'enabled', 'featured'];
        $product_id = $request->get('id');
        $target = $request->get('target');
        $value = $request->get('value');
        $errorMessage = null;
        if(in_array($target, $allowed_targets)){
            $product = Product::find($product_id);
            if($product){
                $merchant = MerchantInfo::where('id', '=', $product->merchant_info_id)->first();
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
                    }else if($target === 'featured'){
                        $value = (int)$value;
                        if($value === 0 || $value === 1){
                            $product->featured = $value;
                            if($product->save()){
                                return response()->json(['code' => 200, 'message' => 'Saved']);
                            }else{
                                $errorMessage = 'Status not saved';
                            }
                        }else{
                            $errorMessage = 'Only yes or no';
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
     * Display the specified resource.
     *
     * @param $merchant_friendly_url
     * @param $product_friendly_url
     * @return void
     */
    public function show($merchant_id, $product_id = null){
        $merchant = MerchantInfo::with(['privateCategories' => function($query){
            $query->enabled()->orderBy('name', 'DESC');
        }])->where('id', '=', $merchant_id)->firstOrFail();
        if($merchant){
            $product = null;
            if($product_id){
                $product = Product::with(['privateCategory', 'mutators', 'mutatorGroups'])
                    ->where('merchant_info_id', '=', $merchant->id)
                    ->where('id', '=', $product_id)
                    ->first();
            }
            if(!$product){
                $product = new Product();
            }
            return view('backend.merchant.product', compact('merchant', 'product'));
        }

        abort(404);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeInfo(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'private_category_id' => ['nullable', 'exists:private_categories,id'],
            'price' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string', 'min:0', 'max:50000'],
        ]);
        if($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $merchant_id = $request->get('merchant_id');
        $merchant = MerchantInfo::where('id', '=', $merchant_id)->first();
        if(!$merchant){
            return redirect()->back()->withErrors(['error' => 'Your Shop could not be found'])->withInput();
        }
        $product_id = $request->get('product_id');


        $name = $request->get('name');
        $private_category_id = $request->get('private_category_id');
        $price = (double)$request->get('price',0);
        $discount = (double)$request->get('discount', 0);
        $description = $request->get('description');

        if(!$product_id){
            $productSafeTitlte = $name . '_' . Str::random(3) .  $merchant->id;
            $product = new Product();
            $product->merchant_info_id = $merchant->id;
            $product->friendly_url = ProductHelper::slug($productSafeTitlte);
            $product->enabled = 1;
        }else{
            $product = Product::with(['privateCategory', 'mutators', 'mutatorGroups'])
                ->where('merchant_info_id', '=', $merchant->id)
                ->where('id', '=', $product_id)
                ->first();
            if(!$product){
                return redirect()->back()->withErrors(['error' => 'Your Product could not be found'])->withInput();
            }
        }
        $product->name = $name;
        $product->private_category_id = $private_category_id;
        $product->price = $price;
        $product->discount = $discount;
        $product->description = $description;
        $private_category = '';
        if($product->private_category_id){
            $private_categoryObj = PrivateCategory::find($product->private_category_id);
            if($private_categoryObj){
                $private_category = $private_categoryObj->name;
            }
        }
        $searchable = trim($merchant->name . ' ' . $product->name . ' ' . $product->description . ' ' . $private_category);

        $product->searchable = $searchable;

        if ($request->hasFile('attachment')) {
            $attachment = $request->file('attachment');
            $uploadBasepath =  rtrim('assets/uploads/merchant', '/\\');
            $attachment_file_name = Helper::slug($product->id . '-' . Str::random(55)) . '.' .  strtolower($attachment->getClientOriginalExtension());
            $attachment->move($uploadBasepath, $attachment_file_name);
            $old_image = $product->image;
            $product->image = $uploadBasepath . '/' .$attachment_file_name;
            try{
                $img = Image::make(file_get_contents(public_path($product->image)));
                $img->resize(1000, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $img->save(public_path($product->image), 90);
                if($old_image and File::exists(public_path($old_image))){
                    File::delete(public_path($old_image));
                }
            }catch (\Exception $exception){
                return redirect()->back()->withErrors(['error' => 'There was a problem uploading the product image'])->withInput();
            }
        }
        if ($product->save()) {
            return redirect(route('backend.merchant.product', ['merchant_id' => $product->merchant_info_id, 'product_id' => $product->id]))->with(['success' => 'Your product was saved successfully']);
        }
        return redirect()->back()->withErrors(['error' => 'Your Product could not be saved'])->withInput();
    }

    private function resizeToCanvas($imagePath, $width = 1000, $height = 1000 ){
        try{
            $path = public_path($imagePath);
            $canvas = Image::canvas($width, $height, '#FFFFFF');
            $image = Image::make($path)->resize($width - ((20 * $width / 100)), $height - ((20 * $width / 100)),
                function ($constraint) {
                    $constraint->aspectRatio();
                });
            $canvas->insert($image, 'center');
            $canvas->save($path);
        }catch (\Throwable $exception){

        }
    }
}
