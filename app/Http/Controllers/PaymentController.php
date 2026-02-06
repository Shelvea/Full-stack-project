<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Order;

use Inertia\Inertia;

class PaymentController extends Controller
{
    //
    public function index(){
        
        $orders = Order::pendingTransferPayment(auth()->id())
        ->with('orderItems.product')
        ->orderByDesc('created_at')
        ->paginate(5);

        //return view('customer.order.payment-transfer', compact('orders'));
        return Inertia::render('Payment', [
            'orders' => $orders
        ]);
    }
    
    
    

    
}
