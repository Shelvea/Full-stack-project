<?php

namespace App\Services;

use App\Interfaces\Repositories\ProductRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProductService
{
    protected ProductRepositoryInterface $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function getFruits(?string $search): LengthAwarePaginator
    {
        return $this->productRepository->getFruits($search);
    }

    public function getVegetables(?string $search): LengthAwarePaginator
    {
        return $this->productRepository->getVegetables($search);
    }
}