<?php

namespace App\Helpers;

use Carbon\Carbon;

class Helper {

    public static function applyDiscount($value, $rate){
        return $value - (($value * $rate) / 100);
//        return  (($value * $rate) / 100);
    }

    public static function rand_color() {
        return sprintf('#%06X', mt_rand(0, 0xFFFFFF));
    }

    public static function validArgentinianPhoneNumber($phone) {
        $phone = preg_replace( '/\D+/', '', $phone);
        return preg_match(
            '/^(?:(?:00)?549?)?0?(?:11|[2368]\d)(?:(?=\d{0,2}15)\d{2})??\d{8}$/D',
            $phone
        );
    }

    public static function getEncodedUrl($url = null){
        if(!$url){
            $url = url()->current();
        }
        return self::base64_url_encode($url);
    }
    public static function getDecodedUrl($encoded_url){
        if(!$encoded_url) return null;
        return self::base64_url_decode($encoded_url);
    }

    public static function stripString($string){
        $string = str_replace(' ', '', $string);
        $string = str_replace('-', '', $string);
        $string = str_replace('_', '', $string);
        return preg_replace('/[^A-Za-z0-9\-]/', '', $string);
    }

    public static function base64_url_encode($input) {
        $base_64_encode = base64_encode($input);
        $base_64_encode = str_replace('/', '-', $base_64_encode);
        $base_64_encode = str_replace('+', '~', $base_64_encode);
        $base_64_encode = str_replace('=', '_', $base_64_encode);
        return $base_64_encode;
    }

    public static function base64_url_decode($input) {
        $input = str_replace('=', '_', $input);
        $input = str_replace('+', '~', $input);
        $input = str_replace('/', '-', $input);
        return base64_decode($input);;
    }

    public static function slug($string){
        $string = strip_tags($string);
        $string = str_replace(array('[\', \']'), '', $string);
        $string = preg_replace('/\[.*\]/U', '', $string);
        $string = preg_replace('/&(amp;)?#?[a-z0-9]+;/i', '-', $string);
        $string = htmlentities($string, ENT_COMPAT, 'utf-8');
        $string = preg_replace('/&([a-z])(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig|quot|rsquo);/i', '\\1', $string );
        $string = preg_replace(array('/[^a-z0-9]/i', '/[-]+/') , '-', $string);
        return strtolower(trim($string, '-'));
    }

    public static function trimText($text, $len, $tailText = '...'){
        if(empty($text)) return '';
        if(strlen($text) > $len){
            if(strlen($tailText) > 0){
                return substr($text, 0, $len) . ' ' . $tailText;
            }else{
                return substr($text, 0, $len);
            }
        }
        return $text;
    }

    public static function formatBytes($size, $precision = 2)
    {
        if ($size > 0) {
            $size = (int) $size;
            $base = log($size) / log(1024);
            $suffixes = array(' bytes', ' KB', ' MB', ' GB', ' TB');
            return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
        } else {
            return $size;
        }
    }

    public static function cleanHtml($value, $tags_allowed = "", $removeAll = FALSE){
        if($removeAll){
            $value = strip_tags($value);
        }else{
            if($tags_allowed == ""){
                $tags_allowed = "a|b|i|s|u|br|p|div|ul|li|table|tr|td";
            }
            $search = array('@<script[^>]*?>.*?</script>@si',  // Strip out javascript
                '#</?(?!('.$tags_allowed.'))\b([^><]*>)#sim',
                '@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
                '@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments including CDATA
            );
            $value = preg_replace($search, '', $value);
        }
        return $value;
    }

    public static function generate_uuid() {
        return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            random_int( 0, 0xffff ), random_int( 0, 0xffff ),
            random_int( 0, 0xffff ),
            random_int( 0, 0x0fff ) | 0x4000,
            random_int( 0, 0x3fff ) | 0x8000,
            random_int( 0, 0xffff ), random_int( 0, 0xffff ), random_int( 0, 0xffff )
        );
    }

    public static function getCurrentDayOfWeek(){
        $translation = ['Monday' => 'Lunes', 'Tuesday' => 'Martes', 'Wednesday' => 'Miércoles',  'Thursday' => 'Jueves', 'Friday' => 'Viernes', 'Saturday' => 'Sábado', 'Sunday' => 'Domingo'];
        $date = Carbon::now();
        $dayName = $date->format('l');
        return $translation[$dayName];
    }

    public static function dayName($number){
        $weekMap = [
            0 => 'Sunday',
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            6 => 'Saturday',
        ];
        try{
            return $weekMap[$number];
        }catch (\Exception $exception){
            return '';
        }
    }

    public static function generateRandomNumber($digits = 10){
        $ch = "0123456789";
        $l = strlen ($ch) - 1;
        $str = "";
        for ($i=0; $i < $digits; $i++){
            $x = rand (0, $l);
            $str .= $ch[$x];
        }
        return $str;
    }


    public static function safeURLParameter($string){
        $string = rawurlencode($string);
        return $string;
    }

    public static function emptyString($input){
        if(strlen(trim($input)) > 0){
            return false;
        }
        return true;
    }

    public static function nullIfEmpty($var, $nullString = 'null'){
        if (isset($var)) {
            if (!self::emptyString($var)) {
                if(strtolower($var) !== strtolower($nullString)){
                    return $var;
                }
            }
        }
        return null;
    }

    public static function splitSentence($term, $excludedWords = ['(', ')', '|', 'de']){
        $termParts = explode(' | ', $term);
        $termList = [];
        foreach ($termParts as $part){
            $termPartsInternal = explode(' ', $part);
            foreach ($termPartsInternal as $internalPart){
                $internalPart = trim(str_replace($excludedWords, "", $internalPart));
                if(self::nullIfEmpty($internalPart)){
                    $termList[] = $internalPart;
                }
            }
        }
        return $termList;
    }

    public static function splitSentenceV2($term, $excludedWords = ['(', ')', '|'], $joiners = ['de'])
    {
        $term = strtolower($term);
        $termParts = explode(' ', $term);

        $terms = [];

        for($i = 0, $iMax = count($termParts); $i< $iMax; $i++){
            $word = trim($termParts[$i]);

            $next = $i + 1;
            $next_to = $i + 2;
            $next_word = $next < $iMax ?  trim($termParts[$next]) : null;
            $next_to_word = $next_to < $iMax ?  trim($termParts[$next_to]) : null;

            if($next_word and in_array($next_word, $joiners) and $next_to_word){
                $word = $word . ' ' . $next_word . ' ' . $next_to_word;
                $i = $next_to;
                $terms[] = $word;
                continue;
            }
            $terms[] = $word;

        }

        return $terms;

        //dd($terms);

//        $termList = [];
//        foreach ($termParts as $part){
//            $termPartsInternal = explode(' ', $part);
//            dd($part);
//            foreach ($termPartsInternal as $internalPart){
//                $internalPart = trim(str_replace($excludedWords, "", $internalPart));
//                if(self::nullIfEmpty($internalPart)){
//                    $termList[] = $internalPart;
//                }
//            }
//        }
//
//        dd($termList);
//        return $termList;
    }


    public static function splitSentenceV3($term, $word_cutters = [' '], $excluded_words = ['(', ')', '|'], $joiners = ['de', 'y', '-', ',', '.'])
    {
        $term = strtolower($term);
        $term = str_replace($excluded_words, '', $term);

        $cutters = implode('|', $word_cutters);

        $termParts = Helper::multiexplode($cutters, $term);

        $terms_builder = [];
        for($i = 0, $iMax = count($termParts); $i< $iMax; $i++){
            $word = trim($termParts[$i]);

            $next = $i + 1;
            $next_to = $i + 2;
            $next_word = $next < $iMax ?  trim($termParts[$next]) : null;
            $next_to_word = $next_to < $iMax ?  trim($termParts[$next_to]) : null;

            if($next_word and in_array($next_word, $joiners) and $next_to_word){
                $word = $word . ' ' . $next_word . ' ' . $next_to_word;
                $i = $next_to;
                $terms_builder[] = $word;
                continue;
            }
            $terms_builder[] = $word;
        }

//        $terms = [];
//        //$terms_builder = array_reverse($terms_builder);
//        foreach ($terms_builder as $item){
//            $tmp_term = trim(strstr($term, $item, true));
//            if(strlen($tmp_term) > 0){
//                $terms[] = $tmp_term;
//            }
//        }
//        $terms[] = $term;
//        $terms = array_reverse($terms);
//        $terms = array_merge($terms, $terms_builder);
//        $terms = array_unique($terms);

        $terms = $terms_builder;

        return $terms;

    }


    public static function multiexplode ($delimiters,$string) {

        $ready = str_replace($delimiters, $delimiters[0], $string);
        $launch = explode($delimiters[0], $ready);
        return  $launch;
    }


    public static function permutations($array) {
        $result = [];

        $recurse = function($array, $start_i = 0) use (&$result, &$recurse) {
            if ($start_i === count($array)-1) {
                array_push($result, $array);
            }

            for ($i = $start_i; $i < count($array); $i++) {
                //Swap array value at $i and $start_i
                $t = $array[$i]; $array[$i] = $array[$start_i]; $array[$start_i] = $t;

                //Recurse
                $recurse($array, $start_i + 1);

                //Restore old order
                $t = $array[$i]; $array[$i] = $array[$start_i]; $array[$start_i] = $t;
            }
        };

        $recurse($array);

        return $result;
    }

    public static function stripTagsContent($string) {
        if($string && is_string($string)){
            $string = preg_replace ('/<[^>]*>/', ' ', $string);
            $string = str_replace("\r", '', $string);
            $string = str_replace("\n", ' ', $string);
            $string = str_replace("\t", ' ', $string);
            $string = trim(preg_replace('/ {2,}/', ' ', $string));
            $string = strip_tags($string);
            $string = preg_replace('/^<\?php.*\?\>/', '', $string);
            return $string;
        }
        return '';
    }

    public static function normalizeString($s) {
        $replace = array(
            'ъ'=>'-', 'Ь'=>'-', 'Ъ'=>'-', 'ь'=>'-',
            'Ă'=>'A', 'Ą'=>'A', 'À'=>'A', 'Ã'=>'A', 'Á'=>'A', 'Æ'=>'A', 'Â'=>'A', 'Å'=>'A', 'Ä'=>'Ae',
            'Þ'=>'B',
            'Ć'=>'C', 'ץ'=>'C', 'Ç'=>'C',
            'È'=>'E', 'Ę'=>'E', 'É'=>'E', 'Ë'=>'E', 'Ê'=>'E',
            'Ğ'=>'G',
            'İ'=>'I', 'Ï'=>'I', 'Î'=>'I', 'Í'=>'I', 'Ì'=>'I',
            'Ł'=>'L',
            'Ñ'=>'N', 'Ń'=>'N',
            'Ø'=>'O', 'Ó'=>'O', 'Ò'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'Oe',
            'Ş'=>'S', 'Ś'=>'S', 'Ș'=>'S', 'Š'=>'S',
            'Ț'=>'T',
            'Ù'=>'U', 'Û'=>'U', 'Ú'=>'U', 'Ü'=>'Ue',
            'Ý'=>'Y',
            'Ź'=>'Z', 'Ž'=>'Z', 'Ż'=>'Z',
            'â'=>'a', 'ǎ'=>'a', 'ą'=>'a', 'á'=>'a', 'ă'=>'a', 'ã'=>'a', 'Ǎ'=>'a', 'а'=>'a', 'А'=>'a', 'å'=>'a', 'à'=>'a', 'א'=>'a', 'Ǻ'=>'a', 'Ā'=>'a', 'ǻ'=>'a', 'ā'=>'a', 'ä'=>'ae', 'æ'=>'ae', 'Ǽ'=>'ae', 'ǽ'=>'ae',
            'б'=>'b', 'ב'=>'b', 'Б'=>'b', 'þ'=>'b',
            'ĉ'=>'c', 'Ĉ'=>'c', 'Ċ'=>'c', 'ć'=>'c', 'ç'=>'c', 'ц'=>'c', 'צ'=>'c', 'ċ'=>'c', 'Ц'=>'c', 'Č'=>'c', 'č'=>'c', 'Ч'=>'ch', 'ч'=>'ch',
            'ד'=>'d', 'ď'=>'d', 'Đ'=>'d', 'Ď'=>'d', 'đ'=>'d', 'д'=>'d', 'Д'=>'D', 'ð'=>'d',
            'є'=>'e', 'ע'=>'e', 'е'=>'e', 'Е'=>'e', 'Ə'=>'e', 'ę'=>'e', 'ĕ'=>'e', 'ē'=>'e', 'Ē'=>'e', 'Ė'=>'e', 'ė'=>'e', 'ě'=>'e', 'Ě'=>'e', 'Є'=>'e', 'Ĕ'=>'e', 'ê'=>'e', 'ə'=>'e', 'è'=>'e', 'ë'=>'e', 'é'=>'e',
            'ф'=>'f', 'ƒ'=>'f', 'Ф'=>'f',
            'ġ'=>'g', 'Ģ'=>'g', 'Ġ'=>'g', 'Ĝ'=>'g', 'Г'=>'g', 'г'=>'g', 'ĝ'=>'g', 'ğ'=>'g', 'ג'=>'g', 'Ґ'=>'g', 'ґ'=>'g', 'ģ'=>'g',
            'ח'=>'h', 'ħ'=>'h', 'Х'=>'h', 'Ħ'=>'h', 'Ĥ'=>'h', 'ĥ'=>'h', 'х'=>'h', 'ה'=>'h',
            'î'=>'i', 'ï'=>'i', 'í'=>'i', 'ì'=>'i', 'į'=>'i', 'ĭ'=>'i', 'ı'=>'i', 'Ĭ'=>'i', 'И'=>'i', 'ĩ'=>'i', 'ǐ'=>'i', 'Ĩ'=>'i', 'Ǐ'=>'i', 'и'=>'i', 'Į'=>'i', 'י'=>'i', 'Ї'=>'i', 'Ī'=>'i', 'І'=>'i', 'ї'=>'i', 'і'=>'i', 'ī'=>'i', 'ĳ'=>'ij', 'Ĳ'=>'ij',
            'й'=>'j', 'Й'=>'j', 'Ĵ'=>'j', 'ĵ'=>'j', 'я'=>'ja', 'Я'=>'ja', 'Э'=>'je', 'э'=>'je', 'ё'=>'jo', 'Ё'=>'jo', 'ю'=>'ju', 'Ю'=>'ju',
            'ĸ'=>'k', 'כ'=>'k', 'Ķ'=>'k', 'К'=>'k', 'к'=>'k', 'ķ'=>'k', 'ך'=>'k',
            'Ŀ'=>'l', 'ŀ'=>'l', 'Л'=>'l', 'ł'=>'l', 'ļ'=>'l', 'ĺ'=>'l', 'Ĺ'=>'l', 'Ļ'=>'l', 'л'=>'l', 'Ľ'=>'l', 'ľ'=>'l', 'ל'=>'l',
            'מ'=>'m', 'М'=>'m', 'ם'=>'m', 'м'=>'m',
            'ñ'=>'n', 'н'=>'n', 'Ņ'=>'n', 'ן'=>'n', 'ŋ'=>'n', 'נ'=>'n', 'Н'=>'n', 'ń'=>'n', 'Ŋ'=>'n', 'ņ'=>'n', 'ŉ'=>'n', 'Ň'=>'n', 'ň'=>'n',
            'о'=>'o', 'О'=>'o', 'ő'=>'o', 'õ'=>'o', 'ô'=>'o', 'Ő'=>'o', 'ŏ'=>'o', 'Ŏ'=>'o', 'Ō'=>'o', 'ō'=>'o', 'ø'=>'o', 'ǿ'=>'o', 'ǒ'=>'o', 'ò'=>'o', 'Ǿ'=>'o', 'Ǒ'=>'o', 'ơ'=>'o', 'ó'=>'o', 'Ơ'=>'o', 'œ'=>'oe', 'Œ'=>'oe', 'ö'=>'oe',
            'פ'=>'p', 'ף'=>'p', 'п'=>'p', 'П'=>'p',
            'ק'=>'q',
            'ŕ'=>'r', 'ř'=>'r', 'Ř'=>'r', 'ŗ'=>'r', 'Ŗ'=>'r', 'ר'=>'r', 'Ŕ'=>'r', 'Р'=>'r', 'р'=>'r',
            'ș'=>'s', 'с'=>'s', 'Ŝ'=>'s', 'š'=>'s', 'ś'=>'s', 'ס'=>'s', 'ş'=>'s', 'С'=>'s', 'ŝ'=>'s', 'Щ'=>'sch', 'щ'=>'sch', 'ш'=>'sh', 'Ш'=>'sh', 'ß'=>'ss',
            'т'=>'t', 'ט'=>'t', 'ŧ'=>'t', 'ת'=>'t', 'ť'=>'t', 'ţ'=>'t', 'Ţ'=>'t', 'Т'=>'t', 'ț'=>'t', 'Ŧ'=>'t', 'Ť'=>'t', '™'=>'tm',
            'ū'=>'u', 'у'=>'u', 'Ũ'=>'u', 'ũ'=>'u', 'Ư'=>'u', 'ư'=>'u', 'Ū'=>'u', 'Ǔ'=>'u', 'ų'=>'u', 'Ų'=>'u', 'ŭ'=>'u', 'Ŭ'=>'u', 'Ů'=>'u', 'ů'=>'u', 'ű'=>'u', 'Ű'=>'u', 'Ǖ'=>'u', 'ǔ'=>'u', 'Ǜ'=>'u', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'У'=>'u', 'ǚ'=>'u', 'ǜ'=>'u', 'Ǚ'=>'u', 'Ǘ'=>'u', 'ǖ'=>'u', 'ǘ'=>'u', 'ü'=>'ue',
            'в'=>'v', 'ו'=>'v', 'В'=>'v',
            'ש'=>'w', 'ŵ'=>'w', 'Ŵ'=>'w',
            'ы'=>'y', 'ŷ'=>'y', 'ý'=>'y', 'ÿ'=>'y', 'Ÿ'=>'y', 'Ŷ'=>'y',
            'Ы'=>'y', 'ž'=>'z', 'З'=>'z', 'з'=>'z', 'ź'=>'z', 'ז'=>'z', 'ż'=>'z', 'ſ'=>'z', 'Ж'=>'zh', 'ж'=>'zh'
        );
        return strtr($s, $replace);
    }

}
