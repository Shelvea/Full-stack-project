<?php

namespace App\Services;

use App\Interfaces\Services\OrderServiceInterface;
use App\Interfaces\Repositories\AdminOrderRepositoryInterface;
use App\Interfaces\Repositories\UserOrderRepositoryInterface;
use App\Models\Order;

class OrderService implements OrderServiceInterface
{
    protected AdminOrderRepositoryInterface $adminOrderRepository;
    protected UserOrderRepositoryInterface $userOrderRepository;
    //service connect to repository
    public function __construct(AdminOrderRepositoryInterface $adminOrderRepository, UserOrderRepositoryInterface $userOrderRepository){

        $this->adminOrderRepository = $adminOrderRepository;
        $this->userOrderRepository = $userOrderRepository;

    }
     //business logic
    public function adminOrderIndex(?int $highlightId): array{

        $perPage = 5;
        $page = null;

    if ($highlightId) {
        $position = $this->adminOrderRepository//get index position
            ->getLatestOrderIds()
            ->search((int) $highlightId);

        if ($position !== false) {
            $page = floor($position / $perPage) + 1;
        }
    }

        $orders = $this->adminOrderRepository
            ->getAdminOrderByStatus($perPage, $page);

        return compact('orders', 'page');
    }

    public function userOrderIndex(int $userId, ?string $status): array{
        // Normalize status to lowercase for comparison
        $statusNormalized = strtolower($status ?? 'pending');

        // Group statuses that share the "pending shipment" view
        $pendingShipmentStatuses = ['pending', 'Confirmed', 'Processing', 'Ready for Delivery'];
    
        // Default
        $ordersStatus = $pendingShipmentStatuses;
        $view = 'partials.pending_shipment';

         // Define a mapping of normalized status => DB value & view
        $statusMap = [
            'pending payment' => ['db' => ['pending payment'], 'view' => 'partials.pending_payment'],
            'on the way'      => ['db' => ['On the Way'], 'view' => 'partials.on_the_way'],
            'completed'       => ['db' => ['Completed'], 'view' => 'partials.order_completed'],
            'returned'        => ['db' => ['Returned'], 'view' => 'partials.returned_goods_or_refunds'],
            'cancelled'       => ['db' => ['Cancelled'], 'view' => 'partials.cancelled'],
        ];

        if (array_key_exists($statusNormalized, $statusMap)) {
            
            $ordersStatus =  $statusMap[$statusNormalized]['db'];
            $view = $statusMap[$statusNormalized]['view'];

        }

        $orders = $this->userOrderRepository->getUserOrderByStatus($userId, $ordersStatus);

        return compact('orders', 'view', 'statusNormalized');
    }

    public function adminOrderDelivery(int $orderId): ?Order{
        
        return $this->adminOrderRepository->findById($orderId);

    }

    public function adminUpdateStatus(string $status, int $orderId):void
    {
        $order = $this->adminOrderRepository->findById($orderId);

        if (! $order) {
            abort(404);
        }

        $this->adminOrderRepository->updateStatus($order, $status);
    }

    public function adminDeleteOrder(int $orderId): void{
        
        $order = $this->adminOrderRepository->findById($orderId);

        if (! $order) {
            abort(404);
        }

        $this->adminOrderRepository->delete($order);

    }
}