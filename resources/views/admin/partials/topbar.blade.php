<style>
    .top-header { 
        height: var(--header-h); 
        background: var(--panel-bg); 
        border-bottom: 1px solid var(--border); 
        display: flex; 
        align-items: center; 
        justify-content: space-between; 
        padding: 0 24px; 
        flex-shrink: 0; 
        box-shadow: 0 1px 2px rgba(0,0,0,0.02);
        position: sticky;
        top: 0;
        z-index: 40;
    }
    .header-left { display: flex; align-items: center; gap: 16px; }
    .menu-toggle { font-size: 18px; color: var(--text-muted); cursor: pointer; border: none; background: none; }
    
    .global-search { 
        display: flex; 
        align-items: center; 
        background: var(--main-bg); 
        border: 1px solid var(--border); 
        border-radius: 4px; 
        padding: 0 12px; 
        width: 300px; 
        height: 32px; 
    }
    .global-search i { color: var(--text-muted); font-size: 12px; }
    .global-search input { border: none; background: transparent; padding: 0 8px; outline: none; width: 100%; font-size: 13px; color: var(--text-main); }

    .header-right { display: flex; align-items: center; gap: 20px; }
    .h-icon { position: relative; color: var(--text-muted); font-size: 16px; cursor: pointer; background: none; border: none; }
    .h-icon .badge { position: absolute; top: -6px; right: -6px; background: var(--danger); color: white; font-size: 9px; font-weight: 700; padding: 2px 4px; border-radius: 4px; }
    
    .h-profile { display: flex; align-items: center; gap: 10px; cursor: pointer; border-left: 1px solid var(--border); padding-left: 20px; position: relative; }
    .h-avatar { 
        width: 32px; height: 32px; border-radius: 4px; 
        background: var(--brand-blue); color: white; 
        display: flex; align-items: center; justify-content: center; 
        font-weight: 600; font-size: 14px; 
    }
    .h-info { display: flex; flex-direction: column; text-align: right; }
    .h-name { font-size: 13px; font-weight: 600; color: var(--text-main); line-height: 1.2; }
    .h-role { font-size: 11px; color: var(--text-muted); }

    .profile-dropdown {
        position: absolute;
        top: calc(100% + 10px);
        right: 0;
        width: 200px;
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 4px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        padding: 8px 0;
        z-index: 100;
    }
    .dropdown-link {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 16px;
        font-size: 13px;
        color: var(--text-main);
        transition: background 0.2s;
    }
    .dropdown-link:hover { background: var(--main-bg); }
    .dropdown-link.danger { color: var(--danger); }
</style>

<header class="top-header">
    <div class="header-left">
        <button class="menu-toggle" @click="sidebarOpen = !sidebarOpen">
            <i class="fas fa-bars"></i>
        </button>
        <div class="global-search hidden md:flex">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Cari Order ID, SKU, atau Pelanggan...">
        </div>
    </div>

    <div class="header-right">
        {{-- Notifications --}}
        <div x-data="{ open: false }" style="position:relative;">
            <button class="h-icon" @click="open = !open">
                <i class="fas fa-bell"></i>
                @if(auth()->user()->unreadNotifications->count() > 0)
                    <span class="badge">{{ auth()->user()->unreadNotifications->count() }}</span>
                @endif
            </button>
            
            <div x-show="open" @click.outside="open = false" x-cloak class="profile-dropdown" style="width:280px;">
                <div style="padding:10px 16px; border-bottom:1px solid var(--border); font-weight:700;">Notifikasi</div>
                <div style="max-height:300px; overflow-y:auto;">
                    @forelse(auth()->user()->unreadNotifications->take(5) as $notif)
                        <a href="{{ $notif->data['url'] ?? '#' }}" class="dropdown-link">
                            <div style="flex:1;">
                                <div style="font-weight:600;">{{ $notif->data['title'] ?? 'Notifikasi baru' }}</div>
                                <div style="font-size:11px; color:var(--text-muted);">{{ $notif->created_at->diffForHumans() }}</div>
                            </div>
                        </a>
                    @empty
                        <div style="padding:20px; text-align:center; color:var(--text-muted);">Belum ada notifikasi</div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Profile --}}
        <div x-data="{ open: false }" style="position:relative;">
            <div class="h-profile" @click="open = !open">
                <div class="h-info hidden sm:flex">
                    <span class="h-name">{{ auth()->user()->name }}</span>
                    <span class="h-role">{{ strtoupper(auth()->user()->roles->first()->name ?? 'STAFF') }}</span>
                </div>
                <div class="h-avatar">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
            </div>

            <div x-show="open" @click.outside="open = false" x-cloak class="profile-dropdown">
                <a href="#" class="dropdown-link">
                    <i class="fas fa-user-circle"></i> Profil Saya
                </a>
                @if(Route::has('admin.settings.index'))
                    <a href="{{ route('admin.settings.index') }}" class="dropdown-link">
                        <i class="fas fa-cog"></i> Pengaturan
                    </a>
                @endif
                <div style="border-top: 1px solid var(--border); margin: 4px 0;"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-link danger" style="width:100%; background:none; border:none; cursor:pointer;">
                        <i class="fas fa-sign-out-alt"></i> Keluar
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
