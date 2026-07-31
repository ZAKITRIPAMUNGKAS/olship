<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ApiProductController extends Controller
{
    /**
     * Receive a stock sync request from WMS.
     *
     * Endpoint: POST /api/v1/products/sync-stock
     *
     * Request Body:
     * {
     *   "kode_barang": "PRD-001",
     *   "total_stock": 45,
     *   "calculated_at": "2026-05-26T19:30:00Z"
     * }
     */
    public function syncStock(Request $request)
    {
        $data = $request->validate([
            'kode_barang'   => 'required|string|max:100',
            'total_stock'   => 'required|integer|min:0',
            'calculated_at' => 'required|date_format:Y-m-d\TH:i:s\Z',
        ]);

        $sku = $data['kode_barang'];
        $totalStock = $data['total_stock'];
        $calculatedAt = Carbon::parse($data['calculated_at']);

        // Set log context
        $logger = Log::channel('api_sync');

        $product = Product::where('sku', $sku)->first();

        if (!$product) {
            $category = \App\Models\Category::first();
            $brand = \App\Models\Brand::first();
            $seller = \App\Models\User::first();

            $product = Product::create([
                'seller_id'           => $seller ? $seller->id : 1,
                'category_id'         => $category ? $category->id : 1,
                'brand_id'            => $brand ? $brand->id : null,
                'name'                => $request->input('nama', 'Produk ' . $sku),
                'sku'                 => $sku,
                'price'               => $request->input('harga', 0),
                'stock'               => $totalStock,
                'is_active'           => true,
                'last_stock_sync_at'  => $calculatedAt,
            ]);

            $logger->info("Stock Sync Auto-Created Product: SKU [{$sku}] added to Olshop with stock {$totalStock}.");

            return response()->json([
                'message' => 'Product created and stock synced successfully',
                'product_id' => $product->id
            ], 201);
        }

        // Validate timestamp to prevent race conditions
        if ($product->last_stock_sync_at) {
            $lastSync = Carbon::parse($product->last_stock_sync_at);
            if ($calculatedAt->lte($lastSync)) {
                $logger->info("Stock Sync Skipped (Stale Data): SKU [{$sku}] incoming computed_at [{$calculatedAt->toIso8601String()}] is older or equal to last sync [{$lastSync->toIso8601String()}]");
                return response()->json([
                    'message' => 'Skipped: stale data'
                ], 200);
            }
        }

        $oldStock = $product->stock;
        
        // Update product stock and timestamp
        $product->update([
            'stock' => $totalStock,
            'last_stock_sync_at' => $calculatedAt
        ]);

        $logger->info("Stock Sync Success: SKU [{$sku}] updated from {$oldStock} to {$totalStock}. calculated_at: [{$calculatedAt->toIso8601String()}]");

        return response()->json([
            'message' => 'Stock updated successfully'
        ], 200);
    }
}
