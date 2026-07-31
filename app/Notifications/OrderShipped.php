<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderShipped extends Notification implements ShouldQueue
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
            ->subject('Pesanan Sedang Dikirim - #' . $this->order->order_number)
            ->greeting('Kabar Gembira!')
            ->line('Pesanan Anda #' . $this->order->order_number . ' telah diserahkan ke kurir dan sedang dalam perjalanan.')
            ->line('Kurir: ' . strtoupper($this->order->courier))
            ->line('Nomor Resi: ' . $this->order->tracking_number)
            ->action('Lacak Pengiriman', route('dashboard.orders.show', $this->order->order_number))
            ->line('Terima kasih telah berbelanja!');
    }
}
