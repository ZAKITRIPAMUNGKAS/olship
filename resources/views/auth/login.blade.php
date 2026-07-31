@extends('layouts.auth')
@section('title', 'Masuk ke Akun')

@section('content')
    <div class="auth-header">
        <h2 class="auth-title">Selamat Datang</h2>
        <p class="auth-subtitle">Silakan masuk untuk mengelola sistem LISTRINDO JAYA ELEKTRIK</p>
    </div>

    <form action="{{ route('login') }}" method="POST">
        @csrf
        <div class="form-group">
            <label class="form-label">Alamat Email</label>
            <input type="email" name="email" class="form-control" placeholder="admin@listrindojayaelektrik.com" value="{{ old('email') }}" required autofocus>
            @error('email') 
                <div class="error-msg"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div> 
            @enderror
        </div>

        <div class="form-group" x-data="{ show: false }">
            <label class="form-label">Password</label>
            <div class="password-wrapper">
                <input :type="show ? 'text' : 'password'" name="password" class="form-control" placeholder="Masukkan password" required>
                <div class="password-toggle" @click="show = !show">
                    <i class="fas" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                </div>
            </div>
            @error('password') 
                <div class="error-msg"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div> 
            @enderror
        </div>

        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;">
            <label style="display:flex;align-items:center;gap:8px;font-size:13px;color:var(--text-muted);cursor:pointer;">
                <input type="checkbox" name="remember" style="accent-color:var(--brand-blue);width:16px;height:16px;"> Ingat Sesi
            </label>
            <a href="{{ route('password.request') }}" style="font-size:13px;color:var(--brand-blue);text-decoration:none;font-weight:500;">Lupa Password?</a>
        </div>

        <button type="submit" class="btn-volt btn-primary">MASUK SEKARANG</button>
        <a href="/" class="btn-volt" style="display:flex;justify-content:center;align-items:center;width:100%;margin-top:12px;padding:14px;background:transparent;color:var(--text-main);border:1px solid #94a3b8;border-radius:12px;text-decoration:none;font-weight:600;font-size:13px;transition:all 0.2s;"><i class="fas fa-arrow-left" style="margin-right:8px;"></i> KEMBALI KE TOKO</a>
    </form>

    <div class="auth-footer">
        Belum punya akun? <a href="{{ route('register') }}">Daftar Sekarang</a>
    </div>
@endsection

