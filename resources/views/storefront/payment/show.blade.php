@extends('layouts.app')

@section('title', 'Pembayaran #' . $order->order_number . ' - LISTRINDO JAYA ELEKTRIK')

@push('styles')
<style>
  .pay-wrap {
    min-height: calc(100vh - 200px);
    display: flex;
    align-items: flex-start;
    justify-content: center;
    padding: 48px 20px 80px;
  }
  .pay-card {
    width: 100%;
    max-width: 540px;
    background: #fff;
    border-radius: 20px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 24px rgba(0,0,0,0.06);
    overflow: hidden;
  }

  /* Header */
  .pay-header {
    background: linear-gradient(135deg, #025cca 0%, #02469c 100%);
    padding: 32px 32px 28px;
    text-align: center;
    position: relative;
    overflow: hidden;
  }
  .pay-header::before {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(circle at 80% 20%, rgba(255,255,255,.1) 0%, transparent 60%);
    pointer-events: none;
  }
  .pay-header-icon {
    width: 64px;
    height: 64px;
    background: rgba(255,255,255,.15);
    border: 1.5px solid rgba(255,255,255,.25);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 16px;
    position: relative;
    z-index: 1;
  }
  .pay-header-icon i { font-size: 26px; color: #fff; }
  .pay-header h1 {
    font-family: 'Barlow Condensed', sans-serif;
    font-size: 26px;
    font-weight: 800;
    color: #fff;
    margin: 0 0 6px 0;
    position: relative;
    z-index: 1;
  }
  .pay-header p {
    font-size: 13px;
    color: rgba(255,255,255,.8);
    margin: 0;
    position: relative;
    z-index: 1;
  }
  .pay-order-num {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    margin-top: 12px;
    background: rgba(255,255,255,.15);
    border: 1px solid rgba(255,255,255,.2);
    border-radius: 20px;
    padding: 4px 14px;
    font-size: 12px;
    font-weight: 700;
    color: #fff;
    font-family: monospace;
    position: relative;
    z-index: 1;
  }

  /* Body */
  .pay-body { padding: 28px 32px; }

  /* Summary block */
  .pay-summary {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 20px;
    margin-bottom: 24px;
  }
  .pay-summary-title {
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
  .pay-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
  }
  .pay-row:not(:last-child) {
    border-bottom: 1px dashed #e2e8f0;
  }
  .pay-row-label {
    font-size: 13px;
    color: #64748b;
  }
  .pay-row-value {
    font-size: 13.5px;
    font-weight: 600;
    color: #1e293b;
  }
  .pay-total-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px 20px;
    background: #ebf2ff;
    border: 1.5px solid rgba(2,92,202,.15);
    border-radius: 12px;
    margin-bottom: 24px;
  }
  .pay-total-label {
    font-size: 14px;
    font-weight: 700;
    color: #334155;
  }
  .pay-total-amount {
    font-family: 'Barlow Condensed', sans-serif;
    font-size: 26px;
    font-weight: 800;
    color: #025cca;
    line-height: 1;
  }

  /* Buttons */
  .pay-btn-main {
    width: 100%;
    height: 52px;
    background: #025cca;
    color: #fff;
    border: none;
    border-radius: 12px;
    font-size: 16px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    font-family: 'DM Sans', sans-serif;
    box-shadow: 0 4px 14px rgba(2,92,202,.25);
  }
  .pay-btn-main:hover {
    background: #02469c;
    box-shadow: 0 6px 20px rgba(2,92,202,.35);
    transform: translateY(-1px);
  }
  .pay-btn-main:active {
    transform: translateY(0);
  }
  .pay-btn-main .spinner {
    display: none;
    width: 18px; height: 18px;
    border: 2px solid rgba(255,255,255,.4);
    border-top-color: #fff;
    border-radius: 50%;
    animation: spin .7s linear infinite;
  }
  .pay-btn-main.loading .btn-text { display: none; }
  .pay-btn-main.loading .spinner { display: block; }

  @keyframes spin { to { transform: rotate(360deg); } }

  .pay-link-skip {
    display: block;
    text-align: center;
    margin-top: 14px;
    font-size: 13px;
    color: #94a3b8;
    text-decoration: none;
    transition: color .15s;
  }
  .pay-link-skip:hover { color: #64748b; }

  /* Security badges */
  .pay-security {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 20px;
    margin-top: 22px;
    padding-top: 20px;
    border-top: 1px solid #f1f5f9;
    flex-wrap: wrap;
  }
  .pay-sec-item {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 11px;
    color: #94a3b8;
    font-weight: 600;
  }
  .pay-sec-item i { color: #10b981; font-size: 12px; }

  /* Items preview */
  .pay-items {
    display: flex;
    flex-direction: column;
    gap: 0;
  }
  .pay-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 0;
    border-bottom: 1px solid #f1f5f9;
  }
  .pay-item:last-child { border-bottom: none; }
  .pay-item-img {
    width: 44px;
    height: 44px;
    border-radius: 8px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    flex-shrink: 0;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .pay-item-img img { width: 100%; height: 100%; object-fit: contain; padding: 4px; }
  .pay-item-name {
    flex: 1;
    min-width: 0;
    font-size: 13px;
    font-weight: 600;
    color: #1e293b;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  .pay-item-qty {
    font-size: 12px;
    color: #94a3b8;
    font-weight: 500;
    flex-shrink: 0;
  }

  @media (max-width: 480px) {
    .pay-wrap { padding: 20px 16px 60px; align-items: flex-start; }
    .pay-header { padding: 24px 20px 22px; }
    .pay-body { padding: 20px; }
    .pay-total-amount { font-size: 22px; }
  }
</style>
@endpush

@section('content')
<div class="pay-wrap">
  <div class="pay-card">

    {{-- Header --}}
    <div class="pay-header">
      <div class="pay-header-icon">
        <i class="fas fa-shield-alt"></i>
      </div>
      <h1>Selesaikan Pembayaran</h1>
      <p>Transaksi aman terenkripsi 256-bit SSL</p>
      <div class="pay-order-num">
        <i class="fas fa-receipt" style="font-size:10px;"></i>
        {{ $order->order_number }}
      </div>
    </div>

    {{-- Body --}}
    <div class="pay-body">

      {{-- Items purchased --}}
      @if($order->items->count())
      <div class="pay-summary" style="margin-bottom: 20px;">
        <div class="pay-summary-title">
          <i class="fas fa-box" style="font-size:10px;"></i>
          {{ $order->items->count() }} Produk Dipesan
        </div>
        <div class="pay-items">
          @foreach($order->items->take(3) as $item)
          <div class="pay-item">
            <div class="pay-item-img">
              @if($item->product && $item->product->primaryImage)
                <img src="{{ asset('storage/'.$item->product->primaryImage->image_path) }}" alt="{{ $item->product_name }}">
              @else
                <i class="fas fa-box" style="font-size:16px; color:#cbd5e1;"></i>
              @endif
            </div>
            <div class="pay-item-name">{{ $item->product_name }}</div>
            <div class="pay-item-qty">× {{ $item->quantity }}</div>
          </div>
          @endforeach
          @if($order->items->count() > 3)
          <div style="text-align:center; padding: 8px 0; font-size: 12px; color:#94a3b8;">
            dan {{ $order->items->count() - 3 }} produk lainnya...
          </div>
          @endif
        </div>
      </div>
      @endif

      {{-- Order summary --}}
      <div class="pay-summary">
        <div class="pay-summary-title">
          <i class="fas fa-clipboard-list" style="font-size:10px;"></i>
          Rincian Pesanan
        </div>
        <div class="pay-row">
          <span class="pay-row-label">Subtotal produk</span>
          <span class="pay-row-value">Rp {{ number_format($order->subtotal ?? $order->total_amount - ($order->shipping_cost ?? 0), 0, ',', '.') }}</span>
        </div>
        <div class="pay-row">
          <span class="pay-row-label">Ongkos kirim</span>
          <span class="pay-row-value">Rp {{ number_format($order->shipping_cost ?? 0, 0, ',', '.') }}</span>
        </div>
        <div class="pay-row">
          <span class="pay-row-label">Kurir</span>
          <span class="pay-row-value" style="text-transform:uppercase;">{{ $order->shipping_courier }} · {{ $order->shipping_service }}</span>
        </div>
      </div>

      {{-- Total --}}
      <div class="pay-total-row">
        <div class="pay-total-label">Total Tagihan</div>
        <div class="pay-total-amount">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</div>
      </div>

      {{-- Pay button --}}
      <button id="pay-button" class="pay-btn-main">
        <span class="btn-text"><i class="fas fa-lock" style="font-size:14px;"></i> Bayar Sekarang</span>
        <div class="spinner"></div>
      </button>

      <a href="{{ route('dashboard.orders') }}" class="pay-link-skip">
        <i class="fas fa-clock" style="margin-right:4px;"></i> Bayar nanti — lihat riwayat pesanan
      </a>

      {{-- Security badges --}}
      <div class="pay-security">
        <div class="pay-sec-item">
          <i class="fas fa-lock"></i> SSL Terenkripsi
        </div>
        <div class="pay-sec-item">
          <i class="fas fa-shield-alt"></i> Pembayaran Aman
        </div>
        <div class="pay-sec-item">
          <i class="fas fa-certificate"></i> Midtrans Certified
        </div>
      </div>

    </div>
  </div>
</div>
@endsection

@push('scripts')
@php
  $snapUrl = config('midtrans.is_production')
    ? 'https://app.midtrans.com/snap/snap.js'
    : 'https://app.sandbox.midtrans.com/snap/snap.js';
@endphp
<script src="{{ $snapUrl }}" data-client-key="{{ $clientKey }}"></script>
<script>
  const payButton = document.getElementById('pay-button');

  payButton.addEventListener('click', function () {
    // Show loading state
    payButton.classList.add('loading');
    payButton.disabled = true;

    window.snap.pay('{{ $snapToken }}', {
      onSuccess: function(result) {
        window.location.href = '{{ route("dashboard.orders.show", $order->order_number) }}?payment=success';
      },
      onPending: function(result) {
        window.location.href = '{{ route("dashboard.orders.show", $order->order_number) }}?payment=pending';
      },
      onError: function(result) {
        payButton.classList.remove('loading');
        payButton.disabled = false;
        alert('Pembayaran gagal! Silakan coba lagi.');
      },
      onClose: function() {
        // Reset button when popup closed
        payButton.classList.remove('loading');
        payButton.disabled = false;
      }
    });
  });
</script>
@endpush
