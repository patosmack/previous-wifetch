<?php

namespace App\Http\Controllers\Backend\Merchant;


use App\Http\Controllers\Controller;
use App\Models\Merchant\Product;
use App\Models\Merchant\ProductMutator;
use App\Models\Merchant\ProductMutatorGroup;
use App\Models\User\MerchantInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MerchantProductMutatorGroupController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string'],
            'choice_mode' => ['required', 'string', 'in:single,multiple'],
            'merchant_id' => ['required', 'numeric', 'min:1', 'exists:merchant_infos,id'],
            'product_id' => ['required', 'numeric', 'min:1', 'exists:products,id'],
        ], [
            'merchant_id.exists' => 'Select a valid Merchant',
            'product_id.exists' => 'Select a valid Product',
            'choice_mode.in' => 'Select a valid Mode'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $merchant_id = $request->get('merchant_id');
        $product_id = $request->get('product_id');
        $choice_mode = $request->get('choice_mode');
        $name = $request->get('name');
        $allow_quantity_selector = $request->get('allow_quantity_selector') ? 1 : 0;


        $merchant = MerchantInfo::with(['privateCategories' => function($query){
            $query->enabled()->orderBy('name', 'DESC');
        }])->where('id', '=', $merchant_id)->firstOrFail();
        if($merchant){
            $product = Product::where('merchant_info_id', '=', $merchant->id)->where('id', '=', $product_id)->first();
            if(!$product){
                return redirect()->back()->withErrors(['error' => 'Your Product could not be found'])->withInput();
            }else{
                $productMutatorGroup = new ProductMutatorGroup();
                $productMutatorGroup->name = $name;
                $productMutatorGroup->product_id = $product->id;
                $productMutatorGroup->choice_mode = $choice_mode;
                if($choice_mode === 'multiple'){
                    $productMutatorGroup->allow_quantity_selector = $allow_quantity_selector;
                }
                $productMutatorGroup->enabled = 1;
                if($productMutatorGroup->save()){
                    return redirect()->back()->with(['success' => 'The product variation was created successfully']);
                }
            }
        }

        return redirect()->back()->withErrors(['error' => 'Your Product could not be found'])->withInput();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $merchant_id = $request->get('merchant_id');
        $product_id = $request->get('product_id');
        $product_mutator_group_id = $request->get('product_mutator_group_id');

        $merchant = MerchantInfo::with(['privateCategories' => function($query){
            $query->enabled()->orderBy('name', 'DESC');
        }])->where('id', '=', $merchant_id)->firstOrFail();
        if($merchant){
            $product = Product::where('merchant_info_id', '=', $merchant->id)->where('id', '=', $product_id)->first();
            if(!$product){
                return redirect()->back()->withErrors(['error' => 'Your Product could not be found'])->withInput();
            }else{
                $productMutatorGroup = ProductMutatorGroup::where('id', '=', (int)$product_mutator_group_id)->where('product_id', '=', $product_id)->first();
                if(!$productMutatorGroup){
                    return redirect()->back()->withErrors(['error' => 'Your Product Variation could not be found' .  $productMutatorGroup])->withInput();
                }else{
                    if($productMutatorGroup->delete()){
                        return redirect()->back()->with(['success' => 'The product variation was removed successfully']);
                    }
                }

            }
        }

        return redirect()->back()->withErrors(['error' => 'Your Product Variation could not be found'])->withInput();
    }
}


