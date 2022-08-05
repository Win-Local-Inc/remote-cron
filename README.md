# Remote call Package
### Installation

- update array to your needs in `config/remotecron.php` with 

```php
[ 
    'middleware' => [
        WinLocal\RemoteCron\Middleware\Throttle::class,
        WinLocal\RemoteCron\Middleware\TokenAuth::class
    ],
    'path' => env('WINLOCAL_CRON_PATH', 'remote/cron'),
    'queue_connection' => env('WINLOCAL_CRON_QUEUE_CONNECTION', 'redis'),
    'interval' => env('WINLOCAL_CRON_THROTTLE_INTERVAL', 30),
    'token' => env('WINLOCAL_CRON_TOKEN', 'SET UP TOKEN')
]
```

- add envs :

```env
WINLOCAL_CRON_PATH=
WINLOCAL_CRON_TOKEN=
WINLOCAL_CRON_THROTTLE_INTERVAL=
WINLOCAL_CRON_QUEUE_CONNECTION=
```

- each service needs to run supervisor

`php artisan queue:work redis --max-time=3600 --max-jobs=100 --tries=3 --timeout=3550`

- new cron jobs needs to be add in super admin service database in tables : cron_services, cron_jobs

```example

cron_services
name: Advert
url: https://ad.stage.winlocal.com/remote/cron 
token: ***token***

cron_jobs
name: Report Upload
command: facebook:upload
parameters:
schedule: */10 * * * *

```