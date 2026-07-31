@extends('admin.layouts.app')
@section('title', 'Kupon')
@section('page-title', 'Kupon')

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:22px;" class="fade-up">
    <div>
        <h2 style="font-size:22px;font-weight:700;">Kupon & Promo</h2>
        <p style="font-size:13px;color:var(--muted);margin-top:3px;">Buat dan kelola kode promo</p>
    </div>
    <a href="{{ Route::has('admin.coupons.create') ? route('admin.coupons.create') : '#' }}" class="btn-ag btn-primary">
        <i class="fas fa-plus"></i> Buat Kupon
    </a>
</div>

<div class="glass-card fade-up delay-1">
    <div class="table-responsive">
    <table class="ag-table">
        <thead>
            <tr>
                <th>Kode</th>
                <th>Jenis Diskon</th>
                <th>Nilai</th>
                <th>Min. Pembelian</th>
                <th>Penggunaan</th>
                <th>Berlaku</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($coupons ?? [] as $coupon)
            <tr>
                <td>
                    <span style="font-family:'DM Mono',monospace;font-size:13px;font-weight:600;
                                 padding:4px 10px;border-radius:8px;background:var(--surface-2);
                                 color:var(--amber);letter-spacing:1px;">
                        {{ $coupon->code }}
                    </span>
                </td>
                <td style="font-size:12px;color:var(--text-2);">{{ $coupon->discount_type === 'percent' ? 'Persentase' : 'Nominal' }}</td>
                <td>
                    <span class="dm-mono" style="font-size:13px;font-weight:600;color:var(--green);">
                        {{ $coupon->discount_type === 'percent' ? $coupon->discount_value.'%' : 'Rp'.number_format($coupon->discount_value,0,',','.') }}
                    </span>
                </td>
                <td style="font-size:12px;color:var(--muted);">Rp{{ number_format($coupon->min_purchase ?? 0, 0, ',', '.') }}</td>
                <td>
                    <span class="dm-mono" style="font-size:12px;color:var(--text-2);">{{ $coupon->used_count ?? 0 }}</span>
                    @if($coupon->usage_limit)
                        <span style="color:var(--muted);font-size:11px;"> / {{ $coupon->usage_limit }}</span>
                    @endif
                </td>
                <td style="font-size:12px;color:var(--muted);">
                    @if($coupon->expires_at)
                        {{ $coupon->expires_at->format('d M Y') }}
                        @if($coupon->expires_at < now())
                            <span style="color:var(--danger);font-size:10px;display:block;">Expired</span>
                        @endif
                    @else
                        <span style="color:var(--muted);">∞</span>
                    @endif
                </td>
                <td>
                    <span class="badge-ag {{ $coupon->is_active ? 'badge-completed' : 'badge-cancelled' }}">
                        {{ $coupon->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </td>
                <td>
                    <div style="display:flex;gap:6px;">
                        <a href="{{ Route::has('admin.coupons.edit') ? route('admin.coupons.edit', $coupon) : '#' }}"
                           class="btn-ag btn-ghost btn-sm"><i class="fas fa-pen"></i></a>
                        @if(Route::has('admin.coupons.toggle'))
                        <form action="{{ route('admin.coupons.toggle', $coupon) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-ag btn-ghost btn-sm"
                                    style="color:{{ $coupon->is_active ? 'var(--danger)' : 'var(--green)' }};">
                                <i class="fas fa-{{ $coupon->is_active ? 'pause' : 'play' }}"></i>
                            </button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8">
                    <div class="empty-state">
                        <i class="fas fa-tag"></i>
                        <p>Belum ada kupon ditemukan.<br>
                           Buat kupon pertama Anda sekarang.</p>
                        <a href="{{ Route::has('admin.coupons.create') ? route('admin.coupons.create') : '#' }}" class="btn-ag btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Buat Kupon
                        </a>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>
</div>
@endsection
