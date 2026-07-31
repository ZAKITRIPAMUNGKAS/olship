@extends('layouts.app')
@section('title', 'Manajemen Pesanan - Seller Center')

@section('content')
<div class="seller-container" style="padding: 40px 0;">
    <div style="margin-bottom: 30px;">
        <h1 style="font-size: 28px; font-weight: 800; color: var(--ink);">Pesanan Masuk</h1>
        <p style="color: var(--slate-500);">Pantau dan kelola semua pesanan pelanggan untuk produk Anda</p>
    </div>

    <div class="card" style="background: #fff; border-radius: 16px; border: 1px solid var(--slate-200); overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="text-align: left; background: var(--slate-50); border-bottom: 1px solid var(--slate-200);">
                    <th style="padding: 16px; font-size: 13px; color: var(--slate-500); text-transform: uppercase;">ID Pesanan</th>
                    <th style="padding: 16px; font-size: 13px; color: var(--slate-500); text-transform: uppercase;">Pelanggan</th>
                    <th style="padding: 16px; font-size: 13px; color: var(--slate-500); text-transform: uppercase;">Produk</th>
                    <th style="padding: 16px; font-size: 13px; color: var(--slate-500); text-transform: uppercase;">Status</th>
                    <th style="padding: 16px; font-size: 13px; color: var(--slate-500); text-transform: uppercase;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr style="border-bottom: 1px solid var(--slate-50);">
                    <td style="padding: 16px;">
                        <div style="font-weight: 700; color: var(--ink);">#{{ $order->order_number }}</div>
                        <div style="font-size: 12px; color: var(--slate-400);">{{ $order->created_at->format('d M Y H:i') }}</div>
                    </td>
                    <td style="padding: 16px; font-size: 14px; color: var(--slate-600);">{{ $order->user->name }}</td>
                    <td style="padding: 16px; font-size: 14px; color: var(--slate-600);">{{ $order->items->count() }} Item</td>
                    <td style="padding: 16px;">
                        <span style="padding: 4px 8px; border-radius: 6px; font-size: 11px; font-weight: 800; background: var(--slate-100); color: var(--slate-600); text-transform: uppercase;">
                            {{ $order->status }}
                        </span>
                    </td>
                    <td style="padding: 16px;">
                        <a href="{{ route('seller.orders.show', $order) }}" style="color: var(--primary); text-decoration: none; font-weight: 600;">Detail <i class="fas fa-chevron-right" style="font-size: 10px;"></i></a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="padding: 40px; text-align: center; color: var(--slate-400);">Belum ada pesanan masuk.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div style="padding: 16px;">
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection
