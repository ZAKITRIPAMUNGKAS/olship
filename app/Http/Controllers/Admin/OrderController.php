<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::with(['user', 'items'])
            ->when($request->status && $request->status !== 'all', fn($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.product', 'items.variant']);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $data = $request->validate([
            'status'          => 'required|in:pending,processing,shipped,completed,cancelled',
            'tracking_number' => 'nullable|string|max:100',
        ]);

        $order->update(array_filter($data));

        // Notify User
        if ($order->user) {
            $order->user->notify(new \App\Notifications\OrderStatusNotification($order, $data['status']));
        }

        return back()->with('success', 'Status order diperbarui.');
    }

    public function invoice(Order $order)
    {
        return view('admin.orders.invoice', compact('order'));
    }

    public function export()
    {
        $orders = Order::with('user')->get();
        $csv = "Order Number,Customer,Total,Status,Tanggal\n";
        foreach ($orders as $o) {
            $csv .= "{$o->order_number},{$o->user->name},{$o->total_amount},{$o->status},{$o->created_at->format('Y-m-d')}\n";
        }
        return response($csv, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="orders_'.now()->format('Y-m-d').'.csv"',
        ]);
    }

    public function refund(Order $order, Request $request)
    {
        if ($order->payment_status !== 'paid') {
            return back()->with('error', 'Hanya pesanan yang sudah dibayar yang dapat di-refund.');
        }

        try {
            $paymentService = app(\App\Services\PaymentService::class);
            $reason = $request->reason ?? 'Refund oleh Admin';
            
            // Panggil API Midtrans Refund
            $paymentService->refund($order, $reason);

            DB::transaction(function () use ($order, $reason) {
                $order->update([
                    'status' => 'cancelled',
                    'payment_status' => 'refunded',
                    'cancelled_at' => now(),
                    'cancellation_reason' => $reason
                ]);

                // Restore stock
                $orderService = app(\App\Services\OrderService::class);
                $orderService->restoreStock($order);
            });

            return back()->with('success', 'Pesanan berhasil di-refund di Midtrans & sistem lokal, stok telah dikembalikan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses refund di Midtrans: ' . $e->getMessage());
        }
    }
}
