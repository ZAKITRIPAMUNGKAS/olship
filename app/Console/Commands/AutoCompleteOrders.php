<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Notifications\OrderDelivered;
use Illuminate\Console\Command;

class AutoCompleteOrders extends Command
{
    protected $signature = 'orders:auto-complete';
    protected $description = 'Selesaikan pesanan yang sudah dikirim lebih dari 7 hari secara otomatis';

    public function handle()
    {
        $days = 7;
        $orders = Order::where('status', 'shipped')
            ->where('shipped_at', '<=', now()->subDays($days))
            ->get();

        $this->info("Menemukan {$orders->count()} pesanan untuk diselesaikan otomatis.");

        foreach ($orders as $order) {
            $order->update(['status' => 'delivered']);
            $order->user->notify(new OrderDelivered($order));
            $this->info("Pesanan #{$order->order_number} telah diselesaikan.");
        }

        $this->info("Proses selesai.");
    }
}
