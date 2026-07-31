@extends('admin.layouts.app')
@section('title', 'Penarikan Dana')
@section('page-title', 'Penarikan Dana')

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:22px;" class="fade-up">
    <div>
        <h2 style="font-size:22px;font-weight:700;">Penarikan Dana Seller</h2>
        <p style="font-size:13px;color:var(--muted);margin-top:3px;">Kelola request penarikan saldo toko</p>
    </div>
</div>

{{-- Filter --}}
<div style="display:flex;gap:6px;margin-bottom:18px;" class="fade-up delay-1">
    @foreach(['all'=>'Semua','pending'=>'Menunggu','approved'=>'Disetujui','rejected'=>'Ditolak'] as $key => $label)
    <a href="?status={{ $key }}"
       style="padding:7px 16px;border-radius:20px;font-size:12px;text-decoration:none;
              border:1px solid {{ request('status','all') === $key ? 'var(--blue)' : 'var(--border)' }};
              background:{{ request('status','all') === $key ? 'var(--blue-dim)' : 'var(--surface)' }};
              color:{{ request('status','all') === $key ? 'var(--blue)' : 'var(--muted)' }};">
        {{ $label }}
    </a>
    @endforeach
</div>

<div class="glass-card fade-up delay-2">
    <div class="table-responsive">
    <table class="ag-table">
        <thead>
            <tr>
                <th>Toko</th>
                <th>Jumlah</th>
                <th>Bank</th>
                <th>No. Rekening</th>
                <th>Tanggal</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($withdrawals ?? [] as $w)
            <tr>
                <td>
                    <div style="font-size:13px;font-weight:500;">{{ $w->store->name ?? '-' }}</div>
                    <div style="font-size:11px;color:var(--muted);">{{ $w->store->seller->name ?? '-' }}</div>
                </td>
                <td><span class="dm-mono" style="font-size:13px;font-weight:600;color:var(--green);">Rp{{ number_format($w->amount,0,',','.') }}</span></td>
                <td style="font-size:12px;text-transform:uppercase;color:var(--text-2);">{{ $w->bank_name ?? '-' }}</td>
                <td><span class="dm-mono" style="font-size:12px;">{{ $w->account_number ?? '-' }}</span></td>
                <td style="font-size:12px;color:var(--muted);">{{ $w->created_at->format('d M Y') }}</td>
                <td>
                    <span class="badge-ag {{ $w->status === 'approved' ? 'badge-completed' : ($w->status === 'rejected' ? 'badge-cancelled' : 'badge-pending') }}">
                        {{ ucfirst($w->status) }}
                    </span>
                </td>
                <td>
                    @if($w->status === 'pending')
                    <div style="display:flex;gap:6px;">
                        <form action="{{ route('admin.withdrawals.approve', $w) }}" method="POST">
                            @csrf
                            <button class="btn-ag btn-ghost btn-sm" style="color:var(--green);">
                                <i class="fas fa-check"></i> Approve
                            </button>
                        </form>
                        <form action="{{ route('admin.withdrawals.reject', $w) }}" method="POST" onsubmit="event.preventDefault(); confirmAction({title: 'Tolak Penarikan?', body: 'Apakah kamu yakin ingin menolak request penarikan ini?', label: 'Ya, Tolak', onConfirm: () => { this.submit(); }})">
                            @csrf
                            <button type="submit" class="btn-ag btn-ghost btn-sm" style="color:var(--danger);">
                                <i class="fas fa-times"></i> Tolak
                            </button>
                        </form>
                    </div>
                    @else
                        <span style="font-size:11px;color:var(--muted);">Selesai</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7">
                    <div class="empty-state">
                        <i class="fas fa-money-bill-transfer"></i>
                        <p>Belum ada request penarikan ditemukan.</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>
</div>
@endsection
