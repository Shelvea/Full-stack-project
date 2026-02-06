<?php

namespace App\Interfaces\Services;
use App\Models\Order;

interface OrderServiceInterface
{
    public function adminOrderIndex(?int $highlightId): array;

    public function userOrderIndex(int $userId, ?string $status): array;

    public function adminOrderDelivery(int $orderId): ?Order;

    public function adminUpdateStatus(string $status, int $orderId):void;

    public function adminDeleteOrder(int $orderId): void;


}