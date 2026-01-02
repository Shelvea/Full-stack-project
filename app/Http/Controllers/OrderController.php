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
    public function index(Request $request)
    {
        //
        $highlightId = $request->query('highlight');
        // Get all orders with their order items and related product data
        $orders = Order::with('orderItems.product')->orderByDesc('created_at')->paginate(5);
        
        if ($highlightId) {
        // find the position of the order
        $position = Order::latest()
            ->pluck('id')
            ->search((int)$highlightId);

        if ($position !== false) {
            $page = floor($position / $orders->perPage()) + 1;

            // redirect to correct pagination page
            if ($page != $orders->currentPage()) {
                return redirect()->route('admin.orders.index', [
                    'page' => $page,
                    'highlight' => $highlightId
                ]);
            }
        }
    }

        // Pass to view
        return view('admin.orders.index', compact('orders', 'highlightId'));

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
