<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\FlashSale;
use Illuminate\Http\Request;

class FlashSaleController extends Controller
{
    public function index()
    {
        $flashSales = FlashSale::where('is_active', true)
            ->where('ends_at', '>=', now())
            ->with('items.product')
            ->get();

        return view('storefront.flash-sale.index', compact('flashSales'));
    }
}
