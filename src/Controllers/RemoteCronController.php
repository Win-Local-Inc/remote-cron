<?php

namespace WinLocal\RemoteCron\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Artisan;

class RemoteCronController extends Controller
{
    public function cron(Request $request)
    {
        $command = $request->input('command');
        $parameters = $request->input('parameters') ?? [];
        try {
            Artisan::queue($command, $parameters);
        } catch (\Throwable $th) {
            response()->json(['message' => $th->getMessage()], Response::HTTP_BAD_REQUEST);
        }
        return response()->json(['message' => 'Command is Running']);
    }
}
