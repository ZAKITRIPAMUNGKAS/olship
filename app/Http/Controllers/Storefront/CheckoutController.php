<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use App\Services\OrderService;
use App\Services\ShippingService;
use App\Models\UserAddress;
use App\Notifications\OrderPlaced;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function __construct(
        protected CartService $cartService,
        protected OrderService $orderService,
        protected ShippingService $shippingService
    ) {}

    public function index()
    {
        $cartSummary = $this->cartService->getSummary();
        
        if ($cartSummary['count'] === 0) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja Anda kosong.');
        }

        $addresses = Auth::user()->addresses()->with(['province', 'city'])->get();
        $defaultAddress = $addresses->where('is_default', true)->first() ?? $addresses->first();

        return view('storefront.checkout.index', [
            'items'          => $cartSummary['items'],
            'subtotal'       => $cartSummary['subtotal'],
            'discount'       => $cartSummary['discount'],
            'total'          => $cartSummary['total'],
            'coupon'         => $cartSummary['coupon'],
            'weight'         => $cartSummary['weight'],
            'addresses'      => $addresses,
            'defaultAddress' => $defaultAddress,
            'provinces'      => $this->shippingService->getProvinces(),
        ]);
    }

    public function applyCoupon(Request $request)
    {
        $request->validate(['code' => 'required|string']);

        try {
            $coupon = $this->cartService->applyCoupon($request->code);
            $summary = $this->cartService->getSummary();

            return response()->json([
                'status'   => 'success',
                'message'  => 'Kupon berhasil diterapkan!',
                'discount' => $summary['discount'],
                'total'    => $summary['total'],
                'coupon'   => [
                    'code' => $coupon->code,
                    'name' => $coupon->name
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function removeCoupon()
    {
        $this->cartService->removeCoupon();
        $summary = $this->cartService->getSummary();

        return response()->json([
            'status'  => 'success',
            'message' => 'Kupon dihapus.',
            'total'   => $summary['total']
        ]);
    }

    public function process(Request $request)
    {
        $data = $request->validate([
            'address_id'      => ['required', 'exists:user_addresses,id'],
            'shipping_courier' => ['required', 'string'],
            'shipping_service' => ['required', 'string'],
            'shipping_cost'    => ['required', 'integer'],
            'shipping_etd'     => ['nullable', 'string'],
        ]);

        // Validate that the address belongs to the user
        $address = UserAddress::where('user_id', Auth::id())->findOrFail($data['address_id']);

        $cart = $this->cartService->getCart();
        $cartSummary = $this->cartService->getSummary();
        $cartItems = $cartSummary['items'];

        // [FIX BUG 1] Pre-check stok sebelum memulai proses pembuatan order
        foreach ($cartItems as $item) {
            if ($item->product->stock < $item->quantity) {
                return back()->with('error', "Stok produk '{$item->product->name}' tidak mencukupi. Tersedia: {$item->product->stock}.");
            }
        }

        // [FIX BUG 2] Validasi harga ongkir di sisi server (Security Check)
        // Kita hitung ulang ongkir berdasarkan destinasi dan berat total
        $validShippingOptions = $this->shippingService->calculateCost(
            $address->city_id, 
            $cartSummary['weight']
        );

        // Cari opsi yang sesuai dengan pilihan user
        $selectedOption = collect($validShippingOptions)->first(function ($option) use ($data) {
            return strtolower($option['courier']) === strtolower($data['shipping_courier']) && 
                   strtolower($option['service']) === strtolower($data['shipping_service']);
        });

        // Jika opsi tidak ditemukan atau harga tidak cocok, batalkan order
        if (!$selectedOption || (int) $selectedOption['cost'] !== (int) $data['shipping_cost']) {
            return back()->with('error', 'Terjadi kesalahan pada perhitungan ongkos kirim. Silakan coba lagi.');
        }
        
        try {
            // Gunakan harga ongkir yang sudah divalidasi dari server
            $order = $this->orderService->createFromCart($cart, $address->id, [
                'courier' => $selectedOption['courier'],
                'service' => $selectedOption['service'],
                'cost'    => $selectedOption['cost'],
                'etd'     => $selectedOption['etd'],
            ]);

            Auth::user()->notify(new OrderPlaced($order));

            return redirect()->route('payment.show', $order)->with('success', 'Pesanan berhasil dibuat!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses pesanan: ' . $e->getMessage());
        }
    }
}
