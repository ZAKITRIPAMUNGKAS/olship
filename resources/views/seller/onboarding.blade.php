@extends('layouts.app')

@section('title', 'Mulai Berjualan - LISTRINDO JAYA ELEKTRIK')

@section('content')
<div class="onboarding-container" style="padding: 80px 0; max-width: 800px; margin: 0 auto; text-align: center;">
    <div style="font-size: 60px; color: var(--primary); margin-bottom: 30px;">
        <i class="fas fa-store"></i>
    </div>
    <h1 style="font-size: 36px; font-weight: 800; color: var(--ink); margin-bottom: 15px;">Buka Tokomu di LISTRINDO JAYA ELEKTRIK</h1>
    <p style="font-size: 18px; color: var(--slate-600); margin-bottom: 40px; line-height: 1.6;">
        Jangkau jutaan profesional dan penggemar alat teknik di seluruh Indonesia. Proses pendaftaran mudah, cepat, dan 100% transparan.
    </p>

    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px; margin-bottom: 50px; text-align: left;">
        <div class="card" style="padding: 24px; border-radius: 16px; border: 1px solid var(--slate-100); background: #fff;">
            <div style="font-size: 24px; color: var(--primary); margin-bottom: 15px;"><i class="fas fa-rocket"></i></div>
            <h3 style="font-weight: 700; margin-bottom: 10px;">Mudah & Cepat</h3>
            <p style="font-size: 14px; color: var(--slate-500);">Buka toko hanya dalam hitungan menit dengan formulir sederhana.</p>
        </div>
        <div class="card" style="padding: 24px; border-radius: 16px; border: 1px solid var(--slate-100); background: #fff;">
            <div style="font-size: 24px; color: var(--primary); margin-bottom: 15px;"><i class="fas fa-shield-alt"></i></div>
            <h3 style="font-weight: 700; margin-bottom: 10px;">Aman & Terpercaya</h3>
            <p style="font-size: 14px; color: var(--slate-500);">Sistem pembayaran otomatis yang menjamin dana Anda aman.</p>
        </div>
        <div class="card" style="padding: 24px; border-radius: 16px; border: 1px solid var(--slate-100); background: #fff;">
            <div style="font-size: 24px; color: var(--primary); margin-bottom: 15px;"><i class="fas fa-chart-line"></i></div>
            <h3 style="font-weight: 700; margin-bottom: 10px;">Insight Bisnis</h3>
            <p style="font-size: 14px; color: var(--slate-500);">Dapatkan data analitik mendalam untuk mengembangkan bisnis Anda.</p>
        </div>
    </div>

    <form action="{{ route('seller.onboarding.store') }}" method="POST" class="card" style="padding: 40px; border-radius: 24px; border: 2px solid var(--slate-100); text-align: left; background: #fff;">
        @csrf
        <h3 style="font-size: 22px; font-weight: 700; margin-bottom: 24px;">Informasi Toko</h3>
        
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: 600; margin-bottom: 8px;">Nama Toko</label>
            <input type="text" name="store_name" placeholder="Contoh: LISTRINDO JAYA ELEKTRIK Official" style="width: 100%; padding: 12px 16px; border-radius: 10px; border: 1px solid var(--slate-200);">
        </div>

        <div style="margin-bottom: 30px;">
            <label style="display: block; font-weight: 600; margin-bottom: 8px;">Deskripsi Singkat</label>
            <textarea name="description" placeholder="Jelaskan produk unggulan toko Anda..." style="width: 100%; padding: 12px 16px; border-radius: 10px; border: 1px solid var(--slate-200); height: 100px;"></textarea>
        </div>

        <button type="submit" class="btn btn-primary btn-lg" style="width: 100%; height: 56px; font-size: 18px; border-radius: 14px;">
            Buka Toko Sekarang
        </button>
    </form>
</div>
@endsection
