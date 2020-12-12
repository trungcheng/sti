<?php

namespace App\Utilities;

use Twilio\Rest\Client;

/**
 * Class SMS
 * @package App\Utilities
 */
class Sms {
    /**
     * Send sms via SNS AWS
     *
     * @param string $phone
     * @param string $message
     * @throws \Exception
     */
    public static function send($phone, $message)
    {
        $sid = env('TWILIO_SID');
        $token = env('TWILIO_TOKEN');
        $client = new Client($sid, $token);

        $client->messages->create(
            $phone,
            [
                'from' => env('TWILIO_FROM'),
                'body' => $message,
            ]
        );
    }

    /**
     * Random mobile code
     *
     * @param int $length
     * @return string
     */
    public static function randomMobileCode(int $length = 6): string
    {
        $permitted_chars = '0123456789';
        return substr(str_shuffle($permitted_chars), 0, $length);
    }
}
