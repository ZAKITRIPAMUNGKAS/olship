<?php

namespace App\Services;

use App\Models\Order;
use App\Models\UserAddress;
use App\Models\Cart;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderService
{
    /**
     * Create an order from cart items.
     * 
     * @param Cart $cart
     * @param int $addressId
     * @param array $shipping
     * @return Order
     * @throws \Exception
     */
    public function createFromCart(Cart $cart, int $addressId, array $shipping)
    {
        return DB::transaction(function () use ($cart, $addressId, $shipping) {
            $address = UserAddress::findOrFail($addressId);
            $items = $cart->items()->with('product')->get();
            
            if ($items->isEmpty()) {
                throw new \Exception('Keranjang belanja kosong.');
            }

            $cartService = app(CartService::class);
            $summary = $cartService->getSummary();
            
            $subtotal = $summary['subtotal'];
            $discountAmount = $summary['discount'];
            $shippingCost = (int) $shipping['cost'];
            $total = max(0, $subtotal - $discountAmount + $shippingCost);

            $order = Order::create([
                'order_number'      => 'VG-' . strtoupper(Str::random(10)),
                'user_id'           => $cart->user_id,
                'status'            => 'pending',
                'payment_status'    => 'unpaid',
                'shipping_status'   => 'pending',
                
                // Address Snapshot
                'shipping_name'        => $address->recipient_name,
                'shipping_phone'       => $address->phone,
                'shipping_address'     => $address->address_detail,
                'shipping_city'        => $address->city->name,
                'shipping_province'    => $address->province->name,
                'shipping_postal_code' => $address->postal_code,
                'recipient_notes'      => $address->notes,

                // Amounts
                'subtotal'          => $subtotal,
                'discount_amount'   => $discountAmount,
                'coupon_code'       => $summary['coupon']?->code,
                'shipping_cost'     => $shippingCost,
                'total_amount'      => $total,

                // Shipping Info
                'shipping_courier'  => $shipping['courier'],
                'shipping_service'  => $shipping['service'],
                'shipping_etd'      => $shipping['etd'] ?? null,
            ]);

            // Mark coupon as used
            if ($summary['coupon']) {
                $summary['coupon']->increment('used_count');
            }

            foreach ($items as $item) {
                // [FIX BUG 1] Validasi stok akhir sebelum decrement untuk menghindari stok minus
                if ($item->product->stock < $item->quantity) {
                    throw new \Exception("Stok produk '{$item->product->name}' tidak mencukupi (Tersisa: {$item->product->stock}).");
                }

                $order->items()->create([
                    'product_id'    => $item->product_id,
                    'seller_id'     => $item->product->seller_id,
                    'quantity'      => $item->quantity,
                    'price'         => $item->price,
                    'options'       => $item->options,
                    'product_name'  => $item->product->name, // Snapshot
                    'product_sku'   => $item->product->sku,   // Snapshot
                ]);

                // [FIX BUG 1] Aktifkan kembali pengurangan stok produk secara atomik
                $item->product->decrement('stock', $item->quantity);
            }

            // Kosongkan keranjang setelah order berhasil dibuat
            $cart->items()->delete();

            return $order;
        });
    }

    /**
     * Restore product stock when order is cancelled/expired.
     * 
     * @param Order $order
     * @return void
     */
    public function restoreStock(Order $order)
    {
        DB::transaction(function () use ($order) {
            foreach ($order->items as $item) {
                if ($item->product) {
                    $item->product->increment('stock', $item->quantity);
                }
            }
        });
    }
}
