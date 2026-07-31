@extends('admin.layouts.app')
@section('title', 'Detail Pengguna - ' . $user->name)
@section('page-title', 'Detail Pengguna')

@section('breadcrumb')
    <i class="fas fa-chevron-right"></i>
    <a href="{{ route('admin.users.index') }}">Pengguna</a>
@endsection

@section('content')
<div class="fade-up" style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
    <div>
        <a href="{{ route('admin.users.index') }}" class="btn-ag btn-ghost btn-sm" style="margin-bottom: 8px; display: inline-flex;">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Pengguna
        </a>
        <h2 style="font-size: 24px; font-weight: 800; color: var(--text-main); line-height: 1.2;">
            Detail User: {{ $user->name }}
        </h2>
    </div>

    <div style="display: flex; gap: 10px;">
        <a href="{{ route('admin.users.edit', $user) }}" class="btn-ag btn-primary btn-sm">
            <i class="fas fa-pen"></i> Edit Profile & Role
        </a>
    </div>
</div>

<div class="grid-2-1 fade-up delay-1">

    {{-- LEFT COLUMN: Profil Pengguna & Informasi Akun --}}
    <div style="display: flex; flex-direction: column; gap: 24px;">

        {{-- Panel Informasi Profil --}}
        <div class="panel">
            <div class="panel-header">
                <div class="panel-title">
                    <i class="fas fa-user-circle"></i> Informasi Akun & Profil
                </div>
            </div>

            <div style="display: flex; gap: 24px; align-items: center; padding-bottom: 20px; border-bottom: 1px solid var(--border);">
                <div style="width: 80px; height: 80px; border-radius: 20px; background: linear-gradient(135deg, var(--brand-primary), #4f46e5); color: #ffffff; display: flex; align-items: center; justify-content: center; font-size: 28px; font-weight: 800; flex-shrink: 0; box-shadow: 0 10px 25px rgba(37,99,235,0.25);">
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                </div>
                <div>
                    <h3 style="font-size: 20px; font-weight: 800; color: var(--text-main);">{{ $user->name }}</h3>
                    <div style="font-size: 13.5px; color: var(--text-muted); margin-top: 2px;">
                        <i class="fas fa-envelope" style="margin-right: 4px;"></i> {{ $user->email }}
                    </div>
                    <div style="display: flex; gap: 8px; align-items: center; margin-top: 10px;">
                        @foreach($user->roles as $role)
                            <span class="badge-ag badge-info" style="text-transform: uppercase; font-family: monospace;">
                                {{ $role->name }}
                            </span>
                        @endforeach

                        <span class="badge-ag {{ $user->is_active ? 'badge-success' : 'badge-danger' }}">
                            <i class="fas fa-circle" style="font-size: 7px;"></i>
                            {{ $user->is_active ? 'Status: Aktif' : 'Status: Nonaktif' }}
                        </span>
                    </div>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-top: 20px; font-size: 13px;">
                <div style="background: var(--surface-2); padding: 14px 18px; border-radius: 12px; border: 1px solid var(--border);">
                    <div style="font-size: 11px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px;">Terdaftar Sejak</div>
                    <div style="font-weight: 700; color: var(--text-main);">{{ $user->created_at->format('d F Y, H:i') }} WIB</div>
                </div>
                <div style="background: var(--surface-2); padding: 14px 18px; border-radius: 12px; border: 1px solid var(--border);">
                    <div style="font-size: 11px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px;">Terakhir Diperbarui</div>
                    <div style="font-weight: 700; color: var(--text-main);">{{ $user->updated_at->format('d F Y, H:i') }} WIB</div>
                </div>
            </div>
        </div>

        {{-- Panel Riwayat Pesanan --}}
        <div class="panel">
            <div class="panel-header">
                <div class="panel-title">
                    <i class="fas fa-shopping-cart"></i> Riwayat Pesanan Terakhir
                </div>
            </div>

            @php $orders = $user->orders()->latest()->take(5)->get(); @endphp
            @if($orders->isEmpty())
                <div class="empty-state">
                    <i class="fas fa-box-open"></i>
                    <p>Belum ada riwayat pesanan dari pengguna ini.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="ag-table">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Tanggal</th>
                                <th>Total Biaya</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order) }}" style="font-family: monospace; font-weight: 800; color: var(--brand-primary);">
                                        #{{ $order->order_number }}
                                    </a>
                                </td>
                                <td style="color: var(--text-muted);">{{ $order->created_at->format('d M Y, H:i') }}</td>
                                <td style="font-weight: 800; color: var(--text-main);">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                <td>
                                    @php
                                        $badgeClass = match($order->status) {
                                            'completed' => 'badge-success',
                                            'pending'   => 'badge-warning',
                                            'cancelled' => 'badge-danger',
                                            default     => 'badge-info',
                                        };
                                    @endphp
                                    <span class="badge-ag {{ $badgeClass }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order) }}" class="btn-ag btn-ghost btn-sm">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

    </div>

    {{-- RIGHT COLUMN: Daftar Alamat Pengiriman --}}
    <div style="display: flex; flex-direction: column; gap: 24px;">

        <div class="panel">
            <div class="panel-header">
                <div class="panel-title">
                    <i class="fas fa-map-marker-alt"></i> Daftar Alamat Pengiriman
                </div>
            </div>

            @if($user->addresses->isEmpty())
                <div class="empty-state">
                    <i class="fas fa-map-marked-alt"></i>
                    <p>Belum ada alamat pengiriman terdaftar.</p>
                </div>
            @else
                <div style="display: flex; flex-direction: column; gap: 14px;">
                    @foreach($user->addresses as $address)
                    <div style="background: var(--surface-2); padding: 16px; border-radius: 12px; border: 1px solid var(--border); position: relative;">
                        @if($address->is_default)
                        <span class="badge-ag badge-success" style="position: absolute; top: 14px; right: 14px; font-size: 10px;">
                            Alamat Utama
                        </span>
                        @endif

                        <div style="font-size: 11px; font-weight: 800; color: var(--brand-primary); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px;">
                            {{ $address->label ?? 'Alamat' }}
                        </div>
                        <div style="font-weight: 800; color: var(--text-main); font-size: 14px;">
                            {{ $address->recipient_name }}
                        </div>
                        <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 8px;">
                            <i class="fas fa-phone-alt" style="font-size: 10px;"></i> {{ $address->phone }}
                        </div>
                        <div style="font-size: 13px; color: var(--text-secondary); line-height: 1.4;">
                            {{ $address->address_detail }}, {{ $address->city->name ?? '' }}, {{ $address->province->name ?? '' }}
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>

</div>
@endsection
