@extends('layouts.app')

@section('title', $product->name . ' - LISTRINDO JAYA ELEKTRIK')

@push('styles')
@vite(['resources/css/components/product-detail.css'])
<script type="application/ld+json">
{
  "@@context": "https://schema.org/",
  "@@type": "Product",
  "name": "{{ $product->name }}",
  "description": "{{ strip_tags($product->short_description ?? $product->name) }}",
  "brand": { "@@type": "Brand", "name": "{{ $product->brand?->name ?? 'LISTRINDO JAYA ELEKTRIK' }}" },
  "offers": {
    "@@type": "Offer",
    "price": "{{ $product->price }}",
    "priceCurrency": "IDR",
    "availability": "{{ $product->stock > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock' }}"
  },
  "aggregateRating": {
    "@@type": "AggregateRating",
    "ratingValue": "{{ $product->rating_avg ?? 0 }}",
    "reviewCount": "{{ $product->rating_count ?? 0 }}"
  }
}
</script>
@endpush



@section('content')
  <!-- BREADCRUMB -->
  <nav class="breadcrumb" aria-label="Navigasi lokasi">
    <a href="{{ route('home') }}">Home</a><span class="sep"><i class="fas fa-chevron-right"></i></span>
    <a href="#">{{ $product->category?->name ?? 'Uncategorized' }}</a><span class="sep"><i class="fas fa-chevron-right"></i></span>
    <span>{{ $product->name }}</span>
  </nav>

  <div class="product-layout" x-data="{ 
    price: {{ $product->price }}, 
    maxStock: {{ $product->stock }}, 
    qty: 1, 
    currentImage: '{{ $product->primaryImage ? asset('storage/'.$product->primaryImage->image_path) : 'https://placehold.co/800x800?text='.urlencode($product->name) }}',
    noteOpen: false,
    isWishlist: {{ $isInWishlist ? 'true' : 'false' }},
    variants: @json($variantMap),
    attributes: @json($attributes),
    selectedOptions: {},
    selectedVariant: null,
    lightboxOpen: false,

    init() {
        // Initialize selectedOptions if only 1 option exists for some attributes
        Object.keys(this.attributes).forEach(attr => {
            if (this.attributes[attr].length === 1) {
                this.selectedOptions[attr] = this.attributes[attr][0];
            }
        });
        this.checkVariant();
    },

    selectOption(attr, val) {
        this.selectedOptions[attr] = val;
        this.checkVariant();
    },

    checkVariant() {
        if (Object.keys(this.selectedOptions).length === Object.keys(this.attributes).length) {
            const selectedStr = Object.values(this.selectedOptions).sort().join(',');
            this.selectedVariant = this.variants.find(v => {
                const vStr = Object.values(v.options).sort().join(',');
                return vStr === selectedStr;
            });

            if (this.selectedVariant) {
                this.price = this.selectedVariant.price;
                this.maxStock = this.selectedVariant.stock;
                if (this.qty > this.maxStock) this.qty = this.maxStock;
            }
        } else {
            this.selectedVariant = null;
            this.price = {{ $product->price }};
            this.maxStock = {{ $product->stock }};
        }
    },

    inc() { if (this.qty < this.maxStock) this.qty++; },
    dec() { if (this.qty > 1) this.qty--; },
    updateQty(val) { this.qty = Math.max(1, Math.min(this.maxStock, parseInt(val) || 1)); },
    formatPrice(n) { return 'Rp ' + (n * this.qty).toLocaleString('id-ID').replace(/,/g, '.'); },
    setMainImage(src) { this.currentImage = src; },
    toggleWishlist() {
        if (!{{ auth()->check() ? 'true' : 'false' }}) {
            window.location.href = '{{ route('login') }}';
            return;
        }
        
        fetch('{{ route('dashboard.wishlist.toggle') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ product_id: {{ $product->id }} })
        })
        .then(response => response.json())
        .then(data => {
            this.isWishlist = (data.status === 'added');
            if (typeof showToast === 'function') {
                showToast(data.message, 'success');
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Gagal memproses wishlist.');
        });
    }
  }">

    <!-- 1. GALLERY -->
    <aside class="product-gallery">
      <div class="gallery-main" x-on:click="lightboxOpen = true" role="button" aria-label="Perbesar gambar" tabindex="0">
        <img id="productMainImage"
          src="{{ $product->primaryImage ? asset('storage/'.$product->primaryImage->image_path) : 'https://placehold.co/800x800?text='.urlencode($product->name) }}"
          :src="currentImage"
          alt="{{ $product->name }}"
          loading="lazy">
        <span class="gallery-zoom-hint" aria-hidden="true"><i class="fas fa-search-plus"></i> Perbesar</span>
      </div>
      @if($product->images && $product->images->count() > 0)
      <div class="thumb-track" role="list" aria-label="Thumbnail produk">
        @foreach($product->images as $img)
        <div class="thumb" :class="currentImage === '{{ asset('storage/'.$img->image_path) }}' ? 'active' : ''" 
          role="listitem" tabindex="0"
          x-on:click="setMainImage('{{ asset('storage/'.$img->image_path) }}')"
          x-on:keydown.enter="setMainImage('{{ asset('storage/'.$img->image_path) }}')">
          <img src="{{ asset('storage/'.$img->image_path) }}" alt="{{ $product->name }}" loading="lazy">
        </div>
        @endforeach
      </div>
      @endif
      <div class="gallery-badges">
        <span class="g-badge g-badge-orig"><i class="fas fa-check-circle"></i> 100% Original</span>
        <span class="g-badge g-badge-garansi"><i class="fas fa-shield-alt"></i> Garansi Resmi</span>
      </div>

    </aside>

    <!-- LIGHTBOX (outside grid, at product-layout level) -->
    <div class="lightbox" :class="lightboxOpen ? 'open' : ''" 
         role="dialog" aria-modal="true" aria-label="Tampilan gambar besar" 
         x-on:click.self="lightboxOpen = false"
         x-on:keydown.escape.window="lightboxOpen = false">
      <button class="lb-close" x-on:click="lightboxOpen = false" aria-label="Tutup">&times;</button>
      <img :src="currentImage" alt="{{ $product->name }}">
    </div>
    <!-- 2. PRODUCT INFO -->
    <section class="product-info" aria-label="Informasi produk">
      <div class="p-sold"><i class="fas fa-fire" style="color:var(--star)"></i> Terjual {{ $product->total_sold ?? '0' }}+ unit</div>
      <h1 class="p-title">{{ $product->name }}</h1>

      <div class="p-rating">
        <div class="stars" role="img" aria-label="Rating {{ $product->rating_avg }} dari 5 bintang">
          @for($i=1; $i<=5; $i++)
            @if($i <= round($product->rating_avg ?? 0))
              <i class="fas fa-star"></i>
            @else
              <i class="fas fa-star empty"></i>
            @endif
          @endfor
        </div>
        <span class="rating-score">{{ number_format($product->rating_avg ?? 0, 1) }}</span>
        <span class="rating-sep">•</span>
        <a href="#reviews" class="rating-count">{{ $product->rating_count ?? 0 }} ulasan</a>
        <span class="rating-sep">•</span>
        <span class="rating-sold">Diskusi ({{ $product->discussions->count() }})</span>
      </div>

      <!-- price area -->
      <div class="p-price-area">
        @if($product->discount_percentage > 0)
        <div class="p-discount-row">
          <span class="badge-disc">HEMAT {{ $product->discount_percentage }}%</span>
          <span class="p-orig">Rp {{ number_format($product->compare_price, 0, ',', '.') }}</span>
        </div>
        @endif
        <div class="p-price">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
        @if($product->compare_price > $product->price)
        <div class="p-saving"><i class="fas fa-tag"></i> Hemat Rp {{ number_format($product->compare_price - $product->price, 0, ',', '.') }} dari harga normal</div>
        @endif
      </div>

      <!-- specs -->
      <div class="p-specs">
        <div class="spec-item">
          <div class="spec-lbl">Kondisi</div>
          <div class="spec-val">Baru</div>
        </div>
        <div class="spec-item">
          <div class="spec-lbl">Min. Pesan</div>
          <div class="spec-val">1 Buah</div>
        </div>
        <div class="spec-item">
          <div class="spec-lbl">Kategori</div>
          <div class="spec-val link">{{ $product->category?->name ?? 'Uncategorized' }}</div>
        </div>
        <div class="spec-item">
          <div class="spec-lbl">Berat</div>
          <div class="spec-val">{{ $product->weight_display }}</div>
        </div>
      </div>

      <!-- Variants Selection -->
      @if(count($attributes) > 0)
      <div class="p-variants" style="margin-top: 24px; padding-top: 24px; border-top: 1px solid var(--line);">
        @foreach($attributes as $attrName => $values)
        <div style="margin-bottom: 16px;">
          <div style="font-size: 13px; font-weight: 700; color: var(--ink-1); margin-bottom: 8px;">Pilih {{ $attrName }}:</div>
          <div style="display: flex; gap: 8px; flex-wrap: wrap;">
            @foreach($values as $val)
            <button type="button" 
              class="variant-chip"
              :class="selectedOptions['{{ $attrName }}'] === '{{ $val }}' ? 'active' : ''"
              @click="selectOption('{{ $attrName }}', '{{ $val }}')"
            >
              {{ $val }}
            </button>
            @endforeach
          </div>
        </div>
        @endforeach
      </div>
      @endif

      <!-- store -->
      <div class="store-card">
        <div class="store-avatar"><i class="fas fa-store"></i></div>
        <div class="store-info">
          <div class="store-name">
            {{ $product->seller?->store?->name ?? 'LISTRINDO JAYA ELEKTRIK Official Store' }}
            <i class="fas fa-check-circle verified" aria-label="Toko terverifikasi"></i>
          </div>
          <div class="store-meta">
            <span class="dot-online" aria-hidden="true"></span>
            Online 5 menit lalu &nbsp;·&nbsp; Kota Tangerang
          </div>
        </div>
        <button class="store-follow" x-on:click="toggleWishlist" :class="isWishlist ? 'following' : ''" x-text="isWishlist ? 'Following' : 'Follow'">Follow</button>
      </div>

      <!-- promos -->
      <div class="promo-list" aria-label="Keuntungan berbelanja">
        <div class="promo-item">
          <div class="promo-icon-wrap green"><i class="fas fa-medal"></i></div>
          <div class="promo-text"><strong>Garansi Resmi 1 Tahun.</strong> Barang 100% Original dari Official Store.</div>
        </div>
        <div class="promo-item">
          <div class="promo-icon-wrap blue"><i class="fas fa-truck"></i></div>
          <div class="promo-text"><strong>Gratis Ongkir.</strong> Estimasi tiba besok.</div>
        </div>
        <div class="promo-item">
          <div class="promo-icon-wrap orange"><i class="fas fa-undo"></i></div>
          <div class="promo-text"><strong>Retur Mudah 7 Hari.</strong> Barang rusak atau tidak sesuai? Kami tanggung sepenuhnya.</div>
        </div>
      </div>

      <!-- tabs -->
      <div x-data="tabs('detail')">
        <div class="tab-nav" role="tablist" aria-label="Detail produk">
            <button class="tab-btn" :class="activeTab === 'detail' ? 'active' : ''" x-on:click="setTab('detail')" role="tab">Deskripsi</button>
            <button class="tab-btn" :class="activeTab === 'spec' ? 'active' : ''" x-on:click="setTab('spec')" role="tab">Spesifikasi</button>
            <button class="tab-btn" :class="activeTab === 'info' ? 'active' : ''" x-on:click="setTab('info')" role="tab">Info Penting</button>
            <button class="tab-btn" :class="activeTab === 'discussion' ? 'active' : ''" x-on:click="setTab('discussion')" role="tab">Diskusi ({{ $product->discussions->count() }})</button>
        </div>

        <div x-show="activeTab === 'detail'" class="tab-panel" :class="activeTab === 'detail' ? 'active' : ''" role="tabpanel">
            @if($product->description)
                {!! $product->description !!}
            @else
                <p>Belum ada deskripsi untuk produk ini.</p>
            @endif
        </div>

        <div x-show="activeTab === 'spec'" class="tab-panel" :class="activeTab === 'spec' ? 'active' : ''" role="tabpanel">
            <table class="spec-table">
            <tbody>
                @if($product->productAttributes && $product->productAttributes->count() > 0)
                    @foreach($product->productAttributes as $attr)
                    <tr><td>{{ $attr->attribute_name }}</td><td>{{ $attr->attribute_value }}</td></tr>
                    @endforeach
                @else
                    <tr><td colspan="2">Belum ada spesifikasi tambahan.</td></tr>
                @endif
            </tbody>
            </table>
        </div>

        <div x-show="activeTab === 'info'" class="tab-panel" :class="activeTab === 'info' ? 'active' : ''" role="tabpanel">
            <div style="display:flex;flex-direction:column;gap:10px;font-size:13px;color:#374151;">
            <div style="padding:12px;background:#fff3e0;border-radius:6px;border-left:3px solid #f97316;">
                <strong style="color:#111827;">Perhatian keamanan:</strong> Selalu periksa produk saat barang datang.
            </div>
            <div style="padding:12px;background:#e8f1fd;border-radius:6px;border-left:3px solid #025cca;">
                <strong style="color:#111827;">Pengiriman:</strong> Dikemas dengan bubble wrap double layer + kardus tebal.
            </div>
            </div>
        </div>

        <div x-show="activeTab === 'discussion'" class="tab-panel" :class="activeTab === 'discussion' ? 'active' : ''" role="tabpanel">
            @auth
                <form action="{{ route('dashboard.products.discussions.store', $product) }}" method="POST" style="margin-bottom: 30px;">
                    @csrf
                    <div style="margin-bottom: 12px;">
                        <textarea name="message" class="form-control" rows="3" placeholder="Apa yang ingin Anda tanyakan mengenai produk ini?" required style="border-radius: 12px; padding: 15px; font-size: 14px; border: 1px solid var(--line); width: 100%;"></textarea>
                    </div>
                    <div style="display: flex; justify-content: flex-end;">
                        <button type="submit" class="btn btn-primary" style="padding: 10px 24px; font-size: 14px;">Kirim Pertanyaan</button>
                    </div>
                </form>
            @else
                <div style="padding: 20px; background: var(--bg-1); border-radius: 12px; text-align: center; margin-bottom: 30px; border: 1px dashed var(--line);">
                    <p style="font-size: 14px; color: var(--ink-3); margin-bottom: 10px;">Punya pertanyaan? Masuk untuk berdiskusi dengan kami.</p>
                    <a href="{{ route('login') }}" class="btn btn-outline btn-sm">Masuk</a>
                </div>
            @endauth

            <div class="discussion-list">
                @forelse($product->discussions as $discussion)
                    <div class="discussion-item" style="margin-bottom: 24px; padding-bottom: 20px; border-bottom: 1px solid var(--line);">
                        <div style="display: flex; gap: 15px;">
                            <div style="width: 40px; height: 40px; border-radius: 50%; background: var(--slate-100); flex-shrink: 0; display: flex; align-items: center; justify-content: center; font-weight: 700; color: var(--primary);">
                                {{ substr($discussion->user->name, 0, 1) }}
                            </div>
                            <div style="flex: 1;">
                                <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 5px;">
                                    <span style="font-weight: 700; font-size: 14px; color: var(--ink-1);">{{ $discussion->user->name }}</span>
                                    <span style="font-size: 12px; color: var(--ink-3);">{{ $discussion->created_at->diffForHumans() }}</span>
                                </div>
                                <p style="font-size: 14px; color: var(--ink-2); line-height: 1.5;">{{ $discussion->message }}</p>
                                
                                @foreach($discussion->replies as $reply)
                                    <div style="margin-top: 15px; padding: 15px; background: var(--bg-1); border-radius: 12px; border-left: 3px solid var(--primary);">
                                        <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 5px;">
                                            <span style="font-weight: 700; font-size: 13px; color: var(--primary);">{{ $reply->is_admin_reply ? 'Admin LISTRINDO' : $reply->user->name }}</span>
                                            <span style="font-size: 11px; color: var(--ink-3);">{{ $reply->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p style="font-size: 13px; color: var(--ink-2); line-height: 1.4; margin: 0;">{{ $reply->message }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @empty
                    <div style="text-align: center; padding: 40px; color: var(--ink-3);">
                        <i class="far fa-comments" style="font-size: 48px; margin-bottom: 15px; opacity: 0.2;"></i>
                        <p>Belum ada diskusi untuk produk ini.</p>
                    </div>
                @endforelse
            </div>
        </div>
      </div>
    </section>

    <!-- 3. ACTION CARD -->
    <aside class="product-action" aria-label="Beli produk">
      <form action="{{ route('cart.add') }}" method="POST" id="addToCartForm">
        @csrf
        <input type="hidden" name="product_id" value="{{ $product->id }}">
        <input type="hidden" name="options" :value="JSON.stringify(selectedOptions)">
        <input type="hidden" name="buy_now" id="buyNowFlag" value="0">
        
        <div class="action-card">
            @if($product->discount_percentage > 0)
            <div class="action-head">
            <div class="action-price" x-text="formatPrice(price)">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
            <div class="action-orig">
                <span class="action-disc">-{{ $product->discount_percentage }}%</span>
                <span>Rp {{ number_format($product->compare_price, 0, ',', '.') }}</span>
            </div>
            </div>
            @else
            <div class="action-head">
            <div class="action-price" x-text="formatPrice(price)">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
            </div>
            @endif
            <div class="action-body">
             <div class="qty-label">Jumlah</div>
             <div class="qty-row">
                 <div class="qty-ctrl" role="group" aria-label="Pilih jumlah">
                 <button type="button" class="qty-btn" x-on:click="dec" :disabled="qty <= 1 || maxStock <= 0" aria-label="Kurangi jumlah">-</button>
                 <input
                     type="number"
                     name="quantity"
                     class="qty-input"
                     x-model="qty"
                     x-on:input="updateQty($event.target.value)"
                     :disabled="maxStock <= 0"
                     aria-label="Jumlah produk">
                 <button type="button" class="qty-btn" x-on:click="inc" :disabled="qty >= maxStock || maxStock <= 0" aria-label="Tambah jumlah">+</button>
                 </div>
                 <div class="qty-stock" :class="maxStock <= 0 ? 'low' : (maxStock - qty <= 10 ? 'low' : '')" style="display: flex; align-items: center; gap: 4px;">
                     <template x-if="maxStock > 0">
                         <span>Stok: <strong x-text="maxStock"></strong> unit</span>
                     </template>
                     <template x-if="maxStock <= 0">
                         <span style="color: #ef4444; font-weight: bold; display: inline-flex; align-items: center; gap: 4px;"><i class="fas fa-times-circle"></i> Stok Habis</span>
                     </template>
                 </div>
             </div>
 
             <button type="button" class="note-toggle" x-on:click="noteOpen = !noteOpen" :aria-expanded="noteOpen" :disabled="maxStock <= 0">
                 <i class="fas fa-pencil-alt"></i> Tambah Catatan untuk Penjual
             </button>
             <div class="note-area" :class="noteOpen ? 'open' : ''">
                 <textarea name="note" rows="3" placeholder="Misal: warna, ukuran, atau permintaan khusus..." aria-label="Catatan untuk penjual"></textarea>
             </div>
 
             <div class="subtotal-row">
                 <span class="subtotal-lbl">Subtotal</span>
                 <span class="subtotal-val" x-text="formatPrice(price)">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
             </div>
 
             <button type="submit" class="btn-buy" aria-label="Beli sekarang"
                 :disabled="maxStock <= 0"
                 x-on:click="document.getElementById('buyNowFlag').value = '1'"
                 :style="maxStock <= 0 ? 'background: #9ca3af; cursor: not-allowed; opacity: 0.6;' : ''">
                 <i class="fas fa-bolt"></i> <span x-text="maxStock > 0 ? 'Beli Langsung' : 'Stok Habis'"></span>
             </button>
             <button type="submit" class="btn-cart" aria-label="Masukkan ke keranjang"
                 :disabled="maxStock <= 0"
                 x-on:click="document.getElementById('buyNowFlag').value = '0'"
                 :style="maxStock <= 0 ? 'background: #e5e7eb; color: #9ca3af; border-color: #d1d5db; cursor: not-allowed; opacity: 0.6;' : ''">
                 <i class="fas fa-shopping-cart"></i> <span x-text="maxStock > 0 ? '+ Keranjang' : 'Stok Habis'"></span>
             </button>
 
             <div class="action-links">
                 <div class="action-link" role="button" tabindex="0" aria-label="Chat dengan penjual">
                 <i class="far fa-comment-dots"></i><span>Chat</span>
                 </div>
                 <div class="action-link" role="button" tabindex="0" x-on:click="toggleWishlist" aria-label="Tambah ke wishlist" :aria-pressed="isWishlist">
                 <i class="far fa-heart" :class="isWishlist ? 'fas' : 'far'" :style="isWishlist ? 'color:#ef4444' : ''"></i><span>Wishlist</span>
                 </div>
                 <div class="action-link" role="button" tabindex="0" aria-label="Bagikan produk">
                 <i class="fas fa-share-alt"></i><span>Bagikan</span>
                 </div>
             </div>
             </div>
             <div class="action-guarantees" aria-label="Jaminan belanja">
             <div class="guarantee-item"><i class="fas fa-shield-alt"></i> Pembayaran 100% aman &amp; terenkripsi</div>
             <div class="guarantee-item"><i class="fas fa-undo"></i> Retur mudah dalam 7 hari</div>
             <div class="guarantee-item"><i class="fas fa-headset"></i> CS siap bantu 7x24 jam</div>
             </div>
         </div>
       </form>
     </aside>
 
   </div><!-- end product-layout -->

  <!-- 4. REVIEWS SECTION -->
  <section id="reviews" class="section reviews-section" style="margin-top: 48px;">
    <div class="section-hdr">
      <h2 class="section-title">Ulasan Pembeli <span>({{ $product->reviews->count() }})</span></h2>
    </div>

    <div class="reviews-layout" style="display: grid; grid-template-columns: 300px 1fr; gap: 40px; margin-top: 24px;">
      <!-- Summary -->
      <div class="reviews-summary" style="background: var(--bg-1); padding: 24px; border-radius: 12px; border: 1px solid var(--line); height: fit-content;">
        <div style="font-size: 14px; color: var(--ink-3); margin-bottom: 8px;">Rating Keseluruhan</div>
        <div style="display: flex; align-items: baseline; gap: 10px;">
          <div style="font-size: 48px; font-weight: 800; color: var(--ink-1);">{{ number_format($product->rating_avg, 1) }}</div>
          <div style="color: var(--ink-3);">/ 5.0</div>
        </div>
        <div class="stars" style="color: var(--star); font-size: 18px; margin: 12px 0;">
          @for($i=1; $i<=5; $i++)
            @if($i <= round($product->rating_avg))
              <i class="fas fa-star"></i>
            @else
              <i class="fas fa-star empty" style="color: var(--line);"></i>
            @endif
          @endfor
        </div>
        <div style="font-size: 14px; color: var(--ink-3);">{{ $product->rating_count }} ulasan terverifikasi</div>
      </div>

      <!-- List -->
      <div class="reviews-list">
        @forelse($product->reviews->where('status', 'approved') as $review)
        <div class="review-item" style="padding: 24px 0; border-bottom: 1px solid var(--line);">
          <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
            <div style="display: flex; align-items: center; gap: 12px;">
              <div style="width: 40px; height: 40px; background: var(--blue-pale); color: var(--blue); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700;">
                {{ substr($review->user->name, 0, 1) }}
              </div>
              <div>
                <div style="font-weight: 700; color: var(--ink-1);">{{ $review->user->name }}</div>
                <div style="font-size: 12px; color: var(--ink-3);">{{ $review->created_at->diffForHumans() }}</div>
              </div>
            </div>
            <div style="color: var(--star); font-size: 14px;">
              @for($i=1; $i<=5; $i++)
                <i class="{{ $i <= $review->rating ? 'fas' : 'far' }} fa-star"></i>
              @endfor
            </div>
          </div>
          <div style="font-weight: 600; color: var(--ink-1); margin-bottom: 8px;">{{ $review->title }}</div>
          <p style="color: var(--ink-2); line-height: 1.6; font-size: 14px;">{{ $review->comment }}</p>
          @if($review->seller_reply)
          <div style="margin-top: 16px; padding: 16px; background: var(--bg-2); border-radius: 8px; border-left: 3px solid var(--blue);">
            <div style="font-weight: 700; font-size: 12px; color: var(--blue); margin-bottom: 4px;">Respon Penjual:</div>
            <p style="font-size: 13px; color: var(--ink-2); margin: 0;">{{ $review->seller_reply }}</p>
          </div>
          @endif
        </div>
        @empty
        <div style="padding: 48px; text-align: center; color: var(--ink-3);">
          <i class="far fa-comment-dots" style="font-size: 48px; margin-bottom: 16px; opacity: 0.3;"></i>
          <p>Belum ada ulasan untuk produk ini.</p>
        </div>
        @endforelse
      </div>
    </div>
  </section>

   <!-- MOBILE BOTTOM BAR -->
   <div class="mobile-bar" role="complementary" aria-label="Tombol beli cepat">
     <div class="mobile-bar-price">
       <div class="lbl">Harga</div>
       <div class="val">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
     </div>
     <div class="mobile-bar-btns">
       <button type="button" class="mb-cart"
         :disabled="maxStock <= 0"
         :style="maxStock <= 0 ? 'background: #9ca3af; cursor: not-allowed; opacity: 0.6;' : ''"
         x-on:click="document.getElementById('buyNowFlag').value = '0'; document.getElementById('addToCartForm').submit()"
         aria-label="Keranjang"><span x-text="maxStock > 0 ? 'Keranjang' : 'Stok Habis'"></span></button>
       <button type="button" class="mb-buy"
         :disabled="maxStock <= 0"
         :style="maxStock <= 0 ? 'background: #9ca3af; cursor: not-allowed; opacity: 0.6;' : ''"
         x-on:click="document.getElementById('buyNowFlag').value = '1'; document.getElementById('addToCartForm').submit()"
         aria-label="Beli langsung"><span x-text="maxStock > 0 ? 'Beli Langsung' : 'Stok Habis'"></span></button>
     </div>
   </div>
   </div><!-- end product-layout -->
@endsection

@push('scripts')
{{-- productDetail logic is now in head --}}
@endpush
