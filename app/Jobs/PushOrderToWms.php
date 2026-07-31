<?php

namespace App\Jobs;

use App\Models\Order;
use App\Models\FailedSyncLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class PushOrderToWms implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $order;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 20;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     *
     * @var int
     */
    public $backoff = 60;

    /**
     * Create a new job instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Load relationships needed
        $this->order->load(['user', 'items.product']);

        $wmsUrl = config('services.api.wms_url') . '/api/v1/orders/receive';
        $token = config('services.api.olshop_token');

        if (empty($token)) {
            throw new \Exception("PushOrderToWms: API_OLSHOP_TOKEN / services.api.olshop_token is empty or not configured.");
        }

        // Map items payload
        $itemsPayload = [];
        foreach ($this->order->items as $item) {
            if (!$item->product) continue;
            $itemsPayload[] = [
                'sku'      => $item->product->sku,
                'quantity' => (int) $item->quantity,
                'price'    => (float) $item->price,
            ];
        }

        if (empty($itemsPayload)) {
            Log::channel('api_sync')->warning("PushOrderToWms: Order ID {$this->order->id} has no valid items with SKUs.");
            return;
        }

        // Setup customer contact data
        // Check standard address properties or fallbacks
        $customerName = $this->order->user->name ?? 'Pelanggan Toko';
        $customerEmail = $this->order->user->email ?? 'pelanggan@email.com';
        $customerPhone = $this->order->shipping_phone ?? $this->order->user->phone ?? '08123456789';
        $customerAddress = $this->order->shipping_address ?? 'Alamat Pengiriman';

        // HTTP POST call with timeout
        $response = Http::withToken($token)
            ->timeout(10)
            ->retry(3, 100)
            ->post($wmsUrl, [
                'order_number'  => $this->order->order_number,
                'tanggal'       => $this->order->created_at->format('Y-m-d'),
                'customer'      => [
                    'name'    => $customerName,
                    'email'   => $customerEmail,
                    'phone'   => $customerPhone,
                    'address' => $customerAddress,
                ],
                'courier_name'  => $this->order->shipping_courier ?? 'Courier',
                'total_payment' => (float) $this->order->total_amount,
                'items'         => $itemsPayload,
            ]);

        if ($response->status() === 422) {
            $msg = "SKU tidak ditemukan di WMS (422): " . ($response->json('error') ?? $response->body());
            $this->fail(new \Exception($msg));
            return;
        }

        if ($response->failed()) {
            throw new \Exception("PushOrderToWms HTTP Error [Status {$response->status()}]: " . $response->body());
        }

        Log::channel('api_sync')->info("PushOrderToWms Success: Order #{$this->order->order_number} pushed to WMS. DO Response: " . $response->json('do_number'));
    }

    /**
     * Handle a job failure.
     */
    public function failed(Throwable $exception): void
    {
        Log::channel('api_sync')->error("PushOrderToWms Failed: Order #{$this->order->order_number}. Error: " . $exception->getMessage());

        try {
            FailedSyncLog::create([
                'type'          => 'order_push',
                'payload'       => [
                    'order_id'     => $this->order->id,
                    'order_number' => $this->order->order_number,
                    'total_price'  => $this->order->total_price,
                ],
                'error_message' => $exception->getMessage(),
                'attempts'      => $this->attempts(),
            ]);

            // Notify all Admin users
            $admins = \App\Models\User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new \App\Notifications\WmsSyncFailedNotification(
                    'order_push',
                    $this->order->order_number,
                    $exception->getMessage()
                ));
            }

            // Also notify direct config email if configured in .env
            $directEmail = config('services.api.admin_notify_email');
            if ($directEmail) {
                \Illuminate\Support\Facades\Notification::route('mail', $directEmail)
                    ->notify(new \App\Notifications\WmsSyncFailedNotification(
                        'order_push',
                        $this->order->order_number,
                        $exception->getMessage()
                    ));
            }
        } catch (\Exception $e) {
            Log::channel('api_sync')->error("PushOrderToWms: Gagal menyimpan log kegagalan atau mengirim notifikasi: " . $e->getMessage());
        }
    }
}
