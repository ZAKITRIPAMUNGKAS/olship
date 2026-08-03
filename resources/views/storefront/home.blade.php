@extends('layouts.app')

@push('styles')
<style>
  .hero-slide-wrapper {
    position: absolute;
    inset: 0;
    display: flex;
    flex-direction: column;
    justify-content: center;
  }
  .slide-enter-active, .slide-leave-active {
    transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1) !important;
  }
  .slide-enter-start {
    transform: translateX(100%) !important;
  }
  .slide-enter-end {
    transform: translateX(0) !important;
  }
  .slide-leave-start {
    transform: translateX(0) !important;
  }
  .slide-leave-end {
    transform: translateX(-100%) !important;
  }
</style>
@endpush

@section('content')
  <!-- HERO -->
  <section class="hero" x-data="{ activeSlide: 0, slidesCount: {{ max(count($banners), 1) }} }" x-init="slidesCount > 1 && setInterval(() => activeSlide = (activeSlide + 1) % slidesCount, 5000)">
    <div class="hero-grid">
      <div class="hero-main" style="position:relative; overflow:hidden; min-height:320px;">
        @forelse($banners as $index => $banner)
        <div class="hero-slide-wrapper" 
             x-show="activeSlide === {{ $index }}" 
             x-transition:enter="slide-enter-active"
             x-transition:enter-start="slide-enter-start"
             x-transition:enter-end="slide-enter-end"
             x-transition:leave="slide-leave-active"
             x-transition:leave-start="slide-leave-start"
             x-transition:leave-end="slide-leave-end"
             style="{{ $index === 0 ? '' : 'display:none;' }}"
             x-cloak>
          @php
            $bgGradient = $index === 0 
              ? 'linear-gradient(105deg, rgba(2, 25, 75, 0.95) 38%, rgba(2, 25, 75, 0.4) 100%)' 
              : 'linear-gradient(105deg, rgba(15, 23, 42, 0.95) 38%, rgba(15, 23, 42, 0.4) 100%)';
            $chipText = $index === 0 ? 'Bulan Perkakas Nasional 2026' : 'Pencahayaan & Instalasi Listrik';
            $chipIcon = $index === 0 ? 'fa-bolt' : 'fa-lightbulb';
            $subText = $index === 0 
              ? 'Ribuan alat teknik & kelistrikan original dari brand terpercaya. Gratis ongkir hingga Rp 50.000.'
              : 'Koleksi lengkap lampu hemat energi, saklar, kabel, MCB, dan material kelistrikan standar SNI berkualitas.';
            $ctaColor = $index === 0 ? 'var(--brand-blue, #025cca)' : 'var(--amber, #ea580c)';
          @endphp
          <div class="hero-bg-img" style="background: {{ $bgGradient }}, url('{{ $banner->image_url }}') center/cover; position:absolute; inset:0; z-index:1;"></div>
          <div class="hero-body" style="position:relative; z-index:2; padding: 48px; color:#fff;">
            <div class="hero-chip" style="background: rgba(255,255,255,.15); border: 1px solid rgba(255,255,255,.3); color:#fff; display:inline-flex; align-items:center; gap:6px; font-size:11px; font-weight:600; padding:4px 12px; border-radius:20px; text-transform:uppercase; margin-bottom:16px;">
              <i class="fas {{ $chipIcon }}" style="color: #ea580c;"></i> {{ $chipText }}
            </div>
            <h1 class="hero-h" style="font-family: 'Barlow Condensed', sans-serif; font-size:48px; font-weight:800; line-height:1.0; letter-spacing:-1px; margin-bottom:12px; color:#fff; text-transform:uppercase;">
              {{ $banner->title }}
            </h1>
            <p class="hero-sub" style="font-size:15px; margin-bottom:28px; max-width:360px; color:rgba(255,255,255,0.9); line-height:1.6;">{{ $subText }}</p>
            <div class="hero-cta">
              <a href="{{ $banner->link ?: '#' }}" class="btn-hero btn-hero-main" style="background: {{ $ctaColor }}; padding:12px 28px; border-radius:8px; font-size:14px; font-weight:700; color:#fff; display:inline-block; text-decoration:none;">Belanja Sekarang</a>
              <a href="{{ $banner->link ?: '#' }}" class="btn-hero-ghost" style="background:transparent; color:#fff; border:1px solid rgba(255,255,255,0.3); padding:11px 24px; border-radius:8px; display:inline-block; text-decoration:none; margin-left:10px;">Lihat Promo <i class="fas fa-arrow-right"></i></a>
            </div>
          </div>
        </div>
        @empty
        <div class="hero-slide-wrapper" style="position:relative; padding: 48px; color:#fff; background: linear-gradient(105deg, rgba(2, 25, 75, 0.95) 38%, rgba(2, 25, 75, 0.4) 100%);">
          <div class="hero-body">
            <h1 class="hero-h" style="font-family: 'Barlow Condensed', sans-serif; font-size:48px; font-weight:800; color:#fff;">CV. LISTRINDO JAYA ELEKTRIK</h1>
            <p class="hero-sub" style="font-size:15px; max-width:400px; color:rgba(255,255,255,0.9);">Distributor alat teknik & kelistrikan terpercaya.</p>
          </div>
        </div>
        @endforelse
        
        <!-- Slider Navigation Controls -->
        @if(count($banners) > 1)
        <div class="slider-dots" style="position:absolute; bottom:20px; left:48px; display:flex; gap:8px; z-index:10;">
          @foreach($banners as $index => $banner)
          <span class="dot" :class="activeSlide === {{ $index }} ? 'active' : ''" @click="activeSlide = {{ $index }}" style="width:10px; height:10px; border-radius:50%; background:rgba(255,255,255,0.4); cursor:pointer; transition:all 0.2s;" :style="activeSlide === {{ $index }} ? 'background:#fff; width:24px; border-radius:5px;' : ''"></span>
          @endforeach
        </div>
        @endif
      </div>
      </div>

      <div class="hero-side">
        <div class="side-card">
          <div class="side-card-tag"><i class="fas fa-certificate"></i> Official Store</div>
          <h3>Bosch Professional</h3>
          <p>Garansi resmi 1 tahun. Dijamin 100% original langsung dari distributor.</p>
          <div class="side-card-footer">Kunjungi Toko <i class="fas fa-arrow-right"></i></div>
        </div>
        <div class="side-card">
          <div class="side-card-tag"><i class="fas fa-truck"></i> Keuntungan Eksklusif</div>
          <h3>Bebas Ongkir</h3>
          <p>Potongan ongkir s.d Rp 50.000 untuk pengiriman kargo ke seluruh Indonesia.</p>
          <div class="side-card-footer">Syarat &amp; Ketentuan <i class="fas fa-arrow-right"></i></div>
        </div>
      </div>
    </div>
  </section>

  @if($flashSale)
  <!-- FLASH SALE COUNTDOWN -->
  <div class="promo-strip" x-data="countdown('{{ $flashSale->ends_at }}')">
    <div class="promo-left">
      <div class="promo-chip"><i class="fas fa-bolt" style="font-size:9px;"></i> Flash Sale</div>
      <div class="promo-h">PENAWARAN TERBATAS</div>
      <div class="promo-sub">Rebut sebelum kehabisan — berakhir dalam:</div>
    </div>
    <div class="promo-countdown">
      <div class="countdown-block"><span class="countdown-num" x-text="hours">00</span><div class="countdown-lbl">Jam</div></div>
      <div class="countdown-sep">:</div>
      <div class="countdown-block"><span class="countdown-num" x-text="minutes">00</span><div class="countdown-lbl">Menit</div></div>
      <div class="countdown-sep">:</div>
      <div class="countdown-block"><span class="countdown-num" x-text="seconds">00</span><div class="countdown-lbl">Detik</div></div>
    </div>
    <a href="{{ route('flash-sale') }}" class="btn btn-primary" style="flex-shrink:0;padding:12px 24px;font-size:14px;">Lihat Semua <i class="fas fa-arrow-right"></i></a>
  </div>

  <!-- FLASH SALE PRODUCTS -->
  <section class="section">
    <div class="section-hdr">
      <div class="section-title"><span>Flash</span> Sale</div>
      <a href="{{ route('flash-sale') }}" class="section-all">Lihat Semua <i class="fas fa-arrow-right"></i></a>
    </div>
    <div class="flash-row">
      @foreach($flashSale->items->take(5) as $item)
      <div class="flash-pcard" onclick="window.location='{{ $item->product ? route('products.show', $item->product->slug) : '#' }}'">
        <div class="flash-img">
            <img 
                src="{{ $item->product?->primaryImage ? asset('storage/'.$item->product->primaryImage->image_path) : 'https://placehold.co/400x400?text='.urlencode($item->product?->name ?? 'Produk') }}" 
                alt="{{ $item->product?->name ?? 'Produk' }}"
                loading="lazy"
                class="lazy-img">
        </div>
        <div class="flash-info">
          <div class="flash-name">{{ $item->product?->name ?? 'Produk' }}</div>
          <div class="flash-price">Rp {{ number_format($item->flash_price, 0, ',', '.') }}</div>
          <div style="display:flex;gap:8px;align-items:center;">
            <div class="flash-orig">Rp {{ number_format($item->product?->price ?? 0, 0, ',', '.') }}</div>
            <div class="flash-disc">-{{ $item->product?->discount_percentage ?? 0 }}%</div>
          </div>
        </div>
        <div class="flash-bar-wrap">
          @php $percent = ($item->sold_quota / $item->quota) * 100; @endphp
          <div class="flash-bar-bg"><div class="flash-bar-fill" style="width:{{ $percent }}%"></div></div>
          <div class="flash-bar-lbl">{{ round($percent) }}% terjual</div>
        </div>
      </div>
      @endforeach
    </div>
  </section>
  @endif

  <!-- FEATURED PRODUCTS -->
  <section class="section">
    <div class="section-hdr">
      <div class="section-title"><span>Rekomendasi</span> Untukmu</div>
      <a href="#" class="section-all">Lihat Semua <i class="fas fa-arrow-right"></i></a>
    </div>
    <div class="pgrid">
      @foreach($featuredProducts as $product)
      <div class="pcard" onclick="window.location='{{ route('products.show', $product->slug) }}'">
        <div class="pcard-img">
          <img 
            src="{{ $product->primaryImage ? asset('storage/'.$product->primaryImage->image_path) : 'https://placehold.co/400x400?text='.urlencode($product->name) }}" 
            alt="{{ $product->name }}"
            loading="lazy"
            class="lazy-img">
          @if($product->discount_percentage > 0)
            <div class="disc-badge">-{{ $product->discount_percentage }}%</div>
          @endif
        </div>
        <div class="pcard-body">
          <div class="pcard-name">{{ $product->name }}</div>
          <div class="pcard-price">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
          @if($product->compare_price > $product->price)
            <div class="pcard-price-orig">Rp {{ number_format($product->compare_price, 0, ',', '.') }}</div>
          @endif
          <div class="pcard-meta">
            <i class="fas fa-star star"></i> <span>{{ $product->rating_avg }}</span> | Terjual {{ $product->total_sold }}
          </div>
        </div>
        <div class="pcard-footer">
          <button class="btn-cart"><i class="fas fa-shopping-cart"></i> + Keranjang</button>
        </div>
      </div>
      @endforeach
    </div>
  </section>

@endsection

