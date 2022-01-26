<?php

use WinLocal\RemoteCron\Controllers\RemoteCronController;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get(config('remotecron.path'), [RemoteCronController::class, 'cron'])
    ->middleware(config('remotecron.middleware'));
