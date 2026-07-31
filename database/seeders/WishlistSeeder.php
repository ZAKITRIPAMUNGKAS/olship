<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WishlistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = \App\Models\User::role('customer')->first();
        if (!$user) return;

        $products = \App\Models\Product::take(5)->get();

        foreach ($products as $product) {
            \App\Models\Wishlist::updateOrCreate([
                'user_id' => $user->id,
                'product_id' => $product->id,
            ]);
        }
    }
}
