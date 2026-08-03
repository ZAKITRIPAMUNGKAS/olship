@extends('admin.layouts.app')
@section('title', 'Informasi Tambah Produk')
@section('page-title', 'Single Source of Truth WMS')

@section('breadcrumb')
    <i class="fas fa-chevron-right"></i>
    <a href="{{ route('admin.products.index') }}">Produk</a>
@endsection

@section('content')
<div class="fade-up" style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center;">
    <div style="display: flex; align-items: center; gap: 12px;">
        <a href="{{ route('admin.products.index') }}" class="btn-ag btn-ghost btn-sm">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Produk
        </a>
    </div>
</div>

<div class="panel fade-up delay-1" style="max-width: 680px; margin: 40px auto; text-align: center; padding: 48px 32px; background: var(--surface-1); border: 1px solid var(--border); border-radius: 24px;">
    <div style="width: 80px; height: 80px; border-radius: 24px; background: rgba(2, 92, 202, 0.1); color: var(--brand-primary); display: flex; align-items: center; justify-content: center; font-size: 36px; margin: 0 auto 24px;">
        <i class="fas fa-database"></i>
    </div>
    
    <h2 style="font-size: 22px; font-weight: 800; color: var(--text-main); margin-bottom: 12px;">Single Source of Truth (WMS)</h2>
    
    <p style="font-size: 14px; color: var(--text-muted); line-height: 1.6; max-width: 520px; margin: 0 auto 28px;">
        Master produk dikelola melalui WMS sebagai Single Source of Truth. Silakan tambahkan produk pada Dashboard WMS. Setelah tersimpan, produk akan otomatis tersinkronisasi ke Olshop.
    </p>

    <div style="display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;">
        <a href="{{ route('admin.products.index') }}" class="btn-ag btn-primary" style="padding: 12px 24px;">
            <i class="fas fa-list"></i> Lihat Daftar Produk Olshop
        </a>
    </div>
</div>
@endsection
