<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class LalamoveController extends Controller
{
    //
    public function estimate(Request $request){

        $pickup = $request->input('pickup');
        $destination = $request->input('destination');
        $pickupAddress = $request->input('pickupAddress');
        $destAddress = $request->input('destAddress');


        if (!$pickup || count($pickup) !== 2) {
            return response()->json(['error' => 'Invalid pickup coordinates'], 422);
        }
        
        if (!$destination || count($destination) !== 2) {
            return response()->json(['error' => 'Invalid destination coordinates'], 422);
        }
        //Assign lon and lat
    
        $latitude = $pickup[1];
        $longitude = $pickup[0];
        
        $latitudeDest = $destination[1];
        $longitudeDest = $destination[0];

        $apiKey = env('LALAMOVE_API_KEY');
        $apiSecret = env('LALAMOVE_API_SECRET');

        if (!$apiKey || !$apiSecret) {
        Log::error('Lalamove API key or secret is missing!');
        return response()->json(['error' => 'API key/secret missing'], 500);
    }

        $timestamp = round(microtime(true) * 1000); // current time in milliseconds
        
        $market = 'TW';

        $method = 'POST';
        $path = '/v3/quotations';

    $bodyArray = [
    "data" => [
        "serviceType" => "MOTORCYCLE_INTERCITY",
        "language" => "zh_TW",
        "stops" => [
            [
                "coordinates" => ["lat" => (string)$latitude, "lng" => (string)$longitude],
                "address" => $pickupAddress,
            ],
            [
                "coordinates" => ["lat" => (string)$latitudeDest, "lng" => (string)$longitudeDest],
                "address" => $destAddress,
            ],
        ],
        "item" => ["quantity" => "1","weight" => "LESS_THAN_3KG","categories"=>["FOOD_AND_BEVERAGE"],"handlingInstructions"=>["FRAGILE_OR_DONT_STACK","TEMPERATURE_SENSITIVE"]],
        "isRouteOptimized" => true,
    ],
];


        $body = json_encode($bodyArray, JSON_UNESCAPED_SLASHES);

        $rawSignature = "{$timestamp}\r\n{$method}\r\n{$path}\r\n\r\n{$body}";
        $signature = hash_hmac('sha256', $rawSignature, $apiSecret);

        $token = "{$apiKey}:{$timestamp}:{$signature}";
        
        $authorization = "hmac {$token}";
        Log::info('Lalamove Request Body: '.$body);
        Log::info('Authorization: '.$authorization);

        // Generate a unique Request-ID (UUID)
        $requestId = Str::uuid()->toString();

    try {
        // Make the request
        $response = Http::timeout(5)->retry(3, 1000)->withHeaders([            
        'Authorization' => $authorization,
        'Market'        => $market,
        'Request-ID'    => $requestId,
        'Content-Type'  => 'application/json',
        ])->withBody($body, 'application/json')->post('https://rest.sandbox.lalamove.com/v3/quotations');

        if (!$response->successful()) {
                Log::error('Lalamove API returned error', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return response()->json(['error' => 'Failed to get quote'], 500);
        }

        $data = $response->json();

        // ✅ Return only what your frontend needs
        return response()->json([
            'fee' => $data['data']['priceBreakdown']['total'] ?? 0,
            'currency' => $data['data']['priceBreakdown']['currency'] ?? 'TWD',
            'distance_m' => $data['data']['distance']['value'] ?? null,
            'quotation_id' => $data['data']['quotationId'] ?? 0,
            'sender_stop_id' => $data['data']['stops'][0]['stopId'] ?? null,
            'recipients_stop_id' => $data['data']['stops'][1]['stopId'] ?? null, 
            'raw' => $data, // optional, for debugging
        ]);

        } catch (\Exception $e) {
            // --- Catch network or JSON errors ---
            Log::error('Lalamove quote failed', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to get quote'], 500);
        }
    }
}
