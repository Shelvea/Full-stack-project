<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use illuminate\support\facades\Gate;

class CartController extends Controller
{

    //add to cart
    public function addToCart(Request $request, $productId){

    // Block preview customer mode for security cannot add to cart
    if (Gate::allows('viewAsCustomer')) {

        return redirect()->back()->with('error', 'Preview mode: cannot add to cart.');
    
    }

        $user = Auth::user();

        //Get or create the cart for this user
        $cart = Cart::firstOrCreate(['user_id' => $user->id]);

        //check if product already in cart
        $cartItem = CartItem::where('cart_id', $cart->id)->where('product_id', $productId)->first();
        
        //get the input field named quantity
        $quantity = (int)$request->input('quantity', 1);

        $product = Product::findOrFail($productId);

        if($cartItem){
            
        $newQuantity = $cartItem->quantity + $quantity;

         // ✅ Prevent exceeding available stock
        if ($newQuantity > $product->stock) {
            return redirect()->back()->with('error', "Only {$product->stock} units available in stock.");
        }

            $cartItem->quantity = $newQuantity;
            $cartItem->save();
        
        } else {

            // ✅ Prevent adding if requested quantity > stock
            if ($quantity > $product->stock) {
                return redirect()->back()->with('error', "Only {$product->stock} units available in stock.");
            }

            // Add new item
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $productId,
                'quantity' => $quantity,
                'price' => $product->price ?? 0
            ]);
        }

        return redirect()->back()->with('success', 'Product added to cart!');

    }

    //Update quantity
    public function ajaxUpdate(Request $request, $itemId){

        $item = CartItem::findOrFail($itemId);
        $quantity = max(1, (int)$request->quantity);

        $item->update(['quantity' => $quantity]);

        //Recalculate totals (updated)
        $itemTotal = $item->product->price * $quantity;
        $subTotal = $item->cart->cartItems->sum(fn($i) => $i->product->price * $i->quantity);

        return response()->json([
            'success' => true,
            'item_total' => $itemTotal,
            'subtotal' => $subTotal,
        ]);
    }

    //remove Item
    public function removeItem($itemId){

        $cartItem = CartItem::findOrFail($itemId);

        $cartItem->delete();

        return redirect()->back()->with('success','Item removed from cart!');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

         // Create a cart if it doesn't exist
        $cart = $user->cart ?? $user->cart()->create();

        // Load related items and their products
        $cart->load('cartItems.product');

        return view('customer.cart.index', compact('cart'));

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
