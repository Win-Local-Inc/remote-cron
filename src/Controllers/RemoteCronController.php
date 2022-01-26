<?php

namespace WinLocal\RemoteCron\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class RemoteCronController extends Controller
{
    public function cron(Request $request)
    {
        $command = $request->input('command');
        $parameters = $request->input('parameters') ?? [];
        Artisan::queue($command, $parameters);

        return response()->json(['message' => 'Command is Running']);
    }
}
