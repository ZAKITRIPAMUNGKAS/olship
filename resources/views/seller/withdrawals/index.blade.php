@extends('layouts.app')
@section('title', 'Penarikan Dana - Seller Center')

@section('content')
<div class="seller-container" style="padding: 40px 0;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <div>
            <h1 style="font-size: 28px; font-weight: 800; color: var(--ink);">Saldo & Penarikan</h1>
            <p style="color: var(--slate-500);">Kelola dana hasil penjualan Anda</p>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 30px;">
        <!-- Withdrawal Form -->
        <div class="card" style="padding: 24px; background: #fff; border-radius: 16px; border: 1px solid var(--slate-200); height: fit-content;">
            <h3 style="font-weight: 700; margin-bottom: 20px;">Tarik Dana</h3>
            
            @if(session('success'))
                <div style="padding: 12px; background: #ecfdf5; color: #059669; border-radius: 8px; margin-bottom: 16px; font-size: 14px;">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('seller.withdrawals.store') }}" method="POST">
                @csrf
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: var(--slate-600); margin-bottom: 6px;">Jumlah Penarikan (Rp)</label>
                    <input type="number" name="amount" class="form-control" placeholder="Min. 10.000" required style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid var(--slate-200);">
                    @error('amount') <span style="font-size: 12px; color: var(--red);">{{ $message }}</span> @enderror
                </div>
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: var(--slate-600); margin-bottom: 6px;">Nama Bank</label>
                    <input type="text" name="bank_name" class="form-control" placeholder="Contoh: BCA, Mandiri" required style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid var(--slate-200);">
                </div>
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: var(--slate-600); margin-bottom: 6px;">Nomor Rekening</label>
                    <input type="text" name="account_number" class="form-control" required style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid var(--slate-200);">
                </div>
                <div style="margin-bottom: 24px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: var(--slate-600); margin-bottom: 6px;">Nama Pemilik Rekening</label>
                    <input type="text" name="account_name" class="form-control" required style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid var(--slate-200);">
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 14px; background: var(--primary); color: #fff; border: none; border-radius: 12px; font-weight: 700; cursor: pointer;">AJUKAN PENARIKAN</button>
            </form>
        </div>

        <!-- History -->
        <div class="card" style="background: #fff; border-radius: 16px; border: 1px solid var(--slate-200); overflow: hidden;">
            <div style="padding: 24px; border-bottom: 1px solid var(--slate-100);">
                <h3 style="font-weight: 700;">Riwayat Penarikan</h3>
            </div>
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="text-align: left; background: var(--slate-50); border-bottom: 1px solid var(--slate-200);">
                        <th style="padding: 16px; font-size: 13px; color: var(--slate-500); text-transform: uppercase;">Tanggal</th>
                        <th style="padding: 16px; font-size: 13px; color: var(--slate-500); text-transform: uppercase;">Jumlah</th>
                        <th style="padding: 16px; font-size: 13px; color: var(--slate-500); text-transform: uppercase;">Bank</th>
                        <th style="padding: 16px; font-size: 13px; color: var(--slate-500); text-transform: uppercase;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($withdrawals as $w)
                    <tr style="border-bottom: 1px solid var(--slate-50);">
                        <td style="padding: 16px; font-size: 14px;">{{ $w->created_at->format('d/m/Y') }}</td>
                        <td style="padding: 16px; font-weight: 700;">Rp {{ number_format($w->amount, 0, ',', '.') }}</td>
                        <td style="padding: 16px; font-size: 14px;">{{ $w->bank_name }} - {{ $w->account_number }}</td>
                        <td style="padding: 16px;">
                            <span style="padding: 4px 8px; border-radius: 6px; font-size: 11px; font-weight: 800; 
                                @if($w->status === 'pending') background: #fff7ed; color: #c2410c;
                                @elseif($w->status === 'approved') background: #ecfdf5; color: #059669;
                                @else background: #fef2f2; color: #b91c1c; @endif text-transform: uppercase;">
                                {{ $w->status }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="padding: 40px; text-align: center; color: var(--slate-400);">Belum ada riwayat penarikan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
