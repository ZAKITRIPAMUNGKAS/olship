<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderStatusNotification extends Notification
{
    use Queueable;

    protected $order;
    protected $status;

    public function __construct(Order $order, $status)
    {
        $this->order = $order;
        $this->status = $status;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        $messages = [
            'pending' => 'Pesanan Anda #' . $this->order->order_number . ' sedang menunggu pembayaran.',
            'processing' => 'Pesanan Anda #' . $this->order->order_number . ' sedang diproses.',
            'shipped' => 'Pesanan Anda #' . $this->order->order_number . ' telah dikirim.',
            'completed' => 'Pesanan Anda #' . $this->order->order_number . ' telah selesai. Jangan lupa berikan ulasan!',
            'cancelled' => 'Pesanan Anda #' . $this->order->order_number . ' telah dibatalkan.',
        ];

        return [
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'status' => $this->status,
            'message' => $messages[$this->status] ?? 'Status pesanan Anda telah diperbarui.',
            'url' => route('dashboard.orders.show', $this->order->order_number),
        ];
    }
}
