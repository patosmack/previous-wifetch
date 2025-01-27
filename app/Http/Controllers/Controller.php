<?php

namespace App\Http\Controllers;

use App\Helpers\CartHelper;
use App\Helpers\CountryHelper;
use App\Models\Location\Country;
use App\Models\Merchant\Category;
use App\Models\User\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            self::shared();
            return $next($request);
        });
    }

    public function shared(){

        $countries = Cache::remember('frontend_countries', 86400, function () {
            return Country::with(
                [
                    'parishes'=> function ($query) {
                        $query->enabled()->sortedByName();
                    }
                ]
            )->enabled()->sortedByName('ASC')->get();
        });

        $categories = Category::whereHas(
            'merchants', function ($query) {
            $query->available()->fromCountry();
        }
        )->enabled()->sortedByOrder('DESC')->get();

        $userAccount = User::with([
            'addresses'=> function ($query) {
                $query->available()->sortedByName();
            }
        ])->find(Auth::id());
        $userCurrentCountry = CountryHelper::getCurrentCountry();
        if(!$userCurrentCountry && $userAccount){
            if($userCurrentAddress = $userAccount->addresses()->current()->first()){
                if($userCurrentAddress->parish){
                    $userCurrentCountry = $userCurrentAddress->parish->country;
                    CountryHelper::setCurrentCountry($userCurrentCountry);
                }
            }
        }
        $cart = CartHelper::getUserCartContent($userAccount);
        View::share(compact('countries', 'categories', 'userCurrentCountry', 'cart', 'userAccount'));
    }


}
