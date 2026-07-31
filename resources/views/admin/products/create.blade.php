@extends('admin.layouts.app')
@section('title', 'Tambah Produk Baru')
@section('page-title', 'Tambah Produk Baru')

@section('breadcrumb')
    <i class="fas fa-chevron-right"></i>
    <a href="{{ route('admin.products.index') }}">Produk</a>
@endsection

@section('content')
<div class="fade-up" style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center;">
    <div style="display: flex; align-items: center; gap: 12px;">
        <a href="{{ route('admin.products.index') }}" class="btn-ag btn-ghost btn-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <span style="color: var(--border); font-size: 16px;">|</span>
        <h2 style="font-size: 18px; font-weight: 800; color: var(--text-main);">Buat Produk Baru</h2>
    </div>
</div>

<form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    
    <div class="grid-2-1 fade-up delay-1">

        {{-- LEFT COLUMN --}}
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
                    <input class="form-input" name="name" value="{{ old('name') }}" required placeholder="Contoh: Mesin Bor Listrik Bosch GSB 550 Pro">
                    @error('name')<span style="color: var(--danger); font-size: 11px; margin-top: 4px; display: block;">{{ $message }}</span>@enderror
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div class="form-group">
                        <label class="form-label">SKU Produk <span style="color: var(--danger);">*</span></label>
                        <div class="input-group">
                            <span class="input-prefix"><i class="fas fa-barcode"></i></span>
                            <input class="form-input" name="sku" value="{{ old('sku') }}" required placeholder="Contoh: SKU-BOSCH-001">
                        </div>
                        @error('sku')<span style="color: var(--danger); font-size: 11px; margin-top: 4px; display: block;">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Kategori <span style="color: var(--danger);">*</span></label>
                        <select class="form-select" name="category_id" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                    📁 {{ $cat->name }}
                                </option>
                                @if(isset($cat->children))
                                    @foreach($cat->children as $child)
                                        <option value="{{ $child->id }}" {{ old('category_id') == $child->id ? 'selected' : '' }}>
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
                    <textarea class="form-input" name="description" rows="6" placeholder="Tulis deskripsi lengkap produk...">{{ old('description') }}</textarea>
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
                            <input class="form-input" type="number" name="price" value="{{ old('price') }}" required min="0" placeholder="0">
                        </div>
                        @error('price')<span style="color: var(--danger); font-size: 11px; margin-top: 4px; display: block;">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Harga Promo / Coret</label>
                        <div class="input-group">
                            <span class="input-prefix">Rp</span>
                            <input class="form-input" type="number" name="sale_price" value="{{ old('sale_price') }}" min="0" placeholder="Opsional">
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Jumlah Stok <span style="color: var(--danger);">*</span></label>
                        <div class="input-group">
                            <input class="form-input" type="number" name="stock" value="{{ old('stock', 0) }}" required min="0" placeholder="0">
                            <span class="input-suffix">Unit</span>
                        </div>
                        @error('stock')<span style="color: var(--danger); font-size: 11px; margin-top: 4px; display: block;">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>

            {{-- Panel: Upload Gambar --}}
            <div class="panel">
                <div class="panel-header">
                    <div class="panel-title">
                        <i class="fas fa-images"></i> Gambar Produk
                    </div>
                </div>

                <div id="dropzone" style="border: 2px dashed var(--border); border-radius: 12px; padding: 32px; text-align: center; cursor: pointer; transition: all 0.2s; background: #fafafa;" onclick="document.getElementById('images').click()">
                    <div style="width: 48px; height: 48px; border-radius: 50%; background: var(--info-bg); color: var(--brand-primary); display: flex; align-items: center; justify-content: center; margin: 0 auto 12px; font-size: 20px;">
                        <i class="fas fa-cloud-upload-alt"></i>
                    </div>
                    <div style="font-size: 13px; font-weight: 700; color: var(--text-main);">Klik atau Seret Gambar Ke Sini</div>
                    <div style="font-size: 11px; color: var(--text-muted); margin-top: 4px;">Pilih 1 atau beberapa gambar produk (PNG, JPG, WEBP — Max 2MB per file)</div>
                    <div id="previewContainer" style="display: flex; flex-wrap: wrap; gap: 10px; justify-content: center; margin-top: 16px;"></div>
                </div>
                <input type="file" id="images" name="images[]" multiple accept="image/*" style="display: none;" onchange="previewImages(this)">
            </div>

        </div>

        {{-- RIGHT COLUMN --}}
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
                        <div style="font-weight: 700; font-size: 13px; color: var(--text-main);">Produk Aktif</div>
                        <div style="font-size: 11px; color: var(--text-muted);">Tampilkan produk di etalase toko</div>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                        <span class="toggle-slider"></span>
                    </label>
                </div>

                <div style="display: flex; flex-direction: column; gap: 10px;">
                    <button type="submit" class="btn-ag btn-primary btn-lg" style="width: 100%;">
                        <i class="fas fa-plus-circle"></i> Simpan Produk Baru
                    </button>
                    <a href="{{ route('admin.products.index') }}" class="btn-ag btn-ghost" style="width: 100%;">
                        <i class="fas fa-times"></i> Batal
                    </a>
                </div>
            </div>

            {{-- Panel: Merek --}}
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
                            <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                🏷️ {{ $brand->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
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
</script>
@endpush
