<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\FlashSale;
use App\Models\Banner;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::with(['primaryImage'])->where('is_active', true)
            ->where('is_featured', true)
            ->take(8)
            ->get();

        $latestProducts = Product::with(['primaryImage'])->where('is_active', true)
            ->latest()
            ->take(10)
            ->get();

        $flashSale = FlashSale::where('is_active', true)
            ->where('starts_at', '<=', now())
            ->where('ends_at', '>=', now())
            ->with('items.product')
            ->first();

        $banners = Banner::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('storefront.home', compact('featuredProducts', 'latestProducts', 'flashSale', 'banners'));
    }
}
