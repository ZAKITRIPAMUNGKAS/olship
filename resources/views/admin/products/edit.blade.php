@extends('admin.layouts.app')
@section('title', 'Edit Produk - ' . $product->name)
@section('page-title', 'Edit Produk')

@section('breadcrumb')
    <i class="fas fa-chevron-right"></i>
    <a href="{{ route('admin.products.index') }}">Produk</a>
@endsection

@section('content')
<div class="fade-up" style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
    <div style="display: flex; align-items: center; gap: 12px;">
        <a href="{{ route('admin.products.index') }}" class="btn-ag btn-ghost btn-sm" title="Kembali ke Daftar Produk">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <span style="color: var(--border); font-size: 16px;">|</span>
        <div>
            <h2 style="font-size: 18px; font-weight: 800; color: var(--text-main); line-height: 1.2;">{{ $product->name }}</h2>
            <div style="font-size: 12px; color: var(--text-muted); margin-top: 2px;">
                SKU: <span style="font-family: monospace; font-weight: 600; color: var(--brand-primary);">{{ $product->sku }}</span>
            </div>
        </div>
    </div>
    <div>
        <span class="badge-ag {{ $product->is_active ? 'badge-success' : 'badge-danger' }}" style="font-size: 12px; padding: 6px 14px;">
            <i class="fas {{ $product->is_active ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
            {{ $product->is_active ? 'Produk Aktif' : 'Nonaktif' }}
        </span>
    </div>
</div>

<form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
    @csrf @method('PUT')
    
    <div class="grid-2-1 fade-up delay-1">

        {{-- LEFT COLUMN (Main Info, Pricing, Images) --}}
        <div style="display: flex; flex-direction: column; gap: 20px;">
            
            {{-- Panel: Informasi Dasar --}}
            <div class="panel">
                <div class="panel-header">
                    <div class="panel-title">
                        <i class="fas fa-box"></i> Informasi Dasar Produk
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Nama Produk <span style="color: var(--danger);">*</span></label>
                    <input class="form-input" name="name" value="{{ old('name', $product->name) }}" required placeholder="Contoh: Mesin Bor Listrik Bosch GSB 550 Pro">
                    @error('name')<span style="color: var(--danger); font-size: 11px; margin-top: 4px; display: block;">{{ $message }}</span>@enderror
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div class="form-group">
                        <label class="form-label">SKU Produk <span style="color: var(--danger);">*</span></label>
                        <div class="input-group">
                            <span class="input-prefix"><i class="fas fa-barcode"></i></span>
                            <input class="form-input" name="sku" value="{{ old('sku', $product->sku) }}" required placeholder="SKU-XXXXXX">
                        </div>
                        @error('sku')<span style="color: var(--danger); font-size: 11px; margin-top: 4px; display: block;">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Kategori <span style="color: var(--danger);">*</span></label>
                        <select class="form-select" name="category_id" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                                    📁 {{ $cat->name }}
                                </option>
                                @if(isset($cat->children))
                                    @foreach($cat->children as $child)
                                        <option value="{{ $child->id }}" {{ old('category_id', $product->category_id) == $child->id ? 'selected' : '' }}>
                                            &nbsp;&nbsp;&nbsp;&nbsp;↳ {{ $child->name }}
                                        </option>
                                    @endforeach
                                @endif
                            @endforeach
                        </select>
                        @error('category_id')<span style="color: var(--danger); font-size: 11px; margin-top: 4px; display: block;">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Deskripsi Produk</label>
                    <textarea class="form-input" name="description" rows="6" placeholder="Tulis deskripsi lengkap mengenai spesifikasi dan keunggulan produk...">{{ old('description', $product->description) }}</textarea>
                </div>
            </div>

            {{-- Panel: Harga & Stok --}}
            <div class="panel">
                <div class="panel-header">
                    <div class="panel-title">
                        <i class="fas fa-tags"></i> Harga & Manajemen Stok
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Harga Normal <span style="color: var(--danger);">*</span></label>
                        <div class="input-group">
                            <span class="input-prefix">Rp</span>
                            <input class="form-input" type="number" name="price" value="{{ old('price', (int)$product->price) }}" required min="0" placeholder="0">
                        </div>
                        @error('price')<span style="color: var(--danger); font-size: 11px; margin-top: 4px; display: block;">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Harga Promo / Coret</label>
                        <div class="input-group">
                            <span class="input-prefix">Rp</span>
                            <input class="form-input" type="number" name="sale_price" value="{{ old('sale_price', (int)$product->sale_price) }}" min="0" placeholder="Opsional">
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Jumlah Stok <span style="color: var(--danger);">*</span></label>
                        <div class="input-group">
                            <input class="form-input" type="number" name="stock" value="{{ old('stock', $product->stock) }}" required min="0" placeholder="0">
                            <span class="input-suffix">Unit</span>
                        </div>
                        @error('stock')<span style="color: var(--danger); font-size: 11px; margin-top: 4px; display: block;">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>

            {{-- Panel: Gambar Produk --}}
            <div class="panel">
                <div class="panel-header">
                    <div class="panel-title">
                        <i class="fas fa-images"></i> Galeri Gambar Produk
                    </div>
                    <span style="font-size: 11px; color: var(--text-muted);">Maksimal 2MB per gambar</span>
                </div>

                {{-- Gambar saat ini --}}
                @if($product->images && count($product->images))
                <div style="margin-bottom: 18px;">
                    <label class="form-label">Gambar Terpasang Saat Ini</label>
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(100px, 1fr)); gap: 12px;">
                        @foreach($product->images as $img)
                        <div style="position: relative; border-radius: 10px; overflow: hidden; border: 1px solid var(--border); background: var(--surface-2); aspect-ratio: 1;">
                            <img src="{{ asset('storage/' . $img->image_path) }}" alt="Gambar Produk" style="width: 100%; height: 100%; object-fit: cover;">
                            @if($img->is_primary)
                            <span style="position: absolute; top: 6px; left: 6px; background: var(--brand-primary); color: #fff; font-size: 9px; font-weight: 700; padding: 2px 6px; border-radius: 4px; text-transform: uppercase;">
                                Utama
                            </span>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Upload Dropzone --}}
                <div id="dropzone" style="border: 2px dashed var(--border); border-radius: 12px; padding: 28px; text-align: center; cursor: pointer; transition: all 0.2s; background: #fafafa;" onclick="document.getElementById('images').click()">
                    <div style="width: 48px; height: 48px; border-radius: 50%; background: var(--info-bg); color: var(--brand-primary); display: flex; align-items: center; justify-content: center; margin: 0 auto 12px; font-size: 20px;">
                        <i class="fas fa-cloud-upload-alt"></i>
                    </div>
                    <div style="font-size: 13px; font-weight: 700; color: var(--text-main);">Klik atau Seret Gambar Baru Ke Sini</div>
                    <div style="font-size: 11px; color: var(--text-muted); margin-top: 4px;">Mengunggah gambar baru akan menggantikan galeri foto lama. (PNG, JPG, WEBP)</div>
                    <div id="previewContainer" style="display: flex; flex-wrap: wrap; gap: 10px; justify-content: center; margin-top: 16px;"></div>
                </div>
                <input type="file" id="images" name="images[]" multiple accept="image/*" style="display: none;" onchange="previewImages(this)">
            </div>

        </div>

        {{-- RIGHT COLUMN (Publish Actions, Brand, Danger Zone) --}}
        <div style="display: flex; flex-direction: column; gap: 20px;">

            {{-- Panel: Status & Publikasi --}}
            <div class="panel" style="position: sticky; top: 84px;">
                <div class="panel-header">
                    <div class="panel-title">
                        <i class="fas fa-sliders-h"></i> Status & Aksi
                    </div>
                </div>

                <div style="display: flex; align-items: center; justify-content: space-between; padding: 14px 16px; border-radius: 10px; background: var(--surface-2); border: 1px solid var(--border); margin-bottom: 20px;">
                    <div>
                        <div style="font-weight: 700; font-size: 13px; color: var(--text-main);">Status Ketersediaan</div>
                        <div style="font-size: 11px; color: var(--text-muted);">Tampilkan produk di etalase toko</div>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                        <span class="toggle-slider"></span>
                    </label>
                </div>

                <div style="display: flex; flex-direction: column; gap: 10px;">
                    <button type="submit" class="btn-ag btn-primary btn-lg" style="width: 100%;">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('admin.products.index') }}" class="btn-ag btn-ghost" style="width: 100%;">
                        <i class="fas fa-times"></i> Batal
                    </a>
                </div>
            </div>

            {{-- Panel: Merek / Brand --}}
            <div class="panel">
                <div class="panel-header">
                    <div class="panel-title">
                        <i class="fas fa-copyright"></i> Merek / Brand
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Pilih Merek</label>
                    <select class="form-select" name="brand_id">
                        <option value="">-- Tanpa Merek / Generic --</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                                🏷️ {{ $brand->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Danger Zone Panel --}}
            <div class="panel" style="border-color: #fecaca; background: #fff5f5;">
                <div class="panel-header" style="border-bottom-color: #fee2e2; background: transparent;">
                    <div class="panel-title" style="color: var(--danger);">
                        <i class="fas fa-exclamation-triangle" style="color: var(--danger);"></i> Zona Bahaya
                    </div>
                </div>
                <p style="font-size: 12px; color: #991b1b; margin-bottom: 14px; line-height: 1.4;">
                    Menghapus produk ini akan mengeliminasi seluruh data produk dari toko online secara permanen.
                </p>
                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" id="deleteForm">
                    @csrf @method('DELETE')
                    <button type="button" onclick="confirmDelete()" class="btn-ag btn-danger-subtle" style="width: 100%;">
                        <i class="fas fa-trash-alt"></i> Hapus Produk Ini
                    </button>
                </form>
            </div>

        </div>

    </div>
</form>

@endsection

@push('scripts')
<script>
function previewImages(input) {
    const container = document.getElementById('previewContainer');
    container.innerHTML = '';
    if (input.files) {
        for (const file of input.files) {
            const reader = new FileReader();
            reader.onload = e => {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.cssText = 'width: 70px; height: 70px; object-fit: cover; border-radius: 8px; border: 1px solid var(--border); box-shadow: 0 1px 3px rgba(0,0,0,0.1);';
                container.appendChild(img);
            };
            reader.readAsDataURL(file);
        }
    }
}

const dz = document.getElementById('dropzone');
if (dz) {
    dz.addEventListener('dragover', e => { e.preventDefault(); dz.style.borderColor = 'var(--brand-primary)'; dz.style.background = '#eff6ff'; });
    dz.addEventListener('dragleave', () => { dz.style.borderColor = 'var(--border)'; dz.style.background = '#fafafa'; });
    dz.addEventListener('drop', e => {
        e.preventDefault(); 
        dz.style.borderColor = 'var(--border)'; 
        dz.style.background = '#fafafa';
        if (e.dataTransfer.files.length) {
            document.getElementById('images').files = e.dataTransfer.files;
            previewImages(document.getElementById('images'));
        }
    });
}

function confirmDelete() {
    confirmAction({
        title: 'Hapus Produk?',
        body: 'Yakin ingin menghapus produk "{{ $product->name }}"? Tindakan ini tidak dapat dibatalkan.',
        label: 'Ya, Hapus Produk',
        onConfirm: () => {
            document.getElementById('deleteForm').submit();
        }
    });
}
</script>
@endpush
