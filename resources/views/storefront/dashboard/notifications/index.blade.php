@extends('storefront.dashboard.layout')

@section('title', 'Pusat Notifikasi - Listrindo Jaya')

@section('dashboard-content')
<div class="db-card animate-fade-in">
    <div class="db-card-header">
        <h2 class="db-card-title">Pusat Notifikasi</h2>
        <p class="db-card-subtitle">Semua riwayat pembaruan status transaksi dan aktivitas akun Anda.</p>
    </div>

    <div class="notification-list" style="display:flex; flex-direction:column; gap:12px;">
        @forelse($notifications as $notification)
            <div style="padding: 20px; border: 1px solid var(--line); border-radius: 16px; display: flex; gap: 16px; align-items: flex-start; transition: all 0.2s;
                {{ $notification->read_at ? 'background: var(--surface); opacity: 0.75;' : 'background: var(--blue-lt); border-color: rgba(2, 92, 202, 0.15);' }}">
                <div style="width: 40px; height: 40px; border-radius: 50%; background: #fff; display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: 0 4px 10px rgba(0,0,0,0.03); border:1px solid var(--line); color: var(--blue);">
                    <i class="fas fa-bell"></i>
                </div>
                <div style="flex: 1; min-width: 0;">
                    <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 6px; flex-wrap: wrap; gap: 6px;">
                        <span style="font-weight: 700; font-size: 14px; color: var(--ink);">Pembaruan Pesanan</span>
                        <span style="font-size: 11.5px; color: var(--ink-3);">{{ $notification->created_at->diffForHumans() }}</span>
                    </div>
                    <p style="font-size: 13.5px; color: var(--ink-2); line-height: 1.5; margin-bottom: 12px;">{{ $notification->data['message'] }}</p>
                    
                    @if(isset($notification->data['url']))
                        <a href="{{ $notification->data['url'] }}" class="db-btn db-btn-secondary" style="height: 30px; font-size: 11.5px; padding: 0 14px; border-radius: 8px;">Lihat Detail</a>
                    @endif
                </div>
            </div>
        @empty
            <div style="text-align: center; padding: 60px 20px; display:flex; flex-direction:column; align-items:center; gap:16px;">
                <div style="width: 80px; height: 80px; border-radius: 50%; background: #f1f5f9; display: flex; align-items: center; justify-content: center; color: var(--ink-3); font-size: 32px;">
                    <i class="far fa-bell-slash"></i>
                </div>
                <div>
                    <h3 style="font-weight:700; color:var(--ink); font-size:16px;">Tidak Ada Notifikasi</h3>
                    <p style="color: var(--ink-3); font-size: 13px; margin-top: 4px;">Belum ada notifikasi baru untuk Anda saat ini.</p>
                </div>
            </div>
        @endforelse
    </div>

    <div style="margin-top: 24px; display: flex; justify-content: center;">
        {{ $notifications->links() }}
    </div>
</div>
@endsection
