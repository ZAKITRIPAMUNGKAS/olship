<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use App\Models\Province;
use App\Models\City;

class ShippingService
{
    public function getProvinces(): array
    {
        return Cache::remember('shipping.provinces', config('rajaongkir.cache.provinces_ttl'), function () {
            return Province::orderBy('name')->get(['id', 'name'])->toArray();
        });
    }

    public function getCitiesByProvince(int $provinceId): array
    {
        $key = "shipping.cities.{$provinceId}";
        return Cache::remember($key, config('rajaongkir.cache.cities_ttl'), function () use ($provinceId) {
            return City::where('province_id', $provinceId)
                ->orderBy('name')
                ->get(['id', 'name', 'type', 'postal_code'])
                ->toArray();
        });
    }

    public function getCities(int $provinceId): array
    {
        return $this->getCitiesByProvince($provinceId);
    }

    /**
     * @return array<array{courier:string, service:string, description:string, cost:int, etd:string}>
     */
    public function calculateCost(int $destinationCityId, int $weightInGram, ?int $originCityId = null): array
    {
        $origin = $originCityId ?? config('rajaongkir.origin');
        $couriers = config('rajaongkir.couriers');

        $cacheKey = sprintf('shipping.cost.%d.%d.%d.%s', $origin, $destinationCityId, $weightInGram, implode(',', $couriers));

        return Cache::remember($cacheKey, config('rajaongkir.cache.cost_ttl'), function () use ($origin, $destinationCityId, $weightInGram, $couriers) {
            $results = [];

            // If API Key is missing or default 'xxxx', return dummy data for development
            if (config('rajaongkir.api_key') === 'xxxx' || empty(config('rajaongkir.api_key'))) {
                return $this->getDummyCosts();
            }

            foreach ($couriers as $courier) {
                $response = Http::rajaongkir()->asForm()->post('/cost', [
                    'origin'      => $origin,
                    'destination' => $destinationCityId,
                    'weight'      => $weightInGram,
                    'courier'     => $courier,
                ]);

                if ($response->failed()) {
                    \Log::warning("RajaOngkir cost failed for {$courier}", ['response' => $response->body()]);
                    continue;
                }

                $costs = $response->json('rajaongkir.results.0.costs', []);

                foreach ($costs as $service) {
                    $results[] = [
                        'courier'     => $courier,
                        'service'     => $service['service'],
                        'description' => $service['description'],
                        'cost'        => (int) ($service['cost'][0]['value'] ?? 0),
                        'etd'         => $service['cost'][0]['etd'] ?? '-',
                    ];
                }
            }

            // Sort termurah dulu
            usort($results, fn ($a, $b) => $a['cost'] <=> $b['cost']);

            return $results;
        });
    }

    protected function getDummyCosts(): array
    {
        return [
            ['courier' => 'jne', 'service' => 'REG', 'description' => 'Layanan Reguler', 'cost' => 15000, 'etd' => '2-3'],
            ['courier' => 'jne', 'service' => 'OKE', 'description' => 'Ongkos Kirim Ekonomis', 'cost' => 12000, 'etd' => '4-5'],
            ['courier' => 'pos', 'service' => 'Pos Reguler', 'description' => 'Pos Reguler', 'cost' => 11000, 'etd' => '3-5'],
            ['courier' => 'tiki', 'service' => 'REG', 'description' => 'Regular Service', 'cost' => 14000, 'etd' => '2-3'],
        ];
    }
}
