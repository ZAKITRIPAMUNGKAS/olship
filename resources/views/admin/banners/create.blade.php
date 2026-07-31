@extends('admin.layouts.app')
@section('title', 'Tambah Banner')
@section('page-title', 'Banner')

@section('content')
<div style="display:flex;align-items:center;gap:12px;margin-bottom:22px;" class="fade-up">
    <a href="{{ route('admin.banners.index') }}" style="color:var(--muted);text-decoration:none;font-size:13px;"><i class="fas fa-arrow-left"></i> Kembali</a>
    <span style="color:var(--border);">/</span>
    <h2 style="font-size:20px;font-weight:700;">Tambah Banner Baru</h2>
</div>

<form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data" class="fade-up delay-1" onsubmit="const btn = this.querySelector('button[type=submit]'); if(btn) setLoading(btn, true, 'Menyimpan...'); return true;">
    @csrf
    <div style="display:grid;grid-template-columns:1fr 340px;gap:20px;">
        <div class="glass-card panel">
            <div class="panel-title" style="margin-bottom:18px;">🖼️ Detail Banner</div>
            <div style="margin-bottom:14px;">
                <label class="form-label">Judul Banner *</label>
                <input class="form-input" name="title" value="{{ old('title') }}" required placeholder="Judul banner untuk referensi admin">
            </div>
            <div style="margin-bottom:14px;">
                <label class="form-label">Link URL</label>
                <input class="form-input" type="url" name="link" value="{{ old('link') }}" placeholder="https://... (target saat diklik)">
            </div>
            <div style="margin-bottom:14px;">
                <label class="form-label">Urutan Tampil</label>
                <input class="form-input" type="number" name="sort_order" value="{{ old('sort_order', 1) }}" min="1" style="width:120px;">
            </div>
            <div>
                <label class="form-label">Gambar Banner *</label>
                <div style="border:2px dashed var(--border);border-radius:12px;padding:32px;text-align:center;cursor:pointer;transition:all 0.2s;" id="bannerDZ" onclick="document.getElementById('bannerImg').click()">
                    <i class="fas fa-image" style="font-size:32px;color:var(--muted);display:block;margin-bottom:8px;"></i>
                    <div style="font-size:13px;font-weight:600;color:var(--text-2);">Klik atau drag gambar kesini</div>
                    <div style="font-size:11px;color:var(--muted);margin-top:4px;">Rekomendasi: 1200×400px · JPG/PNG · max 4MB</div>
                    <div id="bannerPreview" style="margin-top:16px;"></div>
                </div>
                <input type="file" id="bannerImg" name="image" accept="image/*" required style="display:none;" onchange="previewBanner(this)">
            </div>
        </div>
        <div class="glass-card panel" style="align-self:start;">
            <div class="panel-title" style="margin-bottom:16px;">⚙️ Status</div>
            <div style="display:flex;align-items:center;justify-content:space-between;padding:12px;border-radius:10px;background:var(--surface-2);margin-bottom:14px;">
                <span style="font-size:13px;">Banner Aktif</span>
                <label class="toggle-switch">
                    <input type="checkbox" name="is_active" value="1" checked>
                    <span class="toggle-slider"></span>
                </label>
            </div>
            <div style="padding:12px;border-radius:10px;background:var(--blue-dim);border:1px solid rgba(0,212,255,0.15);margin-bottom:14px;font-size:12px;color:var(--blue);">
                <i class="fas fa-info-circle" style="margin-right:6px;"></i>
                Banner akan ditampilkan di homepage sesuai urutan.
            </div>
            <button type="submit" class="btn-ag btn-primary" style="width:100%;justify-content:center;padding:12px;">
                <i class="fas fa-upload"></i> Upload Banner
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
function previewBanner(input) {
    const p = document.getElementById('bannerPreview');
    p.innerHTML = '';
    if (input.files[0]) {
        const r = new FileReader();
        r.onload = e => {
            p.innerHTML = `<img src="${e.target.result}" style="width:100%;max-height:160px;object-fit:cover;border-radius:8px;">`;
        };
        r.readAsDataURL(input.files[0]);
    }
}
const dz = document.getElementById('bannerDZ');
dz.addEventListener('dragover', e => { e.preventDefault(); dz.style.borderColor = 'var(--blue)'; });
dz.addEventListener('dragleave', () => dz.style.borderColor = 'var(--border)');
dz.addEventListener('drop', e => {
    e.preventDefault(); dz.style.borderColor = 'var(--border)';
    const fi = document.getElementById('bannerImg');
    const dt = new DataTransfer(); dt.items.add(e.dataTransfer.files[0]);
    fi.files = dt.files; previewBanner(fi);
});
</script>
@endpush
