<?php

namespace App\Http\Controllers\Frontend\Merchant;


use App\Helpers\MerchantHelper;
use App\Helpers\SeoHelper;
use App\Http\Controllers\Controller;
use App\Models\Merchant\AvailableHour;
use App\Models\Merchant\Category;
use App\Models\Merchant\Product;
use App\Models\User\MerchantInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as Input;

class MerchantController extends Controller
{



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function byCategory($friendly_url){

        $category = Category::where('friendly_url', '=', $friendly_url)->firstOrFail();
        $merchants = MerchantInfo::available()->fromCountry()->where('category_id', '=', $category->id)->orderBy('products_count', 'DESC')->get();
        $merchants->map(function ($item) {
            $availableHoursCollection = AvailableHour::where('merchant_info_id', '=', $item->id)->orderBy('day')->orderBy('open_time', 'ASC')->orderBy('close_time', 'ASC')->get();
            $availableHours = MerchantHelper::buildAvailableHours($availableHoursCollection);
            $item->is_open = false;
            $item->available_hours_count = count($availableHoursCollection);
            if($availableHours && count($availableHours) > 0 && array_key_exists('is_open', $availableHours) && $availableHours['is_open']){
                $item->is_open = true;
            }
        });

        $merchantsKeywords = $merchants->pluck('name')->toArray();
        SeoHelper::setTitle($category->name);
        SeoHelper::setDescription( 'Order what you need from ' . $category->name . ' we fetch and deliver!');
        if($category->icon){
            SeoHelper::setImage(asset($category->icon));
        }
        SeoHelper::addKeywords( array_merge(['Barbados', 'Trinidad'], $merchantsKeywords));
        SeoHelper::setName(env('APP_NAME'));

        return view('frontend.merchant.by_category', compact('category', 'merchants'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function search(){

        $headerSearch = Input::get('search', null);
        $sort = Input::get('sort', null);

        $availableMerchantIds = MerchantInfo::select('id')->available()->fromCountry()->get()->pluck('id')->toArray();

        $merchantProductsQuery = Product::with(['mutators', 'mutators.group', 'merchant' => function($query){
            $query->available()->fromCountry();
        }])->available()->whereIn('merchant_info_id', $availableMerchantIds);

        $merchantProductsQuery = $this->buildMerchantProductsQuery($merchantProductsQuery, $headerSearch, $sort);
        $merchantProducts = $merchantProductsQuery->paginate(12);

        foreach ($merchantProducts as $merchantProduct){
            $mutators_count = 0;
            if(count($merchantProduct->mutators) > 0){
                foreach ($merchantProduct->mutators as $mutator){
                    if($mutator->group){
                        $mutators_count ++;
                    }
                }
            }
            $merchantProduct->mutators_count = $mutators_count;
        }

        $merchantsIdQuery = Product::select('products.merchant_info_id')->whereIn('merchant_info_id', $availableMerchantIds);
        $merchantsIdQuery = $this->buildMerchantProductsQuery($merchantsIdQuery, $headerSearch, $sort);
        $merchantsIds = $merchantsIdQuery->distinct('products.merchant_info_id')->get()->pluck('merchant_info_id')->toArray();

        $searchedMerchantIds = MerchantInfo::where('name', 'LIKE', '%'.$headerSearch.'%')->available()->fromCountry()->get()->pluck('id')->toArray();

        $merchantsIds = array_merge($merchantsIds, $searchedMerchantIds);
        $merchants = MerchantInfo::whereIn('id', $merchantsIds)->available()->fromCountry()->orderBy('name')->get();
        return view('frontend.merchant.search_result', compact( 'merchantProducts', 'sort', 'headerSearch', 'merchants'));

    }

    /**
     * Display the specified resource.
     *
     * @param  string  $friendly_url
     * @return \Illuminate\Http\Response
     */
    public function show($friendly_url)
    {
        $search = Input::get('search', null);
        $sort = Input::get('sort', null);
        $private_category = Input::get('private_category', null);

        $merchant = MerchantInfo::with(['privateCategories' => function($query){
            $query->enabled()->orderBy('name', 'DESC');
        }])->available()->fromCountry()->where('friendly_url', '=', $friendly_url)->firstOrFail();

        $availableHoursCollection = AvailableHour::where('merchant_info_id', '=', $merchant->id)->orderBy('day')->orderBy('open_time', 'ASC')->orderBy('close_time', 'ASC')->get();
        $availableHours = MerchantHelper::buildAvailableHours($availableHoursCollection);


        $merchantProductsQuery = Product::with([ 'mutators', 'mutators.group', 'merchant', 'privateCategory' => function($query){
            $query->enabled()->orderBy('name', 'DESC');
        }])->available()->where('merchant_info_id', '=', $merchant->id);

        if($private_category){
            $merchantProductsQuery->where('private_category_id', '=', $private_category);
        }
        $merchantProductsQuery = $this->buildMerchantProductsQuery($merchantProductsQuery, $search, $sort);
        $merchantProducts = $merchantProductsQuery->paginate(12);

        $keywords = [];
        foreach ($merchantProducts as $merchantProduct){
            $keywords[] = $merchantProduct->name;
            $mutators_count = 0;
            if(count($merchantProduct->mutators) > 0){
                foreach ($merchantProduct->mutators as $mutator){
                    if($mutator->group){
                        $mutators_count ++;
                    }
                }
            }
            $merchantProduct->mutators_count = $mutators_count;
        }


        SeoHelper::setTitle($merchant->name);
        SeoHelper::setDescription( 'Order from ' . $merchant->name . ' we fetch and deliver!');
        if($merchant->logo){
            SeoHelper::setImage(asset($merchant->logo));
        }
        SeoHelper::addKeywords( array_merge(['Barbados', 'Trinidad'], $keywords));
        SeoHelper::setName(env('APP_NAME'));

        return view('frontend.merchant.products', compact('merchant', 'merchantProducts', 'sort', 'private_category', 'search', 'availableHours', 'availableHoursCollection'));

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
     * Filters
     */

    private function filters(){
        $availableFilters = [
            'current' => [
                '-price' => 'Price - Low to High',
            ],
            'available' => [
                '-price' => 'Price - Low to High',
                'price' => 'Price - High to Low',
                'name' => 'Alphabetical',
                '-discount' => '% Off - High to Low',
                'discount' => '% Off - High to Low',
            ]
        ];

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
