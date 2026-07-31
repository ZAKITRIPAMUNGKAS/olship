<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FlashSale;
use App\Models\FlashSaleItem;
use App\Models\Product;
use Illuminate\Http\Request;

class FlashSaleController extends Controller
{
    public function index()
    {
        $flashSales = FlashSale::with('items.product')->latest()->get();
        return view('admin.flash-sales.index', compact('flashSales'));
    }

    public function create()
    {
        $products = Product::where('is_active', true)->orderBy('name')->get();
        return view('admin.flash-sales.create', compact('products'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:255',
            'starts_at'  => 'required|date',
            'ends_at'    => 'required|date|after:starts_at',
            'is_active'  => 'boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active', true);
        FlashSale::create($data);

        return redirect()->route('admin.flash-sales.index')->with('success', 'Flash sale berhasil dibuat.');
    }

    public function show(FlashSale $flashSale)
    {
        $flashSale->load('items.product');
        $products = Product::where('is_active', true)->orderBy('name')->get();
        return view('admin.flash-sales.show', compact('flashSale', 'products'));
    }

    public function edit(FlashSale $flashSale)
    {
        $flashSale->load('items.product');
        $products = Product::where('is_active', true)->orderBy('name')->get();
        return view('admin.flash-sales.edit', compact('flashSale', 'products'));
    }

    public function update(Request $request, FlashSale $flashSale)
    {
        $data = $request->validate([
            'name'      => 'required|string|max:255',
            'starts_at' => 'required|date',
            'ends_at'   => 'required|date|after:starts_at',
            'is_active' => 'boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $flashSale->update($data);

        return redirect()->route('admin.flash-sales.index')->with('success', 'Flash sale diperbarui.');
    }

    public function destroy(FlashSale $flashSale)
    {
        $flashSale->delete();
        return redirect()->route('admin.flash-sales.index')->with('success', 'Flash sale dihapus.');
    }

    public function toggle(FlashSale $flashSale)
    {
        $flashSale->update(['is_active' => !$flashSale->is_active]);
        $status = $flashSale->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Flash sale {$status}.");
    }

    public function addItem(Request $request, FlashSale $flashSale)
    {
        $data = $request->validate([
            'product_id'    => 'required|exists:products,id',
            'promo_price'   => 'required|numeric|min:0',
            'stock_quota'   => 'required|integer|min:1',
            'discount_pct'  => 'nullable|numeric|min:0|max:100',
        ]);

        FlashSaleItem::updateOrCreate(
            ['flash_sale_id' => $flashSale->id, 'product_id' => $data['product_id']],
            $data
        );

        return back()->with('success', 'Produk ditambahkan ke flash sale.');
    }

    public function removeItem(FlashSale $flashSale, FlashSaleItem $item)
    {
        $item->delete();
        return back()->with('success', 'Produk dihapus dari flash sale.');
    }
}
