@extends('admin.layouts.app')
@section('title', 'Merek')
@section('page-title', 'Merek')

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:22px;" class="fade-up">
    <div>
        <h2 style="font-size:22px;font-weight:700;">Manajemen Merek</h2>
        <p style="font-size:13px;color:var(--muted);margin-top:3px;">Kelola brand dan merek produk</p>
    </div>
    <a href="{{ route('admin.brands.create') }}" class="btn-ag btn-primary"><i class="fas fa-plus"></i> Tambah Merek</a>
</div>

<div class="glass-card fade-up delay-1">
    <div class="table-responsive">
    <table class="ag-table">
        <thead>
            <tr>
                <th>Merek</th>
                <th>Website</th>
                <th>Produk</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($brands as $brand)
            <tr>
                <td>
                    <div style="display:flex;align-items:center;gap:10px;">
                        @if($brand->logo)
                        <img src="{{ asset('storage/'.$brand->logo) }}" style="width:36px;height:36px;object-fit:contain;border-radius:6px;background:var(--surface-2);padding:4px;">
                        @else
                        <div style="width:36px;height:36px;border-radius:6px;background:var(--surface-2);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:14px;color:var(--muted);">{{ strtoupper(substr($brand->name,0,1)) }}</div>
                        @endif
                        <span style="font-size:13px;font-weight:500;">{{ $brand->name }}</span>
                    </div>
                </td>
                <td>
                    @if($brand->website)
                    <a href="{{ $brand->website }}" target="_blank" style="color:var(--blue);font-size:12px;text-decoration:none;">{{ $brand->website }}</a>
                    @else
                    <span style="color:var(--muted);font-size:12px;">—</span>
                    @endif
                </td>
                <td><span class="dm-mono" style="font-size:13px;color:var(--amber);">{{ $brand->products_count }}</span></td>
                <td>
                    <span class="badge-ag {{ $brand->is_active ? 'badge-completed' : 'badge-cancelled' }}">
                        {{ $brand->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </td>
                <td>
                    <div style="display:flex;gap:6px;">
                        <a href="{{ route('admin.brands.edit', $brand) }}" class="btn-ag btn-ghost btn-sm"><i class="fas fa-pen"></i></a>
                        <form action="{{ route('admin.brands.destroy', $brand) }}" method="POST" onsubmit="event.preventDefault(); confirmAction({title: 'Hapus Merek?', body: 'Apakah kamu yakin ingin menghapus merek ini?', label: 'Ya, Hapus', onConfirm: () => { this.submit(); }})">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-ag btn-ghost btn-sm" style="color:var(--danger);"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5">
                    <div class="empty-state">
                        <i class="fas fa-trademark"></i>
                        <p>Belum ada merek ditemukan.<br>
                           Tambahkan merek untuk produk Anda.</p>
                        <a href="{{ route('admin.brands.create') }}" class="btn-ag btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Tambah Merek
                        </a>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>
    @if($brands->hasPages())
    <div style="padding:16px 22px;border-top:1px solid var(--border);">{{ $brands->links() }}</div>
    @endif
</div>
@endsection
