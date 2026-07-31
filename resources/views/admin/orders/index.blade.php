@extends('admin.layouts.app')
@section('title', 'Order')
@section('page-title', 'Order')

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:22px;" class="fade-up">
    <div>
        <h2 style="font-size:22px;font-weight:700;">Manajemen Order</h2>
        <p style="font-size:13px;color:var(--muted);margin-top:3px;">Pantau dan kelola semua transaksi</p>
    </div>
    <button class="btn-ag btn-ghost"><i class="fas fa-download"></i> Export CSV</button>
</div>

{{-- Status Filter Tabs --}}
<div style="display:flex;gap:6px;margin-bottom:18px;overflow-x:auto;padding-bottom:4px;" class="fade-up delay-1">
    @foreach(['all'=>'Semua','pending'=>'Pending','processing'=>'Diproses','shipped'=>'Dikirim','completed'=>'Selesai','cancelled'=>'Dibatalkan'] as $key => $label)
    <a href="{{ request()->url() }}?status={{ $key }}"
       style="padding:7px 16px;border-radius:20px;font-size:12px;font-weight:500;white-space:nowrap;text-decoration:none;
              border:1px solid {{ request('status', 'all') === $key ? 'var(--blue)' : 'var(--border)' }};
              background:{{ request('status', 'all') === $key ? 'var(--blue-dim)' : 'var(--surface)' }};
              color:{{ request('status', 'all') === $key ? 'var(--blue)' : 'var(--muted)' }};">
        {{ $label }}
    </a>
    @endforeach
</div>

{{-- Table --}}
<div class="glass-card fade-up delay-2">
    <div class="table-responsive">
    <table class="ag-table">
        <thead>
            <tr>
                <th>Order #</th>
                <th>Customer</th>
                <th>Produk</th>
                <th>Total</th>
                <th>Pembayaran</th>
                <th>Status</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders ?? [] as $order)
            <tr>
                <td><span class="dm-mono" style="font-size:12px;color:var(--blue);">#{{ $order->order_number }}</span></td>
                <td>
                    <div style="font-size:13px;font-weight:500;">{{ $order->user->name }}</div>
                    <div style="font-size:11px;color:var(--muted);">{{ $order->shipping_city ?? '-' }}</div>
                </td>
                <td style="font-size:12px;color:var(--text-2);">{{ $order->items->count() }} item</td>
                <td><span class="dm-mono" style="font-size:13px;font-weight:500;">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</span></td>
                <td>
                    <span class="badge-ag {{ $order->payment_status === 'paid' ? 'badge-completed' : ($order->payment_status === 'pending' ? 'badge-pending' : 'badge-cancelled') }}">
                        {{ ucfirst($order->payment_status) }}
                    </span>
                </td>
                <td>
                    <span class="badge-ag badge-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
                </td>
                <td style="font-size:12px;color:var(--muted);">{{ $order->created_at->format('d M Y') }}</td>
                <td>
                    <a href="{{ Route::has('admin.orders.show') ? route('admin.orders.show', $order) : '#' }}"
                       class="btn-ag btn-ghost btn-sm">
                        <i class="fas fa-eye"></i> Detail
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8">
                    <div class="empty-state">
                        <i class="fas fa-shopping-bag"></i>
                        <p>Belum ada order ditemukan.<br>
                           Order yang masuk akan tampil di sini.</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>
    @if(isset($orders) && $orders->hasPages())
    <div style="padding:16px 22px;border-top:1px solid var(--border);">{{ $orders->links() }}</div>
    @endif
</div>
@endsection
