<?php

namespace App\Http\Controllers\Frontend\Account\Merchant;

use App\Helpers\Helper;
use App\Helpers\ProductHelper;
use App\Http\Controllers\Controller;
use App\Models\Merchant\PrivateCategory;
use App\Models\Merchant\Product;
use App\Models\User\MerchantInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class ProductController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param $merchant_friendly_url
     * @param $product_friendly_url
     * @return void
     */
    public function index($merchant_id, $product_id = null){

        $user = Auth::user();
        if($user->is_merchant){
            $merchant = MerchantInfo::with(['privateCategories' => function($query){
                $query->enabled()->orderBy('name', 'DESC');
            }])->available()->where('user_id', '=', $user->id)->where('id', '=', $merchant_id)->firstOrFail();
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
                return view('frontend.account.merchant.product', compact('merchant', 'product'));
            }
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
        if($user->is_merchant) {
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
            $merchant = MerchantInfo::available()->where('user_id', '=', $user->id)->where('id', '=', $merchant_id)->first();
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
                    ->available()->first();
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
                return redirect(route('account.merchant.product', ['merchant_id' => $product->merchant_info_id, 'product_id' => $product->id]))->with(['success' => 'Your product was saved successfully']);
            }
            return redirect()->back()->withErrors(['error' => 'Your Product could not be saved'])->withInput();
        }
        return redirect()->back()->withErrors(['error' => 'Your account doesn\'t have permissions to access this area'])->withInput();
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
