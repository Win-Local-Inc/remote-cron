<?php

return [
    'middleware' => [
        'throttle:remotecron',
        WinLocal\RemoteCron\Middleware\TokenAuth::class,
    ],
    'path' => env('WINLOCAL_CRON_PATH', 'remote/cron'),
    'queue_connection' => env('WINLOCAL_CRON_QUEUE_CONNECTION', 'redis'),
    'token' => env('WINLOCAL_CRON_TOKEN'),
];
