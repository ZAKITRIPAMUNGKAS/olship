<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function __construct(protected PaymentService $paymentService) {}

    public function show(Order $order)
    {
        // Ensure user owns the order
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        // If already paid, redirect to finish
        if ($order->payment_status === 'paid') {
            return redirect()->route('dashboard.orders.show', $order->order_number);
        }

        try {
            $snapToken = $this->paymentService->getSnapToken($order);
            
            return view('storefront.payment.show', [
                'order'     => $order,
                'snapToken' => $snapToken,
                'clientKey' => config('midtrans.client_key')
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghubungi server pembayaran: ' . $e->getMessage());
        }
    }

    public function finish(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        return view('storefront.payment.finish', [
            'order' => $order
        ]);
    }
}
