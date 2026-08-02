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
            'kode_barang'        => 'required|string|max:100',
            'total_stock'        => 'required|integer|min:0',
            'calculated_at'      => 'required|date_format:Y-m-d\TH:i:s\Z',
            'image_url'          => 'nullable|string',
            'gallery_image_urls' => 'nullable|array',
            'deskripsi'          => 'nullable|string',
            'nama'               => 'nullable|string',
            'harga'              => 'nullable|numeric',
        ]);

        $sku = $data['kode_barang'];
        $totalStock = $data['total_stock'];
        $calculatedAt = Carbon::parse($data['calculated_at']);

        // Set log context
        $logger = Log::channel('api_sync');

        $product = Product::where('sku', $sku)->first();

        $updateData = [
            'stock'               => $totalStock,
            'last_stock_sync_at'  => $calculatedAt,
        ];

        if ($request->filled('image_url')) {
            $updateData['image_url_override'] = $request->input('image_url');
        }
        if ($request->filled('nama')) {
            $updateData['name'] = $request->input('nama');
        }
        if ($request->filled('harga')) {
            $updateData['price'] = $request->input('harga');
        }
        if ($request->filled('deskripsi')) {
            $updateData['description'] = $request->input('deskripsi');
        }

        if (!$product) {
            $category = \App\Models\Category::first();
            $brand = \App\Models\Brand::first();
            $seller = \App\Models\User::first();

            $catId = $category ? $category->id : 1;
            $productName = $request->input('nama', 'Produk ' . $sku);
            $generatedSku = \App\Services\SkuGeneratorService::generateSku($catId, $productName, $sku);

            $createData = array_merge([
                'seller_id'   => $seller ? $seller->id : 1,
                'category_id' => $catId,
                'brand_id'    => $brand ? $brand->id : null,
                'name'        => $productName,
                'sku'         => $generatedSku,
                'price'       => $request->input('harga', 0),
                'is_active'   => true,
            ], $updateData);

            $product = Product::create($createData);

            $logger->info("Stock Sync Auto-Created Product: SKU [{$generatedSku}] (WMS Code: {$sku}) added to Olshop.");
        } else {
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
            $product->update($updateData);
            $logger->info("Stock Sync Success: SKU [{$sku}] updated from {$oldStock} to {$totalStock}. calculated_at: [{$calculatedAt->toIso8601String()}]");
        }

        // Sync Product Gallery Images
        if ($request->has('gallery_image_urls') && is_array($request->input('gallery_image_urls'))) {
            $galleryUrls = array_filter($request->input('gallery_image_urls'));
            if (!empty($galleryUrls)) {
                $product->images()->delete();
                foreach ($galleryUrls as $idx => $url) {
                    $product->images()->create([
                        'image_path' => $url,
                        'is_primary' => ($idx === 0),
                        'sort_order' => $idx,
                    ]);
                }
            }
        }

        return response()->json([
            'message'    => 'Product master data and stock synced successfully',
            'product_id' => $product->id
        ], 200);
    }
}
