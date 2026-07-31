@extends('layouts.app')

@section('title', 'Keranjang Belanja - LISTRINDO JAYA ELEKTRIK')

@push('styles')
@vite(['resources/css/components/cart.css'])
@endpush

@section('content')
<div class="cart-container" x-data="cartPage()">
    <div class="cart-header">
        <h1>Keranjang Belanja</h1>
        <p>Pilih produk yang ingin Anda checkout</p>
    </div>

    @if($items->isEmpty())
        <div class="cart-empty">
            <div class="icon">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <h2>Keranjangmu Kosong</h2>
            <p>Ayo cari produk menarik dan mulai belanja!</p>
            <a href="{{ route('home') }}" class="btn btn-primary btn-lg">Mulai Belanja</a>
        </div>
    @else
        <div class="cart-grid">
            <!-- LEFT COLUMN: Items -->
            <div class="cart-main">
                <div class="card">
                    <div class="cart-select-all">
                        <label>
                            <input type="checkbox" id="selectAll" checked> Pilih Semua
                        </label>
                        <button type="button" class="btn btn-ghost" style="color: var(--red); font-weight: 600;">Hapus</button>
                    </div>

                    <div class="cart-items">
                        @foreach($items as $item)
                            <div class="cart-item" data-id="{{ $item->id }}">
                                <div style="display: flex; align-items: center;">
                                    <input type="checkbox" checked>
                                </div>
                                <div class="cart-item-img">
                                    <img 
                                        src="{{ $item->product->primaryImage ? asset('storage/'.$item->product->primaryImage->image_path) : 'https://placehold.co/200x200?text='.urlencode($item->product->name) }}" 
                                        alt="{{ $item->product->name }}"
                                        loading="lazy">
                                </div>
                                <div class="cart-item-info">
                                    <h3>{{ $item->product->name }}</h3>
                                    <div class="cart-item-brand">{{ $item->product->brand->name ?? 'LISTRINDO JAYA ELEKTRIK Official' }}</div>
                                    <div class="cart-item-price-row">
                                        <div class="cart-item-price">Rp {{ number_format($item->price, 0, ',', '.') }}</div>
                                        
                                        <div class="qty-control">
                                            <button type="button" @click="updateQty({{ $item->id }}, {{ $item->quantity - 1 }})" :disabled="isLoading"><i class="fas fa-minus"></i></button>
                                            <input type="text" value="{{ $item->quantity }}" readonly>
                                            <button type="button" @click="updateQty({{ $item->id }}, {{ $item->quantity + 1 }})" :disabled="isLoading"><i class="fas fa-plus"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="cart-item-remove" @click="removeItem({{ $item->id }})" :disabled="isLoading"><i class="fas fa-trash-alt"></i></button>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN: Summary -->
            <div class="cart-sidebar">
                <div class="card">
                    <h3>Ringkasan Belanja</h3>
                    
                    <div class="summary-row">
                        <span>Total Harga ({{ count($items) }} Barang)</span>
                        <span class="val">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>

                    <div class="summary-row total">
                        <div class="lbl">Total Tagihan</div>
                        <div class="val">Rp {{ number_format($subtotal, 0, ',', '.') }}</div>
                    </div>

                    <a href="{{ route('checkout.index') }}" class="btn btn-primary btn-lg" style="width: 100%; height: 56px; font-size: 18px; border-radius: 14px; margin-top: 24px;">
                        Beli ({{ count($items) }})
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    window.cartPage = function() {
        return {
            isLoading: false,
            
            async updateQty(itemId, newQty) {
                if (newQty < 1) return this.removeItem(itemId);
                
                this.isLoading = true;
                try {
                    const response = await fetch(`/cart/${itemId}`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ quantity: newQty })
                    });
                    if (response.ok) window.location.reload();
                } catch (e) {
                    alert('Gagal memperbarui keranjang');
                } finally {
                    this.isLoading = false;
                }
            },
            
            async removeItem(itemId) {
                if (!confirm('Hapus produk ini dari keranjang?')) return;
                
                this.isLoading = true;
                try {
                    const response = await fetch(`/cart/${itemId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    });
                    if (response.ok) window.location.reload();
                } catch (e) {
                    alert('Gagal menghapus produk');
                } finally {
                    this.isLoading = false;
                }
            }
        };
    };
</script>
@endpush
