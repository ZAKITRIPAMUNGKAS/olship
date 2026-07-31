@extends('admin.layouts.app')

@section('title', 'Diskusi Produk')
@section('page-title', 'Diskusi Produk')

@section('content')
<div class="panel">
    <div class="panel-header">
        <h3 class="panel-title">Semua Pertanyaan Produk</h3>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="ag-table">
                <thead>
                    <tr>
                        <th>Pelanggan</th>
                        <th>Produk</th>
                        <th>Pertanyaan</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th style="width: 150px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($discussions as $discussion)
                        <tr>
                            <td>
                                <strong>{{ $discussion->user->name }}</strong><br>
                                <small>{{ $discussion->user->email }}</small>
                            </td>
                            <td>
                                <a href="{{ route('admin.products.edit', $discussion->product) }}" class="text-primary">
                                    {{ $discussion->product->name }}
                                </a>
                            </td>
                            <td style="max-width: 300px;">
                                <p style="margin-bottom: 0; font-size: 13px;">{{ $discussion->message }}</p>
                                @if($discussion->replies->count() > 0)
                                    <div style="margin-top: 8px; padding-left: 10px; border-left: 2px solid var(--brand-blue);">
                                        <small class="text-muted">Terakhir dijawab oleh: {{ $discussion->replies->last()->is_admin_reply ? 'Admin' : $discussion->replies->last()->user->name }}</small>
                                    </div>
                                @endif
                            </td>
                            <td>
                                @if($discussion->replies->where('is_admin_reply', true)->count() > 0)
                                    <span class="badge-ag badge-success">Sudah Dijawab</span>
                                @else
                                    <span class="badge-ag badge-warning">Menunggu Balasan</span>
                                @endif
                            </td>
                            <td>{{ $discussion->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <div style="display: flex; gap: 5px;">
                                    <button onclick="openReplyModal({{ $discussion->id }}, '{{ addslashes($discussion->message) }}')" class="btn-ag btn-primary btn-sm">
                                        <i class="fas fa-reply"></i>
                                    </button>
                                    <form action="{{ route('admin.discussions.destroy', $discussion) }}" method="POST" onsubmit="return confirm('Hapus diskusi ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn-ag btn-danger btn-sm">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="margin-top: 20px;">
            {{ $discussions->links() }}
        </div>
    </div>
</div>

<!-- REPLY MODAL -->
<div class="modal-overlay" id="replyModal">
    <div class="modal-box" style="width: 500px;">
        <h3>Balas Pertanyaan</h3>
        <p id="originalQuestion" style="font-style: italic; color: var(--text-muted); margin-bottom: 15px; padding: 10px; background: #f8fafc; border-radius: 4px;"></p>
        <form id="replyForm" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Jawaban Anda</label>
                <textarea name="message" class="form-control" rows="5" required placeholder="Tulis jawaban resmi..."></textarea>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn-ag btn-ghost" onclick="closeReplyModal()">Batal</button>
                <button type="submit" class="btn-ag btn-primary">Kirim Balasan</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function openReplyModal(id, message) {
        document.getElementById('originalQuestion').innerText = message;
        document.getElementById('replyForm').action = `/admin/discussions/${id}/reply`;
        document.getElementById('replyModal').classList.add('show');
    }
    function closeReplyModal() {
        document.getElementById('replyModal').classList.remove('show');
    }
</script>
@endpush
