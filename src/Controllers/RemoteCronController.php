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
        $parameters = json_decode($request->input('parameters'), true) ?? [];
        try {
            Artisan::queue($command, $parameters)->onConnection(config('remotecron.queue_connection'));
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], Response::HTTP_BAD_REQUEST);
        }
        return response()->json(['message' => 'Command is Running']);
    }
}
