@extends('admin.layouts.app')
@section('title', 'Laporan Pendapatan & Revenue')
@section('page-title', 'Laporan')

@section('breadcrumb')
    <i class="fas fa-chevron-right"></i>
    <span>Laporan Keuangan</span>
@endsection

@section('content')
<div class="fade-up" style="margin-bottom: 28px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
    <div>
        <h2 style="font-size: 24px; font-weight: 800; color: var(--text-main);">Laporan Pendapatan (Revenue)</h2>
        <p style="font-size: 13px; color: var(--text-muted); margin-top: 4px;">Analisa performa penjualan dan tren transaksi toko online</p>
    </div>
    
    <div style="display: flex; gap: 10px;">
        @if(Route::has('admin.reports.export'))
        <a href="{{ route('admin.reports.export') }}" class="btn-ag btn-primary">
            <i class="fas fa-download"></i> Ekspor Laporan CSV/Excel
        </a>
        @endif
    </div>
</div>

{{-- KPI Stat Widgets (4-Column Grid) --}}
<div class="grid-4 fade-up delay-1">
    <div class="stat-widget">
        <div>
            <div class="lbl">Revenue Bulan Ini</div>
            <div class="val" style="color: var(--brand-primary);">
                Rp {{ number_format($reportData['month_revenue'] ?? 0, 0, ',', '.') }}
            </div>
            <div class="stat-trend trend-up">
                <i class="fas fa-arrow-up"></i> Total Penjualan Bulan Ini
            </div>
        </div>
        <div class="icon" style="background: #eff6ff; color: #2563eb;">
            <i class="fas fa-wallet"></i>
        </div>
    </div>

    <div class="stat-widget">
        <div>
            <div class="lbl">Total Pesanan</div>
            <div class="val">
                {{ number_format($reportData['month_orders'] ?? 0) }}
            </div>
            <div class="stat-trend trend-up" style="color: var(--text-muted);">
                <i class="fas fa-shopping-bag"></i> Transaksi Berhasil
            </div>
        </div>
        <div class="icon" style="background: #ecfdf5; color: #10b981;">
            <i class="fas fa-shopping-cart"></i>
        </div>
    </div>

    <div class="stat-widget">
        <div>
            <div class="lbl">Rata-Rata Transaksi</div>
            <div class="val">
                Rp {{ number_format($reportData['avg_order'] ?? 0, 0, ',', '.') }}
            </div>
            <div class="stat-trend trend-up">
                <i class="fas fa-chart-line"></i> Nilai Keranjang Rata-rata
            </div>
        </div>
        <div class="icon" style="background: #fffbeb; color: #d97706;">
            <i class="fas fa-calculator"></i>
        </div>
    </div>

    <div class="stat-widget">
        <div>
            <div class="lbl">Produk Terjual</div>
            <div class="val">
                {{ number_format($reportData['items_sold'] ?? 0) }} Unit
            </div>
            <div class="stat-trend trend-up" style="color: var(--text-muted);">
                <i class="fas fa-cubes"></i> Total Barang Keluar
            </div>
        </div>
        <div class="icon" style="background: #fef2f2; color: #ef4444;">
            <i class="fas fa-box"></i>
        </div>
    </div>
</div>

{{-- Panel Graphic: Revenue 30 Hari --}}
<div class="panel fade-up delay-2">
    <div class="panel-header">
        <div class="panel-title">
            <i class="fas fa-chart-area"></i> Grafik Tren Pendapatan 30 Hari Terakhir
        </div>
        <div style="display: flex; gap: 6px;">
            <a href="?period=7" class="btn-ag btn-ghost btn-sm" style="{{ request('period') == '7' ? 'background: var(--brand-primary); color: #fff; border-color: var(--brand-primary);' : '' }}">7 Hari</a>
            <a href="?period=30" class="btn-ag btn-ghost btn-sm" style="{{ request('period', '30') == '30' ? 'background: var(--brand-primary); color: #fff; border-color: var(--brand-primary);' : '' }}">30 Hari</a>
            <a href="?period=90" class="btn-ag btn-ghost btn-sm" style="{{ request('period') == '90' ? 'background: var(--brand-primary); color: #fff; border-color: var(--brand-primary);' : '' }}">90 Hari</a>
        </div>
    </div>

    <div style="height: 280px; position: relative;">
        <canvas id="reportRevenueChart"></canvas>
    </div>
</div>

{{-- 2-Column: Produk Terlaris & Revenue per Kategori --}}
<div class="grid-2-1 fade-up delay-3">

    {{-- Panel Produk Terlaris --}}
    <div class="panel">
        <div class="panel-header">
            <div class="panel-title">
                <i class="fas fa-trophy"></i> Produk Terlaris (Top Selling)
            </div>
        </div>

        <div style="display: flex; flex-direction: column; gap: 14px;">
            @forelse($topProducts ?? [] as $i => $product)
            <div style="display: flex; align-items: center; gap: 14px; padding-bottom: 12px; border-bottom: 1px solid var(--border);">
                <div style="width: 32px; height: 32px; border-radius: 10px; 
                            background: {{ $i === 0 ? '#fef3c7' : ($i === 1 ? '#e0e7ff' : '#f1f5f9') }}; 
                            color: {{ $i === 0 ? '#d97706' : ($i === 1 ? '#4f46e5' : 'var(--text-muted)') }};
                            display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 800; flex-shrink: 0;">
                    {{ $i + 1 }}
                </div>
                <div style="flex: 1; min-width: 0;">
                    <div style="font-size: 13.5px; font-weight: 700; color: var(--text-main); line-height: 1.3; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                        {{ $product->name ?? 'Produk #'.($i+1) }}
                    </div>
                    <div style="font-size: 12px; color: var(--text-muted); margin-top: 2px;">
                        <i class="fas fa-box" style="font-size: 10px;"></i> {{ $product->total_sold ?? 0 }} Terjual
                    </div>
                </div>
                <div style="text-align: right; flex-shrink: 0;">
                    <div style="font-size: 14px; font-weight: 800; color: var(--brand-primary);">
                        Rp {{ number_format($product->total_revenue ?? 0, 0, ',', '.') }}
                    </div>
                </div>
            </div>
            @empty
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <p>Belum ada data penjualan produk.</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- Panel Revenue per Kategori --}}
    <div class="panel">
        <div class="panel-header">
            <div class="panel-title">
                <i class="fas fa-pie-chart"></i> Pendapatan per Kategori
            </div>
        </div>

        <div style="display: flex; flex-direction: column; gap: 16px;">
            @forelse($categoryChart ?? [] as $i => $cat)
            @php
                $maxTotal = max(collect($categoryChart ?? [])->pluck('total')->max(), 1);
                $pct = min(round(($cat['total'] / $maxTotal) * 100), 100);
                $colors = ['#2563eb', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'];
            @endphp
            <div>
                <div style="display: flex; justify-content: space-between; font-size: 13px; font-weight: 700; margin-bottom: 6px;">
                    <span style="color: var(--text-main);">{{ $cat['name'] }}</span>
                    <span style="color: var(--brand-primary);">Rp {{ number_format($cat['total'], 0, ',', '.') }}</span>
                </div>
                <div style="height: 8px; background: var(--surface-2); border-radius: 99px; overflow: hidden; border: 1px solid var(--border);">
                    <div style="height: 100%; width: {{ $pct }}%; background: {{ $colors[$i % count($colors)] }}; border-radius: 99px; transition: width 0.8s ease;"></div>
                </div>
            </div>
            @empty
            <div class="empty-state">
                <i class="fas fa-chart-pie"></i>
                <p>Belum ada data pendapatan per kategori.</p>
            </div>
            @endforelse
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const data = @json($revenueChart ?? []);
    const canvas = document.getElementById('reportRevenueChart');
    if (!canvas) return;

    const ctx = canvas.getContext('2d');
    const gradient = ctx.createLinearGradient(0, 0, 0, 260);
    gradient.addColorStop(0, 'rgba(37, 99, 235, 0.25)');
    gradient.addColorStop(1, 'rgba(37, 99, 235, 0.00)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.map(d => d.date ? d.date.slice(5) : ''),
            datasets: [{
                label: 'Revenue (Rp)',
                data: data.map(d => d.total),
                fill: true,
                backgroundColor: gradient,
                borderColor: '#2563eb',
                borderWidth: 3,
                pointBackgroundColor: '#2563eb',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6,
                tension: 0.35,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#0f172a',
                    titleColor: '#94a3b8',
                    bodyColor: '#ffffff',
                    bodyFont: { weight: 'bold', size: 13 },
                    padding: 12,
                    cornerRadius: 10,
                    callbacks: {
                        label: function(context) {
                            return 'Revenue: Rp ' + context.raw.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { color: '#64748b', font: { family: 'Inter', size: 11 } }
                },
                y: {
                    grid: { color: '#f1f5f9' },
                    ticks: {
                        color: '#64748b',
                        font: { family: 'Inter', size: 11 },
                        callback: function(v) {
                            return 'Rp ' + (v / 1000).toLocaleString('id-ID') + 'k';
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush
