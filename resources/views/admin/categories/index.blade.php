@extends('admin.layouts.app')
@section('title', 'Kategori')
@section('page-title', 'Kategori')

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:22px;" class="fade-up">
    <div>
        <h2 style="font-size:22px;font-weight:700;">Manajemen Kategori</h2>
        <p style="font-size:13px;color:var(--muted);margin-top:3px;">Kelola hierarki kategori produk</p>
    </div>
    <a href="{{ route('admin.categories.create') }}" class="btn-ag btn-primary">
        <i class="fas fa-plus"></i> Tambah Kategori
    </a>
</div>

<div class="glass-card fade-up delay-1">
    <div class="table-responsive">
    <table class="ag-table">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Icon</th>
                <th>Produk</th>
                <th>Sub-Kategori</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($categories as $cat)
            <tr>
                <td>
                    <div style="font-size:13px;font-weight:600;">{{ $cat->name }}</div>
                    <div style="font-size:11px;color:var(--muted);">{{ $cat->slug }}</div>
                </td>
                <td>
                    @if($cat->icon_class)
                        <i class="fas {{ $cat->icon_class }}" style="font-size:16px;color:var(--blue);"></i>
                    @else
                        <span style="color:var(--muted);font-size:12px;">—</span>
                    @endif
                </td>
                <td><span class="dm-mono" style="font-size:13px;color:var(--amber);">{{ $cat->products_count ?? $cat->products->count() }}</span></td>
                <td><span class="dm-mono" style="font-size:12px;color:var(--muted);">{{ $cat->children->count() }}</span></td>
                <td>
                    <span class="badge-ag {{ $cat->is_active ? 'badge-completed' : 'badge-cancelled' }}">
                        {{ $cat->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </td>
                <td>
                    <div style="display:flex;gap:6px;">
                        <a href="{{ route('admin.categories.edit', $cat) }}" class="btn-ag btn-ghost btn-sm"><i class="fas fa-pen"></i></a>
                        <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST" onsubmit="event.preventDefault(); confirmAction({title: 'Hapus Kategori?', body: 'Apakah kamu yakin ingin menghapus kategori ini?', label: 'Ya, Hapus', onConfirm: () => { this.submit(); }})">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-ag btn-ghost btn-sm" style="color:var(--danger);"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @if($cat->children->count())
                @foreach($cat->children as $child)
                <tr>
                    <td>
                        <div style="font-size:12px;font-weight:500;color:var(--text-2);padding-left:20px;">↳ {{ $child->name }}</div>
                    </td>
                    <td><span style="color:var(--muted);font-size:12px;">—</span></td>
                    <td><span class="dm-mono" style="font-size:12px;color:var(--muted);">{{ $child->products->count() }}</span></td>
                    <td><span style="font-size:12px;color:var(--muted);">—</span></td>
                    <td>
                        <span class="badge-ag {{ $child->is_active ? 'badge-completed' : 'badge-cancelled' }}" style="font-size:10px;padding:2px 6px;">
                            {{ $child->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td>
                        <div style="display:flex;gap:6px;">
                            <a href="{{ route('admin.categories.edit', $child) }}" class="btn-ag btn-ghost btn-sm"><i class="fas fa-pen"></i></a>
                            <form action="{{ route('admin.categories.destroy', $child) }}" method="POST" onsubmit="event.preventDefault(); confirmAction({title: 'Hapus Sub-Kategori?', body: 'Apakah kamu yakin ingin menghapus sub-kategori ini?', label: 'Ya, Hapus', onConfirm: () => { this.submit(); }})">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-ag btn-ghost btn-sm" style="color:var(--danger);"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            @endif
            @empty
            <tr>
                <td colspan="6">
                    <div class="empty-state">
                        <i class="fas fa-layer-group"></i>
                        <p>Belum ada kategori ditemukan.<br>
                           Tambahkan kategori untuk mengelola produk.</p>
                        <a href="{{ route('admin.categories.create') }}" class="btn-ag btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Tambah Kategori
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
