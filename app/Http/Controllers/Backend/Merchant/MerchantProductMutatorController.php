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

class MerchantProductMutatorController extends Controller
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
            'extra_price' => ['nullable', 'numeric', 'min:0'],
            'merchant_id' => ['required', 'numeric', 'min:1', 'exists:merchant_infos,id'],
            'product_id' => ['required', 'numeric', 'min:1', 'exists:products,id'],
            'product_mutator_group_id' => ['required', 'numeric', 'min:1', 'exists:product_mutator_groups,id'],

        ], [
            'merchant_id.exists' => 'Select a valid Merchant',
            'product_id.exists' => 'Select a valid Product',
            'product_mutator_group_id.exists' => 'Select a valid Variation',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $merchant_id = $request->get('merchant_id');
        $product_id = $request->get('product_id');
        $name = $request->get('name');
        $product_mutator_group_id = $request->get('product_mutator_group_id');
        $product_mutator_group_item_id = $request->get('product_mutator_group_item_id');
        $extra_price = (double)$request->get('extra_price', 0);
        $max_quantity = (int)$request->get('max_quantity', 0);

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
                }
                $productMutator = ProductMutator::where('product_mutator_group_id', '=', $productMutatorGroup->id)->where('id', '=', $product_mutator_group_item_id)->first();
                if(!$productMutator){
                    $productMutator = new ProductMutator();
                    $productMutator->product_id = $product->id;
                    $productMutator->product_mutator_group_id = $product_mutator_group_id;
                }
                $productMutator->name = $name;
                $productMutator->extra_price = $extra_price;
                $productMutator->max_quantity = $max_quantity;
                $productMutator->enabled = 1;
                if($productMutator->save()){
                    return redirect()->back()->with(['success' => 'The product variation item was created successfully']);
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
        $product_mutator_group_item_id = $request->get('product_mutator_group_item_id');

        $merchant = MerchantInfo::with(['privateCategories' => function($query){
            $query->enabled()->orderBy('name', 'DESC');
        }])->where('id', '=', $merchant_id)->firstOrFail();
        if($merchant){
            $product = Product::where('merchant_info_id', '=', $merchant->id)->where('id', '=', $product_id)->first();
            if(!$product){
                return redirect()->back()->withErrors(['error' => 'Your Product could not be found'])->withInput();
            }else{
                $productMutator = ProductMutator::where('id', '=', $product_mutator_group_item_id)->first();

                if($productMutator){
                    $productMutatorGroup = ProductMutatorGroup::where('id', '=', $productMutator->product_mutator_group_id)->where('product_id', '=', $product->id)->first();
                    if(!$productMutatorGroup){
                        return redirect()->back()->withErrors(['error' => 'Your Product Variation could not be found' .  $productMutatorGroup])->withInput();
                    }
                    if($productMutator->delete()){
                        return redirect()->back()->with(['success' => 'The product variation item was removed successfully']);
                    }
                }
            }
        }

        return redirect()->back()->withErrors(['error' => 'Your Product Variation Item could not be found'])->withInput();
    }
}


