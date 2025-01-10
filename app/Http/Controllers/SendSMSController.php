<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SendSMSController extends Controller
{
    public function send2FAMessage(Request $request)
    {
        // Correct phone number format (remove the leading +)
        $applicationId = '123abc456-def789ghi';  // Your actual application ID from Infobip
        $url = "https://wggjgd.api.infobip.com/2fa/2/applications/{$applicationId}/messages";
        // Replace {appId} with your actual application ID from Infobip

        // Prepare the request body, including the phone number
        $body = [
            'pinType' => 'NUMERIC',
            'messageText' => 'Your pin is {{pin}}',  // Use {{pin}} for placeholder
            'pinLength' => 4,
            'senderId' => 'ServiceSMS',
            'msisdn' => '639123456789',  // Use 'msisdn' for phone number
        ];

        try {
            // Send the HTTP POST request using Laravel's Http client
            $response = Http::withHeaders([
                'Authorization' => 'App ef1e6d0259b8db017b879c45f357ec7b-3ea8616f-8a21-4426-8282-b50100767eae', // Your authorization token
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->post($url, $body);

            // Check if the request was successful
            if ($response->successful()) {
                return response()->json(['message' => '2FA message sent successfully', 'data' => $response->json()]);
            } else {
                return response()->json(['error' => 'Failed to send 2FA message', 'status' => $response->status(), 'message' => $response->body()], $response->status());
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Request failed', 'message' => $e->getMessage()], 500);
        }
    }
}
