@extends('layouts.app')

@section('title', 'Pembayaran Berhasil - LISTRINDO JAYA ELEKTRIK')

@push('styles')
<style>
  .finish-wrap {
    min-height: calc(100vh - 200px);
    display: flex;
    align-items: flex-start;
    justify-content: center;
    padding: 48px 20px 80px;
  }
  .finish-card {
    width: 100%;
    max-width: 540px;
    background: #fff;
    border-radius: 20px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 24px rgba(0,0,0,0.06);
    overflow: hidden;
  }

  /* Success header */
  .finish-header {
    background: linear-gradient(135deg, #059669 0%, #047857 100%);
    padding: 40px 32px 32px;
    text-align: center;
    position: relative;
    overflow: hidden;
  }
  .finish-header::before {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(circle at 20% 80%, rgba(255,255,255,.1) 0%, transparent 60%);
    pointer-events: none;
  }
  .finish-icon-ring {
    position: relative;
    width: 80px;
    height: 80px;
    margin: 0 auto 20px;
    z-index: 1;
  }
  .finish-icon-ring::before {
    content: '';
    position: absolute;
    inset: -8px;
    border-radius: 50%;
    border: 2px solid rgba(255,255,255,.2);
    animation: pulse-ring 2s ease-out infinite;
  }
  @keyframes pulse-ring {
    0%   { transform: scale(1); opacity: .5; }
    100% { transform: scale(1.3); opacity: 0; }
  }
  .finish-icon {
    width: 80px;
    height: 80px;
    background: rgba(255,255,255,.2);
    border: 2px solid rgba(255,255,255,.35);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .finish-icon i { font-size: 32px; color: #fff; }
  .finish-header h1 {
    font-family: 'Barlow Condensed', sans-serif;
    font-size: 28px;
    font-weight: 800;
    color: #fff;
    margin: 0 0 8px;
    position: relative;
    z-index: 1;
  }
  .finish-header p {
    font-size: 13.5px;
    color: rgba(255,255,255,.85);
    margin: 0;
    position: relative;
    z-index: 1;
  }
  .finish-order-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    margin-top: 14px;
    background: rgba(255,255,255,.15);
    border: 1px solid rgba(255,255,255,.2);
    border-radius: 20px;
    padding: 5px 16px;
    font-size: 12px;
    font-weight: 700;
    color: #fff;
    font-family: monospace;
    position: relative;
    z-index: 1;
  }

  /* Body */
  .finish-body { padding: 28px 32px; }

  /* Info rows */
  .finish-section {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 20px;
    margin-bottom: 20px;
  }
  .finish-section-title {
    font-size: 11px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .08em;
    color: #94a3b8;
    margin-bottom: 14px;
    display: flex;
    align-items: center;
    gap: 6px;
  }
  .finish-row {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding: 9px 0;
    gap: 12px;
  }
  .finish-row:not(:last-child) {
    border-bottom: 1px dashed #e2e8f0;
  }
  .finish-row-label {
    font-size: 13px;
    color: #64748b;
    flex-shrink: 0;
  }
  .finish-row-value {
    font-size: 13.5px;
    font-weight: 600;
    color: #1e293b;
    text-align: right;
  }

  /* Status highlight */
  .finish-status-box {
    display: flex;
    align-items: center;
    gap: 14px;
    background: #f0fdf4;
    border: 1.5px solid #bbf7d0;
    border-radius: 12px;
    padding: 16px 20px;
    margin-bottom: 20px;
  }
  .finish-status-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #dcfce7;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
  }
  .finish-status-icon i { font-size: 18px; color: #059669; }
  .finish-status-text { font-size: 13px; color: #15803d; font-weight: 600; }
  .finish-status-sub { font-size: 12px; color: #4ade80; margin-top: 2px; }

  /* CTAs */
  .finish-actions {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    margin-top: 8px;
  }
  .finish-btn-primary {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    height: 48px;
    background: #025cca;
    color: #fff;
    border-radius: 12px;
    font-size: 14px;
    font-weight: 700;
    text-decoration: none;
    transition: all .2s;
    box-shadow: 0 4px 12px rgba(2,92,202,.2);
  }
  .finish-btn-primary:hover {
    background: #02469c;
    transform: translateY(-1px);
    box-shadow: 0 6px 18px rgba(2,92,202,.3);
    color: #fff;
  }
  .finish-btn-outline {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    height: 48px;
    background: #fff;
    color: #475569;
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    font-size: 14px;
    font-weight: 700;
    text-decoration: none;
    transition: all .2s;
  }
  .finish-btn-outline:hover {
    border-color: #025cca;
    color: #025cca;
    background: #ebf2ff;
  }

  @media (max-width: 480px) {
    .finish-wrap { padding: 20px 16px 60px; }
    .finish-header { padding: 28px 20px 24px; }
    .finish-body { padding: 20px; }
    .finish-actions { grid-template-columns: 1fr; }
  }
</style>
@endpush

@section('content')
<div class="finish-wrap">
  <div class="finish-card">

    {{-- Success Header --}}
    <div class="finish-header">
      <div class="finish-icon-ring">
        <div class="finish-icon">
          <i class="fas fa-check"></i>
        </div>
      </div>
      <h1>Pembayaran Berhasil!</h1>
      <p>Terima kasih, pesanan Anda sedang kami proses</p>
      <div class="finish-order-badge">
        <i class="fas fa-receipt" style="font-size:10px;"></i>
        {{ $order->order_number }}
      </div>
    </div>

    {{-- Body --}}
    <div class="finish-body">

      {{-- Status highlight --}}
      <div class="finish-status-box">
        <div class="finish-status-icon">
          <i class="fas fa-check-circle"></i>
        </div>
        <div>
          <div class="finish-status-text">Pembayaran dikonfirmasi</div>
          <div class="finish-status-sub" style="color:#16a34a;">Pesanan sedang disiapkan untuk pengiriman</div>
        </div>
      </div>

      {{-- Shipping info --}}
      <div class="finish-section">
        <div class="finish-section-title">
          <i class="fas fa-truck" style="font-size:10px;"></i>
          Informasi Pengiriman
        </div>
        <div class="finish-row">
          <span class="finish-row-label">Penerima</span>
          <span class="finish-row-value">{{ $order->shipping_name }}</span>
        </div>
        <div class="finish-row">
          <span class="finish-row-label">Alamat</span>
          <span class="finish-row-value" style="max-width: 260px;">
            {{ $order->shipping_address }}, {{ $order->shipping_city ?? '' }}
          </span>
        </div>
        <div class="finish-row">
          <span class="finish-row-label">Kurir</span>
          <span class="finish-row-value" style="text-transform:uppercase;">
            {{ $order->shipping_courier }} · {{ $order->shipping_service }}
          </span>
        </div>
        @if($order->shipping_etd)
        <div class="finish-row">
          <span class="finish-row-label">Estimasi Tiba</span>
          <span class="finish-row-value" style="color:#025cca;">
            {{ $order->shipping_etd }} hari kerja
          </span>
        </div>
        @endif
      </div>

      {{-- Payment summary --}}
      <div class="finish-section">
        <div class="finish-section-title">
          <i class="fas fa-wallet" style="font-size:10px;"></i>
          Ringkasan Pembayaran
        </div>
        <div class="finish-row">
          <span class="finish-row-label">Total produk</span>
          <span class="finish-row-value">{{ $order->items->sum('quantity') }} barang</span>
        </div>
        <div class="finish-row">
          <span class="finish-row-label">Total tagihan</span>
          <span class="finish-row-value" style="color:#025cca; font-size:15px;">
            Rp {{ number_format($order->total_amount, 0, ',', '.') }}
          </span>
        </div>
        <div class="finish-row">
          <span class="finish-row-label">Status</span>
          <span class="finish-row-value" style="color:#059669;">
            <i class="fas fa-circle" style="font-size:7px;"></i> Lunas
          </span>
        </div>
      </div>

      {{-- CTA Buttons --}}
      <div class="finish-actions">
        <a href="{{ route('dashboard.orders.show', $order->order_number) }}" class="finish-btn-primary">
          <i class="fas fa-box-open" style="font-size:13px;"></i> Pantau Pesanan
        </a>
        <a href="{{ route('home') }}" class="finish-btn-outline">
          <i class="fas fa-store" style="font-size:13px;"></i> Belanja Lagi
        </a>
      </div>

    </div>
  </div>
</div>
@endsection
