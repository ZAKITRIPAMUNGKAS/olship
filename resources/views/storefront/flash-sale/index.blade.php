@extends('layouts.app')

@section('title', 'Flash Sale - ' . config('app.name', 'LISTRINDO JAYA ELEKTRIK'))

@section('content')
  <!-- BREADCRUMB -->
  <div class="breadcrumb" style="padding:16px 0 12px;">
    <a href="{{ route('home') }}">Beranda</a>
    <i class="fas fa-chevron-right"></i>
    <span id="catBC">Flash Sale</span>
  </div>

  @if($flashSales->isEmpty())
    <div style="padding:60px 20px; text-align:center; background:#ffffff; border-radius:10px; border:1px solid #e4e7eb; box-shadow:0 1px 3px rgba(0,0,0,0.05); margin-bottom: 60px;">
        <div style="font-size:48px; color:#d1d5db; margin-bottom:16px;"><i class="fas fa-fire-extinguisher"></i></div>
        <h3 style="font-size:18px; color:#111827; margin-bottom:8px; font-weight:700;">Belum ada Flash Sale</h3>
        <p style="color:#6b7280; margin-bottom:24px;">Nantikan promo menarik selanjutnya dari kami!</p>
        <a href="{{ route('home') }}" class="btn btn-primary" style="padding:10px 24px;">Belanja Sekarang</a>
    </div>
  @else
    @foreach($flashSales as $flashSale)
      <div class="promo-strip" style="margin-bottom: 24px; cursor: default;">
        <div class="promo-left">
          <div class="promo-chip"><i class="fas fa-fire"></i> {{ $flashSale->name }}</div>
          <div class="promo-h">HARGA <span>GILA-GILAAN</span></div>
          <div class="promo-sub">Promo ini akan berakhir pada: <strong>{{ \Carbon\Carbon::parse($flashSale->ends_at)->format('d M Y, H:i') }}</strong></div>
        </div>
      </div>

      <div class="flash-row" style="margin-bottom: 60px;">
        @foreach($flashSale->items as $item)
        <div class="flash-pcard" onclick="window.location='{{ route('products.show', $item->product->slug) }}'">
          <div class="flash-img">
              <img src="{{ $item->product->primaryImage ? asset('storage/'.$item->product->primaryImage->image_path) : 'https://placehold.co/400x400?text='.urlencode($item->product->name) }}" alt="{{ $item->product->name }}">
          </div>
          <div class="flash-info">
            <div class="flash-name">{{ $item->product->name }}</div>
            <div class="flash-price">Rp {{ number_format($item->flash_price, 0, ',', '.') }}</div>
            <div style="display:flex;gap:8px;align-items:center;">
              <div class="flash-orig">Rp {{ number_format($item->product->price, 0, ',', '.') }}</div>
              <div class="flash-disc">-{{ $item->discount_type === 'percent' ? $item->discount_value.'%' : 'Rp '.number_format($item->discount_value, 0, ',', '.') }}</div>
            </div>
          </div>
          <div class="flash-bar-wrap">
            @php 
                $percent = $item->quota > 0 ? ($item->sold_quota / $item->quota) * 100 : 0; 
                $percent = min(100, max(0, $percent));
            @endphp
            <div class="flash-bar-bg"><div class="flash-bar-fill" style="width:{{ $percent }}%"></div></div>
            <div class="flash-bar-lbl">{{ round($percent) }}% terjual</div>
          </div>
        </div>
        @endforeach
      </div>
    @endforeach
  @endif
@endsection
