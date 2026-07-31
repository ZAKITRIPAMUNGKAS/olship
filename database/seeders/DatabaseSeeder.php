<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            DummyLocationSeeder::class,
            CategorySeeder::class,
            BrandSeeder::class,
            ProductSeeder::class,
            FlashSaleSeeder::class,
            BannerSeeder::class,
            SiteSettingSeeder::class,
            OrderSeeder::class,
            PaymentSeeder::class,
            ReviewSeeder::class,
            WishlistSeeder::class,
            CouponSeeder::class,
        ]);
    }
}
