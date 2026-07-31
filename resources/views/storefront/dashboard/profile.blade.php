@extends('storefront.dashboard.layout')

@section('title', 'Ubah Profil - LISTRINDO JAYA ELEKTRIK')

@section('dashboard-content')
<div class="db-card">
    <div class="db-card-header">
        <h2 class="db-card-title">Ubah Profil</h2>
        <p class="db-card-subtitle">Perbarui informasi pribadi dan keamanan akun Anda.</p>
    </div>

    <form action="{{ route('dashboard.profile.update') }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="db-form-row">
            <div class="db-form-group">
                <label class="db-label">Nama Lengkap</label>
                <div class="db-input-wrapper">
                    <i class="far fa-user db-input-icon"></i>
                    <input type="text" name="name" class="db-input" placeholder="Nama Lengkap" required value="{{ old('name', $user->name) }}">
                </div>
            </div>

            <div class="db-form-group">
                <label class="db-label">Alamat Email</label>
                <div class="db-input-wrapper">
                    <i class="far fa-envelope db-input-icon"></i>
                    <input type="email" name="email" class="db-input" placeholder="email@contoh.com" required value="{{ old('email', $user->email) }}">
                </div>
            </div>
        </div>

        <div style="margin: 28px 0 24px 0; border-top: 1px solid var(--line); padding-top: 24px;">
            <h3 style="font-size: 16px; font-weight: 800; color: var(--ink); margin: 0 0 4px 0; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-key" style="color: var(--blue);"></i> Ubah Kata Sandi
            </h3>
            <p style="color: var(--slate-500); font-size: 13px; margin: 0;">Kosongkan kolom di bawah jika Anda tidak ingin mengubah kata sandi.</p>
        </div>

        <div class="db-form-row">
            <div class="db-form-group">
                <label class="db-label">Kata Sandi Baru</label>
                <div class="db-input-wrapper">
                    <i class="fas fa-lock db-input-icon"></i>
                    <input type="password" name="password" class="db-input" placeholder="Minimal 8 karakter">
                </div>
            </div>
            
            <div class="db-form-group">
                <label class="db-label">Konfirmasi Kata Sandi Baru</label>
                <div class="db-input-wrapper">
                    <i class="fas fa-shield-alt db-input-icon"></i>
                    <input type="password" name="password_confirmation" class="db-input" placeholder="Ulangi kata sandi">
                </div>
            </div>
        </div>

        <div class="db-card-actions">
            <button type="submit" class="db-btn db-btn-primary" style="padding: 12px 28px; font-size: 14px;">
                <i class="fas fa-save"></i> Simpan Perubahan
            </button>
            <a href="{{ route('dashboard.index') }}" class="db-btn db-btn-secondary" style="padding: 12px 24px; font-size: 14px;">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
