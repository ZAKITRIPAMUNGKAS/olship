<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(protected CartService $cartService) {}

    public function index()
    {
        $summary = $this->cartService->getSummary();
        return view('storefront.cart.index', $summary);
    }

    public function add(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity'   => ['required', 'integer', 'min:1'],
            'options'    => ['nullable'],
        ]);

        // options dikirim sebagai JSON string dari Alpine.js
        $options = $data['options'] ?? null;
        if (is_string($options) && $options !== '') {
            $options = json_decode($options, true) ?? null;
        }

        try {
            $this->cartService->addItem($data['product_id'], $data['quantity'], $options);
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'error' => $e->getMessage()
                ], 422);
            }
            return back()->with('error', $e->getMessage());
        }

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Produk berhasil ditambahkan ke keranjang',
                'summary' => $this->cartService->getSummary(),
            ]);
        }

        // Beli Langsung: langsung ke checkout
        if ($request->boolean('buy_now')) {
            return redirect()->route('checkout.index')->with('success', 'Produk ditambahkan, silakan selesaikan pesanan.');
        }

        return back()->with('success', 'Produk berhasil ditambahkan ke keranjang');
    }

    public function update(Request $request, int $itemId)
    {
        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:0'],
        ]);

        $this->cartService->updateItem($itemId, $data['quantity']);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Keranjang berhasil diperbarui',
                'summary' => $this->cartService->getSummary(),
            ]);
        }

        return back()->with('success', 'Keranjang berhasil diperbarui');
    }

    public function remove(Request $request, int $itemId)
    {
        $this->cartService->removeItem($itemId);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Produk berhasil dihapus dari keranjang',
                'summary' => $this->cartService->getSummary(),
            ]);
        }

        return back()->with('success', 'Produk berhasil dihapus dari keranjang');
    }
}
