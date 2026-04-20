<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ProductService;

use App\Models\Product;

class ProductController extends Controller
{
    //
    protected ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function fruits(Request $request){

        $search = $request->query('search');

        $products = $this->productService->getFruits($search);
    
        return response()->json($products);

    }

    public function vegetables(Request $request){
        
        $search = $request->query('search');

        $products = $this->productService->getVegetables($search);

        return response()->json($products);
    }

}
