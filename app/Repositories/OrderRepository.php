<?php

namespace App\Repositories;

use App\Models\Order;
use App\Interfaces\Repositories\AdminOrderRepositoryInterface;
use App\Interfaces\Repositories\UserOrderRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

//one repo multiple interfaces
class OrderRepository implements AdminOrderRepositoryInterface, UserOrderRepositoryInterface
{
    protected Order $orderModel;
    //repository connect to model(db eloquent queries) //database queries
    public function __construct(Order $orderModel)
    {
        $this->orderModel = $orderModel;
    }

    public function findById(int $id): ?Order{

        return $this->orderModel->findOrFail($id);
    }

    public function delete(Order $order): void{
        
        $order->delete();
    }

    public function updateStatus(Order $order, string $status): void{
        $order->status = $status;
        $order->save();
    }

    public function getUserOrderByStatus(int $userId, array $status, int $perPage = 5): LengthAwarePaginator {
        
        return $this->orderModel
            ->where('user_id', $userId)
            ->with('orderItems.product')
            ->whereIn('status', $status)
            ->orderByDesc('created_at')
            ->paginate($perPage);

    }

    public function getAdminOrderByStatus(int $perPage = 5,
    ?int $page = null): LengthAwarePaginator{
        
        return $this->orderModel
            ->with('orderItems.product')
            ->orderByDesc('created_at')
            ->paginate($perPage, ['*'], 'page', $page);
    }

    public function getLatestOrderIds(): Collection
    {
        return $this->orderModel->latest()->pluck('id');
    }
}