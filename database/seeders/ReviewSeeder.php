<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\OrderItem;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $orderItems = OrderItem::with('order')->get();

        if ($orderItems->isEmpty()) return;

        foreach ($orderItems as $item) {
            Review::updateOrCreate(
                ['order_item_id' => $item->id],
                [
                    'product_id' => $item->product_id,
                    'user_id' => $item->order->user_id,
                    'rating' => rand(4, 5),
                    'title' => 'Sangat Puas',
                    'comment' => 'Produk sangat berkualitas, pengiriman cepat dan packing aman. Recomended seller!',
                    'status' => 'approved',
                ]
            );
        }
    }
}
