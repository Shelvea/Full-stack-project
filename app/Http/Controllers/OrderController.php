<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Interfaces\Services\OrderServiceInterface;

class OrderController extends Controller
{   //controller connect to services

    protected OrderServiceInterface $orderService;
    
    public function __construct(OrderServiceInterface $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $highlightId = $request->query('highlight');

        $data = $this->orderService->adminOrderIndex($highlightId);

        return view('admin.orders.index', [
            'orders' => $data['orders'],
            'highlightId' => $highlightId
        ]);

    }

public function indexUser(Request $request)
{
    
    $userId = Auth::id();

    $data = $this->orderService->userOrderIndex($userId, $request->status);

    return view($data['view'], [
        'orders' => $data['orders'],
        'status' => $data['statusNormalized'],
    ]);
}



    public function delivery($orderId)
    {
        
        $order = $this->orderService->adminOrderDelivery($orderId);

        if (!$order) {
            abort(404, 'Order not found');
        }

        return view('admin.orders.delivery', compact('order'));
    }

    public function updateStatus(Request $request, $orderId)
    {
        $this->orderService->adminUpdateStatus($request->status, $orderId);

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
    public function destroy(int $orderId)//the $order parameter must same with the route order parameter name not orderId
    {
        //
        $this->orderService->adminDeleteOrder($orderId);
        
        return redirect()->route('admin.orders.index')->with('success','Order deleted successfully');
    
    }
}
