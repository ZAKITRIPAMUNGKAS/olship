<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class RajaOngkirLocationSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Seeding provinces from Raja Ongkir...');

        $provincesResponse = Http::rajaongkir()->get('/province');
        
        if ($provincesResponse->failed()) {
            $this->command->error('Failed to fetch provinces: ' . $provincesResponse->body());
            return;
        }

        $provinces = $provincesResponse->json('rajaongkir.results');

        $provinceData = collect($provinces)->map(fn ($p) => [
            'id' => (int) $p['province_id'],
            'name' => $p['province'],
            'created_at' => now(),
            'updated_at' => now(),
        ])->all();

        DB::table('provinces')->upsert($provinceData, ['id'], ['name', 'updated_at']);

        $this->command->info("Seeded " . count($provinceData) . " provinces");
        $this->command->info('Seeding cities (this takes ~30 seconds)...');

        $citiesResponse = Http::rajaongkir()->get('/city');
        
        if ($citiesResponse->failed()) {
            $this->command->error('Failed to fetch cities: ' . $citiesResponse->body());
            return;
        }

        $cities = $citiesResponse->json('rajaongkir.results');

        $cityData = collect($cities)->map(fn ($c) => [
            'id' => (int) $c['city_id'],
            'province_id' => (int) $c['province_id'],
            'name' => $c['city_name'],
            'type' => $c['type'],
            'postal_code' => $c['postal_code'] ?? null,
            'created_at' => now(),
            'updated_at' => now(),
        ])->all();

        // Chunked upsert untuk hindari "max placeholder" error
        foreach (array_chunk($cityData, 200) as $chunk) {
            DB::table('cities')->upsert($chunk, ['id'], ['province_id', 'name', 'type', 'postal_code', 'updated_at']);
        }

        $this->command->info("Seeded " . count($cityData) . " cities");
    }
}
