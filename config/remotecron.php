<?php

return [
    'middleware' => [
        WinLocal\RemoteCron\Middleware\Throttle::class,
        WinLocal\RemoteCron\Middleware\TokenAuth::class
    ],
    'path' => env('WINLOCAL_CRON_PATH', 'remote/cron'),
    'interval' => env('WINLOCAL_CRON_THROTTLE_INTERVAL', 30),
    'token' => env('WINLOCAL_CRON_TOKEN')
];
