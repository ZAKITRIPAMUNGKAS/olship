<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            'Bosch', 'Makita', 'Tekiro', 'Krisbow', 'Sanwa', 'Eterna', 'Broco', 'Philips', 'Hanochs', 'Lakoni', 'Rhino',
            'Schneider',
            'So Good', 'Fiesta', 'Cedea', 'Champ', 'Belfoods',
        ];

        foreach ($brands as $brand) {
            Brand::updateOrCreate(
                ['slug' => Str::slug($brand)],
                ['name' => $brand, 'is_active' => true]
            );
        }
    }
}
