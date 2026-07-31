@extends('admin.layouts.app')
@section('title', 'Invoice #' . $order->order_number)
@section('page-title', 'Invoice')

@section('content')
<div style="max-width:800px;margin:0 auto;" class="fade-up">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;">
        <a href="{{ route('admin.orders.show', $order) }}" class="btn-ag btn-ghost btn-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <button onclick="window.print()" class="btn-ag btn-primary btn-sm">
            <i class="fas fa-print"></i> Cetak Invoice
        </button>
    </div>

    <div class="glass-card" style="padding:40px;position:relative;overflow:hidden;" id="invoiceArea">
        {{-- Decorative Glow --}}
        <div style="position:absolute;top:-50px;right:-50px;width:200px;height:200px;background:radial-gradient(circle, var(--blue-dim) 0%, transparent 70%);opacity:0.3;"></div>

        <div style="display:flex;justify-content:space-between;margin-bottom:40px;">
            <div>
                <h1 style="font-family:'Space Grotesk',sans-serif;font-size:28px;font-weight:700;letter-spacing:-1px;">
                    VOLT<span style="color:var(--blue);">GEAR</span>
                </h1>
                <p style="font-size:12px;color:var(--muted);margin-top:4px;">Invoice Resmi Penjualan</p>
            </div>
            <div style="text-align:right;">
                <div style="font-size:18px;font-weight:700;font-family:'DM Mono',monospace;color:var(--blue);">#{{ $order->order_number }}</div>
                <div style="font-size:12px;color:var(--muted);">{{ $order->created_at->format('d M Y, H:i') }}</div>
            </div>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:40px;margin-bottom:40px;">
            <div>
                <div style="font-size:11px;color:var(--muted);text-transform:uppercase;letter-spacing:1px;margin-bottom:8px;">Ditagihkan Ke</div>
                <div style="font-size:14px;font-weight:700;margin-bottom:4px;">{{ $order->user->name ?? $order->shipping_name }}</div>
                <div style="font-size:13px;color:var(--text-2);line-height:1.5;">
                    {{ $order->shipping_address }}<br>
                    {{ $order->shipping_city }}, {{ $order->shipping_province }}<br>
                    {{ $order->shipping_phone }}
                </div>
            </div>
            <div style="text-align:right;">
                <div style="font-size:11px;color:var(--muted);text-transform:uppercase;letter-spacing:1px;margin-bottom:8px;">Metode Pembayaran</div>
                <div style="font-size:14px;font-weight:700;">{{ strtoupper($order->payment_method ?? 'Transfer') }}</div>
                <div style="font-size:12px;color:{{ $order->payment_status === 'paid' ? 'var(--green)' : 'var(--amber)' }};font-weight:600;margin-top:4px;">
                    Status: {{ strtoupper($order->payment_status ?? 'Unpaid') }}
                </div>
            </div>
        </div>

        <table style="width:100%;border-collapse:collapse;margin-bottom:30px;">
            <thead>
                <tr style="border-bottom:1px solid var(--border);">
                    <th style="text-align:left;padding:12px 0;font-size:11px;color:var(--muted);text-transform:uppercase;">Deskripsi Item</th>
                    <th style="text-align:center;padding:12px 0;font-size:11px;color:var(--muted);text-transform:uppercase;">Qty</th>
                    <th style="text-align:right;padding:12px 0;font-size:11px;color:var(--muted);text-transform:uppercase;">Harga</th>
                    <th style="text-align:right;padding:12px 0;font-size:11px;color:var(--muted);text-transform:uppercase;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr style="border-bottom:1px solid rgba(255,255,255,0.03);">
                    <td style="padding:16px 0;">
                        <div style="font-size:14px;font-weight:600;">{{ $item->product_name }}</div>
                        <div style="font-size:11px;color:var(--muted);">SKU: {{ $item->product_sku }}</div>
                    </td>
                    <td style="text-align:center;font-size:13px;font-family:'DM Mono',monospace;">{{ $item->quantity }}</td>
                    <td style="text-align:right;font-size:13px;font-family:'DM Mono',monospace;">Rp{{ number_format($item->price, 0, ',', '.') }}</td>
                    <td style="text-align:right;font-size:13px;font-weight:600;font-family:'DM Mono',monospace;">Rp{{ number_format($item->quantity * $item->price, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div style="display:flex;justify-content:flex-end;">
            <div style="width:240px;display:flex;flex-direction:column;gap:10px;">
                <div style="display:flex;justify-content:space-between;font-size:13px;">
                    <span style="color:var(--muted);">Subtotal</span>
                    <span class="dm-mono">Rp{{ number_format($order->subtotal, 0, ',', '.') }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;font-size:13px;">
                    <span style="color:var(--muted);">Ongkir</span>
                    <span class="dm-mono">Rp{{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                </div>
                @if($order->discount_amount > 0)
                <div style="display:flex;justify-content:space-between;font-size:13px;color:var(--green);">
                    <span>Diskon</span>
                    <span class="dm-mono">-Rp{{ number_format($order->discount_amount, 0, ',', '.') }}</span>
                </div>
                @endif
                <div style="border-top:2px solid var(--blue);margin:6px 0;"></div>
                <div style="display:flex;justify-content:space-between;font-size:18px;font-weight:700;">
                    <span style="font-family:'Space Grotesk',sans-serif;">TOTAL</span>
                    <span class="dm-mono" style="color:var(--blue);">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <div style="margin-top:60px;padding-top:20px;border-top:1px dashed var(--border);text-align:center;font-size:11px;color:var(--muted);">
            Terima kasih telah berbelanja di LISTRINDO JAYA ELEKTRIK. Simpan invoice ini sebagai bukti pembelian yang sah.
        </div>
    </div>
</div>

<style>
@media print {
    body * { visibility: hidden; background: #fff !important; color: #000 !important; }
    #invoiceArea, #invoiceArea * { visibility: visible; }
    #invoiceArea { position: absolute; left: 0; top: 0; width: 100%; border: none !important; box-shadow: none !important; }
    .glass-card { backdrop-filter: none !important; background: #fff !important; }
    .btn-ag { display: none !important; }
    h1, .panel-title, .stat-number { color: #000 !important; }
    .dm-mono { color: #000 !important; }
}
</style>
@endsection
