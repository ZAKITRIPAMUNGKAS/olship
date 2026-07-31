@extends('admin.layouts.app')
@section('title', 'Detail Order #' . $order->order_number)
@section('page-title', 'Detail Order')

@section('breadcrumb')
    <i class="fas fa-chevron-right"></i>
    <a href="{{ route('admin.orders.index') }}">Daftar Pesanan</a>
@endsection

@section('content')
<div class="fade-up" style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
    <div>
        <a href="{{ route('admin.orders.index') }}" class="btn-ag btn-ghost btn-sm" style="margin-bottom: 8px; display: inline-flex;">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Order
        </a>
        <h2 style="font-size: 22px; font-weight: 800; color: var(--text-main); line-height: 1.2;">
            Order <span style="color: var(--brand-primary); font-family: monospace;">#{{ $order->order_number }}</span>
        </h2>
        <div style="font-size: 13px; color: var(--text-muted); margin-top: 4px;">
            <i class="fas fa-calendar-alt"></i> {{ $order->created_at->format('d F Y, H:i') }} WIB
        </div>
    </div>

    <div style="display: flex; align-items: center; gap: 12px;">
        @php
            $badgeStyles = [
                'pending'    => 'background: #fef3c7; color: #d97706; border: 1px solid #fde68a;',
                'processing' => 'background: #dbeafe; color: #2563eb; border: 1px solid #bfdbfe;',
                'shipped'    => 'background: #e0e7ff; color: #4f46e5; border: 1px solid #c7d2fe;',
                'completed'  => 'background: #d1fae5; color: #059669; border: 1px solid #a7f3d0;',
                'cancelled'  => 'background: #fee2e2; color: #dc2626; border: 1px solid #fecaca;',
            ];
        @endphp
        <span style="font-size: 13px; font-weight: 800; padding: 6px 16px; border-radius: 99px; text-transform: uppercase; letter-spacing: 0.05em; {{ $badgeStyles[$order->status] ?? '' }}">
            <i class="fas fa-circle" style="font-size: 8px; margin-right: 6px;"></i>
            {{ ucfirst($order->status) }}
        </span>
        @if(Route::has('admin.orders.invoice'))
        <a href="{{ route('admin.orders.invoice', $order) }}" target="_blank" class="btn-ag btn-ghost btn-sm">
            <i class="fas fa-print"></i> Cetak Invoice
        </a>
        @endif
    </div>
</div>

<div class="grid-2-1 fade-up delay-1">

    {{-- LEFT COLUMN: Item Pesanan & Info Pengiriman --}}
    <div style="display: flex; flex-direction: column; gap: 20px;">

        {{-- Panel: Item Pesanan --}}
        <div class="panel">
            <div class="panel-header">
                <div class="panel-title">
                    <i class="fas fa-shopping-bag"></i> Item Pesanan ({{ $order->items->count() }} Produk)
                </div>
            </div>

            <div style="display: flex; flex-direction: column; gap: 14px;">
                @foreach($order->items as $item)
                <div style="display: flex; gap: 16px; align-items: center; padding-bottom: 14px; border-bottom: 1px solid var(--border);">
                    <div style="width: 60px; height: 60px; border-radius: 10px; overflow: hidden; background: var(--surface-2); flex-shrink: 0; border: 1px solid var(--border);">
                        <img src="{{ $item->product?->primaryImage ? asset('storage/' . $item->product->primaryImage->image_path) : 'https://placehold.co/100x100?text=' . urlencode($item->product_name) }}" 
                             style="width: 100%; height: 100%; object-fit: cover;" 
                             alt="{{ $item->product_name }}">
                    </div>
                    <div style="flex: 1; min-width: 0;">
                        <div style="font-size: 14px; font-weight: 700; color: var(--text-main); line-height: 1.3; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                            {{ $item->product_name }}
                        </div>
                        <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px;">
                            SKU: <span style="font-family: monospace; font-weight: 600;">{{ $item->product_sku }}</span>
                        </div>
                    </div>
                    <div style="text-align: right; flex-shrink: 0;">
                        <div style="font-size: 12px; color: var(--text-muted);">{{ $item->quantity }}x Rp {{ number_format($item->price, 0, ',', '.') }}</div>
                        <div style="font-size: 14px; font-weight: 800; color: var(--brand-primary); margin-top: 2px;">
                            Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Panel: Info Pengiriman --}}
        <div class="panel">
            <div class="panel-header">
                <div class="panel-title">
                    <i class="fas fa-truck"></i> Informasi Pengiriman
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; font-size: 13px;">
                <div style="background: var(--surface-2); padding: 14px 16px; border-radius: 10px; border: 1px solid var(--border);">
                    <div style="font-size: 11px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 6px;">Penerima</div>
                    <div style="font-weight: 700; color: var(--text-main); font-size: 14px;">{{ $order->shipping_name ?? '-' }}</div>
                    <div style="color: var(--text-secondary); margin-top: 2px;"><i class="fas fa-phone-alt" style="font-size: 11px;"></i> {{ $order->shipping_phone ?? '-' }}</div>
                </div>

                <div style="background: var(--surface-2); padding: 14px 16px; border-radius: 10px; border: 1px solid var(--border);">
                    <div style="font-size: 11px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 6px;">Ekspedisi & Kurir</div>
                    <div style="font-weight: 700; color: var(--text-main); font-size: 14px; text-transform: uppercase;">{{ $order->courier ?? '-' }} {{ $order->courier_service ?? '' }}</div>
                    @if($order->tracking_number)
                    <div style="color: var(--brand-primary); font-family: monospace; font-size: 13px; font-weight: 700; margin-top: 4px;">
                        <i class="fas fa-barcode"></i> AWB: {{ $order->tracking_number }}
                    </div>
                    @endif
                </div>

                <div style="grid-column: 1 / -1; background: var(--surface-2); padding: 14px 16px; border-radius: 10px; border: 1px solid var(--border);">
                    <div style="font-size: 11px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 6px;">Alamat Lengkap Pengiriman</div>
                    <div style="color: var(--text-main); font-weight: 500; line-height: 1.5;">
                        {{ $order->shipping_address }}, {{ $order->shipping_city }}, {{ $order->shipping_province }} {{ $order->shipping_postal_code ?? '' }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- RIGHT COLUMN: Ringkasan Biaya & Form Update Status --}}
    <div style="display: flex; flex-direction: column; gap: 20px;">

        {{-- Panel: Ringkasan Transaksi --}}
        <div class="panel">
            <div class="panel-header">
                <div class="panel-title">
                    <i class="fas fa-calculator"></i> Ringkasan Biaya
                </div>
            </div>

            <div style="display: flex; flex-direction: column; gap: 10px; font-size: 13px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="color: var(--text-muted);">Subtotal Produk</span>
                    <span style="font-weight: 600; color: var(--text-main);">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="color: var(--text-muted);">Ongkos Kirim ({{ strtoupper($order->courier ?? '-') }})</span>
                    <span style="font-weight: 600; color: var(--text-main);">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                </div>
                @if($order->discount_amount > 0)
                <div style="display: flex; justify-content: space-between; align-items: center; color: var(--success);">
                    <span>Potongan Diskon</span>
                    <span style="font-weight: 700;">-Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</span>
                </div>
                @endif
                <div style="border-top: 1px solid var(--border); margin: 6px 0;"></div>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-size: 14px; font-weight: 800; color: var(--text-main);">Total Biaya</span>
                    <span style="font-size: 18px; font-weight: 800; color: var(--brand-primary);">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        {{-- Panel: Form Update Status Pesanan --}}
        <div class="panel" style="border-top: 4px solid var(--brand-primary);">
            <div class="panel-header" style="margin-bottom: 16px;">
                <div class="panel-title">
                    <i class="fas fa-sync-alt"></i> Update Status Pesanan
                </div>
            </div>

            @if(Route::has('admin.orders.status'))
            <form action="{{ route('admin.orders.status', $order) }}" method="POST" style="display: flex; flex-direction: column; gap: 14px;">
                @csrf
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Status Pesanan</label>
                    <select name="status" class="form-select">
                        <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending (Menunggu Pembayaran)</option>
                        <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing (Diproses)</option>
                        <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped (Dikirim)</option>
                        <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Completed (Selesai)</option>
                        <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled (Dibatalkan)</option>
                    </select>
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Nomor Resi Pengiriman (AWB)</label>
                    <div class="input-group">
                        <span class="input-prefix"><i class="fas fa-barcode"></i></span>
                        <input type="text" name="tracking_number" class="form-input" 
                               placeholder="Contoh: JNE123456789" value="{{ $order->tracking_number }}">
                    </div>
                </div>

                <button type="submit" class="btn-ag btn-primary" style="width: 100%; padding: 12px;">
                    <i class="fas fa-save"></i> Simpan Perubahan Status
                </button>
            </form>
            @endif
        </div>

    </div>

</div>
@endsection
