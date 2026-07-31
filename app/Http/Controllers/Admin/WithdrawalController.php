<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WithdrawalController extends Controller
{
    public function index(Request $request)
    {
        // If a Withdrawal model exists, use it; otherwise return a placeholder view
        if (class_exists(\App\Models\Withdrawal::class)) {
            $withdrawals = \App\Models\Withdrawal::with('store.seller')
                ->when($request->status && $request->status !== 'all',
                    fn($q) => $q->where('status', $request->status))
                ->latest()
                ->paginate(20);
        } else {
            $withdrawals = collect();
        }

        return view('admin.withdrawals.index', compact('withdrawals'));
    }

    public function approve(Request $request, $withdrawal)
    {
        if (class_exists(\App\Models\Withdrawal::class)) {
            $w = \App\Models\Withdrawal::findOrFail($withdrawal);
            $w->update([
                'status'      => 'approved',
                'approved_at' => now(),
                'note'        => $request->input('note'),
            ]);
        }
        return back()->with('success', 'Penarikan disetujui.');
    }

    public function reject(Request $request, $withdrawal)
    {
        if (class_exists(\App\Models\Withdrawal::class)) {
            $w = \App\Models\Withdrawal::findOrFail($withdrawal);
            $w->update([
                'status'      => 'rejected',
                'rejected_at' => now(),
                'note'        => $request->input('note'),
            ]);
        }
        return back()->with('success', 'Penarikan ditolak.');
    }
}
