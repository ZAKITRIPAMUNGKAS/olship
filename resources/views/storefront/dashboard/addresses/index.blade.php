@extends('storefront.dashboard.layout')

@section('title', 'Buku Alamat - LISTRINDO JAYA ELEKTRIK')

@section('dashboard-content')
<div style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
    <div>
        <h2 style="font-size: 22px; font-weight: 800; color: var(--ink); margin: 0 0 4px 0;">Buku Alamat</h2>
        <p style="color: var(--slate-500); font-size: 14px; margin: 0;">Kelola alamat pengiriman Anda.</p>
    </div>
    <a href="{{ route('dashboard.addresses.create') }}" class="db-btn db-btn-primary" style="padding: 10px 20px; font-size: 13.5px;">
        <i class="fas fa-plus"></i> Tambah Alamat Baru
    </a>
</div>

<div style="display: grid; gap: var(--space-4);">
    @forelse($addresses as $addr)
        <div class="db-card" style="position: relative; {{ $addr->is_default ? 'border: 1.5px solid var(--brand-color); box-shadow: var(--shadow-md);' : '' }}">
            @if($addr->is_default)
                <span class="db-badge" style="position: absolute; top: var(--space-4); right: var(--space-4); background: var(--brand-light); color: var(--brand-color); border: 1.5px solid rgba(2, 92, 202, 0.15);">Utama</span>
            @endif
            
            <div style="font-weight: 700; font-size: 11px; color: var(--neutral-text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: var(--space-3); display: inline-flex; align-items: center; gap: 6px;">
                <i class="fas fa-map-pin" style="color: {{ $addr->is_default ? 'var(--brand-color)' : 'var(--neutral-text-muted)' }};"></i> {{ $addr->label }}
            </div>
            
            <div style="font-size: 18px; font-weight: 700; color: var(--neutral-text-main); margin-bottom: 6px;">{{ $addr->recipient_name }}</div>
            
            <div style="color: var(--neutral-text-sub); font-size: 13.5px; margin-bottom: var(--space-4); display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-phone-alt" style="font-size: 12px; color: var(--neutral-text-muted);"></i> {{ $addr->phone }}
            </div>
            
            <div style="color: var(--neutral-text-main); font-size: 14.5px; line-height: 1.6; margin-bottom: 8px;">
                {{ $addr->address_detail }}
            </div>
            
            <div style="color: var(--neutral-text-main); font-size: 14px; font-weight: 600;">
                {{ $addr->city->name }}, {{ $addr->province->name }} {{ $addr->postal_code }}
            </div>

            <div class="db-card-actions">
                <a href="{{ route('dashboard.addresses.edit', $addr->id) }}" class="db-btn db-btn-secondary">
                    <i class="fas fa-edit"></i> Ubah Alamat
                </a>
                
                @if(!$addr->is_default)
                    <form action="{{ route('dashboard.addresses.set-default', $addr->id) }}" method="POST" style="margin: 0;">
                        @csrf
                        <button type="submit" class="db-btn db-btn-outline">
                            Jadikan Utama
                        </button>
                    </form>
                    
                    <form action="{{ route('dashboard.addresses.destroy', $addr->id) }}" method="POST" style="margin: 0;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="db-btn db-btn-danger" onclick="return confirm('Hapus alamat ini?')">
                            <i class="fas fa-trash-alt"></i> Hapus
                        </button>
                    </form>
                @endif
            </div>
        </div>
    @empty
        <div class="db-card" style="padding: 60px 20px; text-align: center; border-style: dashed; border-width: 2px;">
            <div style="width: 60px; height: 60px; border-radius: 50%; background: var(--slate-100); display: flex; align-items: center; justify-content: center; font-size: 24px; color: var(--slate-400); margin: 0 auto 16px;">
                <i class="fas fa-map-marked-alt"></i>
            </div>
            <p style="color: var(--slate-500); font-weight: 600; margin: 0 0 16px 0;">Anda belum memiliki daftar alamat pengiriman.</p>
            <a href="{{ route('dashboard.addresses.create') }}" class="db-btn db-btn-primary" style="padding: 10px 20px; font-size: 13.5px;">
                <i class="fas fa-plus"></i> Tambah Alamat Baru
            </a>
        </div>
    @endforelse
</div>
@endsection
