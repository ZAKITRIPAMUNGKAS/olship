@extends('storefront.dashboard.layout')

@section('title', 'Dashboard — LISTRINDO JAYA ELEKTRIK')

@push('styles')
<style>
  .db-hero {
    background: var(--brand-color);
    border-radius: var(--radius-lg);
    padding: var(--space-6) var(--space-8);
    display: flex;
    align-items: center;
    justify-content: space-between;
    overflow: hidden;
    position: relative;
    box-shadow: var(--shadow-sm);
    margin-bottom: var(--space-6);
    color: #fff;
  }
  .db-hero-content {
    position: relative;
    z-index: 1;
  }
  .db-hero-icon {
    width: 80px;
    height: 80px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid rgba(255, 255, 255, 0.2);
    position: relative;
    z-index: 1;
  }
  
  .db-quick-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: var(--space-4);
    margin-top: var(--space-6);
  }
  
  .db-quick-action {
    background: var(--neutral-card);
    border: 1px solid var(--neutral-border);
    border-radius: var(--radius-lg);
    padding: var(--space-4) var(--space-3);
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: var(--space-2);
    text-decoration: none;
    transition: all 0.2s ease;
    box-shadow: var(--shadow-sm);
  }
  .db-quick-action:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
    border-color: var(--brand-color);
  }

  @media (max-width: 1024px) {
    .db-hero {
      padding: var(--space-6) var(--space-4);
    }
  }
  
  @media (max-width: 768px) {
    .db-hero {
      flex-direction: column;
      align-items: stretch;
      gap: var(--space-4);
      text-align: center;
      padding: var(--space-4) var(--space-4);
    }
    .db-hero-icon {
      display: none;
    }
    .db-quick-grid {
      grid-template-columns: repeat(2, 1fr);
      gap: var(--space-3);
    }
    .db-quick-action {
      padding: var(--space-3) var(--space-2);
    }
  }
</style>
@endpush

@section('dashboard-content')

@if(auth()->user()->hasAnyRole(['super_admin', 'admin', 'staff']))
<div style="background: linear-gradient(135deg, #1e293b, #0f172a); color: #fff; border-radius: 12px; padding: 16px 20px; margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between; gap: 16px; border: 1px solid rgba(255,255,255,0.1); box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
  <div style="display: flex; align-items: center; gap: 14px;">
    <div style="width: 42px; height: 42px; border-radius: 10px; background: rgba(37,99,235,0.2); border: 1px solid rgba(37,99,235,0.4); display: flex; align-items: center; justify-content: center; font-size: 18px; color: #60a5fa; flex-shrink: 0;">
      <i class="fas fa-user-shield"></i>
    </div>
    <div>
      <div style="font-weight: 700; font-size: 14px; margin-bottom: 2px;">Anda Terhubung Sebagai {{ strtoupper(auth()->user()->getRoleNames()->first() ?? 'ADMIN') }}</div>
      <div style="font-size: 12px; color: #94a3b8;">Halaman ini adalah Dashboard Pembeli. Untuk mengelola produk, pesanan, dan laporan toko, masuk ke Panel Admin.</div>
    </div>
  </div>
  <a href="{{ route('admin.dashboard') }}" class="db-btn" style="background: #2563eb; color: #fff; font-weight: 700; font-size: 13px; white-space: nowrap; flex-shrink: 0;">
    <i class="fas fa-tachometer-alt"></i> Buka Panel Admin
  </a>
</div>
@endif

{{-- HERO --}}
<div class="db-hero animate-fade-in">
  <div style="position:absolute;inset:0;background:radial-gradient(circle at 85% 30%,rgba(255,255,255,.05) 0%,transparent 55%);pointer-events:none;"></div>
  <div class="db-hero-content">
    <div style="display:inline-flex;align-items:center;gap:var(--space-1);background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.2);border-radius:20px;padding:3px 12px;font-size:11px;font-weight:600;color:#fff;margin-bottom:var(--space-3);">
      <i class="fas fa-sun"></i> Selamat datang kembali
    </div>
    <h1 style="font-family:'Barlow Condensed',sans-serif;font-size:28px;font-weight:800;color:#fff;line-height:1.1;margin-bottom:var(--space-2);margin-top:0;">
      Halo, {{ explode(' ', $user->name)[0] }}!
    </h1>
    <p style="font-size:13px;color:rgba(255,255,255,.85);margin-bottom:var(--space-4);">Pantau pesanan dan kelola akun Anda dengan mudah.</p>
    <a href="{{ route('home') }}" class="db-btn" style="background:#fff;color:var(--brand-color);box-shadow:var(--shadow-sm);min-height:44px;">
      <i class="fas fa-shopping-cart"></i> Belanja Sekarang
    </a>
  </div>
  <div class="db-hero-icon">
    <i class="fas fa-box" style="font-size:36px;color:rgba(255,255,255,.85);"></i>
  </div>
</div>

{{-- STATS --}}
@php
  $activeOrders    = $orders->whereIn('status',['pending','processing','shipped'])->count();
  $completedOrders = $orders->where('status','completed')->count();
  $totalBelanja    = $orders->where('status','completed')->sum('total_amount');
@endphp

<div class="db-stats-grid">

  {{-- Stat 1 --}}
  <a href="{{ route('dashboard.orders') }}" class="db-stat-card">
    <div>
      <div class="db-stat-icon" style="background:var(--brand-light);color:var(--brand-color);"><i class="fas fa-shopping-bag"></i></div>
      <div class="db-stat-value" style="color:var(--brand-color);">{{ $activeOrders }}</div>
      <div class="db-stat-label">Pesanan Aktif</div>
    </div>
    <div style="font-size:11px;font-weight:700;padding-top:var(--space-3);margin-top:var(--space-3);border-top:1px solid var(--neutral-border);color:var(--neutral-text-sub);">
      Lihat pesanan →
    </div>
  </a>

  {{-- Stat 2 --}}
  <a href="{{ route('dashboard.wishlist.index') }}" class="db-stat-card">
    <div>
      <div class="db-stat-icon" style="background:#fee2e2;color:var(--color-danger);"><i class="fas fa-heart"></i></div>
      <div class="db-stat-value" style="color:var(--color-danger);">{{ $wishlistCount }}</div>
      <div class="db-stat-label">Wishlist</div>
    </div>
    <div style="font-size:11px;font-weight:700;padding-top:var(--space-3);margin-top:var(--space-3);border-top:1px solid var(--neutral-border);color:var(--neutral-text-sub);">
      Lihat wishlist →
    </div>
  </a>

  {{-- Stat 3 --}}
  <div class="db-stat-card">
    <div>
      <div class="db-stat-icon" style="background:#d1fae5;color:var(--color-success);"><i class="fas fa-check-double"></i></div>
      <div class="db-stat-value" style="color:var(--color-success);">{{ $completedOrders }}</div>
      <div class="db-stat-label">Selesai</div>
    </div>
    <div style="font-size:11px;font-weight:700;padding-top:var(--space-3);margin-top:var(--space-3);border-top:1px solid var(--neutral-border);color:var(--neutral-text-sub);">
      Transaksi sukses
    </div>
  </div>

  {{-- Stat 4 --}}
  <div class="db-stat-card">
    <div>
      <div class="db-stat-icon" style="background:#ffedd5;color:var(--color-warning);"><i class="fas fa-wallet"></i></div>
      <div class="db-stat-value" style="color:var(--color-warning); font-size:{{ $totalBelanja > 9999999 ? '18' : '24' }}px;">
        Rp {{ number_format($totalBelanja,0,',','.') }}
      </div>
      <div class="db-stat-label">Total Belanja</div>
    </div>
    <div style="font-size:11px;font-weight:700;padding-top:var(--space-3);margin-top:var(--space-3);border-top:1px solid var(--neutral-border);color:var(--neutral-text-sub);">
      Akumulasi pembelian
    </div>
  </div>

</div>

{{-- RECENT ORDERS --}}
<div class="db-card" style="padding: 0;">

  <div class="db-card-header-flex">
    <div style="display:flex;align-items:center;gap:var(--space-2);font-weight:700;font-size:15px;color:var(--neutral-text-main);">
      <div style="width:32px;height:32px;border-radius:var(--radius-sm);background:var(--brand-light);color:var(--brand-color);display:flex;align-items:center;justify-content:center;font-size:13px;flex-shrink:0;">
        <i class="fas fa-receipt"></i>
      </div>
      Pesanan Terbaru
    </div>
    <a href="{{ route('dashboard.orders') }}" class="db-btn db-btn-secondary" style="font-size:12px; padding:6px 12px; border-radius:var(--radius-sm); min-height:36px; height:36px; display:inline-flex; align-items:center;">
      Lihat Semua <i class="fas fa-chevron-right" style="font-size:9px; margin-left:4px;"></i>
    </a>
  </div>

  @if($orders->isEmpty())
    <div style="padding:52px 20px;text-align:center;display:flex;flex-direction:column;align-items:center;gap:10px;">
      <div style="width:72px;height:72px;border-radius:50%;background:var(--brand-light);display:flex;align-items:center;justify-content:center;font-size:28px;color:var(--brand-color);margin-bottom:6px;">
        <i class="fas fa-box-open"></i>
      </div>
      <div style="font-weight:700;font-size:16px;color:#1e293b;">Belum ada pesanan</div>
      <p style="font-size:13px;color:#94a3b8;max-width:260px;line-height:1.6;margin:0;">Yuk mulai belanja dan temukan produk terbaik!</p>
      <a href="{{ route('home') }}" class="db-btn db-btn-primary" style="margin-top:6px;">
        <i class="fas fa-store"></i> Jelajahi Produk
      </a>
    </div>
  @else
    @foreach($orders->take(5) as $order)
      @php
        $sc = [
          'pending'    => ['Menunggu', 'db-badge-pending', 'fa-clock'],
          'processing' => ['Diproses', 'db-badge-processing', 'fa-sync-alt'],
          'shipped'    => ['Dikirim',  'db-badge-shipped', 'fa-truck'],
          'completed'  => ['Selesai',  'db-badge-completed', 'fa-check-circle'],
          'cancelled'  => ['Batal',    'db-badge-cancelled', 'fa-times-circle'],
        ];
        [$slabel,$sbadge,$sico] = $sc[$order->status] ?? [ucfirst($order->status),'db-badge-pending','fa-circle'];
      @endphp
      <div class="db-recent-order-item">
        <div class="db-order-item-left">
          <div class="db-order-icon">
            <i class="fas {{ $sico }}"></i>
          </div>
          <div class="db-order-text-meta">
            <div class="db-order-id">#{{ $order->order_number }}</div>
            <div class="db-order-subtext">
              {{ $order->created_at->format('d M Y') }} · {{ $order->items->count() }} produk
            </div>
          </div>
        </div>
        <div class="db-order-item-right">
          <div class="db-order-price-badge">
            <div class="db-order-price-val">
              Rp {{ number_format($order->total_amount,0,',','.') }}
            </div>
            <span class="db-badge {{ $sbadge }}">
              {{ $slabel }}
            </span>
          </div>
          <a href="{{ route('dashboard.orders.show', $order->order_number) }}" class="db-btn db-btn-outline db-btn-sm">
            Detail
          </a>
        </div>
      </div>
    @endforeach
  @endif
</div>

{{-- QUICK ACTIONS --}}
<div class="db-quick-grid">
  @php
    $quickItems = [
      ['route'=>'dashboard.profile',           'ico'=>'fa-user-edit',      'label'=>'Edit Profil'],
      ['route'=>'dashboard.addresses.index',   'ico'=>'fa-map-marker-alt', 'label'=>'Alamat'],
      ['route'=>'dashboard.notifications.index','ico'=>'fa-bell',          'label'=>'Notifikasi'],
      ['route'=>'flash-sale',                  'ico'=>'fa-fire',           'label'=>'Flash Sale'],
    ];
  @endphp
  @foreach($quickItems as $qi)
    <a href="{{ route($qi['route']) }}" class="db-quick-action">
      <div style="width:48px;height:48px;border-radius:var(--radius-sm);display:flex;align-items:center;justify-content:center;font-size:18px;background:var(--brand-light);color:var(--brand-color);">
        <i class="fas {{ $qi['ico'] }}"></i>
      </div>
      <span style="font-size:12.5px;font-weight:700;color:var(--neutral-text-sub);">{{ $qi['label'] }}</span>
    </a>
  @endforeach
</div>

@endsection
