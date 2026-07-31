<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Str;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $customers = User::role('customer')->get();
        $products = Product::all();

        if ($customers->isEmpty() || $products->isEmpty()) return;

        foreach ($customers as $customer) {
            // Create 2 orders for each customer
            for ($i = 1; $i <= 2; $i++) {
                $orderProducts = $products->random(rand(1, 3));
                $subtotal = $orderProducts->sum('price');
                
                $order = Order::create([
                    'order_number' => 'ORD-' . strtoupper(Str::random(10)),
                    'user_id' => $customer->id,
                    'status' => 'completed',
                    'payment_status' => 'paid',
                    'shipping_status' => 'delivered',
                    'shipping_name' => $customer->name,
                    'shipping_phone' => '08123456789',
                    'shipping_address' => 'Jl. Test No. ' . rand(1, 100),
                    'shipping_city' => 'Jakarta',
                    'shipping_province' => 'DKI Jakarta',
                    'shipping_postal_code' => '12345',
                    'subtotal' => $subtotal,
                    'shipping_cost' => 20000,
                    'total_amount' => $subtotal + 20000,
                    'paid_at' => now(),
                    'completed_at' => now(),
                ]);

                foreach ($orderProducts as $product) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'product_sku' => $product->sku,
                        'price' => $product->price,
                        'quantity' => 1,
                        'seller_id' => $product->seller_id,
                    ]);
                }
            }
        }
    }
}
