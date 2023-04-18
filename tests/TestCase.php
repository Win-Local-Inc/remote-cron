<?php

namespace WinLocal\RemoteCron\Tests;

use WinLocal\RemoteCron\RemoteCronServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected $loadEnvironmentVariables = false;

    protected function getPackageProviders($app)
    {
        return [RemoteCronServiceProvider::class];
    }
}
