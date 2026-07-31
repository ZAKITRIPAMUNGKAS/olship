@extends('layouts.app')
@section('title', 'Tambah Produk Baru - Seller Center')

@section('content')
<div class="seller-container" style="padding: 40px 0;">
    <div style="margin-bottom: 30px;">
        <h1 style="font-size: 28px; font-weight: 800; color: var(--ink);">Tambah Produk Baru</h1>
        <p style="color: var(--slate-500);">Isi informasi produk dengan lengkap untuk menarik pembeli</p>
    </div>

    <form action="{{ route('seller.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px;">
            <!-- Main Info -->
            <div style="display: grid; gap: 24px;">
                <div class="card" style="padding: 24px; background: #fff; border-radius: 16px; border: 1px solid var(--slate-200);">
                    <h3 style="font-weight: 700; margin-bottom: 20px;">Informasi Dasar</h3>
                    
                    <div style="margin-bottom: 16px;">
                        <label class="form-label">Nama Produk</label>
                        <input type="text" name="name" class="form-control" placeholder="Contoh: Kabel Listrik NYM 2x1.5mm" required value="{{ old('name') }}" style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid var(--slate-200);">
                        @error('name') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                        <div>
                            <label class="form-label">SKU</label>
                            <input type="text" name="sku" class="form-control" placeholder="Unique SKU" required value="{{ old('sku') }}" style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid var(--slate-200);">
                        </div>
                        <div>
                            <label class="form-label">Kategori</label>
                            <select name="category_id" class="form-control" required style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid var(--slate-200);">
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div style="margin-bottom: 16px;">
                        <label class="form-label">Deskripsi Produk</label>
                        <textarea name="description" rows="6" class="form-control" placeholder="Jelaskan spesifikasi dan keunggulan produk" style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid var(--slate-200);">{{ old('description') }}</textarea>
                    </div>
                </div>

                <div class="card" style="padding: 24px; background: #fff; border-radius: 16px; border: 1px solid var(--slate-200);">
                    <h3 style="font-weight: 700; margin-bottom: 20px;">Harga & Stok</h3>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                        <div>
                            <label class="form-label">Harga (Rp)</label>
                            <input type="number" name="price" class="form-control" required value="{{ old('price') }}" style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid var(--slate-200);">
                        </div>
                        <div>
                            <label class="form-label">Stok</label>
                            <input type="number" name="stock" class="form-control" required value="{{ old('stock') }}" style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid var(--slate-200);">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Info -->
            <div style="display: grid; gap: 24px; height: fit-content;">
                <div class="card" style="padding: 24px; background: #fff; border-radius: 16px; border: 1px solid var(--slate-200);">
                    <h3 style="font-weight: 700; margin-bottom: 20px;">Media Produk</h3>
                    <div style="margin-bottom: 16px;">
                        <label class="form-label">Upload Gambar (Min. 1)</label>
                        <input type="file" name="images[]" multiple class="form-control" style="width: 100%; padding: 8px;">
                        <p style="font-size: 11px; color: var(--slate-400); margin-top: 8px;">Maksimal 2MB per gambar. Gambar pertama akan menjadi foto utama.</p>
                    </div>
                </div>

                <div class="card" style="padding: 24px; background: #fff; border-radius: 16px; border: 1px solid var(--slate-200);">
                    <h3 style="font-weight: 700; margin-bottom: 20px;">Pengiriman</h3>
                    <div style="margin-bottom: 16px;">
                        <label class="form-label">Berat (Gram)</label>
                        <input type="number" name="weight" class="form-control" required value="{{ old('weight', 1000) }}" style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid var(--slate-200);">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 16px; background: var(--primary); color: #fff; border: none; border-radius: 12px; font-weight: 700; font-size: 16px; cursor: pointer;">PUBLIKASIKAN PRODUK</button>
                <a href="{{ route('seller.products.index') }}" style="display: block; text-align: center; color: var(--slate-500); text-decoration: none; font-size: 14px; font-weight: 600;">Batalkan</a>
            </div>
        </div>
    </form>
</div>
@endsection
