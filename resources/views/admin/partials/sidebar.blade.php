@php
$menu = [
    [
        'icon' => 'fa-home',
        'label' => 'Dashboard',
        'route' => 'admin.dashboard',
        'permission' => null,
    ],
    [
        'label' => 'Katalog',
        'type' => 'label',
    ],
    [
        'icon' => 'fa-box',
        'label' => 'Produk',
        'route' => 'admin.products.index',
        'permission' => 'products.view',
        'children' => [
            ['label' => 'Semua Produk',  'route' => 'admin.products.index'],
            ['label' => 'Tambah Produk', 'route' => 'admin.products.create'],
            ['label' => 'Kategori',      'route' => 'admin.categories.index'],
            ['label' => 'Merek',         'route' => 'admin.brands.index'],
        ],
    ],
    [
        'icon' => 'fa-bolt',
        'label' => 'Flash Sale',
        'route' => 'admin.flash-sales.index',
        'permission' => 'flash_sales.view',
        'badge' => 'On',
    ],
    [
        'icon' => 'fa-ticket-alt',
        'label' => 'Kupon Diskon',
        'route' => 'admin.coupons.index',
        'permission' => 'coupons.view',
    ],
    [
        'label' => 'Sales & Pelanggan',
        'type' => 'label',
    ],
    [
        'icon' => 'fa-shopping-cart',
        'label' => 'Daftar Pesanan',
        'route' => 'admin.orders.index',
        'permission' => 'orders.view',
    ],
    [
        'icon' => 'fa-users',
        'label' => 'Pelanggan',
        'route' => 'admin.users.index',
        'permission' => 'users.view',
    ],
    [
        'icon' => 'fa-star',
        'label' => 'Moderasi Ulasan',
        'route' => 'admin.reviews.index',
        'permission' => 'reviews.manage',
    ],
    [
        'icon' => 'fa-comments',
        'label' => 'Diskusi Produk',
        'route' => 'admin.discussions.index',
        'permission' => 'discussions.manage',
    ],
    [
        'label' => 'Sistem',
        'type' => 'label',
    ],
    [
        'icon' => 'fa-image',
        'label' => 'Banner Website',
        'route' => 'admin.banners.index',
        'permission' => 'banners.manage',
    ],
    [
        'icon' => 'fa-chart-bar',
        'label' => 'Laporan Keuangan',
        'route' => 'admin.reports.revenue',
        'permission' => 'reports.view',
    ],
    [
        'icon' => 'fa-cog',
        'label' => 'Pengaturan',
        'route' => 'admin.settings.index',
        'permission' => 'settings.view',
    ],
    [
        'icon' => 'fa-history',
        'label' => 'Log Aktivitas',
        'route' => 'admin.audit-logs.index',
        'permission' => 'system.logs',
    ],
    [
        'icon' => 'fa-exclamation-triangle',
        'label' => 'API Sync Gagal',
        'route' => 'admin.failed-sync-logs.index',
        'permission' => 'system.logs',
    ],
];
@endphp

<style>
    .sidebar { 
        width: var(--sidebar-w); 
        background: var(--sidebar-bg); 
        display: flex; 
        flex-direction: column; 
        flex-shrink: 0; 
        z-index: 100; 
        transition: width 0.3s; 
        position: fixed;
        top: 0; left: 0; bottom: 0;
        overflow: hidden;
    }
    .sidebar.collapsed { width: var(--sidebar-collapsed-w); }

    .sb-header { 
        height: var(--header-h); 
        padding: 0 20px; 
        border-bottom: 1px solid rgba(255,255,255,0.05); 
        display: flex; 
        align-items: center; 
        background: #0b1120; 
        white-space: nowrap;
    }
    .sb-logo { 
        font-family: 'Barlow Condensed', sans-serif; 
        font-size: 24px; 
        font-weight: 700; 
        color: #fff; 
        letter-spacing: 0.5px; 
    }
    .sb-logo span { color: var(--brand-orange); }

    .sb-nav { padding: 16px 0; overflow-y: auto; flex: 1; display: flex; flex-direction: column; gap: 2px; }
    
    .nav-label { 
        font-size: 11px; 
        font-weight: 700; 
        color: #475569; 
        text-transform: uppercase; 
        padding: 12px 20px 4px; 
        letter-spacing: 0.5px; 
        white-space: nowrap;
    }
    
    .nav-item { 
        display: flex; 
        align-items: center; 
        gap: 12px; 
        padding: 10px 20px; 
        color: var(--sidebar-text); 
        font-weight: 500; 
        cursor: pointer; 
        transition: all 0.2s; 
        border-left: 3px solid transparent; 
        width: 100%;
        background: none;
        border: none;
        text-align: left;
        white-space: nowrap;
        font-size: 13px;
    }
    .nav-item:hover { background: var(--sidebar-hover); color: var(--sidebar-text-active); }
    .nav-item.active { background: var(--sidebar-hover); color: var(--sidebar-text-active); border-left-color: var(--brand-orange); }
    .nav-item i { width: 16px; font-size: 14px; text-align: center; }
    
    .nav-badge { 
        margin-left: auto; 
        background: var(--brand-orange); 
        color: #fff; 
        font-size: 10px; 
        padding: 2px 6px; 
        border-radius: 10px; 
        font-weight: 700; 
    }

    .nav-sub {
        padding: 4px 0 8px 48px;
        display: flex;
        flex-direction: column;
        gap: 4px;
        background: rgba(0,0,0,0.1);
    }
    .nav-sub a {
        font-size: 12px;
        color: var(--sidebar-text);
        padding: 6px 0;
        transition: color 0.2s;
    }
    .nav-sub a:hover, .nav-sub a.active {
        color: var(--sidebar-text-active);
    }

    @media (max-width: 768px) {
        .sidebar { transform: translateX(-100%); transition: transform 0.3s; z-index: 100; }
        .sidebar.show { transform: translateX(0); }
    }
</style>

<aside class="sidebar" :class="sidebarOpen ? 'show' : 'collapsed'" id="sidebar">
    <div class="sb-header" style="height: 68px; padding: 0 16px; display: flex; align-items: center; border-bottom: 1px solid rgba(255,255,255,0.06); background: #0b0f19;">
        <a href="{{ route('admin.dashboard') }}" style="display: flex; align-items: center; gap: 8px; text-decoration: none; overflow: hidden; width: 100%;">
            <img src="{{ asset('images/logo.png') }}" alt="Listrindo Jaya" style="height: 30px; width: auto; object-fit: contain; flex-shrink: 0;">
            <span x-show="sidebarOpen" style="height: 16px; width: 1px; background: rgba(255,255,255,0.2); flex-shrink: 0;"></span>
            <img x-show="sidebarOpen" src="{{ asset('images/desain_tanpa_judul.png') }}" alt="Quin Food" style="height: 28px; width: auto; object-fit: contain; border-radius: 4px; flex-shrink: 0;">
        </a>
    </div>
    
    <div class="sb-nav">
        @php
            $user = auth()->user();
            $isSuperAdmin = $user && $user->hasRole('super_admin');
        @endphp

        @foreach($menu as $item)
            @if(isset($item['type']) && $item['type'] === 'label')
                <div class="nav-label" x-show="sidebarOpen" x-transition.opacity>{{ $item['label'] }}</div>
            @elseif($isSuperAdmin || $item['permission'] === null || ($user && $user->can($item['permission'])))
                
                @if(isset($item['children']))
                    @php
                        $isActive = collect($item['children'])->contains(fn($c) => Route::has($c['route']) && request()->routeIs($c['route']));
                    @endphp
                    <div x-data="{ open: {{ $isActive ? 'true' : 'false' }} }">
                        <button @click="open = !open" 
                                class="nav-item {{ $isActive ? 'active' : '' }}"
                                :title="!sidebarOpen ? '{{ $item['label'] }}' : ''">
                            <i class="fas {{ $item['icon'] }}"></i>
                            <span x-show="sidebarOpen" x-transition.opacity style="flex:1;">{{ $item['label'] }}</span>
                            <i x-show="sidebarOpen" class="fas fa-chevron-down" style="font-size:10px; transition:0.2s;" :style="open ? 'transform:rotate(180deg)' : ''"></i>
                        </button>
                        <div x-show="open && sidebarOpen" x-cloak class="nav-sub">
                            @foreach($item['children'] as $child)
                                @if(Route::has($child['route']))
                                    <a href="{{ route($child['route']) }}" class="{{ request()->routeIs($child['route']) ? 'active' : '' }}">
                                        {{ $child['label'] }}
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @else
                    @if(Route::has($item['route']))
                        <a href="{{ route($item['route']) }}" 
                           class="nav-item {{ request()->routeIs(str_replace('index','*', $item['route'])) ? 'active' : '' }}"
                           :title="!sidebarOpen ? '{{ $item['label'] }}' : ''">
                            <i class="fas {{ $item['icon'] }}"></i>
                            <span x-show="sidebarOpen" x-transition.opacity>{{ $item['label'] }}</span>
                            
                            @if(isset($item['badge']))
                                <span class="nav-badge" x-show="sidebarOpen">{{ $item['badge'] }}</span>
                            @endif
                        </a>
                    @endif
                @endif

            @endif
        @endforeach
    </div>

    {{-- User Role Badge --}}
    @if(auth()->check())
    <div style="padding: 10px 20px; background: rgba(255,255,255,0.03); border-top: 1px solid rgba(255,255,255,0.05); display: flex; align-items: center; justify-content: space-between;" x-show="sidebarOpen">
        <div style="font-size: 11px; color: #64748b; font-weight: 600; text-transform: uppercase;">Akses Peran</div>
        <span style="font-size: 10px; font-weight: 800; padding: 2px 8px; border-radius: 99px; text-transform: uppercase; letter-spacing: 0.05em;
            {{ auth()->user()->hasRole('super_admin') ? 'background: #f97316; color: #fff;' : (auth()->user()->hasRole('admin') ? 'background: #2563eb; color: #fff;' : 'background: #10b981; color: #fff;') }}">
            {{ auth()->user()->getRoleNames()->first() ?? 'User' }}
        </span>
    </div>
    @endif

    <div style="padding: 12px 20px; border-top: 1px solid rgba(255,255,255,0.05); cursor: pointer; color: var(--sidebar-text);" @click="sidebarOpen = !sidebarOpen">
        <i class="fas" :class="sidebarOpen ? 'fa-angles-left' : 'fa-angles-right'"></i>
        <span x-show="sidebarOpen" x-transition.opacity style="margin-left: 10px; font-size: 12px;">Sembunyikan Menu</span>
    </div>
</aside>
