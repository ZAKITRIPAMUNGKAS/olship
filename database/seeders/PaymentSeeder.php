<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orders = \App\Models\Order::all();

        foreach ($orders as $order) {
            \App\Models\Payment::updateOrCreate(
                ['order_id' => $order->id],
                [
                    'payment_method' => 'bank_transfer',
                    'transaction_id' => 'TRX-' . \Illuminate\Support\Str::random(10),
                    'payment_gateway' => 'manual',
                    'amount' => $order->total_amount,
                    'status' => $order->payment_status === 'paid' ? 'success' : 'pending',
                    'paid_at' => $order->payment_status === 'paid' ? now() : null,
                ]
            );
        }
    }
}
