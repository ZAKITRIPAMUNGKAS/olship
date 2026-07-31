@extends('layouts.app')
@section('title', 'Halaman Tidak Ditemukan - 404')

@section('content')
<div style="padding: 100px 0; text-align: center;">
    <h1 style="font-size: 120px; font-weight: 800; color: var(--primary); margin: 0;">404</h1>
    <h2 style="font-size: 32px; font-weight: 700; color: var(--ink); margin-bottom: 20px;">Opps! Halaman Tidak Ditemukan</h2>
    <p style="color: var(--slate-500); font-size: 18px; margin-bottom: 40px;">Maaf, halaman yang Anda cari tidak ada atau telah dipindahkan.</p>
    <a href="{{ url('/') }}" class="btn btn-primary btn-lg" style="padding: 12px 32px; border-radius: 12px;">Kembali ke Beranda</a>
</div>
@endsection
