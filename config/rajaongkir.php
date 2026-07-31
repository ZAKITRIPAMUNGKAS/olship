<?php

return [
    'api_key'   => env('RAJAONGKIR_API_KEY'),
    'base_url'  => env('RAJAONGKIR_BASE_URL', 'https://api.rajaongkir.com/starter'),
    'tier'      => env('RAJAONGKIR_TIER', 'starter'),
    'origin'    => (int) env('RAJAONGKIR_ORIGIN_CITY_ID', 152),
    'couriers'  => explode(',', env('RAJAONGKIR_COURIERS', 'jne,pos,tiki')),

    'cache' => [
        'provinces_ttl' => 60 * 60 * 24 * 30, // 30 hari
        'cities_ttl'    => 60 * 60 * 24 * 30, // 30 hari
        'cost_ttl'      => 60 * 60 * 6,       // 6 jam
    ],
];
