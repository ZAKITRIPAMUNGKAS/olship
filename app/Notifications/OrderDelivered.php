<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderDelivered extends Notification implements ShouldQueue
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
            ->subject('Pesanan Telah Tiba! - #' . $this->order->order_number)
            ->greeting('Pesanan Selesai!')
            ->line('Pesanan Anda #' . $this->order->order_number . ' telah sampai di tujuan.')
            ->line('Kami harap Anda puas dengan produk yang diterima.')
            ->action('Beri Ulasan Produk', route('dashboard.orders.show', $this->order->order_number))
            ->line('Sampai jumpa di pesanan berikutnya!');
    }
}
