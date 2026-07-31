<?php

namespace App\Services;

use App\Models\Product;
use App\Models\FlashSaleItem;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ProductService
{
    public function createProduct(array $data, User $seller): Product
    {
        return DB::transaction(function () use ($data, $seller) {
            $product = Product::create(array_merge($data, ['seller_id' => $seller->id]));
            
            if (isset($data['images'])) {
                foreach ($data['images'] as $index => $imagePath) {
                    $product->images()->create([
                        'image_path' => $imagePath,
                        'is_primary' => $index === 0,
                        'sort_order' => $index
                    ]);
                }
            }
            
            return $product;
        });
    }

    public function updateStock(Product $product, int $qty, string $type = 'decrease'): void
    {
        if ($type === 'decrease') {
            $product->decrement('stock', $qty);
            $product->increment('total_sold', $qty);
        } else {
            $product->increment('stock', $qty);
        }
    }

    public function getActivePrice(Product $product): array
    {
        // Check active flash sale
        $flashItem = FlashSaleItem::activeForProduct($product->id)->first();
        if ($flashItem) {
            return [
                'price' => $flashItem->flash_price,
                'type' => 'flash_sale',
                'item' => $flashItem,
                'original_price' => $product->price
            ];
        }

        // Regular price
        return [
            'price' => $product->price,
            'type' => 'normal',
            'original_price' => $product->price
        ];
    }
}
