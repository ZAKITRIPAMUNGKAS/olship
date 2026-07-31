@extends('admin.layouts.app')
@section('title', 'Flash Sale')
@section('page-title', 'Flash Sale')

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:22px;" class="fade-up">
    <div>
        <h2 style="font-size:22px;font-weight:700;">Flash Sale</h2>
        <p style="font-size:13px;color:var(--muted);margin-top:3px;">Kelola event flash sale & diskon kilat</p>
    </div>
    <a href="{{ Route::has('admin.flash-sales.create') ? route('admin.flash-sales.create') : '#' }}" class="btn-ag btn-primary">
        <i class="fas fa-fire"></i> Buat Flash Sale
    </a>
</div>

<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(360px,1fr));gap:18px;" class="fade-up delay-1">
    @forelse($flashSales ?? [] as $sale)
    <div class="glass-card" style="padding:22px;position:relative;overflow:hidden;">
        <div style="position:absolute;top:0;left:0;right:0;height:2px;background:linear-gradient(90deg,var(--danger),var(--amber));"></div>

        {{-- Badge Active/Expired --}}
        @php $isActive = $sale->is_active && $sale->starts_at <= now() && $sale->ends_at >= now(); @endphp
        <span class="badge-ag {{ $isActive ? 'badge-completed' : 'badge-cancelled' }}"
              style="position:absolute;top:16px;right:16px;">
            <i class="fas fa-circle" style="font-size:7px;"></i>
            {{ $isActive ? 'LIVE' : 'Inactive' }}
        </span>

        <div style="font-size:16px;font-weight:700;margin-bottom:6px;">{{ $sale->name }}</div>

        {{-- Countdown / Time --}}
        <div style="font-family:'DM Mono',monospace;font-size:12px;color:var(--muted);margin-bottom:14px;">
            <i class="fas fa-clock" style="color:var(--amber);"></i>
            {{ \Carbon\Carbon::parse($sale->starts_at)->format('d M Y H:i') }} – {{ \Carbon\Carbon::parse($sale->ends_at)->format('d M Y H:i') }}
        </div>

        <div style="display:flex;gap:6px;flex-wrap:wrap;margin-bottom:14px;">
            @foreach($sale->items ?? [] as $item)
            <span style="padding:3px 10px;border-radius:8px;background:var(--danger-dim);color:var(--danger);font-size:11px;">
                {{ $item->product->name ?? 'Produk' }}
            </span>
            @endforeach
        </div>

        <div style="display:flex;gap:8px;align-items:center;margin-top:4px;">
            @if(Route::has('admin.flash-sales.toggle'))
            <form action="{{ route('admin.flash-sales.toggle', $sale) }}" method="POST">
                @csrf
                <button class="btn-ag btn-ghost btn-sm" style="color:{{ $sale->is_active ? 'var(--danger)' : 'var(--green)' }};">
                    <i class="fas fa-{{ $sale->is_active ? 'pause' : 'play' }}"></i>
                    {{ $sale->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                </button>
            </form>
            @endif
            <a href="{{ Route::has('admin.flash-sales.edit') ? route('admin.flash-sales.edit', $sale) : '#' }}"
               class="btn-ag btn-ghost btn-sm">
                <i class="fas fa-pen"></i> Edit
            </a>
        </div>
    </div>
    @empty
    <div style="grid-column:1/-1;">
        <div class="empty-state glass-card">
            <i class="fas fa-fire"></i>
            <p>Belum ada flash sale ditemukan.<br>
               Buat event flash sale pertamamu sekarang.</p>
            <a href="{{ Route::has('admin.flash-sales.create') ? route('admin.flash-sales.create') : '#' }}" class="btn-ag btn-primary btn-sm">
                <i class="fas fa-fire"></i> Buat Flash Sale
            </a>
        </div>
    </div>
    @endforelse
</div>
@endsection
