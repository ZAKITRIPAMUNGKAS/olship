<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderPlaced extends Notification implements ShouldQueue
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
            ->subject('Pesanan Diterima - #' . $this->order->order_number)
            ->greeting('Halo, ' . $notifiable->name . '!')
            ->line('Terima kasih telah berbelanja di LISTRINDO JAYA ELEKTRIK.')
            ->line('Pesanan Anda dengan nomor #' . $this->order->order_number . ' telah kami terima dan sedang menunggu pembayaran.')
            ->line('Total Pembayaran: Rp ' . number_format($this->order->total_amount, 0, ',', '.'))
            ->action('Lihat Detail Pesanan', route('dashboard.orders.show', $this->order->order_number))
            ->line('Segera lakukan pembayaran agar pesanan Anda dapat segera kami proses.');
    }
}
