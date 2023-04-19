<?php

namespace WinLocal\RemoteCron\Middleware;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

class TokenAuth
{
    public function handle(Request $request, $next)
    {
        $token = trim($request->query('token') ??
            $request->input('token') ??
            $request->bearerToken());

        $configToken = trim(config('remotecron.token'));

        if (! $token || ! $configToken || strcmp($configToken, $token) !== 0) {
            throw new AuthorizationException('Not Auhorized');
        }

        return $next($request);
    }
}
