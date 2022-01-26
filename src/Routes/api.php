<?php

use WinLocal\RemoteCron\Controllers\RemoteCommandController;

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

Route::get(config('remotecron.path'), [RemoteCommandController::class, 'cron'])
    ->middleware(config('remotecron.middleware'));
