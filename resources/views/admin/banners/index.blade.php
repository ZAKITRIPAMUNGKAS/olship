@extends('admin.layouts.app')
@section('title', 'Banner')
@section('page-title', 'Banner')

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:22px;" class="fade-up">
    <div>
        <h2 style="font-size:22px;font-weight:700;">Manajemen Banner</h2>
        <p style="font-size:13px;color:var(--muted);margin-top:3px;">Kelola banner & konten promosi homepage</p>
    </div>
    <a href="{{ Route::has('admin.banners.create') ? route('admin.banners.create') : '#' }}" class="btn-ag btn-primary">
        <i class="fas fa-plus"></i> Tambah Banner
    </a>
</div>

<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:16px;" class="fade-up delay-1">
    @forelse($banners ?? [] as $banner)
    <div class="glass-card" style="overflow:hidden;">
        <div style="height:160px;overflow:hidden;position:relative;background:var(--surface-2);">
            <img src="{{ $banner->image_url ?? 'https://placehold.co/400x160/050B1A/00D4FF?text=Banner' }}"
                 alt="{{ $banner->title }}" style="width:100%;height:100%;object-fit:cover;">
            <div style="position:absolute;top:10px;right:10px;">
                <span class="badge-ag {{ $banner->is_active ? 'badge-completed' : 'badge-cancelled' }}">
                    {{ $banner->is_active ? 'Aktif' : 'Nonaktif' }}
                </span>
            </div>
            <div style="position:absolute;top:10px;left:10px;">
                <span style="background:rgba(5,11,26,0.8);backdrop-filter:blur(8px);
                             padding:3px 8px;border-radius:6px;font-size:11px;color:var(--muted);
                             font-family:'DM Mono',monospace;">
                    #{{ $banner->sort_order ?? 0 }}
                </span>
            </div>
        </div>
        <div style="padding:16px 18px;">
            <div style="font-size:14px;font-weight:600;margin-bottom:4px;">{{ $banner->title }}</div>
            @if($banner->link)
            <div style="font-size:11px;color:var(--muted);margin-bottom:12px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                <i class="fas fa-link" style="margin-right:4px;"></i>{{ $banner->link }}
            </div>
            @endif
            <div style="display:flex;gap:8px;">
                <a href="{{ Route::has('admin.banners.edit') ? route('admin.banners.edit', $banner) : '#' }}"
                   class="btn-ag btn-ghost btn-sm"><i class="fas fa-pen"></i> Edit</a>
                @if(Route::has('admin.banners.destroy'))
                <form action="{{ route('admin.banners.destroy', $banner) }}" method="POST" onsubmit="event.preventDefault(); confirmAction({title: 'Hapus Banner?', body: 'Apakah kamu yakin ingin menghapus banner ini?', label: 'Ya, Hapus', onConfirm: () => { this.submit(); }})">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-ag btn-ghost btn-sm" style="color:var(--danger);">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div style="grid-column:1/-1;">
        <div class="empty-state glass-card">
            <i class="fas fa-image"></i>
            <p>Belum ada banner ditemukan.<br>
               Tambahkan banner untuk halaman depan.</p>
            <a href="{{ Route::has('admin.banners.create') ? route('admin.banners.create') : '#' }}" class="btn-ag btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah Banner
            </a>
        </div>
    </div>
    @endforelse
</div>
@endsection
