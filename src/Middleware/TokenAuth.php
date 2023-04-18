<?php

namespace WinLocal\RemoteCron\Middleware;

use Illuminate\Http\Request;

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

        $token = trim($token);
        $configToken = trim(config('remotecron.token'));

        if (! $token || ! $configToken || strcmp($configToken, $token) !== 0) {
            abort(403, 'Not Authorized');
        }

        return $next($request);
    }
}
