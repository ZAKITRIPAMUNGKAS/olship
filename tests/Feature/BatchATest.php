<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use App\Services\OrderService;
use App\Services\PaymentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class BatchATest extends TestCase
{
    use RefreshDatabase;

    public function test_webhook_is_idempotent_and_saves_meta()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $category = Category::create([
            'name' => 'Test Category',
            'slug' => 'test-category',
        ]);

        $product = Product::create([
            'seller_id' => $user->id,
            'category_id' => $category->id,
            'name' => 'Test Product',
            'sku' => 'TEST-SKU',
            'price' => 500,
            'stock' => 10,
            'weight' => 100,
        ]);
        
        $order = Order::create([
            'order_number' => 'TEST-123',
            'user_id' => $user->id,
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'subtotal' => 1000,
            'total_amount' => 1000,
            'shipping_name' => 'Test',
            'shipping_phone' => '123',
            'shipping_address' => 'Test',
            'shipping_city' => 'Test',
            'shipping_province' => 'Test',
            'shipping_postal_code' => '123',
        ]);

        $payment = $order->payment()->create([
            'payment_gateway' => 'midtrans',
            'amount' => 1000,
            'status' => 'pending',
        ]);

        $paymentService = app(PaymentService::class);

        $payload = [
            'order_id' => 'TEST-123',
            'status_code' => '200',
            'gross_amount' => '1000.00',
            'transaction_status' => 'settlement',
            'transaction_id' => 'MID-123',
            'payment_type' => 'credit_card',
            'signature_key' => hash('sha512', 'TEST-1232001000.00' . config('services.midtrans.server_key')),
        ];

        $request = new Request($payload);

        // First call
        $paymentService->handleWebhook($request);

        $payment->refresh();
        $this->assertEquals('success', $payment->status);
        $this->assertNotNull($payment->meta);
        $this->assertEquals('MID-123', $payment->transaction_id);

        // Second call (idempotency check)
        $paymentService->handleWebhook($request);
        
        // No error should occur, and status should still be success
        $this->assertEquals('success', $payment->status);
    }

    public function test_stock_is_restored_on_expire()
    {
        $user = User::create([
            'name' => 'Test User 2',
            'email' => 'test2@example.com',
            'password' => bcrypt('password'),
        ]);

        $category = Category::create([
            'name' => 'Test Category 2',
            'slug' => 'test-category-2',
        ]);

        $product = Product::create([
            'seller_id' => $user->id,
            'category_id' => $category->id,
            'name' => 'Test Product 2',
            'sku' => 'TEST-SKU-2',
            'price' => 500,
            'stock' => 10,
            'weight' => 100,
        ]);
        
        $order = Order::create([
            'order_number' => 'TEST-EXP',
            'user_id' => $user->id,
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'subtotal' => 1000,
            'total_amount' => 1000,
            'shipping_name' => 'Test',
            'shipping_phone' => '123',
            'shipping_address' => 'Test',
            'shipping_city' => 'Test',
            'shipping_province' => 'Test',
            'shipping_postal_code' => '123',
        ]);

        $order->items()->create([
            'product_id' => $product->id,
            'seller_id' => $product->seller_id,
            'quantity' => 2,
            'price' => 500,
            'product_name' => $product->name,
            'product_sku' => $product->sku,
        ]);

        // Simulate stock already deducted
        $product->decrement('stock', 2);
        $this->assertEquals(8, $product->fresh()->stock);

        $payment = $order->payment()->create([
            'payment_gateway' => 'midtrans',
            'amount' => 1000,
            'status' => 'pending',
        ]);

        $paymentService = app(PaymentService::class);

        $payload = [
            'order_id' => 'TEST-EXP',
            'status_code' => '200',
            'gross_amount' => '1000.00',
            'transaction_status' => 'expire',
            'signature_key' => hash('sha512', 'TEST-EXP2001000.00' . config('services.midtrans.server_key')),
        ];

        $request = new Request($payload);
        $paymentService->handleWebhook($request);

        $this->assertEquals('cancelled', $order->fresh()->status);
        $this->assertEquals(10, $product->fresh()->stock);
    }
}
