<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Product;

class ProductController extends Controller
{
    //
    public function fruits(Request $request){

        $search = $request->query('search');

        $products = Product::fruits()->when($search, function ($q) use ($search) {
            $q->where('name', 'LIKE', "%{$search}%");
        })
        ->paginate(6)
        ->withQueryString();

        return view('customer.products.fruits', compact('products'));
    }

    public function vegetables(Request $request){
        
        $search = $request->query('search');

        $products = Product::vegetables()->when($search, function ($q) use ($search) {
            $q->where('name', 'LIKE', "%{$search}%");
        })->paginate(6)
        ->withQueryString();

        return view('customer.products.vegetables', compact('products'));
    }

}
