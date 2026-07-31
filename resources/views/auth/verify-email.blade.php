@extends('layouts.auth')
@section('title', 'Verifikasi Email')

@section('content')
    <div style="text-align: center; margin-bottom: 28px;">
        <div style="width: 72px; height: 72px; background: #e8f1fd; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
            <i class="fas fa-envelope-open-text" style="font-size: 28px; color: var(--brand-blue);"></i>
        </div>
        <h2 class="auth-title">Cek Email Anda!</h2>
        <p class="auth-subtitle">Kami telah mengirimkan link verifikasi ke email Anda. Silakan buka email dan klik link tersebut untuk mengaktifkan akun.</p>
    </div>

    @if (session('status'))
        <div style="margin-bottom: 20px; padding: 12px 16px; background: #ecfdf5; color: #059669; border-radius: 8px; font-size: 14px; display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-check-circle"></i>
            {{ session('status') }}
        </div>
    @endif

    @if (session('status') == 'verification-link-sent')
        <div style="margin-bottom: 20px; padding: 12px 16px; background: #ecfdf5; color: #059669; border-radius: 8px; font-size: 14px; display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-check-circle"></i> Link verifikasi baru telah dikirim ke email Anda.
        </div>
    @endif

    <div style="background: #f8fafc; border-radius: 8px; padding: 16px; margin-bottom: 24px; font-size: 13px; color: var(--text-muted);">
        <p style="margin-bottom: 8px;"><i class="fas fa-info-circle" style="color: var(--brand-blue);"></i> <strong>Tidak menerima email?</strong></p>
        <ul style="padding-left: 20px; line-height: 1.8;">
            <li>Periksa folder <strong>Spam/Junk</strong></li>
            <li>Pastikan alamat email yang digunakan sudah benar</li>
            <li>Klik tombol di bawah untuk kirim ulang</li>
        </ul>
    </div>

    <form action="{{ route('verification.send') }}" method="POST" style="margin-bottom: 12px;">
        @csrf
        <button type="submit" class="btn-volt btn-primary">
            <i class="fas fa-paper-plane"></i> KIRIM ULANG EMAIL VERIFIKASI
        </button>
    </form>

    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="btn-volt" style="width:100%;padding:12px;background:transparent;color:var(--text-muted);border:1px solid #e2e8f0;border-radius:6px;font-weight:600;font-size:13px;cursor:pointer;">
            Keluar dari Akun
        </button>
    </form>
@endsection
