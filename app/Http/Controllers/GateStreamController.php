<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class GateStreamController extends Controller
{
    /**
     * Stream real-time updates to the portaria panel.
     */
    public function stream()
    {
        // Release session lock to prevent blocking other requests from the same user
        if (session_id()) {
            session_write_close();
        }

        return new StreamedResponse(function () {
            while (true) {
                // In a production app, we would use Redis or another message broker.
                // For this MVP, we send a heartbeat every 5 seconds.
                // The client will fetch data when it receives a 'message' or on timer.
                echo "event: update\n";
                echo "data: " . json_encode(['time' => now()->toDateTimeString()]) . "\n\n";

                if (connection_aborted()) {
                    break;
                }

                ob_flush();
                flush();
                sleep(5);
            }
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
        ]);
    }
}
