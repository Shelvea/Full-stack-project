<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Order;

use Inertia\Inertia;

class PaymentController extends Controller
{
    //
    public function index(){
        
        $orders = Order::with('orderItems.product')
        ->where('user_id', auth()->id())
        ->where('payment_method','transfer')
        ->where('status','pending payment')
        ->orderByDesc('created_at')
        ->paginate(5);

        //return view('customer.order.payment-transfer', compact('orders'));
        return Inertia::render('Payment', [
            'orders' => $orders
        ]);
    }
    
    
    

    
}
