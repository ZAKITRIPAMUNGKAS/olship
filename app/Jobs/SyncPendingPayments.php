<?php

namespace App\Jobs;

use App\Models\Order;
use App\Services\PaymentService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncPendingPayments implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(PaymentService $paymentService): void
    {
        // Get orders that are pending and older than 1 hour (or whatever threshold)
        $orders = Order::where('status', 'pending')
            ->where('payment_status', 'unpaid')
            ->where('created_at', '<', now()->subHours(1))
            ->get();

        foreach ($orders as $order) {
            $paymentService->syncPaymentStatus($order);
        }
    }
}
