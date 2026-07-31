@extends('admin.layouts.app')
@section('title', 'Edit Merek')
@section('page-title', 'Merek')

@section('content')
<div style="display:flex;align-items:center;gap:12px;margin-bottom:22px;" class="fade-up">
    <a href="{{ route('admin.brands.index') }}" style="color:var(--muted);text-decoration:none;font-size:13px;"><i class="fas fa-arrow-left"></i> Kembali</a>
    <span style="color:var(--border);">/</span>
    <h2 style="font-size:20px;font-weight:700;">Edit: {{ $brand->name }}</h2>
</div>

<form action="{{ route('admin.brands.update', $brand) }}" method="POST" enctype="multipart/form-data" class="fade-up delay-1" onsubmit="const btn = this.querySelector('button[type=submit]'); if(btn) setLoading(btn, true, 'Menyimpan...'); return true;">
    @csrf @method('PUT')
    <div style="display:grid;grid-template-columns:1fr 340px;gap:20px;">
        <div class="glass-card panel">
            <div class="panel-title" style="margin-bottom:18px;">🏷️ Detail Merek</div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:14px;">
                <div>
                    <label class="form-label">Nama Merek *</label>
                    <input class="form-input" name="name" value="{{ old('name', $brand->name) }}" required>
                </div>
                <div>
                    <label class="form-label">Website</label>
                    <input class="form-input" type="url" name="website" value="{{ old('website', $brand->website) }}">
                </div>
            </div>
            <div style="margin-bottom:14px;">
                <label class="form-label">Deskripsi</label>
                <textarea class="form-input" name="description" rows="3">{{ old('description', $brand->description) }}</textarea>
            </div>
            <div>
                <label class="form-label">Logo</label>
                @if($brand->logo)
                <div style="margin-bottom:10px;">
                    <img src="{{ asset('storage/'.$brand->logo) }}" style="max-height:60px;object-fit:contain;border-radius:6px;background:var(--surface-2);padding:6px;">
                </div>
                @endif
                <div style="border:2px dashed var(--border);border-radius:12px;padding:20px;text-align:center;cursor:pointer;" onclick="document.getElementById('logo').click()">
                    <div style="font-size:12px;color:var(--muted);">Klik untuk ganti logo</div>
                </div>
                <input type="file" id="logo" name="logo" accept="image/*" style="display:none;">
            </div>
        </div>
        <div style="display:flex;flex-direction:column;gap:18px;">
            <div class="glass-card panel">
                <div class="panel-title" style="margin-bottom:16px;">⚙️ Status</div>
                <div style="display:flex;align-items:center;justify-content:space-between;padding:12px;border-radius:10px;background:var(--surface-2);margin-bottom:14px;">
                    <span style="font-size:13px;">Merek Aktif</span>
                    <label class="toggle-switch">
                        <input type="checkbox" name="is_active" value="1" {{ $brand->is_active ? 'checked' : '' }}>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                <button type="submit" class="btn-ag btn-primary" style="width:100%;justify-content:center;padding:12px;">
                    <i class="fas fa-save"></i> Simpan
                </button>
            </div>
            <div class="glass-card panel" style="border-color:rgba(255,71,87,0.2);">
                <div class="panel-title" style="margin-bottom:12px;color:var(--danger);">⚠️ Danger Zone</div>
                <form action="{{ route('admin.brands.destroy', $brand) }}" method="POST" onsubmit="event.preventDefault(); confirmAction({title: 'Hapus Merek?', body: 'Apakah kamu yakin ingin menghapus merek ini?', label: 'Ya, Hapus', onConfirm: () => { this.submit(); }})">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-ag" style="width:100%;justify-content:center;padding:10px;background:var(--danger-dim);color:var(--danger);border:1px solid rgba(255,71,87,0.3);border-radius:10px;">
                        <i class="fas fa-trash"></i> Hapus Merek
                    </button>
                </form>
            </div>
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
