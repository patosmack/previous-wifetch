<?php

namespace App\Helpers;

use Artesaos\SEOTools\Facades\JsonLd;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\TwitterCard as Twitter;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;

class SeoHelper {

    public static function setTitle($value = null, $append_name = true){
        $value = ($value ?: '');
        SEOMeta::setTitle(($append_name ? env('APP_NAME') . ' - '  : '') . $value);
        OpenGraph::setTitle(($append_name ? env('APP_NAME') . ' - '  : '') . $value);
        Twitter::setTitle(($append_name ? env('APP_NAME') . ' - '  : '') . $value);
        JsonLd::setTitle(($append_name ? env('APP_NAME') . ' - '  : '') . $value);
    }

    public static function setDescription($value = null){
        SEOMeta::setDescription($value);
        OpenGraph::setDescription($value);
        JsonLd::setDescription($value);
    }

    public static function setImage($value = null){
        if($value){
            OpenGraph::addImage(asset($value));
            Twitter::addImage(asset($value));
            JsonLd::addImage(asset($value));
        }else{
            OpenGraph::addImage(asset('assets/common/logo-yellow.svg'));
            Twitter::addImage(asset('assets/common/logo-yellow.svg'));
            JsonLd::addImage(asset('assets/common/logo-yellow.svg'));
        }
    }

    public static function setName($value = null){
        if(!$value){
            $value = env('APP_NAME');
        }
        OpenGraph::setSiteName($value);
    }

    public static function setProperty($key = null, $value = null){
        OpenGraph::addProperty($key, $value);
        SEOMeta::addMeta($key, $value, 'property');
    }

    public static function addKeywords($keywords = []){
        if(!is_array($keywords)){
            $keywords = [$keywords];
        }
        SEOMeta::addKeyword($keywords);
    }

    public static function setUrl($value = null){
        if(!$value){
            $value = Request::url();
        }
        SEOMeta::setCanonical($value);
        OpenGraph::setUrl($value);
    }

}
