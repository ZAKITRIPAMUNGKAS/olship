@extends('layouts.app')

@section('title', 'Dashboard Seller - LISTRINDO JAYA ELEKTRIK')

@push('styles')
<style>
.seller-stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 40px; }
.seller-header-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
@media (max-width: 1024px) {
    .seller-stats-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 768px) {
    .seller-stats-grid { grid-template-columns: 1fr; }
    .seller-header-row { flex-direction: column; align-items: flex-start; gap: 16px; }
    .seller-container { padding: 20px 0 !important; }
}
</style>
@endpush

@section('content')
<div class="seller-container" style="padding: 40px 0;">
    <div class="seller-header-row">
        <div>
            <h1 style="font-size: 28px; font-weight: 800; color: var(--ink);">Dashboard Seller</h1>
            <p style="color: var(--slate-500);">Kelola toko <span style="font-weight: 700; color: var(--primary);">{{ $store->name }}</span> Anda</p>
        </div>
        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            <a href="{{ route('seller.products.create') }}" class="btn btn-outline"><i class="fas fa-plus"></i> Tambah Produk</a>
            <a href="{{ route('seller.withdrawals.index') }}" class="btn btn-primary"><i class="fas fa-cog"></i> Pengaturan Toko</a>
        </div>
    </div>

    <!-- STATS -->
    <div class="seller-stats-grid">
        <div class="card" style="padding: 24px; border-radius: 16px; border: 1px solid var(--slate-100); background: #fff;">
            <div style="color: var(--slate-500); font-size: 14px; margin-bottom: 8px;">Total Penjualan</div>
            <div style="font-size: 24px; font-weight: 800; color: var(--ink);">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
            <div style="font-size: 12px; color: #4CAF50; margin-top: 5px;"><i class="fas fa-arrow-up"></i> 100% (Baru)</div>
        </div>
        <div class="card" style="padding: 24px; border-radius: 16px; border: 1px solid var(--slate-100); background: #fff;">
            <div style="color: var(--slate-500); font-size: 14px; margin-bottom: 8px;">Pesanan Baru</div>
            <div style="font-size: 24px; font-weight: 800; color: var(--ink);">{{ $stats['total_orders'] }}</div>
            <div style="font-size: 12px; color: var(--slate-400); margin-top: 5px;">Perlu diproses</div>
        </div>
        <div class="card" style="padding: 24px; border-radius: 16px; border: 1px solid var(--slate-100); background: #fff;">
            <div style="color: var(--slate-500); font-size: 14px; margin-bottom: 8px;">Produk Aktif</div>
            <div style="font-size: 24px; font-weight: 800; color: var(--ink);">{{ $stats['total_products'] }}</div>
            <div style="font-size: 12px; color: var(--slate-400); margin-top: 5px;">Dari total {{ $stats['total_products'] }} produk</div>
        </div>
        <div class="card" style="padding: 24px; border-radius: 16px; border: 1px solid var(--slate-100); background: #fff;">
            <div style="color: var(--slate-500); font-size: 14px; margin-bottom: 8px;">Saldo Wallet</div>
            <div style="font-size: 24px; font-weight: 800; color: var(--primary);">Rp {{ number_format($walletBalance, 0, ',', '.') }}</div>
            <div style="font-size: 12px; color: var(--primary); margin-top: 5px; cursor: pointer;" onclick="window.location='{{ route('seller.withdrawals.index') }}'">Tarik Dana <i class="fas fa-chevron-right" style="font-size: 10px;"></i></div>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px;">
        <!-- RECENT ORDERS -->
        <div class="card" style="padding: 24px; border-radius: 16px; border: 1px solid var(--slate-200); background: #fff;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3 style="font-weight: 700;">Pesanan Masuk</h3>
                <a href="{{ route('seller.orders.index') }}" style="color: var(--primary); font-size: 14px; font-weight: 600;">Lihat Semua</a>
            </div>

            @if($recentOrders->isEmpty())
                <div style="text-align: center; padding: 40px 0;">
                    <p style="color: var(--slate-400);">Belum ada pesanan masuk.</p>
                </div>
            @else
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="text-align: left; border-bottom: 2px solid var(--slate-50);">
                            <th style="padding: 12px 0; font-size: 13px; color: var(--slate-400); text-transform: uppercase;">Order ID</th>
                            <th style="padding: 12px 0; font-size: 13px; color: var(--slate-400); text-transform: uppercase;">Produk</th>
                            <th style="padding: 12px 0; font-size: 13px; color: var(--slate-400); text-transform: uppercase;">Total</th>
                            <th style="padding: 12px 0; font-size: 13px; color: var(--slate-400); text-transform: uppercase;">Status</th>
                            <th style="padding: 12px 0; font-size: 13px; color: var(--slate-400); text-transform: uppercase;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentOrders as $order)
                            <tr style="border-bottom: 1px solid var(--slate-50);">
                                <td style="padding: 15px 0; font-weight: 600;">#{{ $order->order_number }}</td>
                                <td style="padding: 15px 0; font-size: 14px;">{{ $order->items->count() }} Produk</td>
                                <td style="padding: 15px 0; font-weight: 700;">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                <td style="padding: 15px 0;">
                                    <span style="padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: 800; background: var(--slate-100); color: var(--slate-600); text-transform: uppercase;">
                                        {{ $order->status }}
                                    </span>
                                </td>
                                <td style="padding: 15px 0;">
                                    <a href="{{ route('seller.orders.show', $order) }}" class="btn btn-ghost" style="padding: 0; color: var(--primary);"><i class="fas fa-eye"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <!-- QUICK TIPS / PERFORMANCE -->
        <div class="card" style="padding: 24px; border-radius: 16px; border: 1px solid var(--slate-200); background: #fff;">
            <h3 style="font-weight: 700; margin-bottom: 20px;">Performa Toko</h3>
            <div style="display: grid; gap: 15px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-size: 14px; color: var(--slate-500);">Kecepatan Respon</span>
                    <span style="font-weight: 700; color: #4CAF50;">100%</span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-size: 14px; color: var(--slate-500);">Produk Sesuai</span>
                    <span style="font-weight: 700; color: #4CAF50;">4.9/5.0</span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-size: 14px; color: var(--slate-500);">Pengiriman Tepat</span>
                    <span style="font-weight: 700; color: #FF9800;">92%</span>
                </div>
            </div>

            <hr style="border: 0; border-top: 1px solid var(--slate-100); margin: 20px 0;">

            <div style="background: var(--slate-50); padding: 15px; border-radius: 12px;">
                <h4 style="font-weight: 700; font-size: 14px; margin-bottom: 10px;"><i class="fas fa-lightbulb" style="color: var(--primary);"></i> Tips Penjualan</h4>
                <p style="font-size: 13px; color: var(--slate-600); line-height: 1.5;">Gunakan fitur Flash Sale untuk meningkatkan traffic toko Anda hingga 3x lipat!</p>
            </div>
        </div>
    </div>
</div>
@endsection
