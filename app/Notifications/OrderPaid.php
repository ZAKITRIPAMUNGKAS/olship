<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderPaid extends Notification implements ShouldQueue
{
    use Queueable;

    protected $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Pembayaran Berhasil - #' . $this->order->order_number)
            ->greeting('Pembayaran Diterima!')
            ->line('Terima kasih, ' . $notifiable->name . '. Pembayaran untuk pesanan #' . $this->order->order_number . ' telah kami terima.')
            ->line('Pesanan Anda kini sedang dalam proses penyiapan oleh penjual.')
            ->action('Pantau Status Pesanan', route('dashboard.orders.show', $this->order->order_number))
            ->line('Kami akan mengabari Anda kembali saat pesanan telah dikirim.');
    }
}
