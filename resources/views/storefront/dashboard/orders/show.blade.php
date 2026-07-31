@extends('storefront.dashboard.layout')

@section('title', 'Detail Pesanan - LISTRINDO JAYA ELEKTRIK')

@push('styles')
<style>
  .db-detail-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 32px;
    margin-bottom: 30px;
  }
  .db-detail-info-block {
    background: #f8fafc;
    border: 1px solid var(--line);
    border-radius: 16px;
    padding: 20px;
  }
  .db-detail-title {
    font-size: 11px;
    font-weight: 800;
    text-transform: uppercase;
    color: var(--ink-3);
    letter-spacing: 0.05em;
    margin-bottom: 12px;
  }
  .db-detail-item {
    display: flex;
    gap: 16px;
    padding: 16px 0;
    border-bottom: 1px dashed var(--line);
    align-items: center;
  }
  .db-detail-item:last-child {
    border-bottom: none;
  }
  
  @media (max-width: 768px) {
    .db-detail-grid {
      grid-template-columns: 1fr;
      gap: 20px;
    }
  }
</style>
@endpush

@section('dashboard-content')
<div style="margin-bottom: 24px; display: flex; align-items: center; gap: 15px;">
    <a href="{{ route('dashboard.orders') }}" class="db-btn db-btn-secondary" style="width: 44px; height: 44px; border-radius: 50%; padding: 0; display: inline-flex; align-items: center; justify-content: center; font-size: 16px; min-height: 44px;">
        <i class="fas fa-arrow-left"></i>
    </a>
    <div>
        <h2 style="font-size: 20px; font-weight: 800; color: var(--ink); margin: 0;">Detail Pesanan</h2>
        <p style="color: var(--ink-3); font-size: 13px; margin: 2px 0 0 0;">Dibuat pada {{ $order->created_at->format('d M Y, H:i') }}</p>
    </div>
</div>

<div class="db-card">
    {{-- Status Banner --}}
    @php
        $paymentBadge = $order->payment_status == 'paid' ? 'db-badge-completed' : 'db-badge-pending';
        $paymentLabel = $order->payment_status == 'paid' ? 'Sudah Dibayar' : 'Menunggu Pembayaran';
        
        $shippingBadge = 'db-badge-pending';
        if ($order->shipping_status == 'shipped') $shippingBadge = 'db-badge-shipped';
        elseif ($order->shipping_status == 'delivered' || $order->shipping_status == 'completed') $shippingBadge = 'db-badge-completed';
    @endphp
    
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px; margin-bottom: 28px; padding-bottom: 20px; border-bottom: 1px solid #f1f5f9;">
        <div>
            <div style="font-size: 12px; font-weight: 600; color: var(--ink-3); margin-bottom: 6px;">Nomor Pesanan</div>
            <div style="font-family: monospace; font-size: 16px; font-weight: 700; color: var(--ink);">#{{ $order->order_number }}</div>
        </div>
        <div style="display: flex; gap: 12px; flex-wrap: wrap;">
            <div>
                <div style="font-size: 11px; font-weight: 600; color: var(--ink-3); margin-bottom: 4px; text-align: right;">Pembayaran</div>
                <span class="db-badge {{ $paymentBadge }}">{{ $paymentLabel }}</span>
            </div>
            <div>
                <div style="font-size: 11px; font-weight: 600; color: var(--ink-3); margin-bottom: 4px; text-align: right;">Pengiriman</div>
                <span class="db-badge {{ $shippingBadge }}">{{ ucfirst($order->shipping_status ?: 'Pending') }}</span>
            </div>
        </div>
    </div>

    {{-- Info Grid --}}
    <div class="db-detail-grid">
        <div class="db-detail-info-block">
            <h4 class="db-detail-title"><i class="fas fa-map-marker-alt" style="margin-right: 6px;"></i> Alamat Pengiriman</h4>
            <div style="font-weight: 700; font-size: 14px; color: var(--ink);">{{ $order->shipping_name }}</div>
            <div style="color: var(--ink-2); font-size: 13px; margin-top: 4px; font-weight: 600;">{{ $order->shipping_phone }}</div>
            <div style="color: var(--ink-3); font-size: 13px; margin-top: 8px; line-height: 1.5;">
                {{ $order->shipping_address }}, {{ $order->shipping_city }}, {{ $order->shipping_province }}, {{ $order->shipping_postal_code }}
            </div>
        </div>
        
        <div class="db-detail-info-block">
            <h4 class="db-detail-title"><i class="fas fa-truck" style="margin-right: 6px;"></i> Informasi Kurir</h4>
            <div style="font-weight: 700; text-transform: uppercase; font-size: 14px; color: var(--ink);">{{ $order->shipping_courier }}</div>
            <div style="color: var(--ink-2); font-size: 13px; margin-top: 4px;">Layanan: <span style="font-weight: 600;">{{ $order->shipping_service }}</span></div>
            @if($order->tracking_number)
                <div style="color: var(--ink-2); font-size: 13px; margin-top: 8px; padding: 6px 12px; background: #fff; border: 1px solid var(--line); border-radius: 8px; display: inline-flex; align-items: center; gap: 8px;">
                    <span style="color: var(--ink-3);">No. Resi:</span>
                    <strong style="color: var(--blue); font-family: monospace;">{{ $order->tracking_number }}</strong>
                </div>
            @else
                <div style="color: var(--ink-3); font-size: 12.5px; font-style: italic; margin-top: 8px;"><i class="far fa-clock"></i> Nomor resi belum tersedia</div>
            @endif
        </div>
    </div>

    {{-- Product List --}}
    <div class="order-items" style="margin-bottom: 30px;" x-data="{ reviewModalOpen: false, activeItem: null }">
        <h4 class="db-detail-title"><i class="fas fa-box" style="margin-right: 6px;"></i> Produk dipesan</h4>
        
        <div style="display: flex; flex-direction: column;">
            @foreach($order->items as $item)
                <div class="db-detail-item" style="display: flex; gap: 14px; padding: 16px 0; border-bottom: 1px dashed var(--line); align-items: flex-start;">
                    <div style="width: 52px; height: 52px; border-radius: 10px; background: #f8fafc; border: 1px solid var(--line); flex-shrink: 0; overflow: hidden; display: flex; align-items: center; justify-content: center;">
                        @if($item->product && $item->product->primaryImage)
                            <img src="{{ asset('storage/'.$item->product->primaryImage->image_path) }}" style="width: 100%; height: 100%; object-fit: contain; padding: 2px;">
                        @else
                            <i class="fas fa-box" style="color: var(--ink-3); font-size: 16px;"></i>
                        @endif
                    </div>
                    <div style="flex: 1; min-width: 0;">
                        <div style="font-weight: 700; color: var(--ink); font-size: 13.5px; line-height: 1.4; margin-bottom: 4px;">{{ $item->product_name }}</div>
                        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 6px; margin-top: 4px;">
                            <span style="font-size: 12.5px; color: var(--ink-3);">{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                            <strong style="font-size: 13.5px; color: var(--ink); font-weight: 800;">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</strong>
                        </div>
                        
                        @if($order->status === 'completed')
                            <div style="margin-top: 8px;">
                                @if(!$item->review_id)
                                    <button @click="reviewModalOpen = true; activeItem = { id: {{ $item->id }}, name: '{{ addslashes($item->product_name) }}' }" 
                                            class="db-btn db-btn-outline db-btn-sm">
                                        <i class="fas fa-star" style="font-size:9px;"></i> Tulis Ulasan
                                    </button>
                                @else
                                    <span style="font-size: 11.5px; color: var(--green); font-weight: 700;"><i class="fas fa-check-circle"></i> Sudah diulas</span>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- REVIEW MODAL -->
        <div x-show="reviewModalOpen" x-cloak style="position: fixed; inset: 0; background: rgba(15, 23, 42, 0.4); z-index: 1000; display: flex; align-items: center; justify-content: center; padding: 20px; backdrop-filter: blur(4px);">
            <div class="db-card" @click.away="reviewModalOpen = false" style="width: 100%; max-width: 480px; padding: 28px; border-radius: 24px; background: #fff; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h3 style="font-weight: 800; font-size: 18px; color: var(--ink); margin: 0;">Tulis Ulasan</h3>
                    <button @click="reviewModalOpen = false" style="background: none; border: none; font-size: 24px; color: var(--ink-3); cursor: pointer; line-height: 1;">&times;</button>
                </div>
                
                <template x-if="activeItem">
                    <div>
                        <p style="font-size: 13px; color: var(--ink-2); margin-bottom: 20px; line-height: 1.5; background: #f8fafc; padding: 10px 14px; border-radius: 8px; border-left: 3px solid var(--blue);">Ulasan untuk: <strong x-text="activeItem.name" style="color:var(--ink);"></strong></p>

                        <form action="{{ route('dashboard.reviews.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="order_item_id" :value="activeItem.id">
                            
                            <div style="margin-bottom: 20px;">
                                <label style="display: block; font-size: 12.5px; font-weight: 700; margin-bottom: 8px; color: var(--ink-2);">Rating Produk</label>
                                <div style="display: flex; gap: 8px; font-size: 24px; color: #ddd;" x-data="{ hoverRating: 0, selectedRating: 0 }">
                                    <template x-for="i in 5">
                                        <i class="fas fa-star" 
                                           @mouseenter="hoverRating = i" 
                                           @mouseleave="hoverRating = 0" 
                                           @click="selectedRating = i"
                                           :style="{ color: (hoverRating >= i || selectedRating >= i) ? '#f59e0b' : '#cbd5e1', cursor: 'pointer' }"></i>
                                    </template>
                                    <input type="hidden" name="rating" :value="selectedRating" required>
                                </div>
                            </div>

                            <div style="margin-bottom: 20px;">
                                <label style="display: block; font-size: 12.5px; font-weight: 700; margin-bottom: 8px; color: var(--ink-2);">Ulasan Anda</label>
                                <textarea name="comment" rows="4" style="width: 100%; border-radius: 12px; border: 1px solid var(--line); padding: 12px; font-family: inherit; font-size: 13.5px; outline: none; transition: all 0.2s;" onfocus="this.style.borderColor='var(--blue)'; this.style.boxShadow='0 0 0 3px var(--blue-lt)'" onblur="this.style.borderColor='var(--line)'; this.style.boxShadow='none'" placeholder="Bagikan pengalaman Anda menggunakan produk ini..."></textarea>
                            </div>

                            <div style="margin-bottom: 24px;">
                                <label style="display: block; font-size: 12.5px; font-weight: 700; margin-bottom: 8px; color: var(--ink-2);">Foto Produk (Opsional)</label>
                                <input type="file" name="image" accept="image/*" style="width: 100%; font-size: 13px; border: 1px dashed var(--line); border-radius: 12px; padding: 12px; background: #f8fafc;">
                            </div>

                            <button type="submit" class="db-btn db-btn-primary" style="width: 100%; height: 46px; border-radius: 12px; font-size: 14px;">Kirim Ulasan</button>
                        </form>
                    </div>
                </template>
            </div>
        </div>
    </div>

    {{-- Invoice Calculation --}}
    <div style="background: #f8fafc; padding: 24px; border-radius: 16px; border: 1px solid var(--line); margin-top: 24px;">
        <div style="display: flex; justify-content: space-between; margin-bottom: 12px; color: var(--ink-2); font-size: 13px;">
            <span>Subtotal</span>
            <span style="font-weight: 600;">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
        </div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 12px; color: var(--ink-2); font-size: 13px;">
            <span>Ongkos Kirim</span>
            <span style="font-weight: 600;">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
        </div>
        <div style="display: flex; justify-content: space-between; padding-top: 16px; border-top: 1px solid var(--line); margin-top: 16px; align-items: baseline;">
            <span style="font-weight: 700; color: var(--ink); font-size: 14px;">Total Tagihan</span>
            <span style="font-size: 20px; font-weight: 800; color: var(--blue);">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
        </div>
    </div>
</div>
@endsection
