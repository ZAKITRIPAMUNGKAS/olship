@extends('admin.layouts.app')
@section('title', 'Edit Banner')
@section('page-title', 'Banner')

@section('content')
<div style="display:flex;align-items:center;gap:12px;margin-bottom:22px;" class="fade-up">
    <a href="{{ route('admin.banners.index') }}" style="color:var(--muted);text-decoration:none;font-size:13px;display:flex;align-items:center;gap:6px;transition:color 0.2s;" onmouseover="this.style.color='var(--blue)'" onmouseout="this.style.color='var(--muted)'">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
    <span style="color:var(--border);">/</span>
    <h2 style="font-size:20px;font-weight:700;">Edit: {{ $banner->title }}</h2>
</div>

<form action="{{ route('admin.banners.update', $banner) }}" method="POST" enctype="multipart/form-data" onsubmit="const btn = this.querySelector('button[type=submit]'); if(btn) setLoading(btn, true, 'Menyimpan...'); return true;">
    @csrf @method('PUT')
    <div style="display:grid;grid-template-columns:1fr 340px;gap:20px;" class="fade-up delay-1">

        {{-- Left --}}
        <div style="display:flex;flex-direction:column;gap:18px;">
            <div class="glass-card panel">
                <div class="panel-title" style="margin-bottom:18px;">🖼️ Detail Banner</div>
                <div style="margin-bottom:14px;">
                    <label class="form-label">Judul Banner *</label>
                    <input class="form-input" name="title" value="{{ old('title', $banner->title) }}" required placeholder="Judul banner untuk referensi admin">
                    @error('title')<span style="color:var(--danger);font-size:11px;">{{ $message }}</span>@enderror
                </div>
                <div style="margin-bottom:14px;">
                    <label class="form-label">Link URL</label>
                    <input class="form-input" type="url" name="link" value="{{ old('link', $banner->link) }}" placeholder="https://... (target saat diklik)">
                    @error('link')<span style="color:var(--danger);font-size:11px;">{{ $message }}</span>@enderror
                </div>
                <div style="margin-bottom:14px;">
                    <label class="form-label">Urutan Tampil</label>
                    <input class="form-input" type="number" name="sort_order" value="{{ old('sort_order', $banner->sort_order) }}" min="1" style="width:120px;">
                    @error('sort_order')<span style="color:var(--danger);font-size:11px;">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label class="form-label">Ganti Gambar (Opsional)</label>
                    @if($banner->image)
                    <div style="margin-bottom:12px;border-radius:10px;overflow:hidden;border:1px solid var(--border);position:relative;">
                        <img src="{{ asset('storage/' . $banner->image) }}" style="width:100%;height:140px;object-fit:cover;display:block;">
                        <div style="position:absolute;bottom:0;left:0;right:0;background:rgba(5,11,26,0.7);padding:6px;text-align:center;font-size:11px;color:var(--muted);backdrop-filter:blur(4px);">
                            Gambar Saat Ini
                        </div>
                    </div>
                    @endif
                    <div style="border:2px dashed var(--border);border-radius:12px;padding:24px;text-align:center;cursor:pointer;transition:all 0.2s;" id="bannerDZ" onclick="document.getElementById('bannerImg').click()">
                        <i class="fas fa-image" style="font-size:24px;color:var(--muted);display:block;margin-bottom:6px;"></i>
                        <div style="font-size:12px;color:var(--muted);">Klik untuk pilih gambar baru</div>
                        <div id="bannerPreview" style="margin-top:12px;"></div>
                    </div>
                    <input type="file" id="bannerImg" name="image" accept="image/*" style="display:none;" onchange="previewBanner(this)">
                </div>
            </div>
        </div>

        {{-- Right --}}
        <div style="display:flex;flex-direction:column;gap:18px;">
            <div class="glass-card panel">
                <div class="panel-title" style="margin-bottom:16px;">⚙️ Status</div>
                <div style="display:flex;align-items:center;justify-content:space-between;padding:12px;border-radius:10px;background:var(--surface-2);margin-bottom:14px;">
                    <span style="font-size:13px;">Banner Aktif</span>
                    <label class="toggle-switch">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $banner->is_active) ? 'checked' : '' }}>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                <button type="submit" class="btn-ag btn-primary" style="width:100%;justify-content:center;padding:12px;">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
                <a href="{{ route('admin.banners.index') }}" class="btn-ag btn-ghost" style="width:100%;justify-content:center;padding:11px;margin-top:8px;text-decoration:none;">
                    Batal
                </a>
            </div>

            <div class="glass-card panel" style="border-color:rgba(255,71,87,0.2);">
                <div class="panel-title" style="margin-bottom:12px;color:var(--danger);">⚠️ Danger Zone</div>
                <form action="{{ route('admin.banners.destroy', $banner) }}" method="POST" onsubmit="event.preventDefault(); confirmAction({title: 'Hapus Banner?', body: 'Yakin hapus banner ini?', label: 'Ya, Hapus', onConfirm: () => { this.submit(); }})">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-ag" style="width:100%;justify-content:center;padding:10px;background:var(--danger-dim);color:var(--danger);border:1px solid rgba(255,71,87,0.3);border-radius:10px;">
                        <i class="fas fa-trash"></i> Hapus Banner
                    </button>
                </form>
            </div>
        </div>
    </div>
</form>
@endsection

@push('styles')
<style>
.toggle-switch { position:relative;display:inline-block;width:44px;height:24px; }
.toggle-switch input { opacity:0;width:0;height:0; }
.toggle-slider { position:absolute;cursor:pointer;top:0;left:0;right:0;bottom:0;background:var(--surface-2);border:1px solid var(--border);transition:.2s;border-radius:24px; }
.toggle-slider:before { position:absolute;content:"";height:18px;width:18px;left:2px;bottom:2px;background:var(--muted);transition:.2s;border-radius:50%; }
input:checked + .toggle-slider { background:var(--blue);border-color:var(--blue); }
input:checked + .toggle-slider:before { transform:translateX(20px);background:#fff; }
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
            p.innerHTML = `<img src="${e.target.result}" style="width:100%;max-height:140px;object-fit:cover;border-radius:8px;border:1px solid var(--blue);">`;
        };
        r.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
