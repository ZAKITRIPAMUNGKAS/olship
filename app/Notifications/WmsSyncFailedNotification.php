<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WmsSyncFailedNotification extends Notification
{
    use Queueable;

    protected $type;
    protected $reference;
    protected $errorMessage;

    public function __construct(string $type, string $reference, string $errorMessage)
    {
        $this->type = $type;
        $this->reference = $reference;
        $this->errorMessage = $errorMessage;
    }

    public function via($notifiable): array
    {
        // Support database notifications so they show up on admin dashboard
        return ['database', 'mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $title = $this->type === 'order_push' 
            ? "Gagal Sinkronisasi Pesanan ke WMS" 
            : "Gagal Sinkronisasi Stok dari WMS";

        return (new MailMessage)
            ->subject("[ALERT] {$title} — Listrindo Jaya Elektrik")
            ->greeting("Halo Admin,")
            ->line("Terjadi kegagalan kritis pada sistem sinkronisasi API antara Olshop dan WMS.")
            ->line("Detail Kegagalan:")
            ->line("- **Tipe**: " . ($this->type === 'order_push' ? 'Push Order (Olshop -> WMS)' : 'Sync Stock (WMS -> Olshop)'))
            ->line("- **Referensi**: {$this->reference}")
            ->line("- **Pesan Error**: {$this->errorMessage}")
            ->action('Lihat Log Kegagalan', url('/admin/failed-sync-logs'))
            ->line('Mohon periksa pemetaan SKU atau status koneksi server secepatnya untuk mencegah ketidakselarasan data.');
    }

    public function toArray($notifiable): array
    {
        return [
            'type'          => $this->type,
            'reference'     => $this->reference,
            'error_message' => $this->errorMessage,
            'message'       => "API Sync Gagal: " . ($this->type === 'order_push' ? "Order #{$this->reference}" : "SKU {$this->reference}") . " - {$this->errorMessage}",
            'url'           => url('/admin/failed-sync-logs'),
        ];
    }
}
