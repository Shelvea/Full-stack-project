<?php

namespace App\Repositories;

use App\Models\Cart;
use App\Models\CartItem;
use App\Interfaces\Repositories\CartRepositoryInterface;

class CartRepository implements CartRepositoryInterface
{
    
    //Get or create the cart for this user
    public function getOrCreateCart(int $userId){

        return Cart::firstOrCreate(['user_id' =>  $userId]);
    }

    //check if product already in cart
    public function findCartItem(int $cartId, int $productId){
    
        return CartItem::where('cart_id', $cartId)->where('product_id', $productId)->first();
    
    }

    public function findCartItemById(int $itemId){

        return CartItem::with(['product', 'cart.cartItems.product'])->findOrFail($itemId);
    
    }

    public function createCartItem(array $data){

        return CartItem::create($data);
    }

    public function createCart($user){//
        
        return $user->cart()->create();
    }

    public function updateCartItem($cartItem, int $quantity){//
        
        $cartItem->quantity = $quantity;
        $cartItem->save();
    }

    public function updateItem($item, int $quantity){//
        
        return $item->update(['quantity' => $quantity]);

    }

    public function deleteCartItem(int $itemId){

        $item = CartItem::findOrFail($itemId);
        $item->delete();
    }

}