@extends('layouts.auth')
@section('title', 'Daftar Akun Baru')

@section('content')
    <div class="auth-header">
        <h2 class="auth-title">Daftar Akun</h2>
        <p class="auth-subtitle">Bergabung dengan ekosistem profesional LISTRINDO JAYA ELEKTRIK</p>
    </div>

    <form action="{{ route('register') }}" method="POST">
        @csrf
        <div class="form-group">
            <label class="form-label">Nama Lengkap</label>
            <input type="text" name="name" class="form-control" placeholder="Masukkan nama lengkap" value="{{ old('name') }}" required autofocus>
            @error('name') 
                <div class="error-msg"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div> 
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Alamat Email</label>
            <input type="email" name="email" class="form-control" placeholder="email@contoh.com" value="{{ old('email') }}" required>
            @error('email') 
                <div class="error-msg"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div> 
            @enderror
        </div>

        <div class="form-group" x-data="{ show: false }">
            <label class="form-label">Password</label>
            <div class="password-wrapper">
                <input :type="show ? 'text' : 'password'" name="password" class="form-control" placeholder="Minimal 8 karakter" required>
                <div class="password-toggle" @click="show = !show">
                    <i class="fas" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                </div>
            </div>
            @error('password') 
                <div class="error-msg"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div> 
            @enderror
        </div>

        <div class="form-group" x-data="{ show: false }">
            <label class="form-label">Konfirmasi Password</label>
            <div class="password-wrapper">
                <input :type="show ? 'text' : 'password'" name="password_confirmation" class="form-control" placeholder="Ulangi password" required>
                <div class="password-toggle" @click="show = !show">
                    <i class="fas" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                </div>
            </div>
        </div>

        <div style="margin-bottom:24px;">
            <label style="display:flex;align-items:flex-start;gap:10px;font-size:12px;color:var(--text-muted);cursor:pointer;line-height:1.5;">
                <input type="checkbox" required style="accent-color:var(--brand-blue);width:16px;height:16px;margin-top:2px;">
                <span>Dengan mendaftar, saya menyetujui <a href="{{ route('terms') }}" target="_blank">Syarat & Ketentuan</a> serta <a href="{{ route('privacy') }}" target="_blank">Kebijakan Privasi</a>.</span>
            </label>
        </div>

        <button type="submit" class="btn-volt btn-primary">DAFTAR SEKARANG</button>
        <a href="/" class="btn-volt" style="display:flex;justify-content:center;align-items:center;width:100%;margin-top:12px;padding:14px;background:var(--surface-3);color:var(--text-muted);border:1px solid var(--border);border-radius:12px;text-decoration:none;font-weight:600;font-size:13px;transition:all 0.2s;"><i class="fas fa-arrow-left" style="margin-right:8px;"></i> KEMBALI KE TOKO</a>
    </form>

    <div class="auth-footer">
        Sudah punya akun? <a href="{{ route('login') }}">Masuk Ke Akun</a>
    </div>
@endsection

