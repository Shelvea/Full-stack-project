<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    //show the checkout page
    public function index(){

        $user =  Auth::user();
        $cart = Cart::where('user_id', $user->id)->with('cartItems.product')->first();

        $subtotal = 0;

        if($cart){
            foreach($cart->cartItems as $item){

                $subtotal += $item->product->price * $item->quantity; 
            }
        }

        return view('customer.checkout.index', compact('cart','subtotal'));

    }

    //place order form
    public function placeOrder(Request $request){

        $request->validate([
            'recipient_name' => 'required|string|max:20',
            'recipient_email' => 'required|email',
            'recipient_phone' => ['required', 'regex:/^\+8869\d{8}$/'],
            'recipient_address' => 'required|string',
            'delivery_fee' => 'required|numeric|min:1',
            'delivery_distance' => 'required|numeric|min:1',
            'payment_method' => 'required|in:cod,transfer',
            'quotation_id' => 'required|string',
            'sender_stop_id' => 'required|string',
            'recipients_stop_id' => 'required|string',
            'subtotal' => 'required',
            'total' => 'required',
        ]);

        $apiKey = env('LALAMOVE_API_KEY');
        $apiSecret = env('LALAMOVE_API_SECRET');

        if (!$apiKey || !$apiSecret) {
        Log::error('Lalamove API key or secret is missing!');
        return response()->json(['error' => 'API key/secret missing'], 500);
    }

        $timestamp = round(microtime(true) * 1000); // current time in milliseconds
        
        $market = 'TW';

        $method = 'POST';
        $path = '/v3/orders';

        $bodyArray = [
        'data' => [
            'quotationId' => $request->quotation_id,
            'sender' => [
                'stopId' => $request->sender_stop_id,
                'name' => 'Teddy',
                'phone' => '+886912345678', // your store or user phone
            ],
            'recipients' => [
                [
                    'stopId' => $request->recipients_stop_id,
                    'name' => $request->recipient_name,
                    'phone' => $request->recipient_phone,
                ]
            ],
            'isPODEnabled' => true,
        ]
    ];

    $body = json_encode($bodyArray, JSON_UNESCAPED_SLASHES);

        $rawSignature = "{$timestamp}\r\n{$method}\r\n{$path}\r\n\r\n{$body}";
        $signature = hash_hmac('sha256', $rawSignature, $apiSecret);

        $token = "{$apiKey}:{$timestamp}:{$signature}";
        
        $authorization = "hmac {$token}";
        Log::info('Place Order Body: '.$body);
        Log::info('Authorization: '.$authorization);

        // Generate a unique Request-ID (UUID)
        $requestId = Str::uuid()->toString();

         // Make the request
        $response = Http::withHeaders([            
        'Authorization' => $authorization,
        'Market'        => $market,
        'Request-ID'    => $requestId,
        'Content-Type'  => 'application/json',
        ])->withBody($body, 'application/json')->post('https://rest.sandbox.lalamove.com/v3/orders');

        $data = $response->json();
        

        if ($response->failed()) {
            
            Log::error('Lalamove order error response', [
            'status' => $response->status(),
            'body' => $response->body(),
            ]);

            return back()->with('error', 'Failed to place Lalamove order. Try again.');
        }

        // Start database transaction
        DB::beginTransaction();
        
        
        try {        
        // Save order in your database if needed
        $order = Order::create([
            'user_id' => auth()->id(), // if user is logged in
            'recipient_name'    => $request->recipient_name,
            'recipient_email'   => $request->recipient_email,
            'recipient_phone'   => $request->recipient_phone,
            'recipient_address' => $request->recipient_address,
            'quotation_id'      => $request->quotation_id,
            'sender_stop_id'    => $request->sender_stop_id,
            'recipient_stop_id' => $request->recipients_stop_id,
            'delivery_fee'      => $request->delivery_fee,
            'delivery_distance' => $request->delivery_distance,
            'currency'          => 'TWD', // or from Lalamove if available
            'unit'              => 'km',
            'delivery_status'   => $data['data']['status'] ?? null,
            'image'             => $data['data']['stops'][1]['POD']['image'] ?? null,
            'delivered_at'      => $data['data']['stops'][1]['POD']['deliveredAt'] ?? null,
            'payment_method'    => $request->payment_method,
            'payment_status'    => 'unpaid',
            'lalamove_order_id' => $data['data']['orderId'] ?? null,
            'driver_id'         => $data['data']['driverId'] ?? null,
            'share_link'        => $data['data']['shareLink'] ?? null,
            'subtotal'          => $request->subtotal,
            'total'             => $request->total, // or add product cost + delivery_fee
            'pod_status'        => $data['data']['stops'][1]['POD']['status'] ?? null,
        ]);
        Log::info('Order saved successfully');

        $cart = Cart::where('user_id', Auth::id())->with('cartItems.product')->first();
        
        if ($cart) {            
            foreach ($cart->cartItems as $item) {
                $order->orderItems()->create([
                        'product_id' => $item->product_id,
                        'quantity' => $item->quantity,
                        'price' => $item->product->price,
                        'subtotal' => $item->product->price * $item->quantity,
                        'product_name' => $item->product->name,
                ]);

                // Reduce stock
                $item->product->decrement('stock', $item->quantity);
            }

            // Clear cart items
            $cart->cartItems()->delete();
        }
        
        // Commit if everything succeeded
        DB::commit();
        
        return redirect()->route('order.success');
    
    } catch (\Exception $e) {
        
        // Rollback all DB changes
        DB::rollBack();
        Log::error('Order placement failed: ' . $e->getMessage());
        return back()->with('error', 'Something went wrong. Please try again.');
    
    }
        
    }

}
