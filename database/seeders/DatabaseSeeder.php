<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database (Fresh Account & Setup Only).
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
            SiteSettingSeeder::class,
            BannerSeeder::class,
        ]);
    }
}
