<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FlashSale;
use App\Models\FlashSaleItem;
use App\Models\Product;
use Carbon\Carbon;

class FlashSaleSeeder extends Seeder
{
    public function run(): void
    {
        $flashSale = FlashSale::updateOrCreate(
            ['name' => 'Bulan Perkakas Nasional 2026'],
            [
                'starts_at' => Carbon::now()->subHours(2),
                'ends_at' => Carbon::now()->addHours(10),
                'is_active' => true,
            ]
        );

        $products = Product::where('is_featured', true)->take(5)->get();

        foreach ($products as $product) {
            FlashSaleItem::updateOrCreate(
                ['flash_sale_id' => $flashSale->id, 'product_id' => $product->id],
                [
                    'discount_type' => 'percent',
                    'discount_value' => 30,
                    'flash_price' => $product->price * 0.7, // 30% discount
                    'quota' => 20,
                    'sold_quota' => rand(0, 15),
                    'max_per_user' => 2,
                    'is_active' => true,
                ]
            );
        }
    }
}
