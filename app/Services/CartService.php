<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use App\Interfaces\Repositories\CartRepositoryInterface;
use App\Interfaces\Services\CartServiceInterface;

class CartService implements CartServiceInterface
{
    protected CartRepositoryInterface $cartRepository;

    public function __construct(CartRepositoryInterface $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }

    public function addToCart(int $userId, int $productId, int $quantity)
    {
        // Block preview customer mode for security cannot add to cart
        if (Gate::allows('viewAsCustomer')) {

            abort(403, 'Preview mode: cannot add to cart.');

        }

        $product = Product::findOrFail($productId);

        if ($quantity > $product->stock) {
            abort(422, "Only {$product->stock} units available in stock.");
        }

        //Get or create the cart for this user
        $cart = $this->cartRepository->getOrCreateCart($userId);

        //check if product already in cart
        $cartItem = $this->cartRepository->findCartItem($cart->id, $productId);

        if($cartItem){

            $newQuantity = $cartItem->quantity + $quantity;

            if ($newQuantity > $product->stock) {
                abort(422, "Only {$product->stock} units available in stock.");
            }

            return $this->cartRepository->updateCartItem($cartItem, $newQuantity);

        }         
        
        return $this->cartRepository->createCartItem([
                'cart_id' => $cart->id,
                'product_id' => $productId,
                'quantity' => $quantity,
                'price' => $product->price ?? 0
        ]);

    }

    public function updateItem(int $itemId, int $quantity)//
    {
        $item = $this->cartRepository->findCartItemById($itemId);

        $item = $this->cartRepository->updateItem($item, $quantity);

         //Recalculate totals (updated)
        return $item;

    }

    public function calculateItemTotal($item, int $quantity){
         //Recalculate totals (updated)
        return $item->product->price * $quantity;
    }

    public function calculateSubTotal($item){

        return $item->cart->cartItems->sum(fn($i) => $i->product->price * $i->quantity);
    }

    public function removeItem(int $itemId)
    {
        $this->cartRepository->deleteCartItem($itemId);
        
    }

    public function loadCartItems($cart){
        
        return $cart->load('cartItems.product');
    }

    public function getCart($user)
    {
        return $user->cart ?? $this->cartRepository->createCart($user);
    }
}