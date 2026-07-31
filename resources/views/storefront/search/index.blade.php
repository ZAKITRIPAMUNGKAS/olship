@extends('layouts.app')

@section('title', 'Hasil Pencarian: ' . ($query ?? 'Semua Produk'))

@section('content')
<div class="section" style="margin-top: 32px;">
    <div class="section-hdr">
        <h1 class="section-title">Hasil Pencarian: <span>"{{ $query }}"</span></h1>
        <div class="section-all">{{ $products->count() }} Produk ditemukan</div>
    </div>

    @if($products->count() > 0)
    <div class="pgrid">
        @foreach($products as $product)
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
                    <i class="fas fa-star star"></i> <span>{{ number_format($product->rating_avg, 1) }}</span> | Terjual {{ $product->total_sold }}
                </div>
            </div>
            <div class="pcard-footer">
                <button class="btn-cart"><i class="fas fa-shopping-cart"></i> + Keranjang</button>
            </div>
        </div>
        @endforeach
    </div>
    <div style="margin-top: 32px;">
        {{ $products->links() }}
    </div>
    @else
    <div style="padding: 100px 0; text-align: center;">
        <div style="font-size: 64px; color: var(--line); margin-bottom: 24px;">
            <i class="fas fa-search"></i>
        </div>
        <h2 style="color: var(--ink-2); font-weight: 700;">Duh, produk tidak ditemukan!</h2>
        <p style="color: var(--ink-3); margin-top: 12px;">Coba gunakan kata kunci lain atau periksa ejaanmu.</p>
        <a href="{{ route('home') }}" class="btn btn-primary" style="margin-top: 24px; display: inline-block;">Kembali ke Beranda</a>
    </div>
    @endif
</div>
@endsection
