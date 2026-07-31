@extends('admin.layouts.app')

@section('title', 'Log Aktivitas')
@section('page-title', 'Log Aktivitas')

@section('content')
<div class="panel">
    <div class="panel-header">
        <h3 class="panel-title">Rekam Jejak Sistem</h3>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="ag-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Event</th>
                        <th>Target</th>
                        <th>URL</th>
                        <th>IP Address</th>
                        <th>Waktu</th>
                        <th style="width: 80px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($logs as $log)
                        <tr>
                            <td>
                                @if($log->user)
                                    <strong>{{ $log->user->name }}</strong><br>
                                    <small class="text-muted">{{ $log->user->roles->first()?->name }}</small>
                                @else
                                    <span class="text-muted">System/Guest</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge-ag badge-{{ $log->event === 'deleted' ? 'danger' : ($log->event === 'created' ? 'success' : 'info') }}">
                                    {{ strtoupper($log->event) }}
                                </span>
                            </td>
                            <td>
                                <small class="text-muted">{{ basename(str_replace('\\', '/', $log->auditable_type)) }}</small>#{{ $log->auditable_id }}
                            </td>
                            <td>
                                <small title="{{ $log->url }}">{{ Str::limit($log->url, 30) }}</small>
                            </td>
                            <td>{{ $log->ip_address }}</td>
                            <td>{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                            <td>
                                <button onclick="viewLogDetail({{ $log->id }})" class="btn-ag btn-ghost btn-sm">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="margin-top: 20px;">
            {{ $logs->links() }}
        </div>
    </div>
</div>

<!-- LOG DETAIL MODAL -->
<div class="modal-overlay" id="logModal">
    <div class="modal-box" style="width: 700px; max-width: 90%; max-height: 80vh; overflow-y: auto;">
        <h3>Detail Log #<span id="logId"></span></h3>
        <hr style="margin: 15px 0; border: 0; border-top: 1px solid var(--border);">
        
        <div class="grid-2" style="margin-bottom: 20px;">
            <div>
                <label class="form-label">User</label>
                <p id="logUser" class="text-muted"></p>
            </div>
            <div>
                <label class="form-label">Event</label>
                <p id="logEvent"></p>
            </div>
        </div>

        <div style="margin-bottom: 20px;">
            <label class="form-label">User Agent</label>
            <p id="logUA" style="font-size: 11px; color: var(--text-muted);"></p>
        </div>

        <div class="grid-2">
            <div>
                <label class="form-label">Nilai Lama</label>
                <pre id="oldValues" style="background: #fef2f2; padding: 10px; border-radius: 4px; font-size: 11px; color: #991b1b; white-space: pre-wrap;"></pre>
            </div>
            <div>
                <label class="form-label">Nilai Baru</label>
                <pre id="newValues" style="background: #f0fdf4; padding: 10px; border-radius: 4px; font-size: 11px; color: #166534; white-space: pre-wrap;"></pre>
            </div>
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
        document.getElementById('logUser').innerText = log.user ? log.user.name : 'System';
        document.getElementById('logEvent').innerText = log.event.toUpperCase();
        document.getElementById('logUA').innerText = log.user_agent;
        
        document.getElementById('oldValues').innerText = log.old_values ? JSON.stringify(log.old_values, null, 2) : 'N/A';
        document.getElementById('newValues').innerText = log.new_values ? JSON.stringify(log.new_values, null, 2) : 'N/A';
        
        document.getElementById('logModal').classList.add('show');
    }

    function closeLogModal() {
        document.getElementById('logModal').classList.remove('show');
    }
</script>
@endpush
