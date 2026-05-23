<?php
require_once "vendor/autoload.php";
use Twilio\Rest\Client;

class twilioController
{

    public static function sendSmsCode($number, $message_code){

        $sid    = getenv('TWILIO_ACCOUNT_SID');
        $token  = getenv('TWILIO_AUTH_TOKEN');
        $messagingServiceSid = getenv('TWILIO_MESSAGING_SERVICE_SID');

        if (!$sid || !$token || !$messagingServiceSid) {
            error_log('Twilio credentials not configured in environment variables.');
            return;
        }

        $twilio = new Client($sid, $token);

        $message = $twilio->messages->create(
            $number,
            array(
                "messagingServiceSid" => $messagingServiceSid,
                "body" => $message_code
            )
        );
    }

}
