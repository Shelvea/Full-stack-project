<?php

namespace App\Interfaces\Repositories;

interface CartRepositoryInterface
{
    
    //Get or create the cart for this user
    public function getOrCreateCart(int $userId);

    //check if product already in cart
    public function findCartItem(int $cartId, int $productId);

    public function findCartItemById(int $itemId);

    public function createCartItem(array $data);

    public function createCart($user);

    public function updateCartItem($cartItem, int $quantity);

    public function updateItem($item, int $quantity);

    public function deleteCartItem(int $itemId);

}