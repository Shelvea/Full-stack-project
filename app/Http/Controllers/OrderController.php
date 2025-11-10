<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        // Get all orders with their order items and related product data
        $orders = Order::with('orderItems.product')->orderByDesc('created_at')->paginate(5);
        
        // Pass to view
        return view('admin.orders.index', compact('orders'));

    }
public function indexUser(Request $request)
{
    $userId = Auth::id();

    // Normalize status to lowercase for comparison
    $statusNormalized = strtolower($request->status ?? 'pending');

    // Group statuses that share the "pending shipment" view
    $pendingShipmentStatuses = ['pending', 'Confirmed', 'Processing', 'Ready for Delivery'];

    // Map normalized versions for easy comparison
    $pendingShipmentNormalized = array_map('strtolower', $pendingShipmentStatuses);

    if (in_array($statusNormalized, $pendingShipmentNormalized)) {
        $orders = Order::where('user_id', $userId)
                        ->whereIn('status', $pendingShipmentStatuses)
                        ->with('orderItems.product')
                        ->orderByDesc('created_at')
                        ->paginate(5);

        return view('partials.pending_shipment', [
            'orders' => $orders,
            'status' => $statusNormalized // pass normalized status for Blade
        ]);
    }

    // Define a mapping of normalized status => DB value & view
    $statusMap = [
        'pending payment' => ['db' => 'pending payment', 'view' => 'partials.pending_payment'],
        'on the way'      => ['db' => 'On the Way', 'view' => 'partials.on_the_way'],
        'completed'       => ['db' => 'Completed', 'view' => 'partials.order_completed'],
        'returned'        => ['db' => 'Returned', 'view' => 'partials.returned_goods_or_refunds'],
        'cancelled'       => ['db' => 'Cancelled', 'view' => 'partials.cancelled'],
    ];

    if (array_key_exists($statusNormalized, $statusMap)) {
        $orders = Order::where('user_id', $userId)
                        ->where('status', $statusMap[$statusNormalized]['db'])
                        ->with('orderItems.product')
                        ->orderByDesc('created_at')
                        ->paginate(5);

        return view($statusMap[$statusNormalized]['view'], [
            'orders' => $orders,
            'status' => $statusNormalized
        ]);
    }

    // fallback to pending shipment
    $orders = Order::where('user_id', $userId)
                    ->whereIn('status', $pendingShipmentStatuses)
                    ->with('orderItems.product')
                    ->orderByDesc('created_at')
                    ->paginate(5);

    return view('partials.pending_shipment', [
        'orders' => $orders,
        'status' => 'pending'
    ]);
}

    /*public function indexUser(Request $request)
    {
        // Get the currently logged-in user's ID
        $userId = Auth::id();

        $status = $request->status ?? 'pending';

        // Group statuses that use the same view
        $pendingShipmentStatuses = ['pending', 'Confirmed', 'Processing', 'Ready for Delivery'];

        // Normalize status for comparison (trim and lowercase only for checking)
        $statusNormalized = strtolower(trim($status));

        // Check if status belongs to pending-shipment group
    $pendingShipmentNormalized = array_map('strtolower', $pendingShipmentStatuses);


        // If status belongs to any of those, show the same view
    if (in_array($statusNormalized, $pendingShipmentNormalized)) {
        $orders = Order::where('user_id', $userId)
                        ->whereIn('status', $pendingShipmentStatuses)
                        ->with('orderItems.product')
                        ->orderByDesc('created_at')
                        ->paginate(5);

        return view('partials.pending_shipment', compact('orders','status'));
    }

        // Retrieve all orders that belong to that user
        $orders = Order::where('user_id', $userId)
                    ->where('status', $status)
                    ->with('orderItems.product') // include related items (optional)
                    ->orderByDesc('created_at')
                    ->paginate(5); // optional pagination
    
        // Load specific view by status
    switch ($status) {
        
        case 'pending payment':
            return view('partials.pending_payment', compact('orders','status'));

        case 'On the Way':
            return view('partials.on_the_way', compact('orders','status'));

        case 'Completed':
            return view('partials.order_completed', compact('orders','status'));

        case 'Returned':
            return view('partials.returned_goods_or_refunds', compact('orders','status'));

        case 'Cancelled':
            return view('partials.cancelled', compact('orders','status'));

        default:
            // fallback to pending_shipment page if status pending
            return view('partials.pending_shipment', compact('orders','status'));
    }

    }*/

    public function delivery($orderId)
    {
        $order = Order::findOrFail($orderId);//fetch the order by ID

        return view('admin.orders.delivery', compact('order'));
    }

    public function updateStatus(Request $request, $orderId)
    {
        $order = Order::findOrFail($orderId);
        $order->status = $request->status;
        $order->save();

        return response()->json(['success' => true]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)//the $order parameter must same with the route order parameter name not orderId
    {
        //
        $order->delete();
        return redirect()->route('admin.orders.index')->with('success','Order deleted successfully');
    
    }
}
