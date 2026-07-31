<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();

        $product->load(['category', 'brand', 'images', 'variants.options', 'productAttributes', 'reviews.user', 'discussions' => function($q) {
            $q->whereNull('parent_id')->with(['user', 'replies.user'])->latest();
        }]);

        $variantMap = $product->variants->map(function($v) {
            $options = $v->options->pluck('attribute_value', 'attribute_name')->toArray();
            return [
                'id' => $v->id,
                'price' => $v->price,
                'stock' => $v->stock,
                'sku' => $v->sku,
                'options' => $options,
                'option_string' => implode(',', array_values($options))
            ];
        });

        $attributes = [];
        foreach($product->variants as $v) {
            foreach($v->options as $opt) {
                $attributes[$opt->attribute_name][] = $opt->attribute_value;
            }
        }
        foreach($attributes as $key => $val) {
            $attributes[$key] = array_unique($val);
        }

        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->limit(4)
            ->get();

        $isInWishlist = auth()->check() ? auth()->user()->wishlists()->where('product_id', $product->id)->exists() : false;

        return view('storefront.product.show', compact('product', 'relatedProducts', 'isInWishlist', 'variantMap', 'attributes'));
    }
}
