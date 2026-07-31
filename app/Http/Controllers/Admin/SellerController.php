<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;

class SellerController extends Controller
{
    public function index(Request $request)
    {
        $stores = Store::with('seller')
            ->when($request->status && $request->status !== 'all', fn($q) => $q->where('verification_status', $request->status))
            ->latest()
            ->paginate(12);

        return view('admin.sellers.index', compact('stores'));
    }

    public function show(Store $store)
    {
        $store->load('seller', 'products');
        return view('admin.sellers.show', compact('store'));
    }

    public function verify(Store $store)
    {
        $store->update(['verification_status' => 'verified', 'is_verified' => true]);
        return back()->with('success', "Toko {$store->name} berhasil diverifikasi.");
    }

    public function suspend(Store $store)
    {
        $store->update(['is_active' => false]);
        return back()->with('success', "Toko {$store->name} disuspend.");
    }
}
