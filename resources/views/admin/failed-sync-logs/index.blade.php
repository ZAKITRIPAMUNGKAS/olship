@extends('admin.layouts.app')

@section('title', 'Log Kegagalan Sinkronisasi API')
@section('page-title', 'Log Kegagalan Sinkronisasi API')

@section('content')
<div class="panel">
    <div class="panel-header">
        <h3 class="panel-title">Gagal Sinkronisasi (Olshop ⇄ WMS)</h3>
    </div>
    <div class="panel-body">
        @if(session('success'))
            <div style="background: #f0fdf4; border: 1px solid #bbf7d0; padding: 10px 15px; border-radius: 4px; color: #166534; margin-bottom: 20px;">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div style="background: #fef2f2; border: 1px solid #fecaca; padding: 10px 15px; border-radius: 4px; color: #991b1b; margin-bottom: 20px;">
                {{ session('error') }}
            </div>
        @endif

        <div class="table-responsive">
            <table class="ag-table">
                <thead>
                    <tr>
                        <th>Tipe</th>
                        <th>Payload Ringkas</th>
                        <th>Pesan Error</th>
                        <th>Percobaan</th>
                        <th>Waktu Kejadian</th>
                        <th style="width: 150px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td>
                                <span class="badge-ag badge-{{ $log->type === 'order_push' ? 'info' : 'warning' }}">
                                    {{ strtoupper(str_replace('_', ' ', $log->type)) }}
                                </span>
                            </td>
                            <td>
                                @if($log->type === 'order_push')
                                    <small><strong>Order #{{ $log->payload['order_number'] ?? 'N/A' }}</strong></small><br>
                                    <small class="text-muted">Total: Rp{{ number_format($log->payload['total_price'] ?? 0, 0, ',', '.') }}</small>
                                @else
                                    <small><strong>SKU: {{ $log->payload['kode_barang'] ?? 'N/A' }}</strong></small><br>
                                    <small class="text-muted">Qty: {{ $log->payload['total_stock'] ?? 0 }}</small>
                                @endif
                            </td>
                            <td>
                                <small class="text-danger" title="{{ $log->error_message }}">{{ Str::limit($log->error_message, 60) }}</small>
                            </td>
                            <td>{{ $log->attempts }}</td>
                            <td>{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                            <td style="text-align: center; display: flex; justify-content: center; gap: 5px;">
                                <button onclick="viewLogDetail({{ $log->id }})" class="btn-ag btn-ghost btn-sm" title="Lihat Payload Detail">
                                    <i class="fas fa-eye"></i> Detail
                                </button>
                                @if($log->type === 'order_push')
                                    <form action="{{ route('admin.failed-sync-logs.retry', $log->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn-ag btn-primary btn-sm" style="padding: 2px 8px; font-size: 11px;">
                                            <i class="fas fa-sync"></i> Retry
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center;" class="text-muted">Tidak ada log kegagalan sinkronisasi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="margin-top: 20px;">
            {{ $logs->links() }}
        </div>
    </div>
</div>

<!-- PAYLOAD DETAIL MODAL -->
<div class="modal-overlay" id="logModal">
    <div class="modal-box" style="width: 700px; max-width: 90%; max-height: 80vh; overflow-y: auto;">
        <h3>Detail Kegagalan Sinkronisasi #<span id="logId"></span></h3>
        <hr style="margin: 15px 0; border: 0; border-top: 1px solid var(--border);">
        
        <div class="grid-2" style="margin-bottom: 20px;">
            <div>
                <label class="form-label">Tipe Sinkronisasi</label>
                <p id="logType" class="text-muted"></p>
            </div>
            <div>
                <label class="form-label">Percobaan Gagal</label>
                <p id="logAttempts"></p>
            </div>
        </div>

        <div style="margin-bottom: 20px;">
            <label class="form-label">Pesan Error Lengkap</label>
            <pre id="logError" style="background: #fef2f2; padding: 10px; border-radius: 4px; font-size: 12px; color: #991b1b; white-space: pre-wrap; font-family: monospace; border: 1px solid #fecaca;"></pre>
        </div>

        <div style="margin-bottom: 20px;">
            <label class="form-label">Payload Data (JSON)</label>
            <pre id="logPayload" style="background: #f8fafc; padding: 10px; border-radius: 4px; font-size: 11px; color: #334155; white-space: pre-wrap; font-family: monospace; border: 1px solid #e2e8f0;"></pre>
        </div>

        <div class="modal-actions" style="margin-top: 25px;">
            <button class="btn-ag btn-ghost" onclick="closeLogModal()">Tutup</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const allLogs = @json($logs->items());

    function viewLogDetail(id) {
        const log = allLogs.find(l => l.id === id);
        if (!log) return;

        document.getElementById('logId').innerText = log.id;
        document.getElementById('logType').innerText = log.type.toUpperCase().replace('_', ' ');
        document.getElementById('logAttempts').innerText = log.attempts + ' kali percobaan';
        document.getElementById('logError').innerText = log.error_message;
        document.getElementById('logPayload').innerText = JSON.stringify(log.payload, null, 2);
        
        document.getElementById('logModal').classList.add('show');
    }

    function closeLogModal() {
        document.getElementById('logModal').classList.remove('show');
    }
</script>
@endpush
