@extends('admin.layouts.app')
@section('title', 'Produk Terlaris')
@section('page-title', 'Laporan')

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:22px;" class="fade-up">
    <div>
        <h2 style="font-size:22px;font-weight:700;">Produk Terlaris</h2>
        <p style="font-size:13px;color:var(--muted);margin-top:3px;">Ranking produk berdasarkan volume penjualan</p>
    </div>
    <a href="{{ Route::has('admin.reports.revenue') ? route('admin.reports.revenue') : '#' }}" class="btn-ag btn-ghost">
        <i class="fas fa-chart-line"></i> Laporan Revenue
    </a>
</div>

<div class="glass-card fade-up delay-1">
    <table class="ag-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Produk</th>
                <th>Kategori</th>
                <th>Total Terjual</th>
                <th>Revenue</th>
                <th>Rating</th>
            </tr>
        </thead>
        <tbody>
            @forelse($topProducts ?? [] as $i => $product)
            <tr>
                <td>
                    <div style="width:28px;height:28px;border-radius:8px;
                                background:{{ ['var(--blue-dim)','var(--amber-dim)','var(--green-dim)'][$i % 3] }};
                                color:{{ ['var(--blue)','var(--amber)','var(--green)'][$i % 3] }};
                                display:flex;align-items:center;justify-content:center;
                                font-size:12px;font-weight:700;">
                        {{ $i + 1 }}
                    </div>
                </td>
                <td>
                    <div style="display:flex;align-items:center;gap:10px;">
                        <div style="width:44px;height:44px;border-radius:10px;overflow:hidden;background:var(--surface-2);">
                            <img src="{{ $product->image_url ?? 'https://placehold.co/44x44/050B1A/00D4FF?text=P' }}" style="width:100%;height:100%;object-fit:cover;">
                        </div>
                        <div style="font-size:13px;font-weight:500;">{{ $product->name }}</div>
                    </div>
                </td>
                <td style="font-size:12px;color:var(--text-2);">{{ $product->category->name ?? '-' }}</td>
                <td><span class="dm-mono" style="font-size:13px;font-weight:600;color:var(--amber);">{{ $product->total_sold ?? 0 }}</span></td>
                <td><span class="dm-mono" style="font-size:13px;font-weight:600;color:var(--blue);">Rp{{ number_format(($product->total_revenue ?? 0)/1000,0,',','.')}}k</span></td>
                <td>
                    <div style="display:flex;align-items:center;gap:4px;">
                        <span style="color:var(--amber);">★</span>
                        <span class="dm-mono" style="font-size:12px;">{{ number_format($product->avg_rating ?? 0, 1) }}</span>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center;padding:50px 0;color:var(--muted);">
                    <i class="fas fa-chart-bar" style="font-size:28px;display:block;margin-bottom:10px;opacity:0.25;"></i>
                    Belum ada data penjualan
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
