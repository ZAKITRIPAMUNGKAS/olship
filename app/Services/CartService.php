<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartService
{
    public function getCart()
    {
        if (Auth::check()) {
            return Cart::firstOrCreate(['user_id' => Auth::id()]);
        }

        $sessionId = Session::getId();
        return Cart::firstOrCreate(['session_id' => $sessionId]);
    }

    public function addItem(int $productId, int $quantity = 1, ?array $options = null)
    {
        $cart = $this->getCart();
        $product = Product::findOrFail($productId);

        $item = $cart->items()->where('product_id', $productId)->first();
        $requestedQty = $quantity;
        if ($item) {
            $requestedQty += $item->quantity;
        }

        if ($product->stock <= 0) {
            throw new \Exception('Produk ini sedang habis dan tidak dapat dipesan.');
        }

        if ($product->stock < $requestedQty) {
            throw new \Exception('Stok produk tidak mencukupi untuk jumlah yang diminta (Tersedia: ' . $product->stock . ' unit).');
        }

        if ($item) {
            $item->increment('quantity', $quantity);
        } else {
            // Catatan: price_snapshot disimpan sesuai skema database
            $item = $cart->items()->create([
                'product_id' => $productId,
                'quantity'   => $quantity,
                'price' => $product->price,
                'options'    => $options,
            ]);
        }

        return $item;
    }

    public function updateItem(int $itemId, int $quantity)
    {
        $cart = $this->getCart();
        $item = $cart->items()->findOrFail($itemId);

        if ($quantity <= 0) {
            $item->delete();
            return null;
        }

        $item->update(['quantity' => $quantity]);
        return $item;
    }

    public function removeItem(int $itemId)
    {
        $cart = $this->getCart();
        $cart->items()->where('id', $itemId)->delete();
    }

    public function clear()
    {
        $cart = $this->getCart();
        $cart->items()->delete();
    }

    public function getSummary()
    {
        $cart = $this->getCart();
        $items = $cart->items()->with('product')->get();

        $subtotal = $items->sum(fn ($item) => ($item->product->price ?? $item->price) * $item->quantity);
        $weight = $items->sum(fn ($item) => ($item->product->weight ?? 1000) * $item->quantity);

        $discount = 0;
        $coupon = $this->getCoupon();
        
        if ($coupon && $subtotal >= $coupon->min_order_amount) {
            if ($coupon->discount_type === 'percent') {
                $discount = ($subtotal * $coupon->discount_value) / 100;
                if ($coupon->max_discount_amount) {
                    $discount = min($discount, $coupon->max_discount_amount);
                }
            } else {
                $discount = $coupon->discount_value;
            }
        } else if ($coupon) {
            // Remove coupon if min order not met after updates
            $this->removeCoupon();
            $coupon = null;
        }

        return [
            'items'    => $items,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'total'    => max(0, $subtotal - $discount),
            'weight'   => $weight,
            'count'    => $items->sum('quantity'),
            'coupon'   => $coupon,
        ];
    }

    public function applyCoupon(string $code)
    {
        $coupon = \App\Models\Coupon::where('code', $code)->first();

        if (!$coupon || !$coupon->isValid()) {
            throw new \Exception('Kupon tidak valid atau sudah kedaluwarsa.');
        }

        $summary = $this->getSummary();
        if ($summary['subtotal'] < $coupon->min_order_amount) {
            throw new \Exception('Minimal belanja untuk kupon ini adalah Rp ' . number_format($coupon->min_order_amount, 0, ',', '.'));
        }

        Session::put('applied_coupon', $code);
        return $coupon;
    }

    public function getCoupon()
    {
        $code = Session::get('applied_coupon');
        if (!$code) return null;

        $coupon = \App\Models\Coupon::where('code', $code)->first();
        if ($coupon && $coupon->isValid()) {
            return $coupon;
        }

        $this->removeCoupon();
        return null;
    }

    public function removeCoupon()
    {
        Session::forget('applied_coupon');
    }

    /**
     * [FIX BUG 6] Sinkronisasi keranjang Guest ke User saat Login.
     * Menggabungkan item dari session ke database user.
     */
    public function mergeGuestCart(string $sessionId)
    {
        if (!Auth::check()) return;

        $guestCart = Cart::where('session_id', $sessionId)->first();
        if (!$guestCart || !$guestCart->items()->exists()) return;

        $userCart = Cart::firstOrCreate(['user_id' => Auth::id()]);

        foreach ($guestCart->items as $guestItem) {
            $existingItem = $userCart->items()
                ->where('product_id', $guestItem->product_id)
                ->first();

            if ($existingItem) {
                // Update quantity jika produk sudah ada di keranjang user
                $existingItem->increment('quantity', $guestItem->quantity);
            } else {
                // Pindahkan item jika belum ada
                $userCart->items()->create([
                    'product_id' => $guestItem->product_id,
                    'quantity'   => $guestItem->quantity,
                    'price' => $guestItem->price,
                    'options'    => $guestItem->options,
                ]);
            }
        }

        // Hapus keranjang guest setelah di-merge
        $guestCart->delete();
    }
}
