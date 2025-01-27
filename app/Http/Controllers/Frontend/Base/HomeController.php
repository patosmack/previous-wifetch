<?php

namespace App\Http\Controllers\Frontend\Base;


use App\Helpers\CountryHelper;
use App\Helpers\SeoHelper;
use App\Http\Controllers\Controller;
use App\Models\Location\Country;
use App\Models\Merchant\Category;
use App\Models\Merchant\Product;
use App\Models\System\Banner;
use App\Models\User\MerchantInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $total = 10;
        $featuredMerchants = MerchantInfo::available()->fromCountry()->orderBy('products_count', 'DESC')->featured()->get();
        if(count($featuredMerchants) === 0){
            $featuredMerchants = MerchantInfo::available()->fromCountry()->orderBy('products_count', 'DESC')->take($total)->get();
        }


        $featuredProducts = Product::with(['merchant' => function($query){
            $query->available()->fromCountry();
        }])->available()->featured()->get();
        if(count($featuredProducts) === 0){
            $featuredProducts = Product::with(['merchant' => function($query){
                $query->available()->fromCountry();
            }])->available()->whereNotNull('image')->where('discount', '>', 0)->orderBy('discount', 'DESC')->take($total)->get();

            if(count($featuredProducts) === 0){
                $featuredProducts = Product::with(['merchant' => function($query){
                    $query->available()->fromCountry();
                }])->available()->whereNotNull('image')->inRandomOrder()->take($total)->get();
            }
        }

        $banners = Banner::enabled()->get();

        SeoHelper::setTitle('Whatever you need, WiDeliver');
        SeoHelper::setDescription( 'Whatever you need, Wi Fetch and  Wi Deliver, from restaurants, groceries and more');
        SeoHelper::addKeywords(['Barbados', 'Trinidad', 'grocery', 'beauty', 'drinks', 'eat', 'food', 'farm', 'launch', 'dinner']);
        SeoHelper::setName(env('APP_NAME'));
        return view('frontend.home.home', compact('featuredMerchants', 'featuredProducts', 'banners'));
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
