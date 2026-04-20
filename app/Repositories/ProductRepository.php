<?php

namespace App\Repositories;

use App\Models\Product;
use App\Interfaces\Repositories\ProductRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProductRepository implements ProductRepositoryInterface
{
    public function getFruits(?string $search): LengthAwarePaginator
    {
        return Product::fruits()->when($search, function ($q) use ($search) {
            $q->where('name', 'LIKE', "%{$search}%");
        })
        ->paginate(6)
        ->withQueryString();
    }

    public function getVegetables(?string $search): LengthAwarePaginator
    {
        return Product::vegetables()->when($search, function ($q) use ($search) {
            $q->where('name', 'LIKE', "%{$search}%");
        })->paginate(6)
        ->withQueryString();
    }
}