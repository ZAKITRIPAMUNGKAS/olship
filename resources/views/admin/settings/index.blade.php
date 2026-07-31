@extends('admin.layouts.app')
@section('title', 'Pengaturan Sistem')
@section('page-title', 'Pengaturan')

@section('content')
<div class="fade-up" style="margin-bottom: 28px;">
    <h2 style="font-size: 24px; font-weight: 800; color: var(--text-main);">Pengaturan Sistem</h2>
    <p style="font-size: 13px; color: var(--text-muted); margin-top: 4px;">Konfigurasi utama aplikasi dan integrasi Listrindo Jaya & Quin Food</p>
</div>

<div x-data="{ tab: 'general' }" class="fade-up delay-1">

    {{-- Modern Pill Navigation Tabs --}}
    <div style="display: inline-flex; background: var(--surface-2); padding: 6px; border-radius: 14px; border: 1px solid var(--border); margin-bottom: 24px; gap: 4px; flex-wrap: wrap;">
        
        <button type="button" @click="tab = 'general'"
            style="padding: 10px 20px; font-size: 13px; font-weight: 700; border: none; cursor: pointer; border-radius: 10px; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s ease;"
            :style="tab === 'general' ? 'background: #ffffff; color: var(--brand-primary); box-shadow: 0 4px 12px rgba(0,0,0,0.06);' : 'background: transparent; color: var(--text-muted);'">
            <i class="fas fa-cog"></i> Pengaturan Umum
        </button>

        <button type="button" @click="tab = 'payment'"
            style="padding: 10px 20px; font-size: 13px; font-weight: 700; border: none; cursor: pointer; border-radius: 10px; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s ease;"
            :style="tab === 'payment' ? 'background: #ffffff; color: var(--brand-primary); box-shadow: 0 4px 12px rgba(0,0,0,0.06);' : 'background: transparent; color: var(--text-muted);'">
            <i class="fas fa-credit-card"></i> Pembayaran (Midtrans)
        </button>

        <button type="button" @click="tab = 'shipping'"
            style="padding: 10px 20px; font-size: 13px; font-weight: 700; border: none; cursor: pointer; border-radius: 10px; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s ease;"
            :style="tab === 'shipping' ? 'background: #ffffff; color: var(--brand-primary); box-shadow: 0 4px 12px rgba(0,0,0,0.06);' : 'background: transparent; color: var(--text-muted);'">
            <i class="fas fa-truck"></i> Pengiriman (RajaOngkir)
        </button>

        <button type="button" @click="tab = 'notifications'"
            style="padding: 10px 20px; font-size: 13px; font-weight: 700; border: none; cursor: pointer; border-radius: 10px; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s ease;"
            :style="tab === 'notifications' ? 'background: #ffffff; color: var(--brand-primary); box-shadow: 0 4px 12px rgba(0,0,0,0.06);' : 'background: transparent; color: var(--text-muted);'">
            <i class="fas fa-bell"></i> Notifikasi System
        </button>

    </div>

    {{-- GENERAL TAB --}}
    <div x-show="tab === 'general'" class="fade-up">
        <div class="panel">
            <div class="panel-header">
                <div class="panel-title">
                    <i class="fas fa-sliders-h"></i> Konfigurasi Umum Toko
                </div>
            </div>

            <form action="{{ Route::has('admin.settings.update') ? route('admin.settings.update') : '#' }}" method="POST">
                @csrf
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Nama Aplikasi Toko</label>
                        <input class="form-input" name="app_name" value="{{ config('app.name', 'LISTRINDO JAYA ELEKTRIK') }}">
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">URL Utama Aplikasi</label>
                        <input class="form-input" name="app_url" value="{{ config('app.url', 'http://localhost') }}">
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Email Kontak Admin</label>
                        <input class="form-input" type="email" name="admin_email" value="admin@listrindojaya.com" placeholder="admin@listrindojaya.com">
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Zona Waktu (Timezone)</label>
                        <select class="form-select" name="timezone">
                            <option value="Asia/Jakarta" selected>Asia/Jakarta (WIB)</option>
                            <option value="Asia/Makassar">Asia/Makassar (WITA)</option>
                            <option value="Asia/Jayapura">Asia/Jayapura (WIT)</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="btn-ag btn-primary">
                    <i class="fas fa-save"></i> Simpan Konfigurasi Umum
                </button>
            </form>
        </div>
    </div>

    {{-- PAYMENT TAB --}}
    <div x-show="tab === 'payment'" x-cloak class="fade-up">
        <div class="panel">
            <div class="panel-header">
                <div class="panel-title">
                    <i class="fas fa-credit-card"></i> Integrasi Midtrans Payment Gateway
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Midtrans Server Key</label>
                    <input class="form-input" type="password" value="{{ config('midtrans.server_key') ?? 'SB-Mid-server-••••••••••••' }}" readonly>
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Midtrans Client Key</label>
                    <input class="form-input" type="password" value="{{ config('midtrans.client_key') ?? 'SB-Mid-client-••••••••••••' }}" readonly>
                </div>
            </div>

            <div style="padding: 16px; border-radius: 12px; background: #fffbeb; border: 1px solid #fde68a; font-size: 13px; color: #b45309; display: flex; align-items: center; gap: 12px;">
                <i class="fas fa-shield-alt" style="font-size: 18px; color: #d97706;"></i>
                <div>
                    <strong>Petunjuk Keamanan API Key:</strong> Kunci API Midtrans dikonfigurasi melalui berkas lingkungan <code style="font-family: monospace; background: rgba(0,0,0,0.06); padding: 2px 6px; border-radius: 4px; font-weight: 700;">.env</code> di server backend demi keamanan.
                </div>
            </div>
        </div>
    </div>

    {{-- SHIPPING TAB --}}
    <div x-show="tab === 'shipping'" x-cloak class="fade-up">
        <div class="panel">
            <div class="panel-header">
                <div class="panel-title">
                    <i class="fas fa-truck"></i> Integrasi RajaOngkir Logistics
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">RajaOngkir API Key</label>
                    <input class="form-input" type="password" value="••••••••••••••••••••" readonly>
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Tier Layanan</label>
                    <select class="form-select">
                        <option value="starter" {{ config('rajaongkir.tier') === 'starter' ? 'selected' : '' }}>Starter Tier (JNE, POS, TIKI)</option>
                        <option value="pro" {{ config('rajaongkir.tier') === 'pro' ? 'selected' : '' }}>Pro Tier (Semua Kurir)</option>
                    </select>
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">ID Kota Asal Pengiriman (Origin)</label>
                    <input class="form-input" value="{{ config('rajaongkir.origin', '152 (Jakarta Selatan)') }}" readonly>
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Kurir Aktif Toko</label>
                    <input class="form-input" value="JNE, POS Indonesia, TIKI" readonly>
                </div>
            </div>
        </div>
    </div>

    {{-- NOTIFICATIONS TAB --}}
    <div x-show="tab === 'notifications'" x-cloak class="fade-up">
        <div class="panel">
            <div class="panel-header">
                <div class="panel-title">
                    <i class="fas fa-bell"></i> Notifikasi Otomatis Sistem
                </div>
            </div>

            <div style="display: flex; flex-direction: column; gap: 14px;">
                @foreach([
                    'Notifikasi Pesanan Baru Masuk' => 'Kirim email & notifikasi sistem saat pembeli membuat pesanan baru',
                    'Konfirmasi Pembayaran Diterima' => 'Kirim invoice otomatis saat status pembayaran berubah menjadi Paid',
                    'Peringatan Stok Produk Kritis' => 'Kirim peringatan ke admin jika ada produk yang stoknya dibawah 5 unit',
                    'Moderasi Ulasan Pembeli Baru' => 'Beritahu admin jika ada ulasan produk baru yang butuh moderasi',
                    'Permintaan Penarikan Saldo Penjual' => 'Kirim notifikasi saat ada seller mengajukan penarikan dana'
                ] as $title => $desc)
                <div style="display: flex; align-items: center; justify-content: space-between; padding: 16px 20px; border-radius: 12px; background: var(--surface-2); border: 1px solid var(--border);">
                    <div>
                        <div style="font-weight: 700; color: var(--text-main); font-size: 14px;">{{ $title }}</div>
                        <div style="font-size: 12px; color: var(--text-muted); margin-top: 2px;">{{ $desc }}</div>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" checked>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                @endforeach
            </div>
        </div>
    </div>

</div>
@endsection
