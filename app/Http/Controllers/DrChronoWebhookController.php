<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
class DrChronoWebhookController extends Controller
{


    public function webhook(Request $request){


        Log::info('DrChrono Webhook received:', $request->all());

        // Process the webhook payload
        $data = $request->all();

        // Perform your business logic with the received data
        // e.g., Update patient records, create appointments, etc.

        return response()->json(['status' => 'success']);

    }
}
