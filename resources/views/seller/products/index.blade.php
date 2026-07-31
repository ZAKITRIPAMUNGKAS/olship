@extends('layouts.app')
@section('title', 'Manajemen Produk - Seller Center')

@section('content')
<div class="seller-container" style="padding: 40px 0;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <div>
            <h1 style="font-size: 28px; font-weight: 800; color: var(--ink);">Produk Saya</h1>
            <p style="color: var(--slate-500);">Kelola semua produk yang Anda jual di platform kami</p>
        </div>
        <a href="{{ route('seller.products.create') }}" class="btn btn-primary" style="background: var(--primary); color: #fff; padding: 12px 24px; border-radius: 12px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-plus"></i> Tambah Produk Baru
        </a>
    </div>

    @if(session('success'))
        <div style="padding: 16px; background: #ecfdf5; color: #059669; border-radius: 12px; margin-bottom: 24px; font-weight: 600;">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <div class="card" style="background: #fff; border-radius: 16px; border: 1px solid var(--slate-200); overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="text-align: left; background: var(--slate-50); border-bottom: 1px solid var(--slate-200);">
                    <th style="padding: 16px; font-size: 13px; color: var(--slate-500); text-transform: uppercase;">Info Produk</th>
                    <th style="padding: 16px; font-size: 13px; color: var(--slate-500); text-transform: uppercase;">Kategori</th>
                    <th style="padding: 16px; font-size: 13px; color: var(--slate-500); text-transform: uppercase;">Harga</th>
                    <th style="padding: 16px; font-size: 13px; color: var(--slate-500); text-transform: uppercase;">Stok</th>
                    <th style="padding: 16px; font-size: 13px; color: var(--slate-500); text-transform: uppercase;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr style="border-bottom: 1px solid var(--slate-50);">
                    <td style="padding: 16px;">
                        <div style="display: flex; gap: 12px; align-items: center;">
                            @if($product->primary_image)
                                <img src="{{ asset('storage/' . $product->primary_image->image_path) }}" style="width: 50px; height: 50px; border-radius: 8px; object-fit: cover;">
                            @else
                                <div style="width: 50px; height: 50px; border-radius: 8px; background: var(--slate-100); display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-image" style="color: var(--slate-400);"></i>
                                </div>
                            @endif
                            <div>
                                <div style="font-weight: 700; color: var(--ink);">{{ $product->name }}</div>
                                <div style="font-size: 12px; color: var(--slate-400);">SKU: {{ $product->sku }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="padding: 16px; font-size: 14px; color: var(--slate-600);">{{ $product->category->name }}</td>
                    <td style="padding: 16px; font-weight: 700; color: var(--ink);">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                    <td style="padding: 16px;">
                        <span style="font-weight: 600; {{ $product->stock <= 5 ? 'color: var(--red);' : 'color: var(--ink);' }}">
                            {{ $product->stock }}
                        </span>
                    </td>
                    <td style="padding: 16px;">
                        <div style="display: flex; gap: 8px;">
                            <a href="{{ route('seller.products.edit', $product) }}" style="color: var(--primary);"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('seller.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Hapus produk ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" style="background: none; border: none; color: var(--red); cursor: pointer;"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="padding: 40px; text-align: center; color: var(--slate-400);">Belum ada produk.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div style="padding: 16px;">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection
