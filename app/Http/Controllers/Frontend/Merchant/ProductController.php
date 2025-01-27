<?php

namespace App\Http\Controllers\Frontend\Merchant;


use App\Helpers\SeoHelper;
use App\Http\Controllers\Controller;
use App\Models\Merchant\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    /**
     * Display the specified resource.
     *
     * @param $merchant_friendly_url
     * @param $product_friendly_url
     * @return void
     */
    public function show($merchant_friendly_url, $product_friendly_url){

        $product = Product::
            with(['merchant', 'mutatorGroups' => function($query){
                $query->available()->orderBy('choice_mode','ASC');
            }, 'mutatorGroups.mutators' => function($query){
                $query->available()->orderBy('extra_price');
            }, 'privateCategory' => function($query){
                $query->enabled()->orderBy('name', 'DESC');
            }])
            ->withCount('mutatorGroups')
            ->available()
            ->where('friendly_url', '=', $product_friendly_url)
            ->firstOrFail();

        $mutators_count = 0;
        if(count($product->mutators) > 0){
            foreach ($product->mutators as $mutator){
                if($mutator->group){
                    $mutators_count ++;
                }
            }
        }
        $product->mutators_count = $mutators_count;


        SeoHelper::setTitle($product->merchant ? $product->merchant->name : '' . ' - ' . $product->name);
        SeoHelper::setDescription( $product->description ?: 'Order ' . $product->name . ' we fetch and deliver!');
        if($product->image){
            SeoHelper::setImage(asset($product->image));
        }
        SeoHelper::addKeywords( array_merge(['Barbados', 'Trinidad', $product->searchable]));
        SeoHelper::setName(env('APP_NAME'));


        return view('frontend.merchant.product', compact('product'));
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
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
