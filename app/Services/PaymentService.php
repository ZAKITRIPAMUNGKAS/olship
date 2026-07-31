<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class PaymentService
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key') ?? config('services.midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production') ?? config('services.midtrans.is_production', false);
        Config::$isSanitized = config('midtrans.is_sanitized') ?? true;
        Config::$is3ds = config('midtrans.is_3ds') ?? true;
    }

    public function getSnapToken(Order $order)
    {
        $params = [
            'transaction_details' => [
                'order_id' => $order->order_number,
                'gross_amount' => (int) $order->total_amount,
            ],
            'customer_details' => [
                'first_name' => $order->shipping_name,
                'email' => $this->sanitizeEmail($order->user->email ?? 'guest@example.com', $order->user_id),
                'phone' => $order->shipping_phone,
            ],
            'item_details' => $order->items->map(function ($item) {
                return [
                    'id' => $item->product_id,
                    'price' => (int) $item->price,
                    'quantity' => (int) $item->quantity,
                    'name' => substr($item->product_name, 0, 50),
                ];
            })->toArray(),
        ];

        // Add shipping cost as an item
        $params['item_details'][] = [
            'id' => 'shipping',
            'price' => (int) $order->shipping_cost,
            'quantity' => 1,
            'name' => 'Shipping Cost',
        ];

        // Add discount if exists
        if ($order->discount_amount > 0) {
            $params['item_details'][] = [
                'id' => 'discount',
                'price' => -(int) $order->discount_amount,
                'quantity' => 1,
                'name' => 'Discount',
            ];
        }

        return Snap::getSnapToken($params);
    }

    /**
     * Handle webhook request from Midtrans.
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handleWebhook(Request $request)
    {
        $serverKey = config('midtrans.server_key') ?? config('services.midtrans.server_key');
        $hashed = hash('sha512', $request->order_id . $request->status_code . $request->gross_amount . $serverKey);
        
        if ($hashed !== $request->signature_key) {
            Log::warning("Midtrans Webhook: Invalid signature for order " . $request->order_id);
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $order = Order::where('order_number', $request->order_id)->first();
        if (!$order) {
            Log::warning("Midtrans Webhook: Order not found: " . $request->order_id);
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Idempotency: If already paid, do not process again
        if ($order->payment_status === 'paid') {
            return response()->json(['message' => 'Order already paid'], 200);
        }

        $transaction = $request->transaction_status;
        $type = $request->payment_type;
        $fraud = $request->fraud_status;

        DB::transaction(function () use ($order, $transaction, $type, $fraud, $request) {
            $isPaid = false;
            $orderPaymentStatus = 'unpaid';
            $paymentLogStatus = 'pending';

            if ($transaction == 'capture') {
                if ($type == 'credit_card') {
                    if ($fraud == 'challenge') {
                        $orderPaymentStatus = 'unpaid';
                        $paymentLogStatus = 'pending';
                    } else {
                        $isPaid = true;
                        $orderPaymentStatus = 'paid';
                        $paymentLogStatus = 'success';
                    }
                }
            } else if ($transaction == 'settlement') {
                $isPaid = true;
                $orderPaymentStatus = 'paid';
                $paymentLogStatus = 'success';
            } else if ($transaction == 'pending') {
                $orderPaymentStatus = 'unpaid';
                $paymentLogStatus = 'pending';
            } else if ($transaction == 'deny') {
                $orderPaymentStatus = 'failed';
                $paymentLogStatus = 'failed';
            } else if ($transaction == 'expire') {
                $orderPaymentStatus = 'expired';
                $paymentLogStatus = 'expired';
            } else if ($transaction == 'cancel') {
                $orderPaymentStatus = 'failed';
                $paymentLogStatus = 'failed';
            }

            // Update order payment status
            $orderUpdate = [
                'payment_status' => $orderPaymentStatus,
            ];

            if ($isPaid) {
                $orderUpdate['status'] = 'processing';
                $orderUpdate['paid_at'] = now();
                $order->update($orderUpdate);

                // Notify User
                if ($order->user) {
                    $order->user->notify(new \App\Notifications\OrderStatusNotification($order, 'processing'));
                }

                // Dispatch PushOrderToWms Job to sync order to WMS
                \App\Jobs\PushOrderToWms::dispatch($order);
            } else if (in_array($transaction, ['cancel', 'expire', 'deny'])) {
                // Only cancel and restore stock if order is not already cancelled
                if ($order->status !== 'cancelled') {
                    $orderUpdate['status'] = 'cancelled';
                    $orderUpdate['cancelled_at'] = now();
                    $orderUpdate['cancellation_reason'] = 'Midtrans Webhook: ' . $transaction;
                    $order->update($orderUpdate);

                    // Restore stock
                    $orderService = app(OrderService::class);
                    $orderService->restoreStock($order);
                }
            } else {
                $order->update($orderUpdate);
            }

            // Convert request payload to array
            $gatewayResponse = $request->all();

            // Log to payments table
            Payment::updateOrCreate(
                ['order_id' => $order->id],
                [
                    'transaction_id' => $request->transaction_id ?? $request->order_id,
                    'payment_method' => $type,
                    'payment_gateway' => 'midtrans',
                    'amount' => $request->gross_amount,
                    'status' => $paymentLogStatus,
                    'gateway_response' => $gatewayResponse,
                    'meta' => $gatewayResponse,
                    'paid_at' => $isPaid ? now() : null,
                ]
            );
        });

        return response()->json(['message' => 'Notification handled']);
    }

    /**
     * Sync order payment status with Midtrans API.
     * 
     * @param Order $order
     * @return void
     */
    public function syncPaymentStatus(Order $order)
    {
        try {
            $status = Transaction::status($order->order_number);
            
            $transaction = $status->transaction_status;
            $type = $status->payment_type;
            $fraud = $status->fraud_status ?? null;
            $amount = $status->gross_amount ?? $order->total_amount;
            $transactionId = $status->transaction_id ?? null;

            // Convert raw object properties to array for JSON serialization
            $gatewayResponse = (array) $status;

            // If order is already paid in local DB, but sync sees it's still paid, just log and return
            if ($order->payment_status === 'paid') {
                return;
            }

            DB::transaction(function () use ($order, $transaction, $type, $fraud, $amount, $transactionId, $gatewayResponse) {
                $isPaid = false;
                $orderPaymentStatus = 'unpaid';
                $paymentLogStatus = 'pending';

                if ($transaction == 'capture') {
                    if ($type == 'credit_card') {
                        if ($fraud == 'challenge') {
                            $orderPaymentStatus = 'unpaid';
                            $paymentLogStatus = 'pending';
                        } else {
                            $isPaid = true;
                            $orderPaymentStatus = 'paid';
                            $paymentLogStatus = 'success';
                        }
                    }
                } else if ($transaction == 'settlement') {
                    $isPaid = true;
                    $orderPaymentStatus = 'paid';
                    $paymentLogStatus = 'success';
                } else if ($transaction == 'pending') {
                    $orderPaymentStatus = 'unpaid';
                    $paymentLogStatus = 'pending';
                } else if ($transaction == 'deny') {
                    $orderPaymentStatus = 'failed';
                    $paymentLogStatus = 'failed';
                } else if ($transaction == 'expire') {
                    $orderPaymentStatus = 'expired';
                    $paymentLogStatus = 'expired';
                } else if ($transaction == 'cancel') {
                    $orderPaymentStatus = 'failed';
                    $paymentLogStatus = 'failed';
                }

                // Update order payment status
                $orderUpdate = [
                    'payment_status' => $orderPaymentStatus,
                ];

                if ($isPaid) {
                    $orderUpdate['status'] = 'processing';
                    $orderUpdate['paid_at'] = now();
                    $order->update($orderUpdate);

                    // Notify User
                    if ($order->user) {
                        $order->user->notify(new \App\Notifications\OrderStatusNotification($order, 'processing'));
                    }

                    // Dispatch PushOrderToWms Job to sync order to WMS
                    \App\Jobs\PushOrderToWms::dispatch($order);
                } else if (in_array($transaction, ['cancel', 'expire', 'deny'])) {
                    if ($order->status !== 'cancelled') {
                        $orderUpdate['status'] = 'cancelled';
                        $orderUpdate['cancelled_at'] = now();
                        $orderUpdate['cancellation_reason'] = 'Midtrans Sync: ' . $transaction;
                        $order->update($orderUpdate);

                        // Restore stock
                        $orderService = app(OrderService::class);
                        $orderService->restoreStock($order);
                    }
                } else {
                    $order->update($orderUpdate);
                }

                // Log to payments table
                Payment::updateOrCreate(
                    ['order_id' => $order->id],
                    [
                        'transaction_id' => $transactionId ?? $order->order_number,
                        'payment_method' => $type,
                        'payment_gateway' => 'midtrans',
                        'amount' => $amount,
                        'status' => $paymentLogStatus,
                        'gateway_response' => $gatewayResponse,
                        'meta' => $gatewayResponse,
                        'paid_at' => $isPaid ? now() : null,
                    ]
                );
            });

            Log::info("Successfully synced payment status for order: {$order->order_number} to {$transaction}");

        } catch (\Exception $e) {
            // Handle if transaction not found in Midtrans (user never opened payment window)
            // If order is older than 2 hours, cancel it and restore stock
            if (str_contains($e->getMessage(), '404') || str_contains($e->getMessage(), 'not found') || str_contains($e->getMessage(), 'does not exist')) {
                if ($order->created_at < now()->subHours(2) && $order->status !== 'cancelled') {
                    DB::transaction(function () use ($order) {
                        $order->update([
                            'status' => 'cancelled',
                            'payment_status' => 'expired',
                            'cancelled_at' => now(),
                            'cancellation_reason' => 'Expired (No transaction created in Midtrans)',
                        ]);
                        
                        $orderService = app(OrderService::class);
                        $orderService->restoreStock($order);
                    });
                    Log::info("Cancelled idle pending order (no Midtrans transaction created): {$order->order_number}");
                }
            } else {
                Log::error("Error syncing payment status for order {$order->order_number}: " . $e->getMessage());
            }
        }
    }

    /**
     * Refund payment via Midtrans API.
     * 
     * @param Order $order
     * @param string $reason
     * @return object
     */
    public function refund(Order $order, $reason = 'Admin refund')
    {
        $params = [
            'refund_key' => 'ref_' . $order->order_number . '_' . time(),
            'amount' => (int) $order->total_amount,
            'reason' => $reason
        ];

        return Transaction::refund($order->order_number, $params);
    }

    private function sanitizeEmail($email, $userId)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $email;
        }
        if (str_contains($email, '@')) {
            $parts = explode('@', $email);
            if (count($parts) === 2 && !str_contains($parts[1], '.')) {
                $fixed = $email . '.com';
                if (filter_var($fixed, FILTER_VALIDATE_EMAIL)) {
                    return $fixed;
                }
            }
        }
        return 'customer_' . ($userId ?? time()) . '@example.com';
    }
}
