<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Coupon::updateOrCreate(['code' => 'LISTRINDO10'], [
            'name' => 'Promo Listrindo Jaya 10%',
            'discount_type' => 'percent',
            'discount_value' => 10,
            'min_order_amount' => 100000,
            'starts_at' => now(),
            'ends_at' => now()->addMonth(),
            'is_active' => true,
        ]);

        \App\Models\Coupon::updateOrCreate(['code' => 'FIXIT50'], [
            'name' => 'Potongan 50rb',
            'discount_type' => 'fixed',
            'discount_value' => 50000,
            'min_order_amount' => 500000,
            'starts_at' => now()->subDay(),
            'ends_at' => now()->addWeek(),
            'is_active' => true,
        ]);
    }
}
