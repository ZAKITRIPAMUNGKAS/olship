<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('q');
        
        $products = Product::where('is_active', true)
            ->when($query, function($q) use ($query) {
                return $q->where('name', 'like', "%{$query}%")
                         ->orWhere('description', 'like', "%{$query}%");
            })
            ->latest()
            ->paginate(12);

        return view('storefront.search.index', compact('products', 'query'));
    }
}
