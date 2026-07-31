@extends('admin.layouts.app')
@section('title', 'Detail Seller')
@section('page-title', 'Seller')

@section('content')
<div style="display:flex;align-items:center;gap:12px;margin-bottom:22px;" class="fade-up">
    <a href="{{ route('admin.sellers.index') }}" style="color:var(--muted);text-decoration:none;font-size:13px;display:flex;align-items:center;gap:6px;transition:color 0.2s;" onmouseover="this.style.color='var(--blue)'" onmouseout="this.style.color='var(--muted)'">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
    <span style="color:var(--border);">/</span>
    <h2 style="font-size:20px;font-weight:700;">Detail Toko: {{ $store->name }}</h2>
</div>

<div style="display:grid;grid-template-columns:340px 1fr;gap:20px;" class="fade-up delay-1">

    {{-- Left: Store Profile --}}
    <div style="display:flex;flex-direction:column;gap:18px;">
        <div class="glass-card panel" style="text-align:center;padding:32px 24px;">
            <div style="width:100px;height:100px;border-radius:24px;overflow:hidden;background:var(--surface-2);margin:0 auto 18px;border:2px solid var(--border);">
                <img src="{{ $store->logo_url ?? 'https://placehold.co/100x100/050B1A/00D4FF?text='.strtoupper(substr($store->name,0,1)) }}" style="width:100%;height:100%;object-fit:cover;">
            </div>
            <h3 style="font-size:18px;font-weight:700;margin-bottom:4px;">{{ $store->name }}</h3>
            <span class="badge-ag {{ $store->verification_status === 'verified' ? 'badge-completed' : 'badge-pending' }}" style="margin-bottom:20px;">
                {{ ucfirst($store->verification_status ?? 'pending') }}
            </span>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:24px;padding-top:20px;border-top:1px solid var(--border);">
                <div>
                    <div style="font-size:16px;font-weight:700;color:var(--blue);">{{ $store->products_count ?? $store->products->count() }}</div>
                    <div style="font-size:10px;color:var(--muted);text-transform:uppercase;">Produk</div>
                </div>
                <div>
                    <div style="font-size:16px;font-weight:700;color:var(--amber);">{{ number_format($store->avg_rating ?? 0, 1) }} ★</div>
                    <div style="font-size:10px;color:var(--muted);text-transform:uppercase;">Rating</div>
                </div>
            </div>

            <div style="text-align:left;font-size:13px;display:flex;flex-direction:column;gap:12px;">
                <div>
                    <div style="font-size:10px;color:var(--muted);text-transform:uppercase;margin-bottom:4px;">Pemilik</div>
                    <div style="font-weight:600;">{{ $store->seller->name ?? '-' }}</div>
                    <div style="font-size:12px;color:var(--text-2);">{{ $store->seller->email ?? '-' }}</div>
                </div>
                <div>
                    <div style="font-size:10px;color:var(--muted);text-transform:uppercase;margin-bottom:4px;">Lokasi</div>
                    <div style="font-weight:600;">{{ $store->city ?? 'Jakarta' }}, {{ $store->province ?? 'Indonesia' }}</div>
                </div>
                <div>
                    <div style="font-size:10px;color:var(--muted);text-transform:uppercase;margin-bottom:4px;">Bergabung</div>
                    <div style="font-weight:600;">{{ $store->created_at->format('d M Y') }}</div>
                </div>
            </div>
        </div>

        @if($store->verification_status === 'pending')
        <div class="glass-card panel">
            <div class="panel-title" style="margin-bottom:14px;">🛠️ Verifikasi</div>
            <div style="display:flex;gap:10px;">
                <form action="{{ route('admin.sellers.verify', $store) }}" method="POST" style="flex:1;">
                    @csrf
                    <button class="btn-ag btn-primary" style="width:100%;justify-content:center;">Approve</button>
                </form>
                <form action="{{ route('admin.sellers.suspend', $store) }}" method="POST" style="flex:1;">
                    @csrf
                    <button class="btn-ag btn-ghost" style="width:100%;justify-content:center;color:var(--danger);">Reject</button>
                </form>
            </div>
        </div>
        @endif
    </div>

    {{-- Right: Content --}}
    <div style="display:flex;flex-direction:column;gap:18px;">
        <div class="glass-card panel">
            <div class="panel-header"><div class="panel-title">📝 Deskripsi Toko</div></div>
            <div style="font-size:14px;color:var(--text-2);line-height:1.6;">
                {{ $store->description ?? 'Tidak ada deskripsi tersedia untuk toko ini.' }}
            </div>
        </div>

        <div class="glass-card panel">
            <div class="panel-header"><div class="panel-title">📦 Produk Populer</div></div>
            <table class="ag-table">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Terjual</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($store->products->take(5) as $product)
                    <tr>
                        <td style="font-size:13px;font-weight:600;">{{ $product->name }}</td>
                        <td><span class="dm-mono">Rp{{ number_format($product->price,0,',','.') }}</span></td>
                        <td><span class="dm-mono">{{ $product->stock }}</span></td>
                        <td><span class="dm-mono">{{ $product->orders_count ?? 0 }}</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="4" style="text-align:center;padding:20px;color:var(--muted);">Belum ada produk</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
