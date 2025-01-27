<?php

namespace App\Http\Controllers\Backend\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Merchant\PrivateCategory;
use App\Models\Merchant\Product;
use App\Models\User\MerchantInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MerchantPrivateCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $merchant = MerchantInfo::with('privateCategories')->where('id', '=', $id)->firstOrFail();
        $merchant->privateCategories->map(function ($privateCategory) {
            $product_count = Product::where('private_category_id', '=', $privateCategory->id)->where('merchant_info_id', '=', $privateCategory->merchant_info_id)->count();
            $privateCategory->product_count = $product_count;
            return $privateCategory;
        });
        return view('backend.merchant.private_categories', compact('merchant'));
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
            'name' => ['required', 'string', 'max:255'],
        ]);
        if ($validator->fails()) {
            return redirect(route('backend.merchant.private_categories'))->withErrors($validator)->withInput();
        }
        $merchant_id = $request->get('merchant_id');
        $merchant = MerchantInfo::where('id', '=', $merchant_id)->first();

        if($merchant){
            $name = $request->get('name');
            $private_category_id = $request->get('private_category_id');

            if(!$private_category_id){
                $privateCategory = PrivateCategory::where('merchant_info_id', '=', $merchant->id)->where('name', '=', $name)->first();
                if(!$privateCategory){
                    $privateCategory = new PrivateCategory();
                    $privateCategory->merchant_info_id = $merchant->id;
                    $privateCategory->name = $name;
                    $privateCategory->enabled = 1;
                    if($privateCategory->save()){
                        return redirect()->back()->with(['success' => 'The category was created successfully'])->withInput();
                    }
                }else{
                    if(!$privateCategory->enabled){
                        $privateCategory->enabled = 1;
                        if($privateCategory->save()){
                            return redirect()->back()->with(['success' => 'The category was created successfully'])->withInput();
                        }
                    }else{
                        return redirect()->back()->with(['error' => 'The category already exists'])->withInput();
                    }


                }
            }else{
                $privateCategory = PrivateCategory::where('merchant_info_id', '=', $merchant->id)->where('id', '=', $private_category_id)->first();
                if($privateCategory){
                    $tmpPrivateCategory = PrivateCategory::where('merchant_info_id', '=', $merchant->id)->where('id', '!=', $private_category_id)->where('name', '=', $name)->first();
                    if(!$tmpPrivateCategory){
                        $privateCategory->merchant_info_id = $merchant->id;
                        $privateCategory->name = $name;
                        $privateCategory->enabled = 1;
                        if($privateCategory->save()){
                            return redirect()->back();
                        }
                    }else{
                        return redirect()->back()->withErrors(['error' => 'A category with the specified name already exists'])->withInput();
                    }
                }
            }
        }
        return redirect()->back()->withErrors(['error' => 'There was a problem generating the business category'])->withInput();

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $privateCategory = PrivateCategory::where('id', '=', $id)->first();

        if($privateCategory){
            $merchant = MerchantInfo::where('id', '=', $privateCategory->merchant_info_id)->first();
            if($merchant){
                if($privateCategory->delete()){
                    return redirect(route('backend.merchant.private_categories', $merchant->id));
                }
            }
        }
        return redirect()->back()->withErrors(['error' => 'There was a problem removing the business category']);

    }


}
