<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DummyLocationSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Seeding dummy provinces and cities...');

        $provinces = [
            ['id' => 1, 'name' => 'Bali'],
            ['id' => 2, 'name' => 'Bangka Belitung'],
            ['id' => 3, 'name' => 'Banten'],
            ['id' => 4, 'name' => 'Bengkulu'],
            ['id' => 5, 'name' => 'DI Yogyakarta'],
            ['id' => 6, 'name' => 'DKI Jakarta'],
            ['id' => 7, 'name' => 'Gorontalo'],
            ['id' => 8, 'name' => 'Jambi'],
            ['id' => 9, 'name' => 'Jawa Barat'],
            ['id' => 10, 'name' => 'Jawa Tengah'],
            ['id' => 11, 'name' => 'Jawa Timur'],
        ];

        foreach ($provinces as $p) {
            DB::table('provinces')->updateOrInsert(['id' => $p['id']], [
                'name' => $p['name'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $cities = [
            // Bali
            ['id' => 1, 'province_id' => 1, 'name' => 'Badung', 'type' => 'Kabupaten', 'postal_code' => '80351'],
            ['id' => 2, 'province_id' => 1, 'name' => 'Bangli', 'type' => 'Kabupaten', 'postal_code' => '80619'],
            ['id' => 3, 'province_id' => 1, 'name' => 'Buleleng', 'type' => 'Kabupaten', 'postal_code' => '81111'],
            ['id' => 4, 'province_id' => 1, 'name' => 'Denpasar', 'type' => 'Kota', 'postal_code' => '80222'],
            
            // Bangka Belitung
            ['id' => 27, 'province_id' => 2, 'name' => 'Bangka', 'type' => 'Kabupaten', 'postal_code' => '33211'],
            ['id' => 334, 'province_id' => 2, 'name' => 'Pangkal Pinang', 'type' => 'Kota', 'postal_code' => '33111'],

            // Banten
            ['id' => 455, 'province_id' => 3, 'name' => 'Tangerang', 'type' => 'Kota', 'postal_code' => '15111'],
            ['id' => 412, 'province_id' => 3, 'name' => 'Serang', 'type' => 'Kota', 'postal_code' => '42111'],
            ['id' => 106, 'province_id' => 3, 'name' => 'Cilegon', 'type' => 'Kota', 'postal_code' => '42411'],

            // Bengkulu
            ['id' => 66, 'province_id' => 4, 'name' => 'Bengkulu', 'type' => 'Kota', 'postal_code' => '38111'],

            // DI Yogyakarta
            ['id' => 501, 'province_id' => 5, 'name' => 'Yogyakarta', 'type' => 'Kota', 'postal_code' => '55111'],
            ['id' => 419, 'province_id' => 5, 'name' => 'Sleman', 'type' => 'Kabupaten', 'postal_code' => '55511'],
            ['id' => 39, 'province_id' => 5, 'name' => 'Bantul', 'type' => 'Kabupaten', 'postal_code' => '55711'],

            // DKI Jakarta
            ['id' => 151, 'province_id' => 6, 'name' => 'Jakarta Barat', 'type' => 'Kota', 'postal_code' => '11220'],
            ['id' => 152, 'province_id' => 6, 'name' => 'Jakarta Pusat', 'type' => 'Kota', 'postal_code' => '10110'],
            ['id' => 153, 'province_id' => 6, 'name' => 'Jakarta Selatan', 'type' => 'Kota', 'postal_code' => '12110'],
            ['id' => 154, 'province_id' => 6, 'name' => 'Jakarta Timur', 'type' => 'Kota', 'postal_code' => '13110'],
            ['id' => 155, 'province_id' => 6, 'name' => 'Jakarta Utara', 'type' => 'Kota', 'postal_code' => '14110'],

            // Gorontalo
            ['id' => 144, 'province_id' => 7, 'name' => 'Gorontalo', 'type' => 'Kota', 'postal_code' => '96111'],

            // Jambi
            ['id' => 156, 'province_id' => 8, 'name' => 'Jambi', 'type' => 'Kota', 'postal_code' => '36111'],

            // Jawa Barat
            ['id' => 22, 'province_id' => 9, 'name' => 'Bandung', 'type' => 'Kota', 'postal_code' => '40111'],
            ['id' => 23, 'province_id' => 9, 'name' => 'Bandung', 'type' => 'Kabupaten', 'postal_code' => '40311'],
            ['id' => 54, 'province_id' => 9, 'name' => 'Bekasi', 'type' => 'Kota', 'postal_code' => '17111'],
            ['id' => 55, 'province_id' => 9, 'name' => 'Bekasi', 'type' => 'Kabupaten', 'postal_code' => '17511'],
            ['id' => 78, 'province_id' => 9, 'name' => 'Bogor', 'type' => 'Kota', 'postal_code' => '16111'],
            ['id' => 79, 'province_id' => 9, 'name' => 'Bogor', 'type' => 'Kabupaten', 'postal_code' => '16911'],
            ['id' => 115, 'province_id' => 9, 'name' => 'Depok', 'type' => 'Kota', 'postal_code' => '16411'],

            // Jawa Tengah
            ['id' => 399, 'province_id' => 10, 'name' => 'Semarang', 'type' => 'Kota', 'postal_code' => '50111'],
            ['id' => 427, 'province_id' => 10, 'name' => 'Surakarta', 'type' => 'Kota', 'postal_code' => '57111'],
            ['id' => 80, 'province_id' => 10, 'name' => 'Blora', 'type' => 'Kabupaten', 'postal_code' => '58211'],

            // Jawa Timur
            ['id' => 444, 'province_id' => 11, 'name' => 'Surabaya', 'type' => 'Kota', 'postal_code' => '60111'],
            ['id' => 255, 'province_id' => 11, 'name' => 'Malang', 'type' => 'Kota', 'postal_code' => '65111'],
            ['id' => 409, 'province_id' => 11, 'name' => 'Sidoarjo', 'type' => 'Kabupaten', 'postal_code' => '61211'],
        ];

        foreach ($cities as $c) {
            DB::table('cities')->updateOrInsert(['id' => $c['id']], [
                'province_id' => $c['province_id'],
                'name' => $c['name'],
                'type' => $c['type'],
                'postal_code' => $c['postal_code'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('Seeded ' . count($provinces) . ' provinces and ' . count($cities) . ' cities.');
    }
}
