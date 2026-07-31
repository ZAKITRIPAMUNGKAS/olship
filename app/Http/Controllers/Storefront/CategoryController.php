<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function show(Request $request, $slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();

        $query = $category->products()
            ->with(['primaryImage', 'brand'])
            ->where('is_active', true);

        // Filter: Rentang Harga
        if ($request->filled('min_price')) {
            $query->where('price', '>=', (float) $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', (float) $request->max_price);
        }

        // Filter: Merek
        if ($request->filled('brands') && is_array($request->brands)) {
            $query->whereIn('brand_id', $request->brands);
        }

        // Filter: Rating
        if ($request->filled('min_rating')) {
            $query->where('rating_avg', '>=', (float) $request->min_rating);
        }

        // Sorting
        match ($request->sort) {
            'price_low'  => $query->orderBy('price', 'asc'),
            'price_high' => $query->orderBy('price', 'desc'),
            'popular'    => $query->orderBy('total_sold', 'desc'),
            'newest'     => $query->latest(),
            default      => $query->latest(),
        };

        $products = $query->paginate(12)->withQueryString();
        $brands   = Brand::where('is_active', true)->get();

        return view('storefront.category.show', compact('category', 'products', 'brands'));
    }
}
