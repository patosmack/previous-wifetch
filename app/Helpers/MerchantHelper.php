<?php

namespace App\Helpers;


use Carbon\Carbon;
use DateTime;

class MerchantHelper {

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

    public static function buildAvailableHours($availableHours){
        $days = [
            0 => 'Sun',
            1 => 'Mon',
            2 => 'Tue',
            3 => 'Wed',
            4 => 'Thu',
            5 => 'Fri',
            6 => 'Sat'
        ];
        $today = new Carbon(env('TIMEZONE'));
        $hours = [
            'is_open' => false,
            'availability' => [],
        ];
        foreach ($availableHours as $availableHour){
            try{
                $date = new Carbon(env('TIMEZONE'));
                $date = $date->startOfWeek(Carbon::SUNDAY);
                $date->addDays($availableHour->day);

                $start = Carbon::parse($date->format('Y-m-d')  . ' ' . $availableHour->open_time);
                $end = Carbon::parse($date->format('Y-m-d') . ' ' . $availableHour->close_time);

                if (self::isNowBetweenTimes($today, $start, $end)) {
                    $hours['is_open'] = true;
                }
                if(!array_key_exists($availableHour->day, $hours['availability'])){
                    $hours['availability'][$availableHour->day] = [
                        'day' => $days[$availableHour->day],
                        'hours' => []
                    ];
                }
//                $hours['availability'][$availableHour->day]['hours'][] = 'From ' . substr($availableHour->open_time, 0, 5) . ' to ' . substr($availableHour->close_time, 0, 5);

                $hours['availability'][$availableHour->day]['hours'][] = [
                    'from' => substr($availableHour->open_time, 0, 5),
                    'to' => substr($availableHour->close_time, 0, 5)
                ];
                //$hours['availability'][$availableHour->day]['hours'][]['to'] = substr($availableHour->open_time, 0, 5) . ' to ' . substr($availableHour->close_time, 0, 5);
            }catch (\Throwable $exception){

            }
        }
        return $hours;
    }



    private static function isNowBetweenTimes($curTimeLocal, $startDateTime, $endDateTime) {
        $startTime = $startDateTime->copy();
        $startTime->second = 0;
        $endTime = $endDateTime->copy();
        $endTime->second = 0;
        if ($endTime->lessThan($startTime)){
            $endTime->addDay();
        }
        return ($curTimeLocal->isBetween($startTime, $endTime));
    }

}
