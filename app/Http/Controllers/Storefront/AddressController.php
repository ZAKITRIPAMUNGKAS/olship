<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\UserAddress;
use App\Services\ShippingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AddressController extends Controller
{
    public function __construct(protected ShippingService $shippingService) {}

    public function index()
    {
        $addresses = Auth::user()->addresses()->with(['province', 'city'])->latest()->get();
        return view('storefront.dashboard.addresses.index', compact('addresses'));
    }

    public function create()
    {
        $provinces = $this->shippingService->getProvinces();
        return view('storefront.dashboard.addresses.create', compact('provinces'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'label'           => 'required|string|max:50',
            'recipient_name'  => 'required|string|max:100',
            'phone'           => 'required|string|max:20',
            'province_id'     => 'required|integer',
            'city_id'         => 'required|integer',
            'postal_code'     => 'required|string|max:10',
            'address_detail'  => 'required|string',
            'notes'           => 'nullable|string',
            'is_default'      => 'nullable|boolean',
        ]);

        $data['user_id'] = Auth::id();
        $data['is_default'] = $request->has('is_default');

        $address = DB::transaction(function () use ($data) {
            if ($data['is_default']) {
                UserAddress::where('user_id', Auth::id())->update(['is_default' => false]);
            }

            // If this is the first address, make it default anyway
            if (UserAddress::where('user_id', Auth::id())->count() === 0) {
                $data['is_default'] = true;
            }

            return UserAddress::create($data);
        });

        if (request()->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Alamat berhasil ditambahkan.',
                'address' => $address->load(['province', 'city'])
            ]);
        }

        return redirect()->route('dashboard.addresses.index')->with('success', 'Alamat berhasil ditambahkan.');
    }

    public function edit(UserAddress $address)
    {
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        $provinces = $this->shippingService->getProvinces();
        $cities = $this->shippingService->getCities($address->province_id);

        return view('storefront.dashboard.addresses.edit', compact('address', 'provinces', 'cities'));
    }

    public function update(Request $request, UserAddress $address)
    {
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        $data = $request->validate([
            'label'           => 'required|string|max:50',
            'recipient_name'  => 'required|string|max:100',
            'phone'           => 'required|string|max:20',
            'province_id'     => 'required|integer',
            'city_id'         => 'required|integer',
            'postal_code'     => 'required|string|max:10',
            'address_detail'  => 'required|string',
            'notes'           => 'nullable|string',
            'is_default'      => 'nullable|boolean',
        ]);

        $data['is_default'] = $request->has('is_default');

        DB::transaction(function () use ($address, $data) {
            if ($data['is_default']) {
                UserAddress::where('user_id', Auth::id())
                    ->where('id', '!=', $address->id)
                    ->update(['is_default' => false]);
            }

            $address->update($data);
        });

        return redirect()->route('dashboard.addresses.index')->with('success', 'Alamat berhasil diperbarui.');
    }

    public function destroy(UserAddress $address)
    {
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        if ($address->is_default) {
            return back()->with('error', 'Alamat utama tidak dapat dihapus. Silakan set alamat lain sebagai utama terlebih dahulu.');
        }

        $address->delete();

        return back()->with('success', 'Alamat berhasil dihapus.');
    }

    public function setDefault(UserAddress $address)
    {
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        DB::transaction(function () use ($address) {
            UserAddress::where('user_id', Auth::id())->update(['is_default' => false]);
            $address->update(['is_default' => true]);
        });

        return back()->with('success', 'Alamat utama berhasil diubah.');
    }
}
