@extends('layouts.viscous')

@section('title', $farmer->shop_name ?: $farmer->business_name)

@section('content')
<div class="about-title about-title-bg pk-page-hero">
    <div class="d-table"><div class="d-table-cell"><div class="container">
        <div class="about-title-text">
            <h2>{{ $farmer->shop_name ?: $farmer->business_name }}</h2>
            <ul>
                <li><a href="{{ route('landing.catalog') }}">Katalog</a></li>
                <li><i class="icofont-rounded-double-right"></i> Toko</li>
            </ul>
        </div>
    </div></div></div>
</div>

<section class="pt-100 pb-70">
    <div class="container">
        <div class="row g-4 mb-4">
            <div class="col-lg-8">
                <div class="pk-product-card">
                    @if($farmer->shop_cover_path)
                        <img src="{{ asset('storage/'.$farmer->shop_cover_path) }}" class="img-fluid rounded mb-3 pk-shop-cover" alt="cover">
                    @endif
                    <div class="d-flex gap-3 align-items-center mb-2 flex-wrap">
                        @if($farmer->shop_logo_path)
                            <img src="{{ asset('storage/'.$farmer->shop_logo_path) }}" class="pk-shop-logo" alt="logo">
                        @endif
                        <div>
                            <h3 class="mb-0">{{ $farmer->shop_name ?: $farmer->business_name }}</h3>
                            <div class="text-muted">{{ $farmer->owner_name }} · {{ $farmer->district }}, {{ $farmer->regency }}</div>
                        </div>
                    </div>
                    <p>{{ $farmer->shop_description ?: $farmer->bio }}</p>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="pk-product-card">
                    <h4>Kontak</h4>
                    <p class="mb-1">WA:
                        @if($farmer->whatsapp)
                            <a href="https://wa.me/{{ preg_replace('/\D+/', '', $farmer->whatsapp) }}" target="_blank" rel="noopener">{{ $farmer->whatsapp }}</a>
                        @else
                            —
                        @endif
                    </p>
                    <p class="mb-0 small text-muted">{{ $farmer->address }}</p>
                </div>
            </div>
        </div>

        <div class="section-head text-center mb-4"><h2>Produk <span>Toko</span></h2></div>
        <div class="row">
            @forelse($products as $product)
            <div class="col-lg-4 col-md-6 col-sm-6 mb-4">
                <a href="{{ route('landing.product', $product) }}" class="pk-catalog-card text-decoration-none text-dark d-block h-100">
                    <div class="pk-catalog-thumb">
                        @if($product->photos->first())
                            <img src="{{ asset('storage/'.$product->photos->first()->path) }}" alt="{{ $product->title }}">
                        @else
                            <div class="pk-catalog-placeholder">Tanpa foto</div>
                        @endif
                        @if($product->availability === 'sold_out')
                            <span class="pk-stock-badge">Stok Habis</span>
                        @endif
                    </div>
                    <div class="pk-catalog-body">
                        <h3>{{ $product->title }}</h3>
                        <p class="mb-1"><strong>Rp {{ number_format($product->price, 0, ',', '.') }}</strong>/{{ $product->price_unit }}</p>
                        <p class="small text-muted mb-0">{{ \Illuminate\Support\Str::limit($product->description, 80) }}</p>
                    </div>
                </a>
            </div>
            @empty
            <div class="col-12"><div class="pk-product-card text-center">Belum ada produk di toko ini.</div></div>
            @endforelse
        </div>
    </div>
</section>
@endsection
