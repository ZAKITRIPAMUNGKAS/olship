<?php

namespace App\Http\Controllers;

use App\Services\ShippingService;
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    public function __construct(protected ShippingService $shippingService) {}

    public function provinces()
    {
        return response()->json([
            'provinces' => $this->shippingService->getProvinces(),
        ]);
    }

    public function cities(int $provinceId)
    {
        return response()->json([
            'cities' => $this->shippingService->getCitiesByProvince($provinceId),
        ]);
    }

    public function cost(Request $request)
    {
        $data = $request->validate([
            'destination' => ['required', 'integer', 'exists:cities,id'],
            'weight'      => ['required', 'integer', 'min:1', 'max:30000'],
        ]);

        return response()->json([
            'options' => $this->shippingService->calculateCost($data['destination'], $data['weight']),
        ]);
    }
}
