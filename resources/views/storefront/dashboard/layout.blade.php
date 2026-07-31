@extends('layouts.app')

@push('styles')
<style>
  /* --- CUSTOMER DASHBOARD PREMIUM DESIGN SYSTEM --- */
  :root {
    /* Brand Colors */
    --brand-color: #025cca;
    --brand-hover: #02469c;
    --brand-light: #ebf2ff;

    /* Semantic Colors */
    --color-success: #059669;
    --color-warning: #ea580c;
    --color-danger: #dc2626;

    /* Neutrals */
    --neutral-bg: #f8fafc;
    --neutral-card: #ffffff;
    --neutral-border: #cbd5e1;
    --neutral-text-main: #0f172a;
    --neutral-text-sub: #64748b;
    --neutral-text-muted: #94a3b8;

    /* Spacing (multiples of 4px) */
    --space-1: 4px;
    --space-2: 8px;
    --space-3: 12px;
    --space-4: 16px;
    --space-6: 24px;
    --space-8: 32px;

    /* Border Radius (exactly 2 values) */
    --radius-sm: 8px;
    --radius-lg: 12px;

    /* Shadows (exactly 2 levels) */
    --shadow-sm: 0 1px 3px 0 rgba(0, 0, 0, 0.05), 0 1px 2px -1px rgba(0, 0, 0, 0.05);
    --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
  }

  /* Layout Grid */
  .db-container {
    display: grid;
    grid-template-columns: 240px 1fr;
    gap: var(--space-6);
    align-items: start;
    padding: var(--space-8) 0 80px;
  }
  
  /* Sidebar sticky */
  .db-sidebar {
    position: sticky;
    top: 90px;
    display: flex;
    flex-direction: column;
    gap: var(--space-4);
    transition: all 0.3s ease;
    min-width: 0;
    width: 100%;
  }
  
  /* Profile Card Modern */
  .db-profile-card {
    background: var(--neutral-card);
    border: 1px solid var(--neutral-border);
    border-radius: var(--radius-lg);
    padding: var(--space-4);
    box-shadow: var(--shadow-sm);
    position: relative;
    overflow: hidden;
    color: var(--neutral-text-main);
  }
  .db-profile-avatar {
    width: 52px;
    height: 52px;
    border-radius: 50%;
    background: var(--brand-light);
    border: 2px solid var(--brand-color);
    color: var(--brand-color);
    font-size: 22px;
    font-weight: 800;
    font-family: 'Barlow Condensed', sans-serif;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: var(--shadow-sm);
  }
  .db-profile-name {
    font-weight: 700;
    font-size: 15px;
    color: var(--neutral-text-main);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  .db-profile-email {
    font-size: 12px;
    color: var(--neutral-text-sub);
    margin-top: 2px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  
  /* Navigation Card */
  .db-nav-card {
    background: var(--neutral-card);
    border: 1px solid var(--neutral-border);
    border-radius: var(--radius-lg);
    padding: var(--space-3);
    box-shadow: var(--shadow-sm);
  }
  .db-nav-header {
    font-size: 10px;
    font-weight: 800;
    letter-spacing: .08em;
    text-transform: uppercase;
    color: var(--neutral-text-muted);
    padding: 6px 12px 4px;
  }
  .db-nav-link {
    display: flex;
    align-items: center;
    gap: var(--space-3);
    padding: 10px var(--space-3);
    border-radius: var(--radius-sm);
    font-size: 13.5px;
    font-weight: 500;
    color: var(--neutral-text-sub);
    text-decoration: none;
    transition: all 0.2s ease;
    margin-bottom: 2px;
  }
  .db-nav-link:hover {
    background: var(--neutral-bg);
    color: var(--brand-color);
  }
  .db-nav-link.active {
    font-weight: 700;
    color: var(--brand-color);
    background: var(--brand-light);
  }
  .db-nav-icon {
    width: 32px;
    height: 32px;
    flex-shrink: 0;
    border-radius: var(--radius-sm);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    transition: all 0.2s ease;
  }
  
  /* Sidebar Shop CTA */
  .db-sidebar-cta {
    display: flex;
    align-items: center;
    gap: var(--space-3);
    background: var(--brand-color);
    border-radius: var(--radius-lg);
    padding: var(--space-4);
    text-decoration: none;
    box-shadow: var(--shadow-sm);
    color: #ffffff;
    width: 100%;
    transition: all 0.2s ease;
  }
  .db-sidebar-cta:hover {
    background: var(--brand-hover);
    box-shadow: var(--shadow-md);
  }
  
  /* Standard White Card */
  .db-card {
    background: var(--neutral-card);
    border: 1px solid var(--neutral-border);
    border-radius: var(--radius-lg);
    padding: var(--space-6);
    box-shadow: var(--shadow-sm);
    overflow: hidden;
  }
  .db-card-header {
    margin-bottom: var(--space-6);
    border-bottom: 1px solid var(--neutral-border);
    padding-bottom: var(--space-4);
  }
  .db-card-header-flex {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 24px;
    border-bottom: 1px solid var(--neutral-border);
  }
  .db-card-title {
    font-size: 20px;
    font-weight: 700;
    color: var(--neutral-text-main);
    margin: 0 0 6px 0;
  }
  .db-card-subtitle {
    color: var(--neutral-text-sub);
    font-size: 13.5px;
    margin: 0;
  }

  /* Stats Grid */
  .db-stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: var(--space-4);
    margin-bottom: var(--space-6);
  }
  .db-stat-card {
    border-radius: var(--radius-lg);
    padding: var(--space-4) var(--space-3);
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--neutral-border);
    background: var(--neutral-card);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    transition: all 0.2s ease;
    text-decoration: none;
    min-width: 0;
  }
  .db-stat-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
    border-color: var(--brand-color);
  }
  .db-stat-icon {
    width: 40px;
    height: 40px;
    border-radius: var(--radius-sm);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    margin-bottom: var(--space-3);
  }
  .db-stat-value {
    font-family: 'Barlow Condensed', sans-serif;
    font-size: 24px;
    font-weight: 800;
    line-height: 1.1;
    color: var(--neutral-text-main);
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    width: 100%;
  }
  .db-stat-label {
    font-size: 12px;
    font-weight: 600;
    margin-top: var(--space-1);
    color: var(--neutral-text-sub);
  }

  /* Order Cards */
  .db-order-card {
    border: 1px solid var(--neutral-border);
    border-radius: var(--radius-lg);
    overflow: hidden;
    background: var(--neutral-card);
    transition: all 0.2s ease;
    margin-bottom: var(--space-4);
  }
  .db-order-card:hover {
    box-shadow: var(--shadow-md);
    border-color: var(--brand-color);
  }
  .db-order-header {
    padding: 14px 20px;
    background: var(--neutral-bg);
    border-bottom: 1px solid var(--neutral-border);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
  }
  .db-order-meta {
    font-size: 13px;
    color: var(--neutral-text-sub);
  }
  .db-order-body {
    padding: 20px;
    display: flex;
    gap: 20px;
    align-items: center;
  }
  .db-order-img {
    width: 64px;
    height: 64px;
    border-radius: var(--radius-sm);
    background: var(--neutral-bg);
    border: 1px solid var(--neutral-border);
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
  }
  .db-order-info {
    flex: 1;
    min-width: 0;
  }
  .db-order-title {
    font-size: 14px;
    font-weight: 700;
    color: var(--neutral-text-main);
    margin-bottom: 4px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  .db-order-price {
    font-size: 16px;
    font-weight: 800;
    color: var(--brand-color);
  }
  .db-order-actions {
    display: flex;
    flex-direction: column;
    gap: var(--space-2);
    flex-shrink: 0;
  }

  /* Badges */
  .db-badge {
    padding: var(--space-1) var(--space-3);
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    display: inline-flex;
    align-items: center;
    gap: var(--space-1);
    border: 1.5px solid transparent;
  }
  .db-badge-pending { background: #fff7ed; color: var(--color-warning); border-color: #ffedd5; }
  .db-badge-processing { background: #eff6ff; color: var(--brand-color); border-color: #dbeafe; }
  .db-badge-shipped { background: #f0f9ff; color: #0369a1; border-color: #e0f2fe; }
  .db-badge-completed { background: #f0fdf4; color: var(--color-success); border-color: #dcfce7; }
  .db-badge-cancelled { background: #fef2f2; color: var(--color-danger); border-color: #fee2e2; }

  /* Buttons */
  .db-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: var(--space-2);
    padding: 10px var(--space-4);
    border-radius: var(--radius-sm);
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.2s ease;
    border: 1.5px solid transparent;
    text-decoration: none;
    font-family: inherit;
    min-height: 44px;
    box-sizing: border-box;
  }
  .db-btn-primary {
    background: var(--brand-color);
    color: #fff;
  }
  .db-btn-primary:hover {
    background: var(--brand-hover);
  }
  .db-btn-secondary {
    background: var(--brand-light);
    color: var(--brand-color);
    border-color: rgba(2, 92, 202, 0.15);
  }
  .db-btn-secondary:hover {
    background: rgba(2, 92, 202, 0.15);
  }
  .db-btn-outline {
    background: transparent;
    border-color: var(--brand-color);
    color: var(--brand-color);
  }
  .db-btn-outline:hover {
    background: var(--brand-light);
  }
  .db-btn-danger {
    background: #fef2f2;
    color: var(--color-danger);
    border-color: #fecaca;
  }
  .db-btn-danger:hover {
    background: #fee2e2;
  }
  .db-btn-sm {
    min-height: 32px !important;
    padding: 6px 12px !important;
    font-size: 12px !important;
  }

  /* Recent Order List Item */
  .db-recent-order-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 24px;
    border-bottom: 1px solid var(--neutral-bg);
    gap: 16px;
  }
  .db-order-item-left {
    display: flex;
    align-items: center;
    gap: 14px;
    min-width: 0;
    flex: 1;
  }
  .db-order-icon {
    width: 38px;
    height: 38px;
    flex-shrink: 0;
    border-radius: var(--radius-sm);
    font-size: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--neutral-bg);
    border: 1px solid var(--neutral-border);
    color: var(--neutral-text-sub);
  }
  .db-order-text-meta {
    min-width: 0;
  }
  .db-order-id {
    font-weight: 700;
    font-size: 13.5px;
    color: var(--neutral-text-main);
  }
  .db-order-subtext {
    font-size: 11.5px;
    color: var(--neutral-text-sub);
    margin-top: 2px;
  }
  .db-order-item-right {
    display: flex;
    align-items: center;
    gap: 16px;
    flex-shrink: 0;
  }
  .db-order-price-badge {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 4px;
  }
  .db-order-price-val {
    font-weight: 800;
    font-size: 14px;
    color: var(--neutral-text-main);
    white-space: nowrap;
  }

  /* Dashboard Form Styles */
  .db-form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 20px;
  }
  .db-form-group {
    display: flex;
    flex-direction: column;
    gap: var(--space-2);
    margin-bottom: 20px;
    position: relative;
  }
  .db-label {
    font-size: 13.5px;
    font-weight: 700;
    color: var(--neutral-text-sub);
  }
  .db-input-wrapper {
    position: relative;
    display: flex;
    align-items: center;
  }
  .db-input-icon {
    position: absolute;
    left: 16px;
    color: var(--neutral-text-muted);
    font-size: 14px;
    pointer-events: none;
  }
  .db-input, .db-select, .db-textarea {
    width: 100%;
    border-radius: var(--radius-sm);
    border: 1.5px solid var(--neutral-border);
    padding: 12px 16px;
    font-size: 14.5px;
    color: var(--neutral-text-main);
    background: var(--neutral-card);
    transition: all 0.2s ease;
    box-sizing: border-box;
    font-family: inherit;
    min-height: 44px;
  }
  .db-input-wrapper .db-input {
    padding-left: 46px;
  }
  .db-input:focus, .db-select:focus, .db-textarea:focus {
    border-color: var(--brand-color);
    outline: none;
    box-shadow: 0 0 0 4px rgba(2, 92, 202, 0.08);
  }
  .db-input:disabled, .db-select:disabled, .db-textarea:disabled {
    background: var(--neutral-bg);
    border-color: var(--neutral-border);
    color: var(--neutral-text-muted);
    cursor: not-allowed;
  }
  .db-checkbox-container {
    display: flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
    font-size: 14px;
    color: var(--neutral-text-sub);
    user-select: none;
  }
  .db-checkbox {
    width: 18px;
    height: 18px;
    border-radius: 4px;
    border: 1.5px solid var(--neutral-border);
    cursor: pointer;
    accent-color: var(--brand-color);
  }
  .db-card-actions {
    display: flex;
    gap: var(--space-3);
    margin-top: var(--space-6);
    border-top: 1px solid var(--neutral-border);
    padding-top: var(--space-4);
  }

  /* Responsive Styling */
  @media (max-width: 768px) {
    .db-container {
      grid-template-columns: 1fr;
      gap: 20px;
      padding: var(--space-4) 0 var(--space-8);
    }
    .db-sidebar {
      position: static;
      flex-direction: column;
      gap: var(--space-3);
    }
    .db-profile-card {
      width: 100%;
      padding: var(--space-4);
    }
    
    /* Horizontal Scrolling Navigation for Mobile */
    .db-nav-card {
      width: 100%;
      display: flex;
      flex-direction: row;
      overflow-x: auto;
      white-space: nowrap;
      padding: 6px;
      gap: 6px;
      border-radius: var(--radius-lg);
      box-shadow: var(--shadow-sm);
      scrollbar-width: none;
      -ms-overflow-style: none;
    }
    .db-nav-card::-webkit-scrollbar {
      display: none;
    }
    .db-nav-header {
      display: none !important;
    }
    .db-nav-link {
      display: inline-flex;
      align-items: center;
      padding: 8px var(--space-4);
      margin-bottom: 0;
      border-radius: var(--radius-sm);
      font-size: 13px;
      flex-shrink: 0;
      gap: 8px;
      min-height: 44px;
    }
    .db-nav-icon {
      width: 24px;
      height: 24px;
      font-size: 11px;
      border-radius: 6px;
    }
    .db-nav-card > div[style*="height:1px"] {
      display: none !important;
    }
    
    /* Hide Shop CTA on mobile */
    .db-sidebar-cta {
      display: none !important;
    }

    .db-stats-grid {
      grid-template-columns: repeat(2, 1fr);
      gap: var(--space-3);
    }
    .db-stat-card {
      padding: var(--space-4) var(--space-3);
    }
    .db-stat-value {
      font-size: 18px;
    }
    .db-stat-label {
      font-size: 11px;
    }

    .db-order-body {
      display: grid;
      grid-template-columns: 64px 1fr;
      gap: 14px;
      align-items: start;
    }
    .db-order-actions {
      grid-column: 1 / -1;
      display: flex;
      flex-direction: row;
      gap: var(--space-2);
      margin-top: var(--space-2);
    }
    .db-order-actions > * {
      flex: 1;
    }
    .db-card {
      padding: var(--space-4);
    }

    /* Mobile styles for recent orders */
    .db-recent-order-item {
      flex-direction: column;
      align-items: stretch;
      padding: var(--space-4);
      gap: 12px;
    }
    .db-order-item-left {
      width: 100%;
    }
    .db-order-item-right {
      width: 100%;
      justify-content: space-between;
      border-top: 1px dashed var(--neutral-border);
      padding-top: 12px;
      gap: 12px;
    }
    .db-order-price-badge {
      flex-direction: row;
      align-items: center;
      justify-content: space-between;
      flex: 1;
    }
    .db-order-price-val {
      font-size: 14.5px;
    }
    
    .db-card-header-flex {
      padding: 12px 16px;
      gap: var(--space-2);
    }

    /* Mobile card actions stacking */
    .db-card-actions {
      flex-direction: column;
      align-items: stretch;
      gap: var(--space-2);
    }
    .db-card-actions > * {
      width: 100%;
    }
    .db-card-actions form {
      width: 100%;
    }
    .db-card-actions .db-btn {
      width: 100%;
      box-sizing: border-box;
    }

    .db-btn-sm {
      min-height: 44px !important;
      font-size: 13px !important;
      padding: 10px 16px !important;
    }
    
    .db-form-row {
      grid-template-columns: 1fr;
      gap: var(--space-3);
      margin-bottom: var(--space-3);
    }
  }

  @media (max-width: 480px) {
    .db-order-actions {
      flex-direction: column;
    }
  }
</style>
@endpush

@section('content')
<div class="db-container">

  {{-- ════════════ SIDEBAR ════════════ --}}
  <aside class="db-sidebar">

    {{-- Profile Card --}}
    <div class="db-profile-card">
      <div style="position:relative;z-index:1;display:flex;align-items:center;gap:14px;">
        <div style="position:relative;flex-shrink:0;">
          <div class="db-profile-avatar">
            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
          </div>
          <span style="position:absolute;bottom:2px;right:2px;width:12px;height:12px;border-radius:50%;background:#4ade80;border:2px solid var(--neutral-card);display:block;"></span>
        </div>
        <div style="min-width:0;">
          <div class="db-profile-name">{{ Auth::user()->name }}</div>
          <div class="db-profile-email">{{ Auth::user()->email }}</div>
          <div style="display:inline-flex;align-items:center;gap:4px;margin-top:8px;background:var(--brand-light);border:1px solid rgba(2,92,202,0.15);padding:2px 10px;border-radius:20px;font-size:10px;font-weight:700;color:var(--brand-color);">
            <i class="fas fa-star" style="font-size:8px;"></i> Member Aktif
          </div>
        </div>
      </div>
    </div>

    {{-- Nav --}}
    <div class="db-nav-card">
      <div class="db-nav-header">Menu</div>

      @php
        $navItems = [
          ['route'=>'dashboard.index',       'match'=>'dashboard.index',      'icon'=>'fa-chart-pie',      'label'=>'Ringkasan'],
          ['route'=>'dashboard.orders',      'match'=>'dashboard.orders*',    'icon'=>'fa-shopping-bag',   'label'=>'Pesanan Saya'],
          ['route'=>'dashboard.addresses.index','match'=>'dashboard.addresses*','icon'=>'fa-map-marker-alt','label'=>'Buku Alamat'],
          ['route'=>'dashboard.wishlist.index','match'=>'dashboard.wishlist*', 'icon'=>'fa-heart',         'label'=>'Wishlist'],
        ];
      @endphp

      @foreach($navItems as $item)
        @php $active = request()->routeIs($item['match']); @endphp
        <a href="{{ route($item['route']) }}" class="db-nav-link {{ $active ? 'active' : '' }}">
          <span class="db-nav-icon" style="{{ $active ? 'background:var(--brand-light);color:var(--brand-color);' : 'background:var(--neutral-bg);color:var(--neutral-text-sub);' }}">
            <i class="fas {{ $item['icon'] }}"></i>
          </span>
          {{ $item['label'] }}
        </a>
      @endforeach

      <div style="height:1px;background:var(--neutral-border);margin:8px 4px;"></div>
      <div class="db-nav-header">Akun</div>

      <a href="{{ route('dashboard.profile') }}" class="db-nav-link {{ request()->routeIs('dashboard.profile*') ? 'active' : '' }}">
        <span class="db-nav-icon" style="{{ request()->routeIs('dashboard.profile*') ? 'background:var(--brand-light);color:var(--brand-color);' : 'background:var(--neutral-bg);color:var(--neutral-text-sub);' }}">
          <i class="fas fa-user-cog"></i>
        </span>
        Profil Saya
      </a>

      <form action="{{ route('logout') }}" method="POST" style="margin:0;">
        @csrf
        <button type="submit" class="db-nav-link" style="border:none;background:none;width:100%;text-align:left;cursor:pointer;font-family:inherit;">
          <span class="db-nav-icon" style="background:var(--neutral-bg);color:var(--color-danger);">
            <i class="fas fa-sign-out-alt"></i>
          </span>
          Keluar
        </button>
      </form>
    </div>

    {{-- Shop CTA --}}
    <a href="{{ route('home') }}" class="db-sidebar-cta">
      <div style="width:40px;height:40px;flex-shrink:0;border-radius:var(--radius-sm);background:rgba(255,255,255,.2);display:flex;align-items:center;justify-content:center;font-size:16px;color:#fff;">
        <i class="fas fa-bolt"></i>
      </div>
      <div>
        <div style="font-weight:700;font-size:13.5px;">Mulai Belanja</div>
        <div style="font-size:10.5px;color:rgba(255,255,255,.8);margin-top:1px;">Produk terlengkap</div>
      </div>
      <i class="fas fa-chevron-right" style="margin-left:auto;color:rgba(255,255,255,.7);font-size:12px;"></i>
    </a>

  </aside>

  {{-- ════════════ MAIN ════════════ --}}
  <div style="min-width:0;display:flex;flex-direction:column;gap:var(--space-6);">

    {{-- Alerts --}}
    @if(session('success'))
      <div style="display:flex;align-items:center;gap:var(--space-3);padding:14px 18px;border-radius:var(--radius-sm);font-size:14px;font-weight:600;background:#f0fdf4;color:var(--color-success);border:1px solid #bbf7d0;">
        <i class="fas fa-check-circle" style="font-size:18px;flex-shrink:0;"></i>
        <span>{{ session('success') }}</span>
      </div>
    @endif
    @if(session('error'))
      <div style="display:flex;align-items:center;gap:var(--space-3);padding:14px 18px;border-radius:var(--radius-sm);font-size:14px;font-weight:600;background:#fef2f2;color:var(--color-danger);border:1px solid #fecaca;">
        <i class="fas fa-exclamation-circle" style="font-size:18px;flex-shrink:0;"></i>
        <span>{{ session('error') }}</span>
      </div>
    @endif

    @yield('dashboard-content')
  </div>
</div>
@endsection
