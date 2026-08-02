<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class StorefrontController extends Controller
{
    /**
     * GET /api/v1/storefront/products
     *
     * Returns active products for the landing page.
     * Query params:
     *   - category (slug or id, optional)
     *   - limit (default 24, max 100)
     *   - search (optional keyword)
     */
    public function products(Request $request)
    {
        $limit    = min((int) $request->get('limit', 24), 100);
        $category = $request->get('category');
        $search   = $request->get('search');

        $cacheKey = 'storefront_products_' . md5($category . '|' . $search . '|' . $limit);

        $products = Cache::remember($cacheKey, 120, function () use ($limit, $category, $search) {
            return Product::with(['category', 'images'])
                ->where('is_active', true)
                ->when($category, function ($q) use ($category) {
                    $q->whereHas('category', function ($q2) use ($category) {
                        $q2->where('slug', $category)
                           ->orWhere('id', $category)
                           ->orWhere('name', 'like', '%' . $category . '%');
                    });
                })
                ->when($search, function ($q) use ($search) {
                    $q->where(function ($q2) use ($search) {
                        $q2->where('name', 'like', '%' . $search . '%')
                           ->orWhere('sku', 'like', '%' . $search . '%')
                           ->orWhere('description', 'like', '%' . $search . '%');
                    });
                })
                ->orderByDesc('is_featured')
                ->orderByDesc('total_sold')
                ->orderByDesc('created_at')
                ->limit($limit)
                ->get()
                ->map(function (Product $p) {
                    return [
                        'id'          => $p->id,
                        'name'        => $p->name,
                        'sku'         => $p->sku,
                        'price'       => (float) $p->price,
                        'price_idr'   => 'Rp ' . number_format($p->price, 0, ',', '.'),
                        'description' => trim(strip_tags($p->description ?? $p->short_description ?? '')),
                        'image_url'   => $p->image_url,
                        'gallery'     => $p->images->pluck('image_path')->map(fn($p) => asset('storage/' . $p))->values(),
                        'category'    => $p->category ? [
                            'id'   => $p->category->id,
                            'name' => $p->category->name,
                            'slug' => $p->category->slug,
                        ] : null,
                        'stock'       => (int) $p->stock,
                        'is_featured' => (bool) $p->is_featured,
                    ];
                });
        });

        return response()->json([
            'success'  => true,
            'total'    => $products->count(),
            'products' => $products,
        ])->header('Access-Control-Allow-Origin', '*');
    }

    /**
     * GET /api/v1/storefront/categories
     * Returns all categories that have at least 1 active product.
     */
    public function categories(Request $request)
    {
        $categories = Cache::remember('storefront_categories', 300, function () {
            return Category::whereHas('products', function ($q) {
                    $q->where('is_active', true);
                })
                ->orderBy('name')
                ->get(['id', 'name', 'slug']);
        });

        return response()->json([
            'success'    => true,
            'categories' => $categories,
        ])->header('Access-Control-Allow-Origin', '*');
    }
}
