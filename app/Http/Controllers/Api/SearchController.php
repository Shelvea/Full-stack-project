<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class SearchController extends Controller
{
    //
    public function search(Request $request)
    {
        $q = $request->query('q');

        if (!$q || strlen($q) < 2) {
            return response()->json([]);
        }

        return Product::where('name', 'LIKE', "%{$q}%")
            ->limit(10)
            ->get()
            ->map(fn ($p) => [
                'name' => $p->name,
                'category' => $p->category_id === 1 ? 'fruit' : 'vegetable',
            ]);
    }
}
