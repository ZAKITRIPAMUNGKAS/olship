@extends('admin.layouts.app')
@section('title', 'Edit Flash Sale')
@section('page-title', 'Flash Sale')

@section('content')
<div style="display:flex;align-items:center;gap:12px;margin-bottom:22px;" class="fade-up">
    <a href="{{ route('admin.flash-sales.index') }}" style="color:var(--muted);text-decoration:none;font-size:13px;"><i class="fas fa-arrow-left"></i> Kembali</a>
    <span style="color:var(--border);">/</span>
    <h2 style="font-size:20px;font-weight:700;">Edit: {{ $flashSale->name }}</h2>
</div>

<div style="display:grid;grid-template-columns:1fr 340px;gap:20px;" class="fade-up delay-1">

    {{-- Left: Edit Form --}}
    <div style="display:flex;flex-direction:column;gap:18px;">
        <form action="{{ route('admin.flash-sales.update', $flashSale) }}" method="POST" onsubmit="const btn = this.querySelector('button[type=submit]'); if(btn) setLoading(btn, true, 'Menyimpan...'); return true;">
            @csrf @method('PUT')
            <div class="glass-card panel">
                <div class="panel-title" style="margin-bottom:18px;">🔥 Detail Flash Sale</div>
                <div style="margin-bottom:14px;">
                    <label class="form-label">Nama Campaign *</label>
                    <input class="form-input" name="name" value="{{ old('name', $flashSale->name) }}" required>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:16px;">
                    <div>
                        <label class="form-label">Mulai *</label>
                        <input class="form-input" type="datetime-local" name="starts_at" value="{{ $flashSale->starts_at->format('Y-m-d\TH:i') }}" required>
                    </div>
                    <div>
                        <label class="form-label">Berakhir *</label>
                        <input class="form-input" type="datetime-local" name="ends_at" value="{{ $flashSale->ends_at->format('Y-m-d\TH:i') }}" required>
                    </div>
                </div>
                <div style="display:flex;align-items:center;justify-content:space-between;padding:12px;border-radius:10px;background:var(--surface-2);margin-bottom:14px;">
                    <span style="font-size:13px;">Flash Sale Aktif</span>
                    <label class="toggle-switch">
                        <input type="checkbox" name="is_active" value="1" {{ $flashSale->is_active ? 'checked' : '' }}>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                <button type="submit" class="btn-ag btn-primary" style="width:100%;justify-content:center;padding:12px;">
                    <i class="fas fa-save"></i> Simpan
                </button>
            </div>
        </form>

        {{-- Product Items Table --}}
        <div class="glass-card panel">
            <div class="panel-header" style="margin-bottom:14px;">
                <div class="panel-title">🛒 Produk dalam Flash Sale</div>
            </div>
            <table class="ag-table">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Harga Normal</th>
                        <th>Harga Promo</th>
                        <th>Kuota</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($flashSale->items as $item)
                    <tr>
                        <td style="font-size:13px;font-weight:500;">{{ $item->product->name }}</td>
                        <td><span class="dm-mono" style="font-size:12px;color:var(--muted);text-decoration:line-through;">Rp{{ number_format($item->product->price,0,',','.') }}</span></td>
                        <td><span class="dm-mono" style="font-size:13px;font-weight:600;color:var(--danger);">Rp{{ number_format($item->promo_price,0,',','.') }}</span></td>
                        <td><span class="dm-mono" style="font-size:12px;">{{ $item->stock_quota }}</span></td>
                        <td>
                            <form action="{{ route('admin.flash-sales.items.destroy', [$flashSale, $item]) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-ag btn-ghost btn-sm" style="color:var(--danger);" onclick="return confirm('Hapus produk ini?')">
                                    <i class="fas fa-times"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" style="text-align:center;padding:24px;color:var(--muted);font-size:12px;">Belum ada produk ditambahkan</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Right: Add Product --}}
    <div class="glass-card panel" style="align-self:start;">
        <div class="panel-title" style="margin-bottom:16px;">➕ Tambah Produk</div>
        <form action="{{ route('admin.flash-sales.items.store', $flashSale) }}" method="POST">
            @csrf
            <div style="margin-bottom:12px;">
                <label class="form-label">Produk</label>
                <select class="form-input" name="product_id" required>
                    <option value="">-- Pilih Produk --</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }} (Rp{{ number_format($product->price,0,',','.') }})</option>
                    @endforeach
                </select>
            </div>
            <div style="margin-bottom:12px;">
                <label class="form-label">Harga Promo (Rp)</label>
                <input class="form-input" type="number" name="promo_price" min="0" required placeholder="0">
            </div>
            <div style="margin-bottom:14px;">
                <label class="form-label">Kuota Stok</label>
                <input class="form-input" type="number" name="stock_quota" min="1" required placeholder="50">
            </div>
            <button type="submit" class="btn-ag btn-primary" style="width:100%;justify-content:center;"><i class="fas fa-plus"></i> Tambahkan</button>
        </form>
    </div>
</div>
@endsection
@push('styles')
<style>
.toggle-switch{position:relative;display:inline-block;width:44px;height:24px;}
.toggle-switch input{opacity:0;width:0;height:0;}
.toggle-slider{position:absolute;cursor:pointer;top:0;left:0;right:0;bottom:0;background:var(--surface-2);border:1px solid var(--border);transition:.2s;border-radius:24px;}
.toggle-slider:before{position:absolute;content:"";height:18px;width:18px;left:2px;bottom:2px;background:var(--muted);transition:.2s;border-radius:50%;}
input:checked+.toggle-slider{background:var(--danger);border-color:var(--danger);}
input:checked+.toggle-slider:before{transform:translateX(20px);background:#fff;}
</style>
@endpush
