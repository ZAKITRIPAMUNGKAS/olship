@extends('admin.layouts.app')
@section('title', 'Seller')
@section('page-title', 'Seller')

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:22px;" class="fade-up">
    <div>
        <h2 style="font-size:22px;font-weight:700;">Manajemen Seller</h2>
        <p style="font-size:13px;color:var(--muted);margin-top:3px;">Verifikasi dan pantau toko seller</p>
    </div>
    <div style="display:flex;gap:10px;">
        <a href="{{ Route::has('admin.withdrawals.index') ? route('admin.withdrawals.index') : '#' }}" class="btn-ag btn-ghost">
            <i class="fas fa-money-bill-transfer"></i> Penarikan Dana
        </a>
    </div>
</div>

{{-- Filter --}}
<div style="display:flex;gap:6px;margin-bottom:18px;" class="fade-up delay-1">
    @foreach(['all'=>'Semua','pending'=>'Menunggu','verified'=>'Terverifikasi','rejected'=>'Ditolak'] as $key => $label)
    <a href="?status={{ $key }}"
       style="padding:7px 16px;border-radius:20px;font-size:12px;text-decoration:none;
              border:1px solid {{ request('status','all') === $key ? 'var(--blue)' : 'var(--border)' }};
              background:{{ request('status','all') === $key ? 'var(--blue-dim)' : 'var(--surface)' }};
              color:{{ request('status','all') === $key ? 'var(--blue)' : 'var(--muted)' }};">
        {{ $label }}
    </a>
    @endforeach
</div>

<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:16px;" class="fade-up delay-2">
    @forelse($stores ?? [] as $store)
    <div class="glass-card" style="padding:22px;">
        <div style="display:flex;gap:14px;align-items:flex-start;margin-bottom:16px;">
            <div style="width:52px;height:52px;border-radius:12px;overflow:hidden;background:var(--surface-2);flex-shrink:0;">
                <img src="{{ $store->logo ?? 'https://placehold.co/52x52/050B1A/00D4FF?text='.strtoupper(substr($store->name,0,1)) }}"
                     alt="" style="width:100%;height:100%;object-fit:cover;">
            </div>
            <div style="flex:1;">
                <div style="font-size:15px;font-weight:700;">{{ $store->name }}</div>
                <div style="font-size:12px;color:var(--muted);">{{ $store->seller->name ?? '-' }}</div>
                <div style="font-size:11px;color:var(--muted);">{{ $store->seller->email ?? '-' }}</div>
            </div>
            <span class="badge-ag {{ $store->verification_status === 'verified' ? 'badge-completed' : ($store->verification_status === 'rejected' ? 'badge-cancelled' : 'badge-pending') }}">
                {{ ucfirst($store->verification_status ?? 'pending') }}
            </span>
        </div>

        <div style="font-size:12px;color:var(--text-2);margin-bottom:14px;line-height:1.6;">
            {{ Str::limit($store->description, 80) ?? 'Tidak ada deskripsi.' }}
        </div>

        <div style="display:flex;gap:8px;">
            <a href="{{ Route::has('admin.sellers.show') ? route('admin.sellers.show', $store) : '#' }}"
               class="btn-ag btn-ghost btn-sm"><i class="fas fa-eye"></i> Detail</a>
            @if($store->verification_status === 'pending')
                @if(Route::has('admin.sellers.verify'))
                <form action="{{ route('admin.sellers.verify', $store) }}" method="POST">
                    @csrf
                    <button class="btn-ag btn-ghost btn-sm" style="color:var(--green);">
                        <i class="fas fa-check"></i> Verifikasi
                    </button>
                </form>
                @endif
            @endif
        </div>
    </div>
    @empty
    <div class="glass-card" style="padding:50px;text-align:center;color:var(--muted);grid-column:1/-1;">
        <i class="fas fa-store" style="font-size:28px;display:block;margin-bottom:10px;opacity:0.25;"></i>
        Belum ada seller terdaftar
    </div>
    @endforelse
</div>
@endsection
