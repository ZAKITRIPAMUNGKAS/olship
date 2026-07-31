@extends('layouts.app')

@section('title', $category . ' — LISTRINDO JAYA ELEKTRIK')

@push('styles')
<style>
.cat-layout {
  display: grid !important;
  grid-template-columns: 220px 1fr !important;
  gap: 24px; align-items: start;
}
.cat-content { min-width: 0 !important; display: block !important; }
.cat-grid {
  display: grid !important;
  grid-template-columns: repeat(4, 1fr) !important;
  gap: 12px;
}
@media (max-width: 1200px) {
  .cat-grid { grid-template-columns: repeat(3, 1fr) !important; }
}
@media (max-width: 768px) {
  .cat-layout { grid-template-columns: 1fr !important; gap: 12px; }
  .filter-col { position: static !important; display: none !important; }
  .filter-col.open { display: block !important; }
  .filter-toggle-btn { display: flex !important; }
  .cat-grid { grid-template-columns: repeat(2, 1fr) !important; gap: 8px; }
}
</style>
@endpush

@section('content')
<div id="cat-view">

  <nav class="cat-breadcrumb">
    <a href="{{ url('/') }}">Beranda</a>
    <i class="fas fa-chevron-right"></i>
    <span>{{ $category }}</span>
  </nav>

  <button class="filter-toggle-btn" id="filterToggle" onclick="toggleFilter()">
    <i class="fas fa-sliders-h"></i> Filter
  </button>

  <div class="cat-layout">

    <aside class="filter-col" id="filterSidebar">
      <div class="filter-head">
        <div class="filter-h"><i class="fas fa-sliders-h"></i> Filter</div>
        <button class="filter-reset">Reset</button>
      </div>

      <div class="filter-section">
        <div class="filter-section-title">Batas Harga</div>
        <div class="filter-price-row">
          <input type="number" class="filter-price-input" placeholder="Min">
          <span class="filter-price-sep">—</span>
          <input type="number" class="filter-price-input" placeholder="Maks">
        </div>
      </div>

      <div class="filter-section">
        <div class="filter-section-title">Merek</div>
        <label class="filter-check"><input type="checkbox"><span>Bosch</span></label>
        <label class="filter-check"><input type="checkbox"><span>Maktec</span></label>
        <label class="filter-check"><input type="checkbox"><span>Philips</span></label>
        <label class="filter-check"><input type="checkbox"><span>Tekiro</span></label>
        <label class="filter-check"><input type="checkbox"><span>Broco</span></label>
        <a href="#" class="filter-see-all">+ Lihat Semua</a>
      </div>

      <div class="filter-section">
        <div class="filter-section-title">Rating</div>
        <label class="filter-check">
          <input type="checkbox">
          <span><i class="fas fa-star" style="color:#f59e0b;font-size:10px;"></i> 4 ke atas</span>
        </label>
        <label class="filter-check">
          <input type="checkbox">
          <span><i class="fas fa-star" style="color:#f59e0b;font-size:10px;"></i> 3 ke atas</span>
        </label>
      </div>

      <div class="filter-section">
        <div class="filter-section-title">Penawaran</div>
        <label class="filter-check"><input type="checkbox"><span>Diskon Spesial</span></label>
        <label class="filter-check"><input type="checkbox"><span>Bebas Ongkir</span></label>
        <label class="filter-check"><input type="checkbox"><span>Grosir</span></label>
      </div>
    </aside>

    <div class="cat-content">
      <div class="cat-header">
        <div class="cat-header-left">
          <div class="cat-title">{{ $category }}</div>
        </div>
        <div class="cat-header-right">
          <span class="sort-label">Urutkan:</span>
          <select class="sort-select">
            <option>Paling Sesuai</option>
            <option>Terbaru</option>
            <option>Harga Terendah</option>
            <option>Harga Tertinggi</option>
            <option>Ulasan Terbanyak</option>
          </select>
        </div>
      </div>

      <div class="cat-grid">
        <div class="pcard" onclick="window.location.href='{{ url('/product/1') }}'">
          <div class="pcard-img">
            <span class="disc-badge">-18%</span>
            <img src="https://images.unsplash.com/photo-1504148455328-c376907d081c?auto=format&fit=crop&w=400&q=80" alt="Bor" loading="lazy">
          </div>
          <div class="pcard-body">
            <div class="pcard-name">Mesin Bor Tembok Listrik Bosch GSB 550 Professional 13mm</div>
            <div class="pcard-price">Rp 450.000</div>
            <div class="pcard-price-orig">Rp 550.000</div>
            <div class="pcard-meta"><i class="fas fa-star star"></i> 4.8 <span style="color:var(--border)">|</span> Terjual 750+</div>
          </div>
          <div class="pcard-footer">
            <button class="btn-cart" onclick="event.stopPropagation()"><i class="fas fa-cart-plus"></i> Keranjang</button>
          </div>
        </div>

        <div class="pcard" onclick="window.location.href='#'">
          <div class="pcard-img">
            <img src="https://images.unsplash.com/photo-1572981779307-38b8cabb2407?auto=format&fit=crop&w=400&q=80" alt="Gerinda" loading="lazy">
          </div>
          <div class="pcard-body">
            <div class="pcard-name">Maktec MT90 Mesin Gerinda Tangan 4 Inch 540W</div>
            <div class="pcard-price">Rp 350.000</div>
            <div class="pcard-meta"><i class="fas fa-star star"></i> 4.9 <span style="color:var(--border)">|</span> Terjual 1.2rb+</div>
          </div>
          <div class="pcard-footer">
            <button class="btn-cart" onclick="event.stopPropagation()"><i class="fas fa-cart-plus"></i> Keranjang</button>
          </div>
        </div>

        <div class="pcard" onclick="window.location.href='#'">
          <div class="pcard-img">
            <img src="https://images.unsplash.com/photo-1574635677864-4e488102a900?auto=format&fit=crop&w=400&q=80" alt="Impact Wrench" loading="lazy">
          </div>
          <div class="pcard-body">
            <div class="pcard-name">Mesin Buka Baut Impact Wrench Cordless Baterai 20V</div>
            <div class="pcard-price">Rp 650.000</div>
            <div class="pcard-meta"><i class="fas fa-star star"></i> 4.6 <span style="color:var(--border)">|</span> Terjual 300+</div>
          </div>
          <div class="pcard-footer">
            <button class="btn-cart" onclick="event.stopPropagation()"><i class="fas fa-cart-plus"></i> Keranjang</button>
          </div>
        </div>

        <div class="pcard" onclick="window.location.href='#'">
          <div class="pcard-img">
            <img src="https://images.unsplash.com/photo-1508212173163-9ee810df6dd6?auto=format&fit=crop&w=400&q=80" alt="Mata Bor" loading="lazy">
          </div>
          <div class="pcard-body">
            <div class="pcard-name">Set Mata Bor Besi Baja HSS Twist Drill Bit (13 Pcs)</div>
            <div class="pcard-price">Rp 45.000</div>
            <div class="pcard-meta"><i class="fas fa-star star"></i> 4.9 <span style="color:var(--border)">|</span> Terjual 1.5rb+</div>
          </div>
          <div class="pcard-footer">
            <button class="btn-cart" onclick="event.stopPropagation()"><i class="fas fa-cart-plus"></i> Keranjang</button>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>
@endsection

@push('scripts')
<script>
function toggleFilter() {
  const sidebar = document.getElementById('filterSidebar');
  const btn = document.getElementById('filterToggle');
  const isOpen = sidebar.classList.toggle('open');
  btn.innerHTML = isOpen
    ? '<i class="fas fa-times"></i> Tutup Filter'
    : '<i class="fas fa-sliders-h"></i> Filter';
}
</script>
@endpush
