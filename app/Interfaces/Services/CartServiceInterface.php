<?php

namespace App\Interfaces\Services;

interface CartServiceInterface
{
    public function addToCart(int $userId, int $productId, int $quantity);

    public function updateItem(int $itemId, int $quantity);

    public function calculateItemTotal($item, int $quantity);

    public function calculateSubTotal($item);

    public function removeItem(int $itemId);

    public function loadCartItems($cart);
    
    public function getCart(int $userId);
}