<?php

namespace App\Http\Controllers\Frontend\Base;

use App\Helpers\CountryHelper;
use App\Http\Controllers\Controller;
use App\Models\Location\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CountrySelectorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function selectCountry($countryIso = null)
    {
        $country = Country::where('iso', '=', $countryIso)->first();
        CountryHelper::setCurrentCountry($country);
        return redirect(route('home'));
    }

}
