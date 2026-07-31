<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class PaymentCallbackController extends Controller
{
    public function __construct(protected PaymentService $paymentService) {}

    public function handle(Request $request)
    {
        return $this->paymentService->handleWebhook($request);
    }
}
