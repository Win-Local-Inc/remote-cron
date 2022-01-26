<?php

namespace WinLocal\RemoteCron;

use Illuminate\Support\ServiceProvider;

class RemoteCronServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->configure();

        $this->loadRoutesFrom(__DIR__.'/Routes/api.php');
    }

    protected function configure()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/remotecron.php', 'remotecron');
        $configPath = $this->app->basePath().'/config/remotecron.php';

        $this->publishes([
            __DIR__.'/../config/remotecron.php' => $configPath,
        ], 'config');
    }
}
