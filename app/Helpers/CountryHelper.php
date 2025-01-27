<?php

namespace App\Helpers;

use App\Models\Location\Country;
use Illuminate\Support\Facades\Session;

class CountryHelper {

    public static function setCurrentCountry($country){
        if($country){
            $country = json_encode($country);
        }
        Session::put('countryIdToDisplay', $country);
    }

    public static function getCurrentCountry(){
        $current = Session::get('countryIdToDisplay');
        if($current){
            $country = json_decode($current,true);
            try{
                if(array_key_exists('name', $country) && array_key_exists('iso', $country)){
                    return (object)$country;
                }
            }catch (\Throwable $exception){

            }
        }
        return null;
    }

}
