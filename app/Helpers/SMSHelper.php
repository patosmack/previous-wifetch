<?php

namespace App\Helpers;

use Twilio\Rest\Client;

class SMSHelper {

    public static function sendMessage($message, $recipients){
        if(env('ENABLE_SMS', false) ){
            if(is_string($recipients)){
                $recipients = [$recipients];
            }
            if(count($recipients) > 0 && $message != ''){
                $account_sid = env("TWILIO_SID");
                $auth_token = env("TWILIO_AUTH_TOKEN");
                $twilio_number = env("TWILIO_NUMBER");
                $client = new Client($account_sid, $auth_token);
                foreach ($recipients as $recipient){
                    $client->messages->create($recipient, ['from' => $twilio_number, 'body' => $message] );
                }
            }
        }
    }

}
