<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FailedSyncLog;
use App\Models\Order;
use App\Jobs\PushOrderToWms;
use Illuminate\Http\Request;

class FailedSyncLogController extends Controller
{
    public function index()
    {
        $logs = FailedSyncLog::latest()->paginate(15);
        return view('admin.failed-sync-logs.index', compact('logs'));
    }

    public function show(FailedSyncLog $log)
    {
        return view('admin.failed-sync-logs.show', compact('log'));
    }

    public function retry(FailedSyncLog $log)
    {
        if ($log->type === 'order_push') {
            $orderId = $log->payload['order_id'] ?? null;
            if ($orderId) {
                $order = Order::find($orderId);
                if ($order) {
                    PushOrderToWms::dispatch($order);
                    $log->delete();
                    return redirect()->route('admin.failed-sync-logs.index')
                        ->with('success', "Job PushOrderToWms untuk order #{$order->order_number} berhasil dikirim ulang ke antrean.");
                }
            }
        }

        return redirect()->route('admin.failed-sync-logs.index')
            ->with('error', "Gagal memproses ulang log sinkronisasi.");
    }
}
