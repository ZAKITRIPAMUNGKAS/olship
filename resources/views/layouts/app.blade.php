<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'LISTRINDO JAYA ELEKTRIK')</title>

  <link
    href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@500;600;700;800&family=DM+Sans:wght@300;400;500;600&display=swap"
    rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
  <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}">

  @vite(['resources/css/listrindojaya.css', 'resources/js/app.js'])
  <style>
    body { display: flex; flex-direction: column; min-height: 100vh; }
    main { flex: 1; }
  </style>
  @stack('head_scripts')
  @stack('styles')
</head>

<body>

  <!-- TOP STRIP -->
  <div class="strip">
    <div class="wrap">
      <div class="strip-links">
        <span><i class="fas fa-bolt"></i> Download App</span>
        <a href="#">Tentang Listrindo Jaya</a>
      </div>
      <div class="strip-links">
        <a href="#"><i class="fas fa-tag"></i> Promo Hari Ini</a>
        <a href="#"><i class="fas fa-headset"></i> Bantuan</a>
      </div>
    </div>
  </div>

  <!-- HEADER -->
  <header class="header">
    <div class="wrap header-inner">
      <a href="{{ route('home') }}" class="logo" style="display:flex; align-items:center; gap:10px;">
        <img src="{{ asset('images/logo.png') }}" alt="Listrindo Jaya" style="height:50px; width:auto; object-fit:contain;">
        <span style="height:30px; width:1px; background:var(--border, #cbd5e1);"></span>
        <img src="{{ asset('images/desain_tanpa_judul.png') }}" alt="Quin Food Nusantara" style="height:50px; width:auto; object-fit:contain; border-radius:6px;">
      </a>
      <form action="{{ route('search') }}" method="GET" class="search-wrap"
            x-data="{ 
              query: '{{ request('q') }}', 
              suggestions: [], 
              open: false, 
              loading: false,
              fetchSuggestions() {
                if (this.query.length < 2) {
                  this.suggestions = [];
                  this.open = false;
                  return;
                }
                this.loading = true;
                this.open = true;
                fetch(`/search-suggestions?q=${encodeURIComponent(this.query)}`)
                  .then(res => res.json())
                  .then(data => {
                    this.suggestions = data;
                    this.open = data.length > 0;
                    this.loading = false;
                  })
                  .catch(err => {
                    console.error(err);
                    this.loading = false;
                    this.open = false;
                  });
              }
            }"
            @click.away="open = false"
            style="position:relative;">
        <input type="text" name="q" placeholder="Cari produk, merek, atau kategori..." 
               x-model="query" 
               @input.debounce.300ms="fetchSuggestions()" 
               @focus="if(suggestions.length > 0) open = true"
               autocomplete="off"
               value="{{ request('q') }}">
        <button type="submit"><i class="fas fa-search"></i></button>

        <!-- Suggestion Dropdown -->
        <div x-show="open" x-cloak 
             style="position:absolute; top:100%; left:0; right:0; background:#fff; border:1px solid #e2e8f0; border-radius:8px; box-shadow:0 10px 15px -3px rgba(0,0,0,0.1); z-index:1000; margin-top:8px; overflow:hidden; max-height: 350px; overflow-y: auto;">
          
          <!-- Skeleton Loading -->
          <div x-show="loading" style="padding: 4px 0;">
            <div style="display:flex; align-items:center; gap:12px; padding:10px 16px; border-bottom:1px solid #f1f5f9;">
              <div class="skeleton" style="width:40px; height:40px; border-radius:4px; flex-shrink:0;"></div>
              <div style="flex:1; display:flex; flex-direction:column; gap:6px;">
                <div class="skeleton" style="width:70%; height:12px; border-radius:3px;"></div>
                <div class="skeleton" style="width:40%; height:10px; border-radius:3px;"></div>
              </div>
            </div>
            <div style="display:flex; align-items:center; gap:12px; padding:10px 16px; border-bottom:1px solid #f1f5f9;">
              <div class="skeleton" style="width:40px; height:40px; border-radius:4px; flex-shrink:0;"></div>
              <div style="flex:1; display:flex; flex-direction:column; gap:6px;">
                <div class="skeleton" style="width:60%; height:12px; border-radius:3px;"></div>
                <div class="skeleton" style="width:30%; height:10px; border-radius:3px;"></div>
              </div>
            </div>
            <div style="display:flex; align-items:center; gap:12px; padding:10px 16px; border-bottom:1px solid #f1f5f9;">
              <div class="skeleton" style="width:40px; height:40px; border-radius:4px; flex-shrink:0;"></div>
              <div style="flex:1; display:flex; flex-direction:column; gap:6px;">
                <div class="skeleton" style="width:80%; height:12px; border-radius:3px;"></div>
                <div class="skeleton" style="width:50%; height:10px; border-radius:3px;"></div>
              </div>
            </div>
          </div>

          <!-- Suggestions List -->
          <div x-show="!loading">
            <template x-for="item in suggestions" :key="item.id">
              <a :href="item.url" style="display:flex; align-items:center; gap:12px; padding:10px 16px; border-bottom:1px solid #f1f5f9; text-decoration:none; color:inherit; transition:background 0.2s;" @mouseenter="$el.style.background='#f8fafc'" @mouseleave="$el.style.background='transparent'">
                <img :src="item.image" style="width:40px; height:40px; object-fit:contain; border-radius:4px; background:#f8fafc;">
                <div style="flex:1; display:flex; flex-direction:column; justify-content:center;">
                  <span x-text="item.name" style="font-weight:600; font-size:13px; color:#1e293b; line-height:1.2; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; max-width:250px;"></span>
                  <span x-text="item.formatted_price" style="font-weight:700; font-size:12px; color:var(--brand-blue, #025cca); margin-top:2px;"></span>
                </div>
              </a>
            </template>
          </div>
        </div>
      </form>
      @auth
      <div class="hdr-actions" x-data="{ notificationsOpen: false }">
        <div class="hdr-icon" @click="notificationsOpen = !notificationsOpen" style="position:relative; cursor:pointer;">
          <i class="fas fa-bell"></i>
          @if(auth()->user()->unreadNotifications->count() > 0)
            <span class="badge" style="background:#ef4444;">{{ auth()->user()->unreadNotifications->count() }}</span>
          @endif
          
          <!-- Dropdown -->
          <div x-show="notificationsOpen" @click.away="notificationsOpen = false" x-cloak
               style="position:absolute; top:100%; right:0; width:300px; background:#fff; border:1px solid #e5e7eb; border-radius:12px; box-shadow:0 10px 15px -3px rgba(0,0,0,0.1); z-index:100; margin-top:10px; padding:10px 0;">
            <div style="padding:10px 15px; border-bottom:1px solid #f3f4f6; font-weight:700; font-size:14px; display:flex; justify-content:space-between; align-items:center;">
              <span>Notifikasi</span>
              <a href="{{ route('dashboard.notifications.index') }}" style="font-size:11px; color:var(--brand-blue);">Lihat Semua</a>
            </div>
            <div style="max-height:300px; overflow-y:auto;">
              @forelse(auth()->user()->notifications()->take(5)->get() as $notification)
                <div style="padding:12px 15px; border-bottom:1px solid #f9fafb; {{ $notification->read_at ? 'opacity:0.6;' : 'background:#f0f7ff;' }}">
                  <div style="font-size:13px; color:#374151; line-height:1.4; margin-bottom:4px;">{{ $notification->data['message'] }}</div>
                  <div style="font-size:11px; color:#9ca3af;">{{ $notification->created_at->diffForHumans() }}</div>
                </div>
              @empty
                <div style="padding:30px 15px; text-align:center; color:#9ca3af; font-size:13px;">
                  <i class="far fa-bell" style="font-size:24px; display:block; margin-bottom:10px; opacity:0.3;"></i>
                  Belum ada notifikasi
                </div>
              @endforelse
            </div>
          </div>
        </div>
        <div class="hdr-icon"><i class="fas fa-envelope"></i></div>
        <a href="{{ route('cart.index') }}" class="hdr-icon">
          <i class="fas fa-shopping-cart"></i>
          <span class="badge" id="cartBadge">0</span>
        </a>
      </div>
      @endauth
      <div class="hdr-auth">
        @auth
          @if(auth()->user()->hasAnyRole(['super_admin', 'admin', 'staff']))
            <a href="{{ route('admin.dashboard') }}" class="btn btn-primary" style="display:inline-flex; align-items:center; gap:6px;">
              <i class="fas fa-user-shield"></i> Panel Admin
            </a>
          @else
            <a href="{{ route('dashboard.index') }}" class="btn btn-ghost">Dashboard Saya</a>
          @endif
          <form action="{{ route('logout') }}" method="POST" style="display:inline">
            @csrf
            <button type="submit" class="btn btn-ghost" style="color:var(--red);">Keluar</button>
          </form>
        @else
          <a href="{{ route('login') }}" class="btn btn-ghost">Masuk</a>
          <a href="{{ route('register') }}" class="btn btn-primary">Daftar</a>
        @endauth
      </div>
    </div>
  </header>

  <!-- NAV DESKTOP & MOBILE ACCORDION -->
  <nav class="nav" x-data="{ open: false }">
    <div class="wrap" style="position:relative;">
      <button class="nav-hamburger" @click="open = !open" aria-label="Menu">
        <i class="fas fa-bars"></i> Kategori & Menu
      </button>
      <ul class="nav-list" :class="open ? 'nav-open' : ''">
        <li><a href="{{ route('home') }}"><i class="fas fa-th-large"></i> Semua Kategori</a></li>
        <li><a href="{{ route('categories.show', 'power-tools') }}"><i class="fas fa-drill"></i> Power Tools</a></li>
        <li><a href="{{ route('categories.show', 'hand-tools') }}"><i class="fas fa-wrench"></i> Hand Tools</a></li>
        <li><a href="{{ route('categories.show', 'alat-ukur') }}"><i class="fas fa-ruler"></i> Alat Ukur</a></li>
        <li><a href="{{ route('categories.show', 'kelistrikan') }}"><i class="fas fa-plug"></i> Kabel & Kelistrikan</a></li>
        <li><a href="{{ route('categories.show', 'lampu') }}"><i class="fas fa-lightbulb"></i> Lampu & Pencahayaan</a></li>
        <li><a href="{{ route('categories.show', 'frozen-food') }}"><i class="fas fa-snowflake"></i> Frozen Food</a></li>
        <li><a href="{{ route('flash-sale') }}" class="sale"><i class="fas fa-fire"></i> Flash Sale</a></li>
        @auth
        <li class="nav-auth-mobile">
          @if(auth()->user()->hasAnyRole(['super_admin', 'admin', 'staff']))
            <a href="{{ route('admin.dashboard') }}"><i class="fas fa-user-shield"></i> Panel Admin Toko</a>
          @else
            <a href="{{ route('dashboard.index') }}"><i class="fas fa-user"></i> Dashboard Saya</a>
          @endif
        </li>
        @else
        <li class="nav-auth-mobile"><a href="{{ route('login') }}"><i class="fas fa-sign-in-alt"></i> Masuk</a></li>
        <li class="nav-auth-mobile"><a href="{{ route('register') }}"><i class="fas fa-user-plus"></i> Daftar</a></li>
        @endauth
      </ul>
    </div>
  </nav>

  <!-- MOBILE BOTTOM NAVIGATION BAR -->
  <div class="mobile-bottom-nav">
    <a href="{{ route('home') }}" class="mobile-nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
      <i class="fas fa-home"></i>
      <span>Beranda</span>
    </a>
    <a href="{{ route('flash-sale') }}" class="mobile-nav-item {{ request()->routeIs('flash-sale') ? 'active' : '' }}">
      <i class="fas fa-bolt"></i>
      <span>Promo</span>
    </a>
    <a href="{{ route('cart.index') }}" class="mobile-nav-item {{ request()->routeIs('cart.*') ? 'active' : '' }}" style="position:relative;">
      <i class="fas fa-shopping-cart"></i>
      <span>Keranjang</span>
    </a>
    @auth
      @if(auth()->user()->hasAnyRole(['super_admin', 'admin', 'staff']))
        <a href="{{ route('admin.dashboard') }}" class="mobile-nav-item {{ request()->routeIs('admin.*') ? 'active' : '' }}">
          <i class="fas fa-user-shield"></i>
          <span>Admin</span>
        </a>
      @else
        <a href="{{ route('dashboard.index') }}" class="mobile-nav-item {{ request()->routeIs('dashboard.*') ? 'active' : '' }}">
          <i class="fas fa-user"></i>
          <span>Akun</span>
        </a>
      @endif
    @else
      <a href="{{ route('login') }}" class="mobile-nav-item {{ request()->routeIs('login') ? 'active' : '' }}">
        <i class="fas fa-user"></i>
        <span>Masuk</span>
      </a>
    @endauth
  </div>

  <main class="wrap">
    @yield('content')
  </main>

  <footer>
    <div class="wrap footer-grid">
      <div class="footer-col">
        <a href="#" class="logo" style="display:flex; align-items:center; margin-bottom:12px; gap:10px;">
          <img src="{{ asset('images/logo.png') }}" alt="Listrindo Jaya" style="height:40px; width:auto; object-fit:contain;">
          <span style="height:24px; width:1px; background:var(--border, #cbd5e1);"></span>
          <img src="{{ asset('images/desain_tanpa_judul.png') }}" alt="Quin Food Nusantara" style="height:40px; width:auto; object-fit:contain; border-radius:4px;">
        </a>
        <p class="footer-logo-desc">Pusat perkakas teknik profesional & frozen food berkualitas terlengkap dengan jaminan 100% original & higienis.</p>
        <div class="footer-socials">
          <div class="social-btn"><i class="fab fa-facebook-f"></i></div>
          <div class="social-btn"><i class="fab fa-instagram"></i></div>
          <div class="social-btn"><i class="fab fa-twitter"></i></div>
          <div class="social-btn"><i class="fab fa-youtube"></i></div>
        </div>
      </div>
      <div class="footer-col">
        <div class="footer-col-title">Perusahaan</div>
        <ul>
          <li><a href="#">Tentang Kami</a></li>
          <li><a href="#">Karir</a></li>
          <li><a href="#">Blog</a></li>
          <li><a href="#">Official Store</a></li>
        </ul>
      </div>
      <div class="footer-col">
        <div class="footer-col-title">Bantuan</div>
        <ul>
          <li><a href="#">Pusat Bantuan</a></li>
          <li><a href="#">Syarat & Ketentuan</a></li>
          <li><a href="#">Kebijakan Privasi</a></li>
          <li><a href="#">Hubungi Kami</a></li>
        </ul>
      </div>
      <div class="footer-col">
        <div class="footer-col-title">Pembayaran & Pengiriman</div>
        <div class="pay-chips">
          {{-- Banks --}}
          @foreach(config('payment.banks') as $bank)
            <div class="pay-chip" title="{{ $bank['name'] }}">
              <img src="{{ asset($bank['logo']) }}" alt="{{ $bank['name'] }}" loading="lazy">
            </div>
          @endforeach

          {{-- Wallets --}}
          @foreach(config('payment.wallets') as $wallet)
            <div class="pay-chip" title="{{ $wallet['name'] }}">
              <img src="{{ asset($wallet['logo']) }}" alt="{{ $wallet['name'] }}" loading="lazy">
            </div>
          @endforeach

          {{-- Shipping --}}
          @foreach(config('payment.shipping') as $shipping)
            <div class="pay-chip" title="{{ $shipping['name'] }}">
              <img src="{{ asset($shipping['logo']) }}" alt="{{ $shipping['name'] }}" loading="lazy">
            </div>
          @endforeach
        </div>
      </div>
    </div>
    <div class="wrap footer-bottom">
      <div>&copy; 2026 Listrindo Jaya Elektrik. All rights reserved.</div>
      <div style="display:flex;gap:20px;">
        <a href="#">Indonesia</a>
        <a href="#">English</a>
      </div>
    </div>
  </footer>

  <!-- TOAST CONTAINER -->
  <div id="toast-container" style="position: fixed; bottom: 24px; right: 24px; z-index: 9999; display: flex; flex-direction: column; gap: 10px; pointer-events: none;"></div>

  <style>
    .sf-toast {
      background: #0f172a;
      color: #ffffff;
      padding: 12px 18px;
      border-radius: 10px;
      font-size: 13px;
      font-weight: 600;
      display: flex;
      align-items: center;
      gap: 10px;
      box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.25);
      animation: sfToastIn 0.25s cubic-bezier(0.4, 0, 0.2, 1) forwards;
      pointer-events: auto;
      border: 1px solid rgba(255,255,255,0.1);
      max-width: 360px;
    }
    .sf-toast.success { border-left: 4px solid #10b981; }
    .sf-toast.error { border-left: 4px solid #ef4444; }
    .sf-toast i { font-size: 16px; }
    .sf-toast.success i { color: #10b981; }
    .sf-toast.error i { color: #ef4444; }
    @keyframes sfToastIn {
      from { opacity: 0; transform: translateY(16px) scale(0.95); }
      to { opacity: 1; transform: translateY(0) scale(1); }
    }
  </style>

  @stack('scripts')

  <script>
    function showToast(message, type = 'success') {
      const container = document.getElementById('toast-container');
      if (!container) return;
      
      const toast = document.createElement('div');
      toast.className = `sf-toast ${type}`;
      toast.innerHTML = `
        <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>
        <span>${message}</span>
      `;
      container.appendChild(toast);

      setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(10px)';
        toast.style.transition = 'all 0.2s ease';
        setTimeout(() => toast.remove(), 200);
      }, 3500);
    }

    function addToCartAjax(productId, quantity = 1, options = null) {
      fetch('{{ route('cart.add') }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
          product_id: productId,
          quantity: quantity,
          options: options
        })
      })
      .then(res => res.json())
      .then(data => {
        if (data.error) {
          showToast(data.error, 'error');
        } else {
          showToast(data.message || 'Produk berhasil ditambahkan ke keranjang', 'success');
          // Update cart badge
          const badge = document.getElementById('cartBadge');
          if (badge && data.summary) {
            badge.innerText = data.summary.item_count || data.summary.count || 1;
          }
        }
      })
      .catch(err => {
        console.error(err);
        showToast('Gagal menambahkan produk ke keranjang', 'error');
      });
    }

    // Intercept default cart form submits if needed
    document.addEventListener('DOMContentLoaded', () => {
      document.querySelectorAll('form[action*="/cart/add"]').forEach(form => {
        form.addEventListener('submit', function(e) {
          // If buy_now flag is 1, let form submit naturally to checkout
          const buyNow = form.querySelector('input[name="buy_now"]');
          if (buyNow && buyNow.value === '1') return;

          e.preventDefault();
          const pId = form.querySelector('input[name="product_id"]')?.value;
          const qty = form.querySelector('input[name="quantity"]')?.value || 1;
          const opts = form.querySelector('input[name="options"]')?.value || null;

          if (pId) {
            addToCartAjax(pId, qty, opts);
          }
        });
      });
    });

    // Clean service worker
    if ('serviceWorker' in navigator) {
      navigator.serviceWorker.getRegistrations().then(function(registrations) {
        for (let registration of registrations) {
          registration.update();
        }
      });
      navigator.serviceWorker.register('/sw.js', { scope: '/' })
        .then(function(reg) {
          console.log('[SW] Kill-switch registered');
        })
        .catch(function(err) {
          console.log('[SW] Registration skipped:', err);
        });
    }
  </script>
</body>

</html>