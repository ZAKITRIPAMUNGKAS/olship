@extends('admin.layouts.app')
@section('title', 'Review')
@section('page-title', 'Review')

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:22px;" class="fade-up">
    <div>
        <h2 style="font-size:22px;font-weight:700;">Moderasi Review</h2>
        <p style="font-size:13px;color:var(--muted);margin-top:3px;">Approve, tolak, atau balas ulasan produk</p>
    </div>
</div>

{{-- Filter Bar --}}
<div class="glass-card panel fade-up delay-1" style="margin-bottom:18px;">
    <div style="display:flex;gap:12px;flex-wrap:wrap;align-items:center;">
        <div style="display:flex;gap:6px;">
            @foreach(['all'=>'Semua','pending'=>'Pending','approved'=>'Approved','rejected'=>'Ditolak'] as $key => $label)
            <a href="?status={{ $key }}"
               style="padding:6px 14px;border-radius:20px;font-size:12px;text-decoration:none;
                      border:1px solid {{ request('status','all') === $key ? 'var(--blue)' : 'var(--border)' }};
                      background:{{ request('status','all') === $key ? 'var(--blue-dim)' : 'transparent' }};
                      color:{{ request('status','all') === $key ? 'var(--blue)' : 'var(--muted)' }};">
                {{ $label }}
            </a>
            @endforeach
        </div>
        <div style="margin-left:auto;display:flex;gap:6px;">
            @foreach([0=>'All',1=>'⭐',2=>'⭐⭐',3=>'⭐⭐⭐',4=>'⭐⭐⭐⭐',5=>'⭐⭐⭐⭐⭐'] as $star => $label)
            <a href="?rating={{ $star }}"
               style="padding:5px 10px;border-radius:8px;font-size:11px;text-decoration:none;
                      border:1px solid var(--border);background:var(--surface);color:var(--muted);">
                {{ $label }}
            </a>
            @endforeach
        </div>
    </div>
</div>

{{-- Review Cards Grid --}}
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(340px,1fr));gap:16px;" class="fade-up delay-2">
    @forelse($reviews ?? [] as $review)
    <div class="glass-card" style="padding:20px;">
        {{-- Header --}}
        <div style="display:flex;align-items:flex-start;gap:12px;margin-bottom:14px;">
            <div style="width:40px;height:40px;border-radius:50%;
                        background:linear-gradient(135deg,var(--blue),#6C63FF);
                        display:flex;align-items:center;justify-content:center;
                        font-size:14px;font-weight:700;flex-shrink:0;">
                {{ strtoupper(substr($review->user->name, 0, 1)) }}
            </div>
            <div style="flex:1;">
                <div style="font-size:13px;font-weight:600;">{{ $review->user->name }}</div>
                <div style="font-size:11px;color:var(--muted);">{{ $review->created_at->diffForHumans() }}</div>
                <div style="color:var(--amber);font-size:13px;margin-top:2px;">
                    @for($i=1;$i<=5;$i++) {{ $i <= $review->rating ? '★' : '☆' }} @endfor
                </div>
            </div>
            <span class="badge-ag {{ $review->status === 'approved' ? 'badge-completed' : ($review->status === 'rejected' ? 'badge-cancelled' : 'badge-pending') }}">
                {{ ucfirst($review->status ?? 'pending') }}
            </span>
        </div>

        {{-- Product --}}
        <div style="font-size:11px;color:var(--muted);margin-bottom:8px;">
            <i class="fas fa-box" style="margin-right:4px;"></i>{{ $review->product->name ?? '-' }}
        </div>

        {{-- Comment --}}
        @if($review->image_path)
        <div style="margin-bottom: 12px;">
            <img src="{{ asset('storage/'.$review->image_path) }}" 
                 alt="Review Image" 
                 style="width: 100%; height: 160px; object-fit: cover; border-radius: 8px; cursor: pointer;"
                 onclick="window.open(this.src, '_blank')">
        </div>
        @endif
        
        @if($review->comment)
        <p style="font-size:13px;color:var(--text-2);line-height:1.6;margin-bottom:14px;">
            "{{ $review->comment }}"
        </p>
        @endif

        {{-- Actions --}}
        <div style="display:flex;gap:8px;">
            @if(Route::has('admin.reviews.approve'))
            <form action="{{ route('admin.reviews.approve', $review) }}" method="POST">
                @csrf
                <button type="submit" class="btn-ag btn-ghost btn-sm" style="color:var(--green);border-color:rgba(46,204,113,0.3);">
                    <i class="fas fa-check"></i> Approve
                </button>
            </form>
            @endif
            @if(Route::has('admin.reviews.reject'))
            <form action="{{ route('admin.reviews.reject', $review) }}" method="POST" onsubmit="event.preventDefault(); confirmAction({title: 'Tolak Ulasan?', body: 'Apakah kamu yakin ingin menolak ulasan ini?', label: 'Ya, Tolak', onConfirm: () => { this.submit(); }})">
                @csrf
                <button type="submit" class="btn-ag btn-ghost btn-sm" style="color:var(--danger);border-color:rgba(255,71,87,0.3);">
                    <i class="fas fa-times"></i> Tolak
                </button>
            </form>
            @endif
            <button class="btn-ag btn-ghost btn-sm" style="margin-left:auto;">
                <i class="fas fa-reply"></i> Balas
            </button>
        </div>
    </div>
    @empty
    <div style="grid-column:1/-1;">
        <div class="empty-state glass-card">
            <i class="fas fa-star"></i>
            <p>Tidak ada review untuk dimoderasi.</p>
        </div>
    </div>
    @endforelse
</div>
@endsection
