@extends('layouts.app')

@section('title', 'Checkout - LISTRINDO JAYA ELEKTRIK')

@push('styles')
<style>
@media (max-width: 1024px) {
    .checkout-grid { grid-template-columns: 1fr !important; }
}
@media (max-width: 768px) {
    .checkout-container { padding: 20px 0 !important; }
}
</style>
@endpush

@section('content')
<div class="checkout-container" style="padding: 40px 0;">
    <div class="checkout-header" style="margin-bottom: 30px;">
        <h1 style="font-size: 28px; font-weight: 700; color: var(--ink);">Checkout</h1>
        <p style="color: var(--slate-600);">Selesaikan pesanan Anda</p>
    </div>

    <form action="{{ route('checkout.process') }}" method="POST" id="checkoutForm">
        @csrf
        <div class="checkout-grid" style="display: grid; grid-template-columns: 1fr 380px; gap: 30px;">
            <!-- LEFT COLUMN: Address & Items -->
            <div class="checkout-main">
                <!-- ADDRESS SECTION -->
                <div class="checkout-section card" style="padding: 24px; margin-bottom: 24px; border-radius: 16px; border: 1px solid var(--slate-200);">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                        <h2 style="font-size: 18px; font-weight: 600; display: flex; align-items: center; gap: 10px;">
                            <i class="fas fa-map-marker-alt" style="color: var(--primary);"></i> Alamat Pengiriman
                        </h2>
                        <button type="button" onclick="openAddressModal()" class="btn btn-ghost" style="font-size: 14px; color: var(--primary);">Pilih Alamat Lain</button>
                    </div>

                    <div id="selectedAddressDisplay" style="padding: 16px; background: var(--slate-50); border-radius: 12px; border: 1px dashed var(--slate-300);">
                        @if($defaultAddress)
                            <div style="font-weight: 600; margin-bottom: 4px;">{{ $defaultAddress->recipient_name }} ({{ $defaultAddress->label }})</div>
                            <div style="color: var(--slate-600); font-size: 14px;">{{ $defaultAddress->phone }}</div>
                            <div style="color: var(--slate-600); font-size: 14px; margin-top: 8px;">
                                {{ $defaultAddress->address_detail }}, {{ $defaultAddress->city->name }}, {{ $defaultAddress->province->name }}, {{ $defaultAddress->postal_code }}
                            </div>
                            <input type="hidden" name="address_id" value="{{ $defaultAddress->id }}" id="addressInput">
                        @else
                            <div style="text-align: center; padding: 20px;">
                                <p style="color: var(--slate-500); margin-bottom: 15px;">Belum ada alamat pengiriman.</p>
                                <button type="button" onclick="openAddressModal()" class="btn btn-primary">Tambah Alamat Baru</button>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- ITEMS SECTION -->
                <div class="checkout-section card" style="padding: 24px; margin-bottom: 24px; border-radius: 16px; border: 1px solid var(--slate-200);">
                    <h2 style="font-size: 18px; font-weight: 600; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-shopping-bag" style="color: var(--primary);"></i> Rincian Pesanan
                    </h2>
                    <div class="order-items">
                        @foreach($items as $item)
                            <div class="order-item" style="display: flex; gap: 15px; padding: 15px 0; border-bottom: 1px solid var(--slate-100);">
                                <div style="width: 80px; height: 80px; border-radius: 8px; background: var(--slate-100); overflow: hidden; flex-shrink: 0;">
                                    <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                </div>
                                <div style="flex: 1;">
                                    <div style="font-weight: 500; color: var(--ink);">{{ $item->product->name }}</div>
                                    <div style="font-size: 14px; color: var(--slate-500);">{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</div>
                                </div>
                                <div style="font-weight: 600; color: var(--ink);">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- SHIPPING OPTIONS -->
                <div class="checkout-section card" style="padding: 24px; border-radius: 16px; border: 1px solid var(--slate-200);">
                    <h2 style="font-size: 18px; font-weight: 600; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-truck" style="color: var(--primary);"></i> Metode Pengiriman
                    </h2>
                    <div id="shippingOptionsContainer">
                        <div style="padding: 20px; text-align: center; border: 1px solid var(--slate-100); border-radius: 12px; background: var(--slate-50);">
                            @if($defaultAddress)
                                <button type="button" id="btnLoadShipping" class="btn btn-outline">Lihat Pilihan Pengiriman</button>
                            @else
                                <p style="color: var(--slate-500);">Pilih alamat terlebih dahulu untuk melihat ongkos kirim.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN: Summary -->
            <div class="checkout-sidebar">
                <div class="card sticky" style="padding: 24px; border-radius: 20px; border: 2px solid var(--slate-100); position: sticky; top: 100px; background: #fff;">
                    <h3 style="font-size: 20px; font-weight: 700; margin-bottom: 24px;">Ringkasan Belanja</h3>
                    
                    <div style="display: flex; justify-content: space-between; margin-bottom: 12px; color: var(--slate-600);">
                        <span>Total Harga ({{ count($items) }} Barang)</span>
                        <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 12px; color: var(--slate-600);">
                        <span>Total Ongkos Kirim</span>
                        <span id="displayShippingCost">Rp 0</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 12px; color: var(--slate-600);">
                        <span>Biaya Jasa Aplikasi</span>
                        <span>Rp 1.000</span>
                    </div>

                    <!-- COUPON SECTION -->
                    <div id="couponContainer" style="margin-top: 15px; padding-top: 15px; border-top: 1px dashed var(--slate-100);">
                        @if($coupon)
                            <div style="display: flex; justify-content: space-between; align-items: center; background: #e8f5e9; padding: 10px; border-radius: 8px; border: 1px solid #c8e6c9;">
                                <div>
                                    <div style="font-weight: 700; color: #2e7d32; font-size: 13px;">{{ $coupon->code }}</div>
                                    <div style="font-size: 11px; color: #43a047;">{{ $coupon->name }}</div>
                                </div>
                                <button type="button" onclick="removeCoupon()" style="color: #c62828; font-size: 14px; background: none; border: none; cursor: pointer;"><i class="fas fa-times-circle"></i></button>
                            </div>
                        @else
                            <div style="display: flex; gap: 8px;">
                                <input type="text" id="couponCode" class="form-control" placeholder="Kode Promo" style="flex: 1; padding: 8px 12px; font-size: 13px;">
                                <button type="button" onclick="applyCoupon()" class="btn btn-outline btn-sm" style="padding: 8px 15px;">Pakai</button>
                            </div>
                        @endif
                    </div>

                    <div id="discountDisplay" style="display: {{ $discount > 0 ? 'flex' : 'none' }}; justify-content: space-between; margin-top: 12px; color: #2e7d32; font-weight: 600;">
                        <span>Diskon Kupon</span>
                        <span>-Rp {{ number_format($discount, 0, ',', '.') }}</span>
                    </div>

                    <div style="border-top: 1px solid var(--slate-100); margin: 20px 0; padding-top: 20px; display: flex; justify-content: space-between; align-items: flex-end;">
                        <div style="font-weight: 600; color: var(--ink);">Total Tagihan</div>
                        <div style="font-size: 24px; font-weight: 800; color: var(--primary);" id="displayTotal">Rp {{ number_format($total + 1000, 0, ',', '.') }}</div>
                    </div>

                    <input type="hidden" name="shipping_courier" id="inputCourier">
                    <input type="hidden" name="shipping_service" id="inputService">
                    <input type="hidden" name="shipping_cost" id="inputCost">
                    <input type="hidden" name="shipping_etd" id="inputEtd">

                    <button type="submit" class="btn btn-primary btn-lg" style="width: 100%; height: 56px; font-size: 18px; border-radius: 14px;" id="btnPlaceOrder" disabled>
                        Pilih Pembayaran
                    </button>
                    <p style="text-align: center; font-size: 12px; color: var(--slate-400); margin-top: 15px;">
                        Dengan mengklik tombol, Anda menyetujui <a href="#" style="color: var(--primary);">Syarat & Ketentuan</a> LISTRINDO JAYA ELEKTRIK.
                    </p>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- ADDRESS MODAL (Redesigned Premium version) -->
<div id="addressModal" class="premium-modal">
    <div class="modal-dialog">
        <!-- Header -->
        <div class="modal-header">
            <h3 class="modal-title">Pilih Alamat Pengiriman</h3>
            <button type="button" onclick="closeAddressModal()" class="modal-close" aria-label="Tutup">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <!-- Body -->
        <div class="modal-body">
            <!-- List of existing addresses -->
            <div class="address-card-list" id="addressList">
                @foreach($addresses as $addr)
                    <div class="address-card {{ $defaultAddress && $defaultAddress->id === $addr->id ? 'selected' : '' }}" 
                         onclick="selectAddress({{ $addr->id }}, '{{ addslashes($addr->recipient_name) }}', '{{ $addr->label }}', '{{ $addr->phone }}', '{{ addslashes($addr->address_detail) }}', '{{ $addr->city->name }}', '{{ $addr->province->name }}', '{{ $addr->postal_code }}', {{ $addr->city_id }})">
                        <span class="address-badge">{{ $addr->label }}</span>
                        <div class="address-recipient">{{ $addr->recipient_name }}</div>
                        <div class="address-phone"><i class="fas fa-phone-alt" style="font-size: 11px; margin-right: 6px; color: #94a3b8;"></i>{{ $addr->phone }}</div>
                        <p class="address-detail">{{ $addr->address_detail }}, {{ $addr->city->name }}, {{ $addr->province->name }} {{ $addr->postal_code }}</p>
                    </div>
                @endforeach
            </div>
            
            <!-- Button to toggle new address form -->
            <button type="button" onclick="toggleNewAddressForm()" class="btn btn-outline" style="width: 100%; border-radius: 12px; height: 44px; display: flex; align-items: center; justify-content: center; gap: 8px; font-weight: 600;">
                <i class="fas fa-plus" style="font-size: 12px;"></i> Tambah Alamat Baru
            </button>

            <!-- NEW ADDRESS FORM (Inside Modal) -->
            <div id="newAddressForm" style="display:none; border-top: 1px solid #f1f5f9; padding-top: 16px; margin-top: 8px;">
                <h4 style="font-weight: 700; font-size: 15px; color: #0f172a; margin: 0 0 16px 0; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-map-pin" style="color: var(--primary);"></i> Isi Detail Alamat Baru
                </h4>
                
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    <div class="form-row-2">
                        <div class="premium-form-group">
                            <label class="premium-form-label">Label Alamat</label>
                            <input type="text" id="newLabel" class="premium-input" placeholder="Contoh: Rumah, Kantor">
                        </div>
                        <div class="premium-form-group">
                            <label class="premium-form-label">Nama Penerima</label>
                            <input type="text" id="newRecipient" class="premium-input" placeholder="Nama Lengkap">
                        </div>
                    </div>
                    
                    <div class="form-row-2">
                        <div class="premium-form-group">
                            <label class="premium-form-label">No. Telepon</label>
                            <input type="text" id="newPhone" class="premium-input" placeholder="Contoh: 08123456789">
                        </div>
                        <div class="premium-form-group">
                            <label class="premium-form-label">Kode Pos</label>
                            <input type="text" id="newPostal" class="premium-input" placeholder="5 digit kode pos">
                        </div>
                    </div>
                    
                    <div class="form-row-2">
                        <div class="premium-form-group">
                            <label class="premium-form-label">Provinsi</label>
                            <select id="newProvince" class="premium-select" onchange="loadNewCities()">
                                <option value="">Pilih Provinsi</option>
                                @foreach($provinces as $p)
                                    <option value="{{ $p['id'] }}">{{ $p['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="premium-form-group">
                            <label class="premium-form-label">Kota / Kabupaten</label>
                            <select id="newCity" class="premium-select">
                                <option value="">Pilih Kota/Kabupaten</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="premium-form-group">
                        <label class="premium-form-label">Alamat Lengkap</label>
                        <textarea id="newAddress" class="premium-textarea" rows="3" placeholder="Tuliskan nama jalan, RT/RW, nomor rumah, nomor gedung, atau patokan"></textarea>
                    </div>
                    
                    <button type="button" onclick="saveNewAddress()" class="btn btn-primary" style="width: 100%; border-radius: 12px; height: 46px; font-weight: 700; margin-top: 8px; display: flex; align-items: center; justify-content: center; gap: 8px;">
                        <i class="fas fa-save" style="font-size: 13px;"></i> Simpan & Gunakan Alamat
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const subtotal = {{ $subtotal }};
    const weight = {{ $weight }};
    window.currentDestinationCityId = {{ $defaultAddress->city_id ?? 0 }};
    
    document.getElementById('btnLoadShipping')?.addEventListener('click', loadShippingOptions);

    async function loadShippingOptions() {
        const addressId = document.getElementById('addressInput').value;
        const container = document.getElementById('shippingOptionsContainer');
        
        container.innerHTML = '<div style="text-align:center; padding:20px;"><i class="fas fa-circle-notch fa-spin"></i> Menghitung ongkir...</div>';

        try {
            // Kita butuh destination city id dari address. 
            // Untuk sementara kita hardcode atau asumsikan kita punya endpoint untuk get cost by address id
            // Atau kita ambil dari $defaultAddress->city_id di backend dan lewatkan ke JS.
            const response = await fetch('{{ route("shipping.cost") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    destination: window.currentDestinationCityId || 0,
                    weight: weight
                })
            });

            const data = await response.json();
            
            if (data.options && data.options.length > 0) {
                let html = '<div style="display:grid; gap:10px;">';
                data.options.forEach((opt, idx) => {
                    html += `
                        <div class="shipping-card" onclick="selectShipping('${opt.courier}', '${opt.service}', ${opt.cost}, '${opt.etd}')" 
                             style="padding:16px; border:1px solid var(--slate-200); border-radius:12px; cursor:pointer; display:flex; justify-content:space-between; align-items:center;">
                            <div>
                                <div style="font-weight:600; text-transform:uppercase; color:var(--primary);">${opt.courier} - ${opt.service}</div>
                                <div style="font-size:13px; color:var(--slate-500);">${opt.description} (${opt.etd} hari)</div>
                            </div>
                            <div style="font-weight:700;">Rp ${opt.cost.toLocaleString('id-ID')}</div>
                        </div>
                    `;
                });
                html += '</div>';
                container.innerHTML = html;
            } else {
                container.innerHTML = '<p style="color:var(--red-500);">Kurir tidak tersedia untuk wilayah ini.</p>';
            }
        } catch (e) {
            container.innerHTML = '<p style="color:var(--red-500);">Gagal memuat data pengiriman.</p>';
        }
    }

    async function applyCoupon() {
        const code = document.getElementById('couponCode').value;
        if (!code) return;

        try {
            const response = await fetch('{{ route("checkout.coupon.apply") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ code: code })
            });

            const data = await response.json();
            if (data.status === 'success') {
                location.reload(); // Simple way to update all amounts correctly
            } else {
                alert(data.message);
            }
        } catch (e) {
            alert('Gagal menerapkan kupon.');
        }
    }

    async function removeCoupon() {
        try {
            const response = await fetch('{{ route("checkout.coupon.remove") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            location.reload();
        } catch (e) {
            alert('Gagal menghapus kupon.');
        }
    }

    function selectShipping(courier, service, cost, etd) {
        // Highlight selection
        document.querySelectorAll('.shipping-card').forEach(el => el.style.borderColor = 'var(--slate-200)');
        event.currentTarget.style.borderColor = 'var(--primary)';
        event.currentTarget.style.background = 'var(--slate-50)';

        // Update inputs
        document.getElementById('inputCourier').value = courier;
        document.getElementById('inputService').value = service;
        document.getElementById('inputCost').value = cost;
        document.getElementById('inputEtd').value = etd;

        // Update Display
        const currentSubtotal = {{ $subtotal }};
        const currentDiscount = {{ $discount }};
        
        document.getElementById('displayShippingCost').innerText = 'Rp ' + cost.toLocaleString('id-ID');
        document.getElementById('displayTotal').innerText = 'Rp ' + (currentSubtotal - currentDiscount + cost + 1000).toLocaleString('id-ID');
        
        // Enable place order
        document.getElementById('btnPlaceOrder').disabled = false;
    }

    function openAddressModal() { document.getElementById('addressModal').style.display = 'flex'; }
    function closeAddressModal() { 
        document.getElementById('addressModal').style.display = 'none'; 
        document.getElementById('newAddressForm').style.display = 'none';
    }

    function toggleNewAddressForm() {
        const form = document.getElementById('newAddressForm');
        form.style.display = form.style.display === 'none' ? 'block' : 'none';
    }

    async function loadNewCities() {
        const provinceId = document.getElementById('newProvince').value;
        const citySelect = document.getElementById('newCity');
        citySelect.innerHTML = '<option value="">Memuat...</option>';
        
        try {
            const response = await fetch(`/shipping/cities/${provinceId}`);
            const data = await response.json();
            
            citySelect.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';
            data.cities.forEach(city => {
                citySelect.innerHTML += `<option value="${city.id}">${city.name}</option>`;
            });
        } catch (e) {
            citySelect.innerHTML = '<option value="">Gagal memuat kota</option>';
        }
    }

    async function saveNewAddress() {
        const payload = {
            label: document.getElementById('newLabel').value,
            recipient_name: document.getElementById('newRecipient').value,
            phone: document.getElementById('newPhone').value,
            province_id: document.getElementById('newProvince').value,
            city_id: document.getElementById('newCity').value,
            postal_code: document.getElementById('newPostal').value,
            address_detail: document.getElementById('newAddress').value,
            is_default: true
        };

        try {
            const response = await fetch('{{ route("dashboard.addresses.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(payload)
            });

            const data = await response.json();
            if (data.status === 'success') {
                location.reload(); // Simplest way to refresh all lists
            } else {
                alert('Gagal menyimpan alamat. Pastikan semua field terisi.');
            }
        } catch (e) {
            alert('Terjadi kesalahan saat menyimpan alamat.');
        }
    }

    function selectAddress(id, name, label, phone, address, city, province, postal, cityId) {
        document.getElementById('addressInput').value = id;
        document.getElementById('selectedAddressDisplay').innerHTML = `
            <div style="font-weight: 600; margin-bottom: 4px;">${name} (${label})</div>
            <div style="color: var(--slate-600); font-size: 14px;">${phone}</div>
            <div style="color: var(--slate-600); font-size: 14px; margin-top: 8px;">
                ${address}, ${city}, ${province}, ${postal}
            </div>
            <input type="hidden" name="address_id" value="${id}" id="addressInput">
        `;
        
        // Update destination for shipping calculation
        window.currentDestinationCityId = cityId;
        
        closeAddressModal();
        // Reset shipping options as address changed
        document.getElementById('shippingOptionsContainer').innerHTML = `
            <div style="padding: 20px; text-align: center; border: 1px solid var(--slate-100); border-radius: 12px; background: var(--slate-50);">
                <button type="button" id="btnLoadShipping" onclick="loadShippingOptions()" class="btn btn-outline">Lihat Pilihan Pengiriman</button>
            </div>
        `;
    }
</script>

<style>
    .shipping-card:hover {
        background: var(--slate-50);
        border-color: var(--primary);
    }
    .btn-ghost:hover {
        background: var(--slate-100);
    }

    /* Modal premium styles */
    .premium-modal {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.4);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        z-index: 1000;
        align-items: center;
        justify-content: center;
        padding: 16px;
        transition: all 0.3s ease;
    }
    .modal-dialog {
        background: #ffffff;
        width: 100%;
        max-width: 550px;
        border-radius: 24px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        border: 1px solid #f1f5f9;
        max-height: 90vh;
        display: flex;
        flex-direction: column;
        animation: modalFadeIn 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }
    @keyframes modalFadeIn {
        from { transform: scale(0.95); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }
    .modal-header {
        padding: 20px 24px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .modal-title {
        font-size: 18px;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
    }
    .modal-close {
        background: #f1f5f9;
        border: none;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #64748b;
        cursor: pointer;
        transition: all 0.2s;
    }
    .modal-close:hover {
        background: #e2e8f0;
        color: #0f172a;
    }
    .modal-body {
        padding: 24px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 16px;
    }
    
    /* Address List premium styles */
    .address-card-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
        max-height: 250px;
        overflow-y: auto;
        padding-right: 4px;
    }
    .address-card-list::-webkit-scrollbar {
        width: 6px;
    }
    .address-card-list::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }
    .address-card {
        padding: 16px;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        cursor: pointer;
        background: #ffffff;
        transition: all 0.2s ease;
        position: relative;
    }
    .address-card:hover {
        border-color: var(--primary, #025cca);
        background: #f8fafc;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }
    .address-card.selected {
        border-color: var(--primary, #025cca);
        background: #eff6ff;
    }
    .address-badge {
        display: inline-block;
        padding: 2px 8px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        background: #e0f2fe;
        color: #0369a1;
        margin-bottom: 6px;
    }
    .address-card.selected .address-badge {
        background: #dbeafe;
        color: #1d4ed8;
    }
    .address-recipient {
        font-size: 14px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 2px;
    }
    .address-phone {
        font-size: 13px;
        color: #64748b;
        margin-bottom: 6px;
    }
    .address-detail {
        font-size: 13px;
        color: #334155;
        line-height: 1.5;
        margin: 0;
    }

    /* Form control improvements */
    .premium-form-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }
    .premium-form-label {
        font-size: 12px;
        font-weight: 600;
        color: #475569;
    }
    .premium-input {
        height: 42px;
        border-radius: 10px;
        border: 1px solid #cbd5e1;
        padding: 0 14px;
        font-size: 13px;
        background: #ffffff;
        transition: all 0.2s;
        width: 100%;
        box-sizing: border-box;
    }
    .premium-input:focus {
        border-color: var(--primary, #025cca);
        outline: none;
        box-shadow: 0 0 0 3px rgba(2, 92, 202, 0.1);
    }
    .premium-select {
        height: 42px;
        border-radius: 10px;
        border: 1px solid #cbd5e1;
        padding: 0 36px 0 14px;
        font-size: 13px;
        background: #ffffff url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%2364748b' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e") no-repeat right 12px center/18px;
        transition: all 0.2s;
        width: 100%;
        box-sizing: border-box;
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        cursor: pointer;
    }
    .premium-select:focus {
        border-color: var(--primary, #025cca);
        outline: none;
        box-shadow: 0 0 0 3px rgba(2, 92, 202, 0.1);
    }
    .premium-textarea {
        border-radius: 10px;
        border: 1px solid #cbd5e1;
        padding: 10px 14px;
        font-size: 13px;
        background: #ffffff;
        transition: all 0.2s;
        width: 100%;
        box-sizing: border-box;
        resize: vertical;
    }
    .premium-textarea:focus {
        border-color: var(--primary, #025cca);
        outline: none;
        box-shadow: 0 0 0 3px rgba(2, 92, 202, 0.1);
    }
    
    .form-row-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }
    @media (max-width: 480px) {
        .form-row-2 {
            grid-template-columns: 1fr;
            gap: 10px;
        }
    }
</style>
@endpush
