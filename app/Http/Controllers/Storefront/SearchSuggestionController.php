<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class SearchSuggestionController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('q');

        if (empty($query) || strlen($query) < 2) {
            return response()->json([]);
        }

        $products = Product::with(['primaryImage', 'images'])
            ->where('is_active', true)
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('short_description', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%")
                  ->orWhere('sku', 'like', "%{$query}%");
            })
            ->latest()
            ->limit(5)
            ->get();

        $formatted = $products->map(function ($product) {
            $imagePath = 'https://placehold.co/100x100?text=' . urlencode($product->name);
            if ($product->primaryImage) {
                $imagePath = asset('storage/' . $product->primaryImage->image_path);
            } elseif ($product->images->count() > 0) {
                $imagePath = asset('storage/' . $product->images->first()->image_path);
            }

            return [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'price' => $product->price,
                'formatted_price' => 'Rp' . number_format($product->price, 0, ',', '.'),
                'image' => $imagePath,
                'url' => route('products.show', $product->slug),
            ];
        });

        return response()->json($formatted);
    }
}
