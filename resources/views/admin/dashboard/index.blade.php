@extends('admin.layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard Overview')

@section('page-actions')
    <button class="btn-ag btn-ghost"><i class="fas fa-download"></i> Unduh Laporan Harian</button>
@endsection

@section('content')

{{-- ══════════════ STAT WIDGETS ══════════════ --}}
<div class="grid-4" style="margin-bottom:24px;">
    
    {{-- Revenue --}}
    @php
        $revDelta = $stats['revenue']['yesterday'] > 0
            ? (($stats['revenue']['today'] - $stats['revenue']['yesterday']) / $stats['revenue']['yesterday']) * 100
            : 0;
    @endphp
    <div class="stat-widget fade-up clickable" onclick="window.location.href='{{ route('admin.reports.revenue') }}'" title="Klik untuk lihat detail">
        <div>
            <div class="lbl">Total Pendapatan (Hari Ini)</div>
            <div class="val">Rp{{ number_format($stats['revenue']['today'], 0, ',', '.') }}</div>
            <div class="stat-trend {{ $revDelta >= 0 ? 'trend-up' : 'trend-down' }}">
                <i class="fas fa-arrow-{{ $revDelta >= 0 ? 'up' : 'down' }}"></i>
                <span>{{ number_format(abs($revDelta), 1) }}% vs kemarin</span>
            </div>
        </div>
        <div class="icon" style="background:var(--success-bg); color:var(--success);">
            <i class="fas fa-wallet"></i>
        </div>
    </div>

    {{-- Orders --}}
    <div class="stat-widget fade-up delay-1 clickable" onclick="window.location.href='{{ route('admin.orders.index') }}'" title="Klik untuk lihat detail">
        <div>
            <div class="lbl">Pesanan Baru (Hari Ini)</div>
            <div class="val">{{ number_format($stats['orders']['today']) }}</div>
            <div class="stat-trend" style="color:var(--text-muted)">
                <i class="fas fa-shopping-cart" style="font-size:11px;"></i>
                <span>Menunggu diproses</span>
            </div>
        </div>
        <div class="icon" style="background:var(--info-bg); color:var(--brand-primary);">
            <i class="fas fa-shopping-bag"></i>
        </div>
    </div>

    {{-- Users --}}
    <div class="stat-widget fade-up delay-2 clickable" onclick="window.location.href='{{ route('admin.users.index') }}'" title="Klik untuk lihat detail">
        <div>
            <div class="lbl">Pelanggan Baru</div>
            <div class="val">{{ number_format($stats['users']['today']) }}</div>
            <div class="stat-trend" style="color:var(--text-muted)">
                <i class="fas fa-user-check" style="font-size:11px;"></i>
                <span>Total: {{ number_format($stats['users']['total']) }} pelanggan</span>
            </div>
        </div>
        <div class="icon" style="background:var(--warning-bg); color:var(--warning);">
            <i class="fas fa-users"></i>
        </div>
    </div>

    {{-- Low Stock --}}
    <div class="stat-widget fade-up delay-3 clickable" onclick="window.location.href='{{ route('admin.products.index', ['search' => 'kritis']) }}'" style="border-left: 4px solid var(--danger);" title="Klik untuk lihat detail">
        <div>
            <div class="lbl">Stok Kritis</div>
            <div class="val" style="color:var(--danger);">{{ number_format($stats['products']['low_stock']) }}</div>
            <div class="stat-trend" style="color:var(--danger)">
                <i class="fas fa-exclamation-circle" style="font-size:11px;"></i>
                <span>Segera restock produk</span>
            </div>
        </div>
        <div class="icon" style="background:var(--danger-bg); color:var(--danger);">
            <i class="fas fa-box-open"></i>
        </div>
    </div>

</div>

{{-- ══════════════ CHARTS SECTION ══════════════ --}}
<div class="grid-2-1" x-data="dashboardCharts" style="margin-bottom:24px;">
    
    {{-- Revenue Chart --}}
    <div class="panel fade-up" style="margin-bottom:0; display:flex; flex-direction:column; justify-content:space-between;">
        <div class="panel-header">
            <div class="panel-title">
                <i class="fas fa-chart-line"></i> Grafik Revenue 30 Hari
            </div>
            <div class="toolbar-actions">
                <span class="badge-ag badge-info" style="font-weight:600;">IDR (Rupiah)</span>
            </div>
        </div>
        <div class="panel-body" style="padding-top:10px;">
            <div style="height:260px; position:relative;">
                <canvas id="revenueChart" data-values='@json($revenueChart)'></canvas>
            </div>
        </div>
    </div>

    {{-- Order By Status --}}
    <div class="panel fade-up delay-1" style="margin-bottom:0; display:flex; flex-direction:column; justify-content:space-between;">
        <div class="panel-header">
            <div class="panel-title">
                <i class="fas fa-chart-pie"></i> Status Pesanan
            </div>
        </div>
        <div class="panel-body" style="display:flex; flex-direction:column; align-items:center; justify-content:center; padding-top:10px;">
            <div style="height:150px; width:150px; position:relative; margin-bottom:16px;">
                <canvas id="statusChart" data-values='@json($stats['orders']['by_status'])'></canvas>
            </div>
            <div style="width:100%; display:flex; flex-direction:column; gap:6px;">
                @forelse($stats['orders']['by_status'] as $status => $count)
                    <div style="display:flex; justify-content:space-between; align-items:center; font-size:12px; padding: 4px 8px; border-radius:6px; background:var(--main-bg);">
                        <span class="badge-ag badge-{{ $status }}" style="text-transform:capitalize;">{{ $status }}</span>
                        <span style="font-weight:700; color:var(--text-main);">{{ number_format($count) }}</span>
                    </div>
                @empty
                    <div style="text-align:center; font-size:12px; color:var(--text-muted);">Belum ada data status</div>
                @endforelse
            </div>
        </div>
    </div>

</div>

{{-- ══════════════ TABLES & RECENT ACTIVITY ══════════════ --}}
<div class="grid-2-1" style="margin-bottom:0;">

    {{-- Recent Orders --}}
    <div class="panel fade-up" style="margin-bottom:0;">
        <div class="panel-header">
            <div class="panel-title">
                <i class="fas fa-shopping-basket"></i> Pesanan Terbaru
            </div>
            <a href="{{ route('admin.orders.index') }}" class="btn-ag btn-sm btn-ghost">
                Lihat Semua <i class="fas fa-arrow-right" style="font-size:10px;"></i>
            </a>
        </div>
        <div class="table-responsive">
            <table class="ag-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Pelanggan</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentOrders as $order)
                        <tr>
                            <td style="font-weight:700; color:var(--brand-primary);">#{{ $order->order_number }}</td>
                            <td>
                                <div style="font-weight:600; color:var(--text-main);">{{ $order->user->name ?? 'Tamu' }}</div>
                                <div style="font-size:11px; color:var(--text-muted);">{{ $order->created_at->format('d M Y, H:i') }}</div>
                            </td>
                            <td style="font-weight:700; color:var(--text-main);">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge-ag badge-{{ $order->status }}" style="text-transform:capitalize;">
                                    {{ $order->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">
                                <div class="empty-state">
                                    <i class="fas fa-inbox"></i>
                                    <p>Belum ada data pesanan terbaru.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pending Reviews --}}
    <div class="panel fade-up delay-2" style="margin-bottom:0; display:flex; flex-direction:column; justify-content:space-between;">
        <div>
            <div class="panel-header">
                <div class="panel-title">
                    <i class="fas fa-star"></i> Ulasan Menunggu Moderasi
                </div>
            </div>
            <div class="panel-body" style="padding: 0;">
                <div style="display:flex; flex-direction:column;">
                    @forelse($recentReviews as $review)
                        <div style="padding:14px 20px; border-bottom:1px solid var(--border); display:flex; gap:12px; align-items:flex-start;">
                            <div style="width:38px; height:38px; border-radius:10px; background:var(--brand-glow); color:var(--brand-primary); display:flex; align-items:center; justify-content:center; font-weight:800; font-size:14px; flex-shrink:0;">
                                {{ strtoupper(substr($review->user->name ?? 'U', 0, 1)) }}
                            </div>
                            <div style="flex:1; min-width:0;">
                                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:2px;">
                                    <div style="font-weight:700; font-size:13px; color:var(--text-main); white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $review->user->name ?? 'User' }}</div>
                                    <div style="color:var(--warning); font-size:10px; flex-shrink:0;">
                                        @for($i=1;$i<=5;$i++)
                                            <i class="fas fa-star{{ $i <= $review->rating ? '' : '-o' }}"></i>
                                        @endfor
                                    </div>
                                </div>
                                <div style="font-size:11px; font-weight:600; color:var(--brand-primary); margin-bottom:4px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                    {{ $review->product->name ?? 'Produk' }}
                                </div>
                                <div style="font-size:12px; color:var(--text-secondary); line-height:1.4; font-style:italic;">
                                    "{{ Str::limit($review->comment, 65) }}"
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state" style="padding: 36px 16px;">
                            <i class="fas fa-check-circle" style="color:var(--success);"></i>
                            <p style="margin-bottom:0;">Semua ulasan telah dimoderasi!</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
        
        @if($recentReviews->count() > 0)
            <div style="padding:14px 20px; text-align:center; border-top:1px solid var(--border); background:var(--main-bg); border-bottom-left-radius:16px; border-bottom-right-radius:16px;">
                <a href="{{ route('admin.reviews.index') }}" style="font-size:12.5px; color:var(--brand-primary); font-weight:700; display:inline-flex; align-items:center; gap:6px;">
                    Lihat Semua Ulasan <i class="fas fa-arrow-right" style="font-size:10px;"></i>
                </a>
            </div>
        @endif
    </div>

</div>

@endsection

@push('scripts')
@vite(['resources/js/admin.js'])
@endpush


