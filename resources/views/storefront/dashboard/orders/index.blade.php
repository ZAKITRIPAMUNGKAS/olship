@extends('storefront.dashboard.layout')

@section('title', 'Pesanan Saya - LISTRINDO JAYA ELEKTRIK')

@section('dashboard-content')
<div class="db-card animate-fade-in">
    <div class="db-card-header">
        <h2 class="db-card-title">Pesanan Saya</h2>
        <p class="db-card-subtitle">Lacak dan kelola semua transaksi pembelian Anda.</p>
    </div>

    @if($orders->isEmpty())
        <div style="text-align: center; padding: 60px 20px; display:flex; flex-direction:column; align-items:center; gap:16px;">
            <div style="width: 80px; height: 80px; border-radius: 50%; background: #f1f5f9; display: flex; align-items: center; justify-content: center; color: var(--ink-3); font-size: 32px;">
                <i class="fas fa-shopping-bag"></i>
            </div>
            <div>
                <h3 style="font-weight:700; color:var(--ink); font-size:16px;">Belum Ada Pesanan</h3>
                <p style="color: var(--ink-3); font-size:13px; margin-top:4px;">Anda belum memiliki riwayat transaksi pemesanan produk.</p>
            </div>
            <a href="{{ route('home') }}" class="db-btn db-btn-primary" style="margin-top: 8px;">Mulai Belanja</a>
        </div>
    @else
        <div style="display: grid; gap: 16px;">
            @foreach($orders as $order)
                @php
                    $badgeClass = 'db-badge-pending';
                    if ($order->status == 'processing') $badgeClass = 'db-badge-processing';
                    elseif ($order->status == 'shipped') $badgeClass = 'db-badge-shipped';
                    elseif ($order->status == 'completed') $badgeClass = 'db-badge-completed';
                    elseif ($order->status == 'cancelled') $badgeClass = 'db-badge-cancelled';
                    
                    $statusLabel = [
                        'pending' => 'Menunggu Pembayaran',
                        'processing' => 'Diproses',
                        'shipped' => 'Dikirim',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan'
                    ][$order->status] ?? $order->status;
                @endphp
                
                <div class="db-order-card">
                    <div class="db-order-header">
                        <div class="db-order-meta">
                            <span style="font-weight: 700; color: var(--ink);">{{ $order->created_at->format('d M Y') }}</span>
                            <span style="color:var(--line); margin:0 8px;">|</span>
                            <span style="font-family:monospace; font-weight:600;">#{{ $order->order_number }}</span>
                        </div>
                        <span class="db-badge {{ $badgeClass }}">
                            <i class="fas @if($order->status == 'pending') fa-clock @elseif($order->status == 'shipped') fa-truck @elseif($order->status == 'completed') fa-check-circle @elseif($order->status == 'cancelled') fa-times-circle @else fa-sync-alt @endif"></i>
                            {{ $statusLabel }}
                        </span>
                    </div>
                    
                    <div class="db-order-body">
                        <div class="db-order-img">
                            @if($order->items->first()?->product)
                                <img src="{{ $order->items->first()->product->image_url }}" alt="{{ $order->items->first()->product->name }}" style="width: 100%; height: 100%; object-fit: contain; padding: 4px;">
                            @else
                                <i class="fas fa-box" style="font-size: 20px; color: var(--neutral-text-muted);"></i>
                            @endif
                        </div>
                        
                        <div class="db-order-info">
                            <div class="db-order-title" style="font-size:13.5px; color:var(--neutral-text-sub); margin-bottom: 2px;">
                                {{ $order->items->first()?->product_name ?? 'Produk Tanpa Nama' }}
                                @if($order->items->count() > 1)
                                    <span style="font-weight:500; color:var(--neutral-text-muted);"> dan {{ $order->items->count() - 1 }} produk lainnya</span>
                                @endif
                            </div>
                            <div style="font-size: 11.5px; color: var(--neutral-text-muted); margin-bottom: 6px;">Total {{ $order->items->sum('quantity') }} barang</div>
                            
                            <div style="display:flex; flex-direction:column; gap:2px;">
                                <span style="font-size: 11px; color: var(--neutral-text-muted); font-weight:600;">Total Tagihan</span>
                                <span class="db-order-price">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                            </div>
                        </div>
                        
                        <div class="db-order-actions">
                            <a href="{{ route('dashboard.orders.show', $order->order_number) }}" class="db-btn db-btn-secondary">Detail Transaksi</a>
                            @if($order->payment_status == 'unpaid' && $order->status !== 'cancelled')
                                <a href="{{ route('payment.show', $order) }}" class="db-btn db-btn-primary">Bayar Sekarang</a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div style="margin-top: 24px; display: flex; justify-content: center;">
            {{ $orders->links() }}
        </div>
    @endif
</div>
@endsection
