<?php

namespace App\Interfaces\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface UserOrderRepositoryInterface
{
    public function getUserOrderByStatus(int $userId, array $status, int $perPage = 5): LengthAwarePaginator;

}