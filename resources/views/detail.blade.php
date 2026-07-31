@extends('layouts.app')

@section('title', 'Mesin Bor Bosch GSB 550 — LISTRINDO JAYA ELEKTRIK')

@section('content')
<div id="detail-view">
  <div class="breadcrumb">
    <a href="{{ url('/') }}">Beranda</a>
    <i class="fas fa-chevron-right"></i>
    <a href="{{ url('/category/Power Tools') }}">Power Tools</a>
    <i class="fas fa-chevron-right"></i>
    <span>Mesin Bor</span>
  </div>
  <div class="detail-layout">
    <!-- Gallery -->
    <div class="detail-gallery">
      <div class="main-img">
        <span class="main-img-badge">-18%</span>
        <img id="mainImg" src="https://images.unsplash.com/photo-1504148455328-c376907d081c?auto=format&fit=crop&w=700&q=80" alt="Bosch GSB 550">
      </div>
      <div class="thumbs">
        <div class="thumb active" onmouseover="changeImg(this,'https://images.unsplash.com/photo-1504148455328-c376907d081c?auto=format&fit=crop&w=700&q=80')">
          <img src="https://images.unsplash.com/photo-1504148455328-c376907d081c?auto=format&fit=crop&w=140&q=80" alt="">
        </div>
        <div class="thumb" onmouseover="changeImg(this,'https://images.unsplash.com/photo-1581092160562-40aa08e78837?auto=format&fit=crop&w=700&q=80')">
          <img src="https://images.unsplash.com/photo-1581092160562-40aa08e78837?auto=format&fit=crop&w=140&q=80" alt="">
        </div>
      </div>
    </div>

    <!-- Info -->
    <div class="detail-info">
      <h1 class="detail-title">Mesin Bor Tembok Listrik Bosch GSB 550 Professional 13mm Original Garansi Resmi</h1>
      <div class="detail-rating">
        <span><i class="fas fa-star" style="color:#F5C518;margin-right:4px;"></i>4.8 (320 rating)</span>
        <span>•</span>
        <span>750+ Terjual</span>
        <span>•</span>
        <span>Diskusi (45)</span>
      </div>

      <div class="detail-price-block">
        <div class="detail-price">Rp450.000</div>
        <div class="detail-orig">
          Rp550.000
          <span class="detail-orig-disc">Hemat 18%</span>
        </div>
      </div>

      <div class="trust-box">
        <div class="trust-title">Keuntungan Beli di LISTRINDO JAYA ELEKTRIK</div>
        <div class="trust-grid">
          <div class="trust-item"><div class="trust-icon ti-gold"><i class="fas fa-medal"></i></div> 100% Produk Asli</div>
          <div class="trust-item"><div class="trust-icon ti-green"><i class="fas fa-shield-alt"></i></div> Garansi Resmi</div>
          <div class="trust-item"><div class="trust-icon ti-blue"><i class="fas fa-box"></i></div> Packing Aman</div>
          <div class="trust-item"><div class="trust-icon ti-gray"><i class="fas fa-undo"></i></div> Retur 7 Hari</div>
        </div>
      </div>

      <div class="tab-row">
        <div class="tab active">Detail Produk</div>
        <div class="tab">Spesifikasi</div>
        <div class="tab">Ulasan (320)</div>
      </div>
      <div class="tab-body">
        Kondisi: <b>Baru</b> &nbsp;|&nbsp; Min. Pemesanan: <b>1 Buah</b><br><br>
        Bosch GSB 550 Professional adalah mesin bor impact bertenaga 550 Watt yang andal dan ringkas. Alat yang tepat untuk tukang profesional maupun perbaikan rumahan.<br><br>
        <b>Keunggulan Produk:</b><br>
        — Motor 550 W yang bertenaga and efisien.<br>
        — Chuck berkunci 13 mm, gagang tambahan & depth stop.<br>
        — Konstruksi bantalan peluru penuh untuk usia pakai lama.<br>
        — Putaran bolak-balik (Reversible).<br><br>
        <i>Barang dijamin 100% Original & Bergaransi Resmi Bosch 1 Tahun.</i>
      </div>
    </div>

    <!-- Action Card -->
    <div class="action-card">
      <div class="action-label">Atur Jumlah</div>
      <div class="qty-row">
        <div class="qty-ctrl">
          <button class="qty-btn" onclick="chgQty(-1)">−</button>
          <input class="qty-in" id="dQty" value="1" readonly>
          <button class="qty-btn" onclick="chgQty(1)">+</button>
        </div>
        <div class="qty-stock">Stok: <span>124 tersedia</span></div>
      </div>
      <div class="subtotal-row">
        <span class="subtotal-lbl">Subtotal</span>
        <span class="subtotal-val" id="dSubtotal">Rp450.000</span>
      </div>
      <button class="btn-detail-cart" onclick="addDetailToCart()"><i class="fas fa-cart-plus"></i> Tambah Keranjang</button>
      <button class="btn-detail-buy" onclick="addDetailToCart(); setTimeout(()=>alert('Mengarahkan ke Checkout...'),400)"><i class="fas fa-bolt"></i> Beli Sekarang</button>
      <div class="action-secure"><i class="fas fa-lock"></i> Pembayaran 100% Aman & Terenkripsi</div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
function changeImg(el, src) {
  document.getElementById('mainImg').src = src;
  document.querySelectorAll('.thumb').forEach(t => t.classList.remove('active'));
  el.classList.add('active');
}

const basePrice = 450000;
function chgQty(d) {
  const inp = document.getElementById('dQty');
  if(!inp) return;
  let v = parseInt(inp.value) + d;
  if (v < 1) v = 1; if (v > 124) v = 124;
  inp.value = v;
  const subtotal = document.getElementById('dSubtotal');
  if(subtotal) subtotal.innerText = 'Rp' + (v * basePrice).toLocaleString('id-ID');
}
function addDetailToCart() {
  const qty = document.getElementById('dQty');
  addToCart('Bor Bosch GSB 550 (' + (qty ? qty.value : 1) + ' pcs)');
}
@endsection
