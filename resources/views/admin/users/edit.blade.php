@extends('admin.layouts.app')
@section('title', 'Edit Pengguna')
@section('page-title', 'Pengguna')

@section('content')
<div style="display:flex;align-items:center;gap:12px;margin-bottom:22px;" class="fade-up">
    <a href="{{ route('admin.users.index') }}" style="color:var(--muted);text-decoration:none;font-size:13px;display:flex;align-items:center;gap:6px;transition:color 0.2s;" onmouseover="this.style.color='var(--blue)'" onmouseout="this.style.color='var(--muted)'">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
    <span style="color:var(--border);">/</span>
    <h2 style="font-size:20px;font-weight:700;">Edit Pengguna: {{ $user->name }}</h2>
</div>

<form action="{{ route('admin.users.update', $user) }}" method="POST" class="fade-up delay-1" onsubmit="const btn = this.querySelector('button[type=submit]'); if(btn) setLoading(btn, true, 'Menyimpan...'); return true;">
    @csrf @method('PUT')
    <div style="display:grid;grid-template-columns:1fr 340px;gap:20px;">

        <div class="glass-card panel">
            <div class="panel-title" style="margin-bottom:18px;">👤 Informasi Profil</div>
            <div style="margin-bottom:14px;">
                <label class="form-label">Nama Lengkap *</label>
                <input class="form-input" name="name" value="{{ old('name', $user->name) }}" required placeholder="Nama lengkap">
                @error('name')<span style="color:var(--danger);font-size:11px;">{{ $message }}</span>@enderror
            </div>
            <div style="margin-bottom:14px;">
                <label class="form-label">Alamat Email *</label>
                <input class="form-input" type="email" name="email" value="{{ old('email', $user->email) }}" required placeholder="email@example.com">
                @error('email')<span style="color:var(--danger);font-size:11px;">{{ $message }}</span>@enderror
            </div>
            <div style="margin-bottom:14px;">
                <label class="form-label">Telepon / WhatsApp</label>
                <input class="form-input" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="08xxxxxxxxxx">
            </div>
            <div style="padding:14px;border-radius:12px;background:var(--amber-dim);border:1px solid rgba(255,184,48,0.15);font-size:12px;color:var(--amber);">
                <i class="fas fa-info-circle" style="margin-right:6px;"></i>
                Password hanya bisa diubah oleh pengguna sendiri melalui menu profil mereka.
            </div>
        </div>

        <div style="display:flex;flex-direction:column;gap:18px;">
            <div class="glass-card panel">
                <div class="panel-title" style="margin-bottom:16px;">🛡️ Status & Akses</div>
                <div style="display:flex;align-items:center;justify-content:space-between;padding:12px;border-radius:10px;background:var(--surface-2);margin-bottom:14px;">
                    <span style="font-size:13px;">Akun Aktif</span>
                    <label class="toggle-switch">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                <button type="submit" class="btn-ag btn-primary" style="width:100%;justify-content:center;padding:12px;">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
            </div>

            <div class="glass-card panel" style="border-color:rgba(255,71,87,0.2);">
                <div class="panel-title" style="margin-bottom:12px;color:var(--danger);">⚠️ Danger Zone</div>
                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="event.preventDefault(); confirmAction({title: 'Hapus Pengguna?', body: 'Apakah kamu yakin ingin menghapus pengguna ini secara permanen?', label: 'Ya, Hapus', onConfirm: () => { this.submit(); }})">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-ag" style="width:100%;justify-content:center;padding:10px;background:var(--danger-dim);color:var(--danger);border:1px solid rgba(255,71,87,0.3);border-radius:10px;">
                        <i class="fas fa-user-minus"></i> Hapus Pengguna
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
