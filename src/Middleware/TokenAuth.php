<?php

namespace WinLocal\RemoteCron\Middleware;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TokenAuth
{
    public function handle(Request $request, $next)
    {
        $token = $request->query('token');

        if (empty($token)) {
            $token = $request->input('token');
        }

        if (empty($token)) {
            $token = $request->bearerToken();
        }

        if (strcmp(config('remotecron.token'), $token) !== 0) {
            throw new AuthorizationException();
        }
 
        return $next($request);
    }
}
