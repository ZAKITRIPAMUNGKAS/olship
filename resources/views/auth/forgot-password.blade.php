@extends('layouts.auth')
@section('title', 'Lupa Password')

@section('content')
    <div class="auth-header">
        <h2 class="auth-title">Lupa Password?</h2>
        <p class="auth-subtitle">Jangan khawatir! Beritahu kami alamat email Anda dan kami akan mengirimkan tautan reset password.</p>
    </div>

    @if (session('status'))
        <div class="success-msg" style="margin-bottom: 20px; padding: 12px; background: #ecfdf5; color: #059669; border-radius: 8px; font-size: 14px;">
            <i class="fas fa-check-circle"></i> {{ session('status') }}
        </div>
    @endif

    <form action="{{ route('password.email') }}" method="POST">
        @csrf
        <div class="form-group">
            <label class="form-label">Alamat Email</label>
            <input type="email" name="email" class="form-control" placeholder="Masukkan email terdaftar" value="{{ old('email') }}" required autofocus>
            @error('email') 
                <div class="error-msg"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div> 
            @enderror
        </div>

        <button type="submit" class="btn-volt btn-primary">KIRIM LINK RESET</button>
    </form>

    <div class="auth-footer">
        Ingat password Anda? <a href="{{ route('login') }}">Kembali Masuk</a>
    </div>
@endsection
