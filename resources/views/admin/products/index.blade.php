@extends('admin.layouts.app')
@section('title', 'Daftar Produk')
@section('page-title', 'Daftar Produk')

@section('breadcrumb')
    <i class="fas fa-chevron-right"></i>
    <span>Katalog</span>
@endsection

@section('content')
<div class="fade-up" style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
    <div>
        <h2 style="font-size: 20px; font-weight: 800; color: var(--text-main); line-height: 1.2;">Manajemen Katalog Produk</h2>
        <p style="font-size: 13px; color: var(--text-muted); margin-top: 3px;">Kelola inventaris, harga, dan ketersediaan produk di toko online.</p>
    </div>
    <div style="display: flex; gap: 10px;">
        <a href="{{ route('admin.products.export') }}" class="btn-ag btn-ghost">
            <i class="fas fa-file-export"></i> Ekspor CSV
        </a>
        <a href="{{ route('admin.products.create') }}" class="btn-ag btn-primary">
            <i class="fas fa-plus"></i> Tambah Produk Baru
        </a>
    </div>
</div>

{{-- Filter Card --}}
<div class="panel fade-up delay-1" style="padding: 16px 20px;">
    <form action="{{ route('admin.products.index') }}" method="GET" style="display: flex; gap: 14px; align-items: center; flex-wrap: wrap;">
        <div style="flex: 1; min-width: 240px; position: relative;">
            <i class="fas fa-search" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--text-muted); font-size: 13px;"></i>
            <input class="form-input" style="padding-left: 40px;" type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama produk atau SKU...">
        </div>

        <div style="width: 180px;">
            <select class="form-select" name="category_id" onchange="this.form.submit()">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>

        <div style="width: 160px;">
            <select class="form-select" name="status" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                <option value="low" {{ request('status') == 'low' ? 'selected' : '' }}>Stok Menipis (≤5)</option>
            </select>
        </div>

        @if(request()->hasAny(['search', 'category_id', 'status']))
            <a href="{{ route('admin.products.index') }}" class="btn-ag btn-ghost btn-sm" title="Reset Filter">
                <i class="fas fa-redo"></i> Reset
            </a>
        @endif
    </form>
</div>

{{-- Product Table Panel --}}
<div class="panel fade-up delay-2" style="padding: 0; overflow: hidden;">
    <div style="overflow-x: auto;">
        <table class="ag-table">
            <thead>
                <tr>
                    <th style="width: 400px;">Informasi Produk</th>
                    <th>SKU</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Status</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr>
                    <td>
                        <div style="display: flex; align-items: center; gap: 14px;">
                            <div style="width: 48px; height: 48px; border-radius: 10px; overflow: hidden; border: 1px solid var(--border); background: var(--surface-2); flex-shrink: 0;">
                                <img src="{{ asset('storage/' . ($product->primaryImage?->image_path ?? 'products/default.png')) }}" 
                                     alt="{{ $product->name }}" 
                                     onerror="this.src='https://placehold.co/48x48?text=Produk'"
                                     style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            <div>
                                <a href="{{ route('admin.products.edit', $product) }}" style="font-weight: 700; color: var(--text-main); font-size: 13.5px; line-height: 1.3; display: block; transition: color 0.15s;" onmouseover="this.style.color='var(--brand-primary)'" onmouseout="this.style.color='var(--text-main)'">
                                    {{ $product->name }}
                                </a>
                                <div style="font-size: 11.5px; color: var(--text-muted); margin-top: 2px;">
                                    {{ $product->brand->name ?? 'Tanpa Merek' }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span style="font-family: monospace; font-size: 12px; font-weight: 600; color: var(--brand-primary); background: var(--info-bg); padding: 3px 8px; border-radius: 6px; border: 1px solid var(--info-border);">
                            {{ $product->sku }}
                        </span>
                    </td>
                    <td>
                        <span style="font-size: 12.5px; color: var(--text-secondary); font-weight: 500;">
                            📁 {{ $product->category->name ?? '-' }}
                        </span>
                    </td>
                    <td>
                        <div>
                            <span style="font-weight: 700; color: var(--text-main); font-size: 13.5px;">
                                Rp{{ number_format($product->price, 0, ',', '.') }}
                            </span>
                            @if($product->sale_price)
                                <div style="font-size: 11px; color: var(--text-muted); text-decoration: line-through;">
                                    Rp{{ number_format($product->sale_price, 0, ',', '.') }}
                                </div>
                            @endif
                        </div>
                    </td>
                    <td>
                        <span class="badge-ag {{ $product->stock === 0 ? 'badge-danger' : ($product->stock <= 5 ? 'badge-warning' : 'badge-success') }}">
                            <i class="fas {{ $product->stock === 0 ? 'fa-times' : ($product->stock <= 5 ? 'fa-exclamation-triangle' : 'fa-check') }}"></i>
                            {{ $product->stock }} Unit
                        </span>
                    </td>
                    <td>
                        <span class="badge-ag {{ $product->is_active ? 'badge-success' : 'badge-gray' }}">
                            {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td style="text-align: right;">
                        <div style="display: inline-flex; gap: 6px;">
                            <a href="{{ route('admin.products.edit', $product) }}" class="btn-ag btn-ghost btn-sm" title="Edit Produk">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" style="display: inline;" id="del-{{ $product->id }}">
                                @csrf @method('DELETE')
                                <button type="button" class="btn-ag btn-danger-subtle btn-sm" title="Hapus Produk" onclick="confirmDeleteProduct({{ $product->id }}, '{{ addslashes($product->name) }}')">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="padding: 48px 24px; text-align: center;">
                        <div style="max-width: 320px; margin: 0 auto; color: var(--text-muted);">
                            <i class="fas fa-box-open" style="font-size: 36px; color: #cbd5e1; margin-bottom: 12px; display: block;"></i>
                            <div style="font-weight: 700; color: var(--text-main); font-size: 15px; margin-bottom: 4px;">Tidak Ada Produk</div>
                            <p style="font-size: 12px; margin-bottom: 16px;">Tidak ada produk yang cocok dengan pencarian atau filter yang Anda pilih.</p>
                            <a href="{{ route('admin.products.create') }}" class="btn-ag btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Tambah Produk Baru
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($products->hasPages())
    <div style="padding: 16px 24px; border-top: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; background: var(--panel-bg);">
        {{ $products->links() }}
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
function confirmDeleteProduct(id, name) {
    confirmAction({
        title: 'Hapus Produk?',
        body: 'Apakah Anda yakin ingin menghapus "' + name + '"? Data yang dihapus tidak bisa dikembalikan.',
        label: 'Ya, Hapus',
        onConfirm: () => {
            document.getElementById('del-' + id).submit();
        }
    });
}
</script>
@endpush
