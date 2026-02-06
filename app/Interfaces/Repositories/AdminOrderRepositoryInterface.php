<?php

namespace App\Interfaces\Repositories;

use App\Models\Order;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface AdminOrderRepositoryInterface
{
    public function findById(int $id): ?Order;

    public function delete(Order $order): void;

    public function updateStatus(Order $order, string $status): void;

    public function getAdminOrderByStatus(int $perPage = 5,
    ?int $page = null): LengthAwarePaginator;

    public function getLatestOrderIds(): Collection;
}