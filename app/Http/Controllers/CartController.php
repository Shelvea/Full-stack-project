<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Interfaces\Services\CartServiceInterface;

class CartController extends Controller
{

    protected CartServiceInterface $cartService;

    public function __construct(CartServiceInterface $cartService)
    {
        $this->cartService = $cartService;
    }

    //add to cart
    public function addToCart(Request $request, $productId){

        try {

            if(!Auth::check()){

                return response()->json(['message' => 'Please login first'], 401);
            }

            $this->cartService->addToCart(Auth::id(), $productId, max(1, (int)$request->quantity));

            return response()->json([
                'message' => 'Product added to cart'
            ]);
        
        } catch (\Exception $e) {

            return response()->json(['message' => 'Failed to add to cart'], 500);
        }
    }

    //Update quantity
    public function ajaxUpdate(Request $request, $itemId){

        $item = $this->cartService->updateItem($itemId, max(1, (int)$request->quantity));

        //Recalculate totals (updated)

        return response()->json([
            'success' => true,
            'item_total' => $this->cartService->calculateItemTotal($item, max(1, (int)$request->quantity)),
            'subtotal' => $this->cartService->calculateSubTotal($item),
        ]);
    }

    //remove Item
    public function removeItem($itemId){

        $this->cartService->removeItem($itemId);

        return response()->json([
            'message' => 'Item removed from cart!'
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {

         // Create a cart if it doesn't exist
        $cart = $this->cartService->getCart(Auth::user());

         // Load related items and their products
        return response()->json([
            'cart' => $this->cartService->loadCartItems($cart)
        ]);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Cart $cart)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cart $cart)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cart $cart)
    {
        //
    }
}
