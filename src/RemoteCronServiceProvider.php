<?php

namespace WinLocal\RemoteCron;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class RemoteCronServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->setupConfig();
        $this->configureRateLimiting();
        $this->loadRoutesFrom(__DIR__.'/Routes/api.php');
    }

    protected function setupConfig()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/remotecron.php', 'remotecron');
        $configPath = $this->app->basePath().'/config/remotecron.php';

        $this->publishes([
            __DIR__.'/../config/remotecron.php' => $configPath,
        ], 'config');
    }

    protected function configureRateLimiting(): void
    {
        RateLimiter::for('remotecron', fn (Request $request) => Limit::perMinute(1)->by($request->input('command', 'default')));
    }
}
