@extends('storefront.dashboard.layout')

@section('title', 'Wishlist Saya - Listrindo Jaya')

@push('styles')
<style>
  .db-wishlist-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 20px;
  }
  .db-wishlist-item {
    border: 1px solid var(--line);
    border-radius: 16px;
    overflow: hidden;
    position: relative;
    display: flex;
    flex-direction: column;
    background: var(--surface);
    transition: all 0.2s ease;
  }
  .db-wishlist-item:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 24px rgba(15, 23, 42, 0.05);
    border-color: rgba(2, 92, 202, 0.2);
  }
</style>
@endpush

@section('dashboard-content')
<div class="db-card">
    <div class="db-card-header">
        <h2 class="db-card-title">Wishlist Saya</h2>
        <p class="db-card-subtitle">Produk-produk yang Anda simpan untuk dibeli nanti.</p>
    </div>

    @if($wishlists->isEmpty())
        <div style="text-align: center; padding: 60px 20px; display:flex; flex-direction:column; align-items:center; gap:16px;">
            <div style="width: 80px; height: 80px; border-radius: 50%; background: #f1f5f9; display: flex; align-items: center; justify-content: center; color: var(--ink-3); font-size: 32px;">
                <i class="fas fa-heart"></i>
            </div>
            <div>
                <h3 style="font-weight: 700; color:var(--ink); font-size:16px;">Wishlist Kosong</h3>
                <p style="color: var(--ink-3); font-size:13px; margin-top:4px;">Belum ada produk yang Anda simpan di wishlist.</p>
            </div>
            <a href="{{ route('home') }}" class="db-btn db-btn-primary" style="margin-top: 8px;">Mulai Belanja</a>
        </div>
    @else
        <div class="db-wishlist-grid">
            @foreach($wishlists as $wishlist)
                @php $product = $wishlist->product; @endphp
                <div class="db-wishlist-item">
                    <a href="{{ route('products.show', $product->slug) }}" style="display: block; background:#f8fafc; border-bottom:1px solid var(--line);">
                        <img src="{{ $product->primaryImage ? asset('storage/'.$product->primaryImage->image_path) : 'https://placehold.co/400x400?text='.urlencode($product->name) }}" 
                             alt="{{ $product->name }}" 
                             style="width: 100%; aspect-ratio: 1; object-fit: contain; padding: 12px;">
                    </a>
                    <div style="padding: 16px; flex: 1; display: flex; flex-direction: column;">
                        <a href="{{ route('products.show', $product->slug) }}" style="font-weight: 700; font-size: 13.5px; color: var(--ink); margin-bottom: 6px; display: block; line-height: 1.4; height: 38px; overflow: hidden;">
                            {{ Str::limit($product->name, 40) }}
                        </a>
                        <div style="font-weight: 800; color: var(--blue); font-size: 15px; margin-bottom: 16px;">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </div>
                        
                        <div style="display: flex; gap: 8px; margin-top: auto; align-items: center;">
                            <form action="{{ route('cart.add') }}" method="POST" style="flex: 1;">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="db-btn db-btn-primary db-btn-sm" style="width: 100%;">
                                    <i class="fas fa-shopping-cart" style="font-size: 10px;"></i> Beli
                                </button>
                            </form>
                            <form action="{{ route('dashboard.wishlist.destroy', $wishlist->id) }}" method="POST" style="flex-shrink:0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="db-btn db-btn-danger db-btn-sm" style="width: 44px;" onclick="return confirm('Hapus dari wishlist?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div style="margin-top: 30px; display: flex; justify-content: center;">
            {{ $wishlists->links() }}
        </div>
    @endif
</div>
@endsection
