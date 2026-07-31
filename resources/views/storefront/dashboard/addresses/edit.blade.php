@extends('storefront.dashboard.layout')

@section('title', 'Ubah Alamat - LISTRINDO JAYA ELEKTRIK')

@section('dashboard-content')
<div class="db-card">
    <div class="db-card-header">
        <h2 class="db-card-title">Ubah Alamat</h2>
        <p class="db-card-subtitle">Perbarui data alamat pengiriman Anda.</p>
    </div>

    <form action="{{ route('dashboard.addresses.update', $address->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="db-form-row">
            <div class="db-form-group">
                <label class="db-label">Label Alamat</label>
                <input type="text" name="label" class="db-input" placeholder="Contoh: Rumah, Kantor, Kos" required value="{{ old('label', $address->label) }}">
            </div>
            <div class="db-form-group">
                <label class="db-label">Nama Penerima</label>
                <input type="text" name="recipient_name" class="db-input" placeholder="Nama Lengkap" required value="{{ old('recipient_name', $address->recipient_name) }}">
            </div>
        </div>

        <div class="db-form-row">
            <div class="db-form-group">
                <label class="db-label">Nomor Telepon</label>
                <input type="text" name="phone" class="db-input" placeholder="0812xxxxxx" required value="{{ old('phone', $address->phone) }}">
            </div>
            <div class="db-form-group">
                <label class="db-label">Kode Pos</label>
                <input type="text" name="postal_code" class="db-input" placeholder="5 Digit Angka" required value="{{ old('postal_code', $address->postal_code) }}">
            </div>
        </div>

        <div class="db-form-row">
            <div class="db-form-group">
                <label class="db-label">Provinsi</label>
                <select name="province_id" id="provinceSelect" class="db-select" required>
                    <option value="">Pilih Provinsi</option>
                    @foreach($provinces as $p)
                        <option value="{{ $p['id'] }}" {{ $p['id'] == $address->province_id ? 'selected' : '' }}>
                            {{ $p['name'] }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="db-form-group">
                <label class="db-label">Kota/Kabupaten</label>
                <select name="city_id" id="citySelect" class="db-select" required>
                    <option value="">Pilih Kota</option>
                    @foreach($cities as $c)
                        <option value="{{ $c['id'] }}" {{ $c['id'] == $address->city_id ? 'selected' : '' }}>
                            {{ $c['type'] }} {{ $c['name'] }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="db-form-group">
            <label class="db-label">Alamat Lengkap</label>
            <textarea name="address_detail" class="db-textarea" rows="3" placeholder="Nama Jalan, Blok, Nomor Rumah" required>{{ old('address_detail', $address->address_detail) }}</textarea>
        </div>

        <div class="db-form-group" style="margin-bottom: 25px;">
            <label class="db-label">Catatan (Opsional)</label>
            <input type="text" name="notes" class="db-input" placeholder="Warna pagar, patokan, dll" value="{{ old('notes', $address->notes) }}">
        </div>

        <div style="margin-bottom: 30px;">
            <label class="db-checkbox-container">
                <input type="checkbox" name="is_default" value="1" class="db-checkbox" {{ $address->is_default ? 'checked' : '' }}>
                <span>Jadikan Alamat Utama</span>
            </label>
        </div>

        <div class="db-card-actions">
            <button type="submit" class="db-btn db-btn-primary" style="padding: 12px 28px; font-size: 14px;">
                Simpan Perubahan
            </button>
            <a href="{{ route('dashboard.addresses.index') }}" class="db-btn db-btn-secondary" style="padding: 12px 24px; font-size: 14px;">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('provinceSelect').addEventListener('change', async function() {
        const provinceId = this.value;
        const citySelect = document.getElementById('citySelect');
        
        citySelect.innerHTML = '<option value="">Pilih Kota</option>';
        citySelect.disabled = true;

        if (!provinceId) return;

        try {
            const response = await fetch(`/shipping/cities/${provinceId}`);
            const data = await response.json();

            data.cities.forEach(city => {
                const option = document.createElement('option');
                option.value = city.id;
                option.textContent = `${city.type} ${city.name}`;
                citySelect.appendChild(option);
            });

            citySelect.disabled = false;
        } catch (e) {
            console.error('Gagal memuat data kota:', e);
            alert('Gagal memuat data kota. Silakan coba lagi.');
        }
    });
</script>
@endpush
