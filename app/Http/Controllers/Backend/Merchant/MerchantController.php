<?php

namespace App\Http\Controllers\Backend\Merchant;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Merchant\Category;
use App\Models\Merchant\PrivateCategory;
use App\Models\Merchant\Product;
use App\Models\User\MerchantInfo;
use Box\Spout\Common\Entity\Style\Color;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Request as Input;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class MerchantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $category = Input::get('category', null);
        $total_products = Product::where('enabled', '=', 1)->count();
        return view('backend.merchant.merchants', compact('category', 'total_products'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($merchant_id)
    {
        $merchant = MerchantInfo::where('id', '=', $merchant_id)->firstOrFail();
        $categories = Category::where('enabled', '=', 1)->get();
        return view('backend.merchant.merchant', compact('merchant', 'categories'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function datatable(Request $request, $category_id = null){

        $data = [];
        $totalData = 0;
        $totalFiltered = 0;

        $columns = [
            'merchant_infos.id',
            'merchant_infos.name',
            'products_by_merchant',
            'merchant_infos.contact_name',
            'merchant_infos.contact_phone',
            'merchant_infos.contact_email',
            'countries.name',
//            'parishes.name',
            'merchant_infos.featured',
            'merchant_infos.enabled',
            'merchant_infos.status',
            'edit_action',
            'item_action',
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

        $itemsQuery = MerchantInfo::select(
            DB::raw('merchant_infos.id as id, merchant_infos.name as name, merchant_infos.contact_name , merchant_infos.contact_phone , merchant_infos.contact_email, countries.name as country_name, parishes.name as parish_name, merchant_infos.featured, merchant_infos.enabled, merchant_infos.status' )
        )->leftjoin('countries', 'countries.id', '=', 'merchant_infos.country_id')
            ->leftjoin('parishes', 'parishes.id', '=', 'merchant_infos.parish_id')
            ->offset($start)
            ->limit($limit)
            ->orderBy($sort_order,$dir);

        $totalDataQuery = MerchantInfo::select(
            DB::raw('merchant_infos.name as name, merchant_infos.contact_name , merchant_infos.contact_phone , merchant_infos.contact_email, countries.name as country_name, parishes.name as parish_name, merchant_infos.featured, merchant_infos.enabled' )
        )->leftjoin('countries', 'countries.id', '=', 'merchant_infos.country_id')
            ->leftjoin('parishes', 'parishes.id', '=', 'merchant_infos.parish_id');

        if($category_id){
            $itemsQuery = $itemsQuery->where('category_id', '=', $category_id);
            $totalDataQuery = $totalDataQuery->where('category_id', '=', $category_id);
        }

        if(!empty($searchQuery)){
            $search = str_replace('-', ' ', $searchQuery);
            $itemsQuery = $this->buildMerchantSearchQuery($itemsQuery, $search);
            $totalDataQuery = $this->buildMerchantSearchQuery($totalDataQuery, $search);
        }

        $items = $itemsQuery->get();
        $totalData = $totalDataQuery->count();

        foreach ($items as $item){

            $productCount = Product::where('enabled', '=', 1)->where('merchant_info_id', '=', $item->id)->count();
            $data[] =  [
                'id' => [
                    'id' => $item->id,
                    'value' => $item->id ?: '',
                ],
                'image' => $item->logo ? asset($item->logo) : asset('assets/common/image_placeholder.png'),
                'name' => [
                    'id' => $item->id,
                    'value' => $item->name ?: '',
                ],
                'product_count' => [
                    'id' => $item->id,
                    'value' => $productCount,
                ],

                'contact_name' => [
                    'id' => $item->id,
                    'value' => $item->contact_name ?: '',
                ],
                'contact_phone' => [
                    'id' => $item->id,
                    'value' => $item->contact_phone ?: '',
                ],
                'contact_email' => [
                    'id' => $item->id,
                    'value' => $item->contact_email ?: '',
                ],
                'country' => [
                    'id' => $item->id,
                    'value' => $item->country_name ?: '',
                ],
                'parish' => [
                    'id' => $item->id,
                    'value' => $item->parish_name ?: '',
                ],
                'featured' => [
                    'id' => $item->id,
                    'value' => $item->featured,
                ],
                'enabled' => [
                    'id' => $item->id,
                    'value' => $item->enabled,
                ],
                'status' => [
                    'id' => $item->id,
                    'value' => ucfirst($item->status),
                ],
                'edit_action' => route('backend.merchant.profile', $item->id),
                'item_action' => route('backend.merchant.products', $item->id),
            ];
        }

        $json_data = array(
            "draw"            => (int)$request->input('draw'),
            "recordsTotal"    => $totalData,
            "recordsFiltered" => $totalData - $totalFiltered,
            "data"            => $data,
            'allowed_status'      => ['Pending','Approved','Cancelled','Rejected'],
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
        $allowed_targets = ['featured', 'enabled', 'status', 'allow_custom_items'];
        $merchant_info_id = $request->get('id');
        $target = $request->get('target');
        $value = $request->get('value');
        $errorMessage = null;
        if(in_array($target, $allowed_targets)){
            $merchant = MerchantInfo::find($merchant_info_id);
            if($merchant){
               if($target === 'featured') {
                    $value = (int)$value;
                    if ($value === 0 || $value === 1) {
                        $merchant->featured = $value;
                        if ($merchant->save()) {
                            return response()->json(['code' => 200, 'message' => 'Saved']);
                        } else {
                            $errorMessage = 'Featured status not saved';
                        }
                    } else {
                        $errorMessage = 'True or false needed';
                    }
                }else if($target === 'enabled'){
                    $value = (int)$value;
                    if($value === 0 || $value === 1){
                        $merchant->enabled = $value;
                        if($merchant->save()){
                            return response()->json(['code' => 200, 'message' => 'Saved']);
                        }else{
                            $errorMessage = 'Enabled status not saved';
                        }
                    }else{
                        $errorMessage = 'Only enabled or disabled';
                    }
               }else if($target === 'allow_custom_items'){
                   $value = (int)$value;
                   if($value === 0 || $value === 1){
                       $merchant->allow_custom_items = $value;
                       if($merchant->save()){
                           return response()->json(['code' => 200, 'message' => 'Saved']);
                       }else{
                           $errorMessage = 'Custom Items value not saved';
                       }
                   }else{
                       $errorMessage = 'Only Yes or No is allowed';
                   }
                }else if($target === 'status'){
                   $value = strtolower((string)$value);

                   $allowed_status = ['pending','approved','cancelled','rejected'];
                   if(in_array($value, $allowed_status)){
                       $merchant->status = $value;
                       if($merchant->save()){
                           return response()->json(['code' => 200, 'message' => 'Saved']);
                       }else{
                           $errorMessage = 'Status not saved';
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

    private function buildMerchantSearchQuery($query, $searchTerm){
        $keyword = trim(str_replace('*', '', $searchTerm));

        if(strlen($keyword) > 0){
            $term = trim(preg_replace('/\s+/', ' ', $keyword));
            $query->where(function ($query) use ($term){
                $query->orWhere('merchant_infos.name', 'LIKE', "%{$term}%")
                    ->orwhere('merchant_infos.contact_name', 'LIKE', "%{$term}%")
                    ->orwhere('merchant_infos.contact_phone', 'LIKE', "%{$term}%")
                    ->orwhere('merchant_infos.contact_email', 'LIKE', "%{$term}%")
                    ->orwhere('countries.name', 'LIKE', "%{$term}%")
                    ->orwhere('parishes.name', 'LIKE', "%{$term}%");
            });
        }
        return $query;
    }

    /**
     * Update
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $merchant_id)
    {
        $merchant = MerchantInfo::where('id', '=', $merchant_id)->first();
        if(!$merchant){
            return redirect()->back()->withErrors(['error' => 'The merchant could not be found'])->withInput();
        }

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'string', 'max:255', 'exists:categories,id'],
            'country_id' => ['required', 'string', 'max:255', 'exists:countries,id'],
            'parish_id' => ['required', 'string', 'max:255', 'exists:parishes,id'],
        ], [
            'country_id.exists' => 'Select a valid Country',
            'parish_id.exists' => 'Select a valid Parish'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if ($request->hasFile('attachment')) {
            $attachment = $request->file('attachment');
            $uploadBasepath =  rtrim('assets/uploads/merchant', '/\\');
            $attachment_file_name = Helper::slug($merchant->id . '-' . Str::random(55)) . '.' .  strtolower($attachment->getClientOriginalExtension());
            $attachment->move($uploadBasepath, $attachment_file_name);
            $old_image = $merchant->logo;
            $merchant->logo = $uploadBasepath . '/' .$attachment_file_name;
            try{
                $img = Image::make(file_get_contents(public_path($merchant->logo)));
                $img->resize(1000, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $img->save(public_path($merchant->logo), 90);
                if($old_image and File::exists(public_path($old_image))){
                    File::delete(public_path($old_image));
                }
                $merchant->save();
            }catch (\Exception $exception){
                return redirect()->back()->withErrors(['error' => 'There was a problem uploading the business logo'])->withInput();
            }
        }

        $data = $request->except('status', 'logo', 'cover_image', 'friendly_url');
        $data['friendly_url'] = Helper::slug(Helper::stripString($data['name'])) . '-' . $merchant->id;
        if($merchant->update($data)){
            return redirect()->back()->with(['success' => 'The merchant information was saved successfully']);
        }
        return redirect()->back()->withErrors(['error' => 'The merchant could not be saved, try again'])->withInput();

    }



    /**
     * Export Merchant List
     */

    public function exportMerchants(){
        $fileName = 'WIFETCH-Merchants';
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
            'User ID',
            'Category',
            'Merchant Name',
            'Friendly URL',
            'Logo',
            'Description',
            'Has Private Categories',
            'Contact Name',
            'Contact Phone',
            'Contact Email',
            'Country',
            'Parish',
            'Public Address',
            'Public Phone',
            'Public Email',
            'Latitude',
            'Longitude',
            'Notification Email',
            'Delivery Fee',
            'Service Fee',
            'Enabled',
            'Featured',
            'Status',
            'Member Since',
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

        $merchants  = MerchantInfo::with(['category', 'privateCategories', 'country', 'parish', 'category', 'category', 'category', 'category'])->get();

        $rowData = [];
        foreach ($merchants as $merchant){
            $flat = [
                $merchant->user_id,
                $merchant->category ? $merchant->category->name : '',
                $merchant->name,
                $merchant->friendly_url,
                asset($merchant->logo),
                $merchant->description,
                ($merchant->privateCategories && $merchant->privateCategories->count() > 0) ? 'Yes' : 'No',
                $merchant->contact_name,
                $merchant->contact_phone,
                $merchant->contact_email,
                $merchant->country ? $merchant->country->name : (($merchant->parish && $merchant->parish->country) ? $merchant->parish->country->name : ''),
                $merchant->parish ? $merchant->parish->name : '',
                $merchant->address,
                $merchant->phone,
                $merchant->email,
                $merchant->lat,
                $merchant->lon,
                $merchant->notification_email,
                $merchant->delivery_fee,
                $merchant->service_fee,
                $merchant->enabled ? 'Yes': 'No',
                $merchant->featured ? 'Yes': 'No',
                $merchant->status ? ucfirst($merchant->status) : '',
                $merchant->created_at ? $merchant->created_at->format('m-d-Y : h:i:s') : '',
            ];

            $rowData[] = WriterEntityFactory::createRowFromArray($flat);
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
