@extends('layouts.auth')
@section('title', 'Atur Ulang Password')

@section('content')
    <div class="auth-header">
        <h2 class="auth-title">Atur Ulang Password</h2>
        <p class="auth-subtitle">Silakan masukkan password baru Anda di bawah ini.</p>
    </div>

    <form action="{{ route('password.update') }}" method="POST">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div class="form-group">
            <label class="form-label">Alamat Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $email) }}" required readonly>
            @error('email') 
                <div class="error-msg"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div> 
            @enderror
        </div>

        <div class="form-group" x-data="{ show: false }">
            <label class="form-label">Password Baru</label>
            <div class="password-wrapper">
                <input :type="show ? 'text' : 'password'" name="password" class="form-control" placeholder="Masukkan password baru" required>
                <div class="password-toggle" @click="show = !show">
                    <i class="fas" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                </div>
            </div>
            @error('password') 
                <div class="error-msg"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div> 
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Konfirmasi Password Baru</label>
            <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password baru" required>
        </div>

        <button type="submit" class="btn-volt btn-primary">SIMPAN PASSWORD BARU</button>
    </form>
@endsection
