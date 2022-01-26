<?php

namespace WinLocal\RemoteCron\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class Throttle
{
    public function handle(Request $request, $next)
    {
        $command = $request->input('command', 'default');
        $mutexName = preg_replace("/[^a-z0-9-_]/i", '', $command);
        $interval = config('remotecron.interval');
        if (false === Redis::set($mutexName, '1', 'EX', $interval, 'NX')) {
            abort(429, "Command $command called less then "
            .$interval
            ." seconds, please wait and run again later");
        }
        
        return $next($request);
    }
}
