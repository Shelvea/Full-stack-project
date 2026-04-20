<?php

namespace App\Interfaces\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ProductRepositoryInterface
{
    public function getFruits(?string $search): LengthAwarePaginator;

    public function getVegetables(?string $search): LengthAwarePaginator;
}