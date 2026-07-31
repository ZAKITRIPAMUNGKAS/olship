@extends('admin.layouts.app')
@section('title', 'Pengguna')
@section('page-title', 'Pengguna')

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:22px;" class="fade-up">
    <div>
        <h2 style="font-size:22px;font-weight:700;">Manajemen Pengguna</h2>
        <p style="font-size:13px;color:var(--muted);margin-top:3px;">Kelola user, role, dan akses</p>
    </div>
</div>

{{-- Stats Row --}}
<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-bottom:20px;" class="fade-up delay-1">
    <div class="glass-card" style="padding:18px;display:flex;align-items:center;gap:14px;">
        <div style="width:42px;height:42px;border-radius:10px;background:var(--blue-dim);display:flex;align-items:center;justify-content:center;color:var(--blue);font-size:18px;flex-shrink:0;">
            <i class="fas fa-users"></i>
        </div>
        <div>
            <div style="font-size:24px;font-weight:700;font-family:'DM Mono',monospace;color:var(--blue);">{{ isset($users) ? $users->total() : 0 }}</div>
            <div style="font-size:11px;color:var(--muted);">Total Pengguna</div>
        </div>
    </div>
    <div class="glass-card" style="padding:18px;display:flex;align-items:center;gap:14px;">
        <div style="width:42px;height:42px;border-radius:10px;background:var(--amber-dim);display:flex;align-items:center;justify-content:center;color:var(--amber);font-size:18px;flex-shrink:0;">
            <i class="fas fa-user-check"></i>
        </div>
        <div>
            <div style="font-size:24px;font-weight:700;font-family:'DM Mono',monospace;color:var(--amber);">{{ isset($userStats) ? ($userStats['active'] ?? 0) : 0 }}</div>
            <div style="font-size:11px;color:var(--muted);">User Aktif</div>
        </div>
    </div>
    <div class="glass-card" style="padding:18px;display:flex;align-items:center;gap:14px;">
        <div style="width:42px;height:42px;border-radius:10px;background:var(--green-dim);display:flex;align-items:center;justify-content:center;color:var(--green);font-size:18px;flex-shrink:0;">
            <i class="fas fa-user-plus"></i>
        </div>
        <div>
            <div style="font-size:24px;font-weight:700;font-family:'DM Mono',monospace;color:var(--green);">{{ isset($userStats) ? ($userStats['today'] ?? 0) : 0 }}</div>
            <div style="font-size:11px;color:var(--muted);">Baru Hari Ini</div>
        </div>
    </div>
</div>

{{-- Filter --}}
<div class="glass-card panel fade-up delay-1" style="margin-bottom:18px;">
    <div style="display:flex;gap:12px;align-items:center;flex-wrap:wrap;">
        <div style="flex:1;min-width:200px;position:relative;">
            <i class="fas fa-search" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--muted);font-size:13px;"></i>
            <input class="form-input" style="padding-left:36px;" type="text" placeholder="Cari nama, email...">
        </div>
        <select class="form-input" style="width:140px;">
            <option>Semua Role</option>
            <option>admin</option>
            <option>staff</option>
            <option>seller</option>
            <option>customer</option>
        </select>
    </div>
</div>

{{-- Table --}}
<div class="glass-card fade-up delay-2">
    <div class="table-responsive">
    <table class="ag-table">
        <thead>
            <tr>
                <th>User</th>
                <th>Email</th>
                <th>Role</th>
                <th>Bergabung</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users ?? [] as $user)
            <tr>
                <td>
                    <div style="display:flex;align-items:center;gap:10px;">
                        <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,var(--blue),#6C63FF);
                                    display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;flex-shrink:0;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <span style="font-size:13px;font-weight:500;">{{ $user->name }}</span>
                    </div>
                </td>
                <td style="font-size:12px;color:var(--text-2);">{{ $user->email }}</td>
                <td>
                    @foreach($user->roles as $role)
                        <span style="padding:2px 8px;border-radius:10px;font-size:11px;
                                     background:var(--blue-dim);color:var(--blue);
                                     font-family:'DM Mono',monospace;">{{ strtoupper($role->name) }}</span>
                    @endforeach
                </td>
                <td style="font-size:12px;color:var(--muted);">{{ $user->created_at->format('d M Y') }}</td>
                <td>
                    <span class="badge-ag {{ $user->is_active ? 'badge-completed' : 'badge-cancelled' }}">
                        {{ $user->is_active ? 'Aktif' : 'Dinonaktifkan' }}
                    </span>
                </td>
                <td>
                    <div style="display:flex;gap:6px;">
                        <a href="{{ Route::has('admin.users.show') ? route('admin.users.show', $user) : '#' }}" class="btn-ag btn-ghost btn-sm"><i class="fas fa-eye"></i></a>
                        <a href="{{ Route::has('admin.users.edit') ? route('admin.users.edit', $user) : '#' }}" class="btn-ag btn-ghost btn-sm"><i class="fas fa-pen"></i></a>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6">
                    <div class="empty-state">
                        <i class="fas fa-users"></i>
                        <p>Belum ada pengguna ditemukan.<br>
                           Pengguna baru akan muncul di sini.</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>
    @if(isset($users) && $users->hasPages())
    <div style="padding:16px 22px;border-top:1px solid var(--border);">{{ $users->links() }}</div>
    @endif
</div>
@endsection
