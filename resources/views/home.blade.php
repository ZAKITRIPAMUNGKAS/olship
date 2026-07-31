@extends('layouts.app')

@section('content')
<div id="home-view">

  <!-- HERO -->
  <section class="hero">
    <div class="hero-grid">
      <div class="hero-main">
        <div class="hero-bg-img"></div>
        <div class="hero-body">
          <div class="hero-chip"><i class="fas fa-bolt"></i> Bulan Perkakas Nasional 2026</div>
          <h1 class="hero-h">DISKON<br>HINGGA <span>70%</span></h1>
          <p class="hero-sub">Ribuan alat teknik & kelistrikan original dari brand terpercaya. Gratis ongkir hingga Rp 50.000.</p>
          <div class="hero-cta">
            <a href="{{ url('/category/Flash Sale') }}" class="btn-hero btn-hero-main">Belanja Sekarang</a>
            <button class="btn-hero-ghost">Lihat Promo <i class="fas fa-arrow-right"></i></button>
          </div>
          <div class="hero-stats">
            <div>
              <div class="hero-stat-val">50rb+</div>
              <div class="hero-stat-lbl">PRODUK</div>
            </div>
            <div style="width:1px;background:rgba(255,255,255,.1);"></div>
            <div>
              <div class="hero-stat-val">200+</div>
              <div class="hero-stat-lbl">MEREK</div>
            </div>
            <div style="width:1px;background:rgba(255,255,255,.1);"></div>
            <div>
              <div class="hero-stat-val">4.9★</div>
              <div class="hero-stat-lbl">RATING</div>
            </div>
          </div>
        </div>
      </div>

      <div class="hero-side">
        <div class="side-card">
          <div>
            <div class="side-card-tag"><i class="fas fa-certificate"></i> Official Store</div>
            <h3>Bosch<br>Professional</h3>
            <p>Garansi resmi 1 tahun. Dijamin 100% original langsung dari distributor.</p>
          </div>
          <div class="side-card-footer">Kunjungi Toko <i class="fas fa-arrow-right"></i></div>
        </div>
        <a href="{{ route('terms') }}" class="side-card" style="text-decoration:none;">
          <div>
            <div class="side-card-tag" style="color:var(--green)"><i class="fas fa-truck"></i> Keuntungan Eksklusif</div>
            <h3>Bebas<br>Ongkir</h3>
            <p>Potongan ongkir s.d Rp 50.000 untuk pengiriman kargo ke seluruh Indonesia.</p>
          </div>
          <div class="side-card-footer" style="color:var(--green)">Syarat & Ketentuan <i class="fas fa-arrow-right"></i></div>
        </a>
      </div>
    </div>
  </section>

  <!-- FLASH SALE COUNTDOWN -->
  <a href="{{ url('/category/Flash Sale') }}" class="promo-strip">
    <div class="promo-left">
      <div class="promo-chip"><i class="fas fa-fire"></i> Flash Sale</div>
      <div class="promo-h">HARGA <span>GILA-GILAAN</span></div>
      <div class="promo-sub">Berakhir dalam waktu — rebut sebelum kehabisan!</div>
    </div>
    <div class="promo-countdown">
      <div class="countdown-block"><span class="countdown-num" id="hr">02</span><div class="countdown-lbl">Jam</div></div>
      <div class="countdown-sep">:</div>
      <div class="countdown-block"><span class="countdown-num" id="mn">47</span><div class="countdown-lbl">Menit</div></div>
      <div class="countdown-sep">:</div>
      <div class="countdown-block"><span class="countdown-num" id="sc">33</span><div class="countdown-lbl">Detik</div></div>
    </div>
    <button class="btn btn-primary" style="flex-shrink:0;padding:12px 24px;font-size:14px;">Lihat Semua <i class="fas fa-arrow-right"></i></button>
  </a>

  <!-- FLASH SALE PRODUCTS -->
  <section class="section">
    <div class="section-hdr">
      <div class="section-title"><span>Flash</span> Sale</div>
      <a href="{{ url('/category/Flash Sale') }}" class="section-all">Lihat Semua <i class="fas fa-arrow-right"></i></a>
    </div>
    <div class="flash-row">
      <div class="flash-pcard" onclick="window.location.href='{{ url('/product/1') }}'">
        <div class="flash-img"><img src="https://images.unsplash.com/photo-1504148455328-c376907d081c?auto=format&fit=crop&w=400&q=80" alt="Bor Bosch"></div>
        <div class="flash-info">
          <div class="flash-name">Mesin Bor Listrik Bosch GSB 550 Pro 13mm</div>
          <div class="flash-price">Rp450.000</div>
          <div style="display:flex;gap:8px;align-items:center;">
            <div class="flash-orig">Rp550.000</div>
            <div class="flash-disc">-18%</div>
          </div>
        </div>
        <div class="flash-bar-wrap">
          <div class="flash-bar-bg"><div class="flash-bar-fill" style="width:73%"></div></div>
          <div class="flash-bar-lbl">73% terjual</div>
        </div>
      </div>
      <div class="flash-pcard" >
        <div class="flash-img"><img src="https://images.unsplash.com/photo-1550989460-0adf9ea622e2?auto=format&fit=crop&w=400&q=80" alt="Lampu"></div>
        <div class="flash-info">
          <div class="flash-name">Lampu Sorot LED Philips 50W BVP150 Outdoor</div>
          <div class="flash-price">Rp149.000</div>
          <div style="display:flex;gap:8px;align-items:center;">
            <div class="flash-orig">Rp185.000</div>
            <div class="flash-disc">-19%</div>
          </div>
        </div>
        <div class="flash-bar-wrap">
          <div class="flash-bar-bg"><div class="flash-bar-fill" style="width:88%"></div></div>
          <div class="flash-bar-lbl">88% terjual</div>
        </div>
      </div>
      <div class="flash-pcard" >
        <div class="flash-img"><img src="https://images.unsplash.com/photo-1580983546571-0857cb3e86c1?auto=format&fit=crop&w=400&q=80" alt="Multimeter"></div>
        <div class="flash-info">
          <div class="flash-name">Digital Multimeter Avometer Pengukur Arus Listrik</div>
          <div class="flash-price">Rp42.000</div>
          <div style="display:flex;gap:8px;align-items:center;">
            <div class="flash-orig">Rp55.000</div>
            <div class="flash-disc">-24%</div>
          </div>
        </div>
        <div class="flash-bar-wrap">
          <div class="flash-bar-bg"><div class="flash-bar-fill" style="width:55%"></div></div>
          <div class="flash-bar-lbl">55% terjual</div>
        </div>
      </div>
      <div class="flash-pcard" >
        <div class="flash-img"><img src="https://images.unsplash.com/photo-1572981779307-38b8cabb2407?auto=format&fit=crop&w=400&q=80" alt="Gerinda"></div>
        <div class="flash-info">
          <div class="flash-name">Maktec MT90 Mesin Gerinda Tangan 4" 540W</div>
          <div class="flash-price">Rp299.000</div>
          <div style="display:flex;gap:8px;align-items:center;">
            <div class="flash-orig">Rp350.000</div>
            <div class="flash-disc">-15%</div>
          </div>
        </div>
        <div class="flash-bar-wrap">
          <div class="flash-bar-bg"><div class="flash-bar-fill" style="width:41%"></div></div>
          <div class="flash-bar-lbl">41% terjual</div>
        </div>
      </div>
      <div class="flash-pcard" >
        <div class="flash-img"><img src="https://images.unsplash.com/photo-1530124566582-a618bc2615dc?auto=format&fit=crop&w=400&q=80" alt="Obeng"></div>
        <div class="flash-info">
          <div class="flash-name">Tekiro Set Obeng Magnetik 7 Pcs JIS Standard</div>
          <div class="flash-price">Rp69.000</div>
          <div style="display:flex;gap:8px;align-items:center;">
            <div class="flash-orig">Rp85.000</div>
            <div class="flash-disc">-19%</div>
          </div>
        </div>
        <div class="flash-bar-wrap">
          <div class="flash-bar-bg"><div class="flash-bar-fill" style="width:62%"></div></div>
          <div class="flash-bar-lbl">62% terjual</div>
        </div>
      </div>
    </div>
  </section>

  <!-- REKOMENDASI -->
  <section class="section">
    <div class="section-hdr">
      <div class="section-title">Rekomendasi <span>Untuk Anda</span></div>
      <a href="#" class="section-all">Lihat Semua <i class="fas fa-arrow-right"></i></a>
    </div>
    <div class="pgrid">

      <div class="pcard" onclick="window.location.href='{{ url('/product/1') }}'">
        <div class="pcard-img">
          <span class="disc-badge">-18%</span>
          <img src="https://images.unsplash.com/photo-1504148455328-c376907d081c?auto=format&fit=crop&w=400&q=80" alt="Bor Bosch">
        </div>
        <div class="pcard-body">
          <div class="pcard-name">Mesin Bor Tembok Listrik Bosch GSB 550 Professional 13mm</div>
          <div class="pcard-price">Rp450.000</div>
          <div class="pcard-price-orig">Rp550.000</div>
          <div class="pcard-meta"><i class="fas fa-star star"></i> 4.8 <span style="color:var(--ink-3)">|</span> 750+ terjual</div>
        </div>
        <div class="pcard-footer">
          <button class="btn-cart" onclick="event.stopPropagation();addToCart('Bosch GSB 550')"><i class="fas fa-cart-plus"></i> Keranjang</button>
        </div>
      </div>

      <div class="pcard" >
        <div class="pcard-img">
          <img src="https://images.unsplash.com/photo-1550989460-0adf9ea622e2?auto=format&fit=crop&w=400&q=80" alt="Lampu Philips">
        </div>
        <div class="pcard-body">
          <div class="pcard-name">Lampu Sorot LED Philips 50W BVP150 Outdoor IP65</div>
          <div class="pcard-price">Rp185.000</div>
          <div class="pcard-meta"><i class="fas fa-star star"></i> 4.9 <span style="color:var(--ink-3)">|</span> 2,1rb+ terjual</div>
        </div>
        <div class="pcard-footer">
          <button class="btn-cart" onclick="event.stopPropagation();addToCart('Lampu Philips 50W')"><i class="fas fa-cart-plus"></i> Keranjang</button>
        </div>
      </div>

      <div class="pcard" >
        <div class="pcard-img">
          <span class="new-badge">NEW</span>
          <img src="https://images.unsplash.com/photo-1555664424-778a1e5e1b48?auto=format&fit=crop&w=400&q=80" alt="Kabel">
        </div>
        <div class="pcard-body">
          <div class="pcard-name">Kabel Roll Broco 15 Meter / Stop Kontak Ekstensi SNI</div>
          <div class="pcard-price">Rp120.000</div>
          <div class="pcard-meta"><i class="fas fa-star star"></i> 5.0 <span style="color:var(--ink-3)">|</span> 100+ terjual</div>
        </div>
        <div class="pcard-footer">
          <button class="btn-cart" onclick="event.stopPropagation();addToCart('Kabel Broco')"><i class="fas fa-cart-plus"></i> Keranjang</button>
        </div>
      </div>

      <div class="pcard" >
        <div class="pcard-img">
          <img src="https://images.unsplash.com/photo-1530124566582-a618bc2615dc?auto=format&fit=crop&w=400&q=80" alt="Obeng Tekiro">
        </div>
        <div class="pcard-body">
          <div class="pcard-name">Tekiro Set Obeng Plus Minus Magnetik (7 Pcs) JIS</div>
          <div class="pcard-price">Rp85.000</div>
          <div class="pcard-meta"><i class="fas fa-star star"></i> 4.9 <span style="color:var(--ink-3)">|</span> 4,5rb+ terjual</div>
        </div>
        <div class="pcard-footer">
          <button class="btn-cart" onclick="event.stopPropagation();addToCart('Obeng Tekiro')"><i class="fas fa-cart-plus"></i> Keranjang</button>
        </div>
      </div>

      <div class="pcard" >
        <div class="pcard-img">
          <span class="disc-badge">Promo</span>
          <img src="https://images.unsplash.com/photo-1580983546571-0857cb3e86c1?auto=format&fit=crop&w=400&q=80" alt="Multimeter">
        </div>
        <div class="pcard-body">
          <div class="pcard-name">Digital Multimeter Avometer Pengukur Arus Listrik</div>
          <div class="pcard-price">Rp55.000</div>
          <div class="pcard-meta"><i class="fas fa-star star"></i> 4.7 <span style="color:var(--ink-3)">|</span> 500+ terjual</div>
        </div>
        <div class="pcard-footer">
          <button class="btn-cart" onclick="event.stopPropagation();addToCart('Multimeter')"><i class="fas fa-cart-plus"></i> Keranjang</button>
        </div>
      </div>

      <div class="pcard" >
        <div class="pcard-img">
          <img src="https://images.unsplash.com/photo-1572981779307-38b8cabb2407?auto=format&fit=crop&w=400&q=80" alt="Gerinda">
        </div>
        <div class="pcard-body">
          <div class="pcard-name">Maktec MT90 Mesin Gerinda Tangan 4 Inch 540W</div>
          <div class="pcard-price">Rp350.000</div>
          <div class="pcard-meta"><i class="fas fa-star star"></i> 4.9 <span style="color:var(--ink-3)">|</span> 1,2rb+ terjual</div>
        </div>
        <div class="pcard-footer">
          <button class="btn-cart" onclick="event.stopPropagation();addToCart('Gerinda Maktec')"><i class="fas fa-cart-plus"></i> Keranjang</button>
        </div>
      </div>

    </div>
  </section>

</div>
@endsection

@section('scripts')
/* Countdown */
let total = 2 * 3600 + 47 * 60 + 33;
function tick() {
  if (total <= 0) return;
  total--;
  const h = String(Math.floor(total / 3600)).padStart(2, '0');
  const m = String(Math.floor((total % 3600) / 60)).padStart(2, '0');
  const s = String(total % 60).padStart(2, '0');
  const hrEl = document.getElementById('hr');
  const mnEl = document.getElementById('mn');
  const scEl = document.getElementById('sc');
  if(hrEl) hrEl.innerText = h;
  if(mnEl) mnEl.innerText = m;
  if(scEl) scEl.innerText = s;
}
setInterval(tick, 1000);
@endsection
