@extends('admin.layouts.app')
@section('title', 'Tambah Merek')
@section('page-title', 'Merek')

@section('content')
<div style="display:flex;align-items:center;gap:12px;margin-bottom:22px;" class="fade-up">
    <a href="{{ route('admin.brands.index') }}" style="color:var(--muted);text-decoration:none;font-size:13px;"><i class="fas fa-arrow-left"></i> Kembali</a>
    <span style="color:var(--border);">/</span>
    <h2 style="font-size:20px;font-weight:700;">Tambah Merek Baru</h2>
</div>

<form action="{{ route('admin.brands.store') }}" method="POST" enctype="multipart/form-data" class="fade-up delay-1" onsubmit="const btn = this.querySelector('button[type=submit]'); if(btn) setLoading(btn, true, 'Menyimpan...'); return true;">
    @csrf
    <div style="display:grid;grid-template-columns:1fr 340px;gap:20px;">
        <div class="glass-card panel">
            <div class="panel-title" style="margin-bottom:18px;">🏷️ Detail Merek</div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:14px;">
                <div>
                    <label class="form-label">Nama Merek *</label>
                    <input class="form-input" name="name" value="{{ old('name') }}" required placeholder="Nama merek">
                </div>
                <div>
                    <label class="form-label">Website</label>
                    <input class="form-input" type="url" name="website" value="{{ old('website') }}" placeholder="https://...">
                </div>
            </div>
            <div style="margin-bottom:14px;">
                <label class="form-label">Deskripsi</label>
                <textarea class="form-input" name="description" rows="3" placeholder="Deskripsi singkat merek...">{{ old('description') }}</textarea>
            </div>
            <div>
                <label class="form-label">Logo</label>
                <div style="border:2px dashed var(--border);border-radius:12px;padding:24px;text-align:center;cursor:pointer;" onclick="document.getElementById('logo').click()">
                    <i class="fas fa-image" style="font-size:24px;color:var(--muted);display:block;margin-bottom:6px;"></i>
                    <div style="font-size:12px;color:var(--muted);">Klik untuk upload logo</div>
                    <div id="logoPreview" style="margin-top:12px;"></div>
                </div>
                <input type="file" id="logo" name="logo" accept="image/*" style="display:none;" onchange="previewLogo(this)">
            </div>
        </div>
        <div class="glass-card panel" style="align-self:start;">
            <div class="panel-title" style="margin-bottom:16px;">⚙️ Status</div>
            <div style="display:flex;align-items:center;justify-content:space-between;padding:12px;border-radius:10px;background:var(--surface-2);margin-bottom:14px;">
                <span style="font-size:13px;">Merek Aktif</span>
                <label class="toggle-switch">
                    <input type="checkbox" name="is_active" value="1" checked>
                    <span class="toggle-slider"></span>
                </label>
            </div>
            <button type="submit" class="btn-ag btn-primary" style="width:100%;justify-content:center;padding:12px;">
                <i class="fas fa-save"></i> Simpan Merek
            </button>
        </div>
    </div>
</form>
@endsection
@push('styles')
<style>
.toggle-switch{position:relative;display:inline-block;width:44px;height:24px;}
.toggle-switch input{opacity:0;width:0;height:0;}
.toggle-slider{position:absolute;cursor:pointer;top:0;left:0;right:0;bottom:0;background:var(--surface-2);border:1px solid var(--border);transition:.2s;border-radius:24px;}
.toggle-slider:before{position:absolute;content:"";height:18px;width:18px;left:2px;bottom:2px;background:var(--muted);transition:.2s;border-radius:50%;}
input:checked+.toggle-slider{background:var(--blue);border-color:var(--blue);}
input:checked+.toggle-slider:before{transform:translateX(20px);background:#fff;}
</style>
@endpush
@push('scripts')
<script>
function previewLogo(input) {
    const p = document.getElementById('logoPreview');
    p.innerHTML = '';
    if (input.files[0]) {
        const r = new FileReader();
        r.onload = e => {
            p.innerHTML = `<img src="${e.target.result}" style="max-width:100px;max-height:80px;object-fit:contain;border-radius:8px;margin:0 auto;display:block;">`;
        };
        r.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
