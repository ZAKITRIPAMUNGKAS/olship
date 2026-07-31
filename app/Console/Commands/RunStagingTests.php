<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Product;
use App\Models\Order;
use App\Models\FailedSyncLog;
use App\Jobs\PushOrderToWms;

class RunStagingTests extends Command
{
    protected $signature = 'integration:test-staging {--wms-url=http://localhost:8000 : The local/staging URL of the WMS} {--olshop-url=http://localhost:8000 : The local/staging URL of the Olshop}';
    protected $description = 'Run integration tests TC-07, TC-06, TC-04, TC-03, TC-01, TC-05 systematically';

    public function handle()
    {
        $wmsUrl = rtrim($this->option('wms-url'), '/');
        $olshopUrl = rtrim($this->option('olshop-url'), '/');
        
        $this->info("==================================================");
        $this->info("  STARTING INTEGRATION TESTS (OLSHOP <-> WMS)     ");
        $this->info("==================================================");
        $this->line("WMS URL   : " . $wmsUrl);
        $this->line("Olshop URL: " . $olshopUrl);
        $this->info("==================================================");

        // Fetch tokens from config
        $wmsToken = config('services.api.wms_token');
        $olshopToken = config('services.api.olshop_token');

        // --- TC-07: Invalid Token Test ---
        $this->warn("\n[TC-07] Testing Token Validation (Unauthorized)...");
        $response = Http::acceptJson()->post($wmsUrl . '/api/v1/orders/receive', []);
        if ($response->status() === 401) {
            $this->info("✓ Success: Request without token returned 401 Unauthorized.");
        } else {
            $this->error("✗ Failed: Expected 401, got " . $response->status());
        }

        $response = Http::acceptJson()->withToken('wrong_token')->post($wmsUrl . '/api/v1/orders/receive', []);
        if ($response->status() === 401) {
            $this->info("✓ Success: Request with wrong token returned 401 Unauthorized.");
        } else {
            $this->error("✗ Failed: Expected 401, got " . $response->status());
        }

        // --- TC-04: Timestamp Validation (Stale Data) ---
        $this->warn("\n[TC-04] Testing Timestamp Validation (Stale Data)...");
        // Get a product
        $product = Product::first();
        if ($product) {
            $originalStock = $product->stock;
            $product->update(['last_stock_sync_at' => now()->toDateTimeString()]);
            
            // Send request with calculated_at in the past
            $staleTime = now()->subHour()->format('Y-m-d\TH:i:s\Z');
            $this->line("Sending stale stock update for product [{$product->sku}] with calculated_at: {$staleTime}...");
            $response = Http::acceptJson()->withToken($wmsToken)->post($olshopUrl . '/api/v1/products/sync-stock', [
                'kode_barang'   => $product->sku,
                'total_stock'   => $originalStock + 10,
                'calculated_at' => $staleTime
            ]);
            
            $product->refresh();
            if ($product->stock === $originalStock && (str_contains($response->body(), 'Skipped') || $response->status() === 200)) {
                $this->info("✓ Success: Stale data skipped correctly. Stock remained: " . $product->stock);
            } else {
                $this->error("✗ Failed: Stale data was not skipped. Response Status: " . $response->status() . " Body: " . $response->body() . " Stock changed to: " . $product->stock);
            }
        } else {
            $this->error("✗ Skipped: No product available in Olshop database for TC-04.");
        }

        // --- TC-03: Order Idempotency ---
        $this->warn("\n[TC-03] Testing Order Idempotency (WMS)...");
        $orderNumber = 'ORD-TEST-IDEM-' . rand(1000, 9999);
        $payload = [
            'order_number'  => $orderNumber,
            'tanggal'       => date('Y-m-d'),
            'customer'      => [
                'name'    => 'Test Idempotency',
                'email'   => 'idem@test.com',
                'phone'   => '0812345678',
                'address' => 'Test Address'
            ],
            'courier_name'  => 'JNE',
            'total_payment' => 100000.00,
            'items'         => [
                [
                    'sku'      => $product ? $product->sku : 'PRD-001',
                    'quantity' => 1,
                    'price'    => 100000.00
                ]
            ]
        ];

        $this->line("Sending order first time...");
        $res1 = Http::acceptJson()->withToken($olshopToken)->post($wmsUrl . '/api/v1/orders/receive', $payload);
        $this->line("Status 1: " . $res1->status() . " | DO: " . ($res1->json('do_number') ?? 'N/A'));

        $this->line("Sending order second time...");
        $res2 = Http::acceptJson()->withToken($olshopToken)->post($wmsUrl . '/api/v1/orders/receive', $payload);
        $this->line("Status 2: " . $res2->status() . " | DO: " . ($res2->json('do_number') ?? 'N/A'));

        if ($res1->status() === 201 && $res2->status() === 200 && $res1->json('do_number') === $res2->json('do_number')) {
            $this->info("✓ Success: Order is idempotent. Returned 201 first and 200 second with identical DO.");
        } else {
            $this->error("✗ Failed: Idempotency check failed. Status 1: " . $res1->status() . " Body 1: " . $res1->body() . " | Status 2: " . $res2->status() . " Body 2: " . $res2->body());
        }

        // --- TC-01: Rollback for Invalid SKU ---
        $this->warn("\n[TC-01] Testing SKU Mismatch Rollback...");
        $invalidPayload = $payload;
        $invalidPayload['order_number'] = 'ORD-TEST-ROLL-' . rand(1000, 9999);
        $invalidPayload['items'][] = [
            'sku'      => 'INVALID-SKU-999',
            'quantity' => 1,
            'price'    => 5000.00
        ];

        $response = Http::acceptJson()->withToken($olshopToken)->post($wmsUrl . '/api/v1/orders/receive', $invalidPayload);
        if ($response->status() === 422 && str_contains($response->body(), 'invalid_skus')) {
            $this->info("✓ Success: WMS rejected order with 422 and listed invalid SKUs.");
        } else {
            $this->error("✗ Failed: Expected 422, got: " . $response->status() . " - " . $response->body());
        }

        // --- TC-06: Rate Limiting Test ---
        $this->warn("\n[TC-06] Testing Rate Limiting (Throttle 60,1)...");
        $this->line("Sending 65 requests rapidly to WMS to trigger throttle...");
        $throttled = false;
        $statusCodes = [];
        for ($i = 1; $i <= 65; $i++) {
            $response = Http::acceptJson()->withToken($olshopToken)->post($wmsUrl . '/api/v1/orders/receive', [
                'order_number' => 'TEST-THROTTLE-' . $i
            ]);
            $statusCodes[$response->status()] = ($statusCodes[$response->status()] ?? 0) + 1;
            if ($response->status() === 429) {
                $throttled = true;
                $this->info("✓ Success: Request #{$i} was throttled (429 Too Many Requests).");
                break;
            }
        }
        if (!$throttled) {
            $this->error("✗ Failed: Sent 65 requests but none were throttled (429). Status codes received: " . json_encode($statusCodes));
        }

        $this->info("\n==================================================");
        $this->info("  INTEGRATION TESTS COMPLETED                     ");
        $this->info("==================================================");
        return 0;
    }
}
