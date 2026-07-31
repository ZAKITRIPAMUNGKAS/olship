@extends('layouts.app')

@section('title', $category->name . ' - LISTRINDO JAYA ELEKTRIK')

@push('styles')
<style>
/* Modern Category View Styling */
.cat-container {
    padding: 24px 0 48px;
}
.cat-breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    color: #64748b;
    margin-bottom: 20px;
    font-weight: 500;
}
.cat-breadcrumb a { color: var(--brand); transition: color 0.15s; }
.cat-breadcrumb a:hover { color: var(--brand-dk); text-decoration: underline; }
.cat-breadcrumb i { font-size: 10px; opacity: 0.5; }

.cat-layout {
    display: grid;
    grid-template-columns: 240px 1fr;
    gap: 28px;
    align-items: start;
}

/* Sidebar Filter */
.filter-card {
    background: #ffffff;
    border: 1px solid var(--border);
    border-radius: 14px;
    padding: 20px;
    position: sticky;
    top: 84px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.03);
}
.filter-card-head {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 18px;
    padding-bottom: 14px;
    border-bottom: 1px solid var(--border);
}
.filter-card-title {
    font-size: 15px;
    font-weight: 700;
    color: var(--ink);
    display: flex;
    align-items: center;
    gap: 8px;
}
.filter-card-title i { color: var(--brand); font-size: 14px; }
.filter-reset-btn {
    font-size: 12px;
    font-weight: 600;
    color: var(--brand);
    border: none;
    background: none;
    cursor: pointer;
    transition: opacity 0.15s;
}
.filter-reset-btn:hover { opacity: 0.8; text-decoration: underline; }

.filter-group {
    margin-bottom: 20px;
    padding-bottom: 20px;
    border-bottom: 1px solid var(--border);
}
.filter-group:last-child {
    margin-bottom: 0;
    padding-bottom: 0;
    border-bottom: none;
}
.filter-group-title {
    font-size: 11px;
    font-weight: 800;
    color: #64748b;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    margin-bottom: 12px;
}
.price-input-row {
    display: flex;
    align-items: center;
    gap: 8px;
}
.price-field {
    flex: 1;
    min-width: 0;
    position: relative;
}
.price-field input {
    width: 100%;
    padding: 8px 10px 8px 24px;
    background: #f8fafc;
    border: 1px solid var(--border);
    border-radius: 8px;
    font-size: 12px;
    color: var(--ink);
    outline: none;
    font-family: inherit;
    transition: all 0.15s;
}
.price-field input:focus { border-color: var(--brand); background: #ffffff; }
.price-prefix {
    position: absolute;
    left: 8px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 11px;
    font-weight: 600;
    color: #94a3b8;
}

.check-item {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 8px;
    cursor: pointer;
    font-size: 13px;
    color: #334155;
    transition: color 0.15s;
}
.check-item:hover { color: var(--brand); }
.check-item input[type="checkbox"] {
    accent-color: var(--brand);
    width: 16px;
    height: 16px;
    border-radius: 4px;
    cursor: pointer;
}

/* Category Content Header */
.cat-top-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #ffffff;
    border: 1px solid var(--border);
    border-radius: 14px;
    padding: 14px 20px;
    margin-bottom: 20px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.02);
}
.cat-title-text {
    font-size: 18px;
    font-weight: 800;
    color: var(--ink);
    display: flex;
    align-items: center;
    gap: 10px;
}
.cat-count-badge {
    font-size: 12px;
    font-weight: 600;
    color: var(--brand);
    background: var(--brand-lt);
    padding: 3px 10px;
    border-radius: 99px;
}
.sort-wrap {
    display: flex;
    align-items: center;
    gap: 8px;
}
.sort-select-box {
    padding: 8px 12px;
    border: 1px solid var(--border);
    border-radius: 8px;
    font-size: 12.5px;
    font-weight: 600;
    color: #334155;
    background: #f8fafc;
    outline: none;
    cursor: pointer;
    transition: all 0.15s;
}
.sort-select-box:focus { border-color: var(--brand); background: #ffffff; }

/* Grid Responsive */
.pgrid-4 {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
}
@media (min-width: 1280px) {
    .pgrid-4 { grid-template-columns: repeat(4, 1fr); }
}
@media (max-width: 900px) {
    .cat-layout { grid-template-columns: 1fr; }
    .filter-card { display: none; }
    .filter-card.open { display: block; }
    .pgrid-4 { grid-template-columns: repeat(2, 1fr); gap: 12px; }
}
</style>
@endpush

@section('content')
<div class="cat-container">

    {{-- Breadcrumb --}}
    <nav class="cat-breadcrumb">
        <a href="{{ route('home') }}">Beranda</a>
        <i class="fas fa-chevron-right"></i>
        <span style="color: var(--ink); font-weight: 700;">{{ $category->name }}</span>
    </nav>

    <form action="{{ route('categories.show', $category->slug) }}" method="GET" id="filterForm">
    <div class="cat-layout">

        {{-- SIDEBAR FILTER --}}
        <aside class="filter-card" id="filterSidebar">
            <div class="filter-card-head">
                <div class="filter-card-title">
                    <i class="fas fa-sliders-h"></i> Filter Produk
                </div>
                <a href="{{ route('categories.show', $category->slug) }}" class="filter-reset-btn">Reset</a>
            </div>

            {{-- Rentang Harga --}}
            <div class="filter-group">
                <div class="filter-group-title">Rentang Harga</div>
                <div class="price-input-row">
                    <div class="price-field">
                        <span class="price-prefix">Rp</span>
                        <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min" onchange="document.getElementById('filterForm').submit()">
                    </div>
                    <span style="color: #94a3b8; font-weight: 600;">-</span>
                    <div class="price-field">
                        <span class="price-prefix">Rp</span>
                        <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Maks" onchange="document.getElementById('filterForm').submit()">
                    </div>
                </div>
            </div>

            {{-- Merek / Brand --}}
            <div class="filter-group">
                <div class="filter-group-title">Merek</div>
                @foreach($brands ?? [] as $brand)
                <label class="check-item">
                    <input type="checkbox" name="brands[]" value="{{ $brand->id }}" 
                           {{ is_array(request('brands')) && in_array($brand->id, request('brands')) ? 'checked' : '' }}
                           onchange="document.getElementById('filterForm').submit()"> 
                    <span>{{ $brand->name }}</span>
                </label>
                @endforeach
            </div>

            {{-- Rating --}}
            <div class="filter-group">
                <div class="filter-group-title">Rating Pembeli</div>
                <label class="check-item">
                    <input type="radio" name="min_rating" value="4" {{ request('min_rating') == '4' ? 'checked' : '' }} onchange="document.getElementById('filterForm').submit()"> 
                    <span><i class="fas fa-star star"></i> 4.0 Ke Atas</span>
                </label>
                <label class="check-item">
                    <input type="radio" name="min_rating" value="3" {{ request('min_rating') == '3' ? 'checked' : '' }} onchange="document.getElementById('filterForm').submit()"> 
                    <span><i class="fas fa-star star"></i> 3.0 Ke Atas</span>
                </label>
            </div>
        </aside>

        {{-- MAIN PRODUCT CONTENT --}}
        <div>
            {{-- Header Bar --}}
            <div class="cat-top-bar">
                <div class="cat-title-text">
                    {{ $category->name }}
                    @if(!$products->isEmpty())
                        <span class="cat-count-badge">{{ $products->total() }} Produk</span>
                    @endif
                </div>

                <div class="sort-wrap">
                    <span style="font-size: 12px; color: #64748b; font-weight: 600;">Urutkan:</span>
                    <select class="sort-select-box" name="sort" onchange="document.getElementById('filterForm').submit()">
                        <option value="default" {{ request('sort') == 'default' ? 'selected' : '' }}>Paling Sesuai</option>
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Harga Terendah</option>
                        <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Harga Tertinggi</option>
                        <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Terlaris</option>
                    </select>
                </div>
            </div>

            {{-- Product Grid --}}
            @if($products->isEmpty())
                <div style="background: #ffffff; border: 1px solid var(--border); border-radius: 14px; padding: 48px 24px; text-align: center;">
                    <div style="width: 56px; height: 56px; border-radius: 50%; background: #f1f5f9; display: flex; align-items: center; justify-content: center; margin: 0 auto 14px; font-size: 24px; color: #94a3b8;">
                        <i class="fas fa-box-open"></i>
                    </div>
                    <h3 style="font-size: 16px; font-weight: 700; color: var(--ink); margin-bottom: 6px;">Belum Ada Produk</h3>
                    <p style="font-size: 13px; color: #64748b; margin-bottom: 20px;">Saat ini belum ada produk di kategori {{ $category->name }}.</p>
                    <a href="{{ route('home') }}" class="btn-ag btn-primary btn-sm" style="display: inline-flex;">Kembali ke Beranda</a>
                </div>
            @else
                <div class="pgrid-4">
                    @foreach($products as $product)
                    <div class="pcard" onclick="window.location='{{ route('products.show', $product->slug) }}'">
                        <div class="pcard-img">
                            @if($product->compare_price > $product->price)
                                @php
                                    $disc = round((($product->compare_price - $product->price) / $product->compare_price) * 100);
                                @endphp
                                <div class="disc-badge">-{{ $disc }}%</div>
                            @endif
                            <img src="{{ $product->primaryImage ? asset('storage/' . $product->primaryImage->image_path) : 'https://placehold.co/400x400?text=' . urlencode($product->name) }}" 
                                 alt="{{ $product->name }}" 
                                 loading="lazy">
                        </div>

                        <div class="pcard-body">
                            <div class="pcard-name" title="{{ $product->name }}">{{ $product->name }}</div>
                            <div class="pcard-price">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                            @if($product->compare_price > $product->price)
                                <div class="pcard-price-orig">Rp {{ number_format($product->compare_price, 0, ',', '.') }}</div>
                            @endif
                            <div class="pcard-meta">
                                <i class="fas fa-star star"></i>
                                <span style="font-weight: 700; color: var(--ink);">{{ number_format($product->rating_avg ?? 4.8, 1) }}</span>
                                <span style="color: #cbd5e1;">|</span>
                                <span>Terjual {{ $product->total_sold ?? rand(20, 300) }}</span>
                            </div>
                        </div>

                        <div class="pcard-footer">
                            <form action="{{ route('cart.add') }}" method="POST" style="width: 100%;">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="btn-cart" onclick="event.stopPropagation();">
                                    <i class="fas fa-shopping-cart"></i> + Keranjang
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div style="margin-top: 32px; display: flex; justify-content: center;">
                    {{ $products->links() }}
                </div>
            @endif

        </div>

    </div>
    </form>

</div>
@endsection
