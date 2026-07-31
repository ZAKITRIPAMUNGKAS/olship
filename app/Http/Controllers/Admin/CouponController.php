<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::latest()->paginate(20);
        return view('admin.coupons.index', compact('coupons'));
    }

    public function create()
    {
        return view('admin.coupons.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code'           => 'required|string|uppercase|unique:coupons|max:30',
            'discount_type'  => 'required|in:percent,fixed',
            'discount_value' => 'required|numeric|min:0',
            'min_purchase'   => 'nullable|numeric|min:0',
            'max_discount'   => 'nullable|numeric|min:0',
            'usage_limit'    => 'nullable|integer|min:1',
            'expires_at'     => 'nullable|date|after:now',
            'is_active'      => 'boolean',
            'description'    => 'nullable|string|max:500',
        ]);

        $data['code']      = strtoupper($data['code']);
        $data['is_active'] = $request->boolean('is_active', true);

        Coupon::create($data);
        return redirect()->route('admin.coupons.index')->with('success', 'Kupon berhasil dibuat.');
    }

    public function show(Coupon $coupon)
    {
        return view('admin.coupons.show', compact('coupon'));
    }

    public function edit(Coupon $coupon)
    {
        return view('admin.coupons.edit', compact('coupon'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        $data = $request->validate([
            'code'           => 'required|string|max:30|unique:coupons,code,' . $coupon->id,
            'discount_type'  => 'required|in:percent,fixed',
            'discount_value' => 'required|numeric|min:0',
            'min_purchase'   => 'nullable|numeric|min:0',
            'max_discount'   => 'nullable|numeric|min:0',
            'usage_limit'    => 'nullable|integer|min:1',
            'expires_at'     => 'nullable|date',
            'is_active'      => 'boolean',
            'description'    => 'nullable|string|max:500',
        ]);

        $data['code']      = strtoupper($data['code']);
        $data['is_active'] = $request->boolean('is_active');
        $coupon->update($data);

        return redirect()->route('admin.coupons.index')->with('success', 'Kupon diperbarui.');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return redirect()->route('admin.coupons.index')->with('success', 'Kupon dihapus.');
    }

    public function toggle(Coupon $coupon)
    {
        $coupon->update(['is_active' => !$coupon->is_active]);
        return back()->with('success', 'Status kupon diperbarui.');
    }
}
