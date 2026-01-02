<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $products = Product::orderByDesc('created_at')->paginate(5);
        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $categories = Category::all(); //get all categories
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        //validate data
        $validated = $request->validate([
            'name' => 'required|string|min:2|max:255',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'category_id' => 'required|exists:categories,id',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }
        //dd('ok');
        //create product, use laravel's eloquent create method on the product model
        Product::create($validated);//create a new record inside database table
        
        return redirect()->route('admin.products.index')->with('success','Product added successfully');//redirect user to previous page and display flash message
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
        // Get the page number from query string (?page=3), default to 1 if not present
        $page = request()->get('page', 1);

        return view('admin.products.show', compact('product','page'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
        $categories = Category::all();
        return view('admin.products.edit', compact('product','categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //
        $validated = $request->validate([
            'name' => 'required|string|min:2|max:255',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'category_id' => 'required|exists:categories,id',
        ]);

        if($request->hasFile('image')){
            $validated['image'] = $request->file('image')->store('products','public');
        }

        $product->update($validated);

        return redirect()->route('admin.products.index')->with('success','Product updated successfully');
    
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
        $product->delete();
        return redirect()->route('admin.products.index')->with('success','Product deleted successfully');
    }

    public function fruits(Request $request){

        $search = $request->query('search');

        $products = Product::where('category_id', 1)->when($search, function ($q) use ($search) {
            $q->where('name', 'LIKE', "%{$search}%");
        })
        ->paginate(6)
        ->withQueryString();

        return view('customer.products.fruits', compact('products'));
    }

    public function vegetables(Request $request){
        
        $search = $request->query('search');

        $products = Product::where('category_id', 2) ->when($search, function ($q) use ($search) {
            $q->where('name', 'LIKE', "%{$search}%");
        })->paginate(6)
        ->withQueryString();

        return view('customer.products.vegetables', compact('products'));
    }

}
