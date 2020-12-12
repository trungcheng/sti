<?php
/*
|--------------------------------------------------------------------------
| Site config
|--------------------------------------------------------------------------
| Config for this site
|
*/

return [
    // Http X-Powered-By header.
    'api_auth' => 'sti',

    // Http X-Version header.
    'api_version' => 'v1.0.0',

    // Config limit record for a page
    'pagination' => [
        "options" => [
            20 => 20,
            50 => 50,
            100 => 100,
            200 => 200
        ],
        "default" => 20,
    ],

    // Config export.
    'export' => [
        'max_rows' => 10000
    ],

    // Config s3_presigned_expiry.
    's3_presigned_expiry' => '10 minutes',

    // life time code when send to user via sms
    'life_time_mobile_code' => env('LIFE_TIME_MOBILE_CODE', 30),
];
