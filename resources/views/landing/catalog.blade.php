@extends('layouts.viscous')

@section('title', 'Katalog Ikan Lokal')

@section('content')
<div class="about-title about-title-bg pk-page-hero">
    <div class="d-table"><div class="d-table-cell"><div class="container">
        <div class="about-title-text">
            <h2>Katalog</h2>
            <ul>
                <li><a href="{{ route('landing') }}">Beranda</a></li>
                <li><i class="icofont-rounded-double-right"></i> Katalog</li>
            </ul>
        </div>
    </div></div></div>
</div>

<section class="team-section pt-100 pb-40">
    <div class="container">
        <form method="GET" action="{{ route('landing.catalog') }}" class="pk-filter-bar mb-4">
            <div class="row g-2 align-items-end">
                <div class="col-lg-5 col-md-6">
                    <label class="form-label small mb-1">Cari produk / toko</label>
                    <input type="text" name="q" value="{{ $filters['q'] }}" class="form-control" placeholder="Nama ikan, toko, lokasi...">
                </div>
                <div class="col-lg-3 col-md-6">
                    <label class="form-label small mb-1">Jenis ikan</label>
                    <select name="species" class="form-select">
                        <option value="">Semua jenis</option>
                        @foreach($speciesList as $sp)
                            <option value="{{ $sp->id }}" @selected((int) $filters['species'] === (int) $sp->id)>{{ $sp->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-2 col-md-6">
                    <label class="form-label small mb-1">Urutkan</label>
                    <select name="sort" class="form-select">
                        <option value="newest" @selected($filters['sort'] === 'newest')>Terbaru</option>
                        <option value="oldest" @selected($filters['sort'] === 'oldest')>Terlama</option>
                        <option value="price_asc" @selected($filters['sort'] === 'price_asc')>Harga terendah</option>
                        <option value="price_desc" @selected($filters['sort'] === 'price_desc')>Harga tertinggi</option>
                    </select>
                </div>
                <div class="col-lg-2 col-md-6">
                    <button class="default-btn page-btn w-100" type="submit">Cari</button>
                </div>
            </div>
            @if($filters['q'] || $filters['species'])
                <div class="mt-2 small">
                    <a href="{{ route('landing.catalog') }}">Reset filter</a>
                </div>
            @endif
        </form>

        <div class="section-head text-center">
            <h2>Toko <span>Pembudidaya</span></h2>
            <p>Profil toko yang sudah diverifikasi admin.</p>
        </div>
        <div class="row">
            @forelse($shops as $shop)
            <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
                <a href="{{ route('landing.shop', $shop) }}" class="pk-catalog-card pk-shop-card text-decoration-none text-dark d-block">
                    <div class="pk-shop-card__top">
                        @if($shop->shop_logo_path)
                            <img src="{{ asset('storage/'.$shop->shop_logo_path) }}" alt="logo" class="pk-shop-logo">
                        @else
                            <div class="pk-shop-logo pk-shop-logo--placeholder">{{ strtoupper(substr($shop->shop_name ?: $shop->business_name ?: 'T', 0, 1)) }}</div>
                        @endif
                        <div>
                            <h3 class="mb-1">{{ $shop->shop_name ?: $shop->business_name }}</h3>
                            <p class="mb-0 small text-muted">{{ $shop->district }}, {{ $shop->regency }}</p>
                        </div>
                    </div>
                </a>
            </div>
            @empty
            <div class="col-12"><div class="pk-product-card text-center">Belum ada toko aktif{{ $filters['q'] ? ' untuk pencarian ini' : '' }}.</div></div>
            @endforelse
        </div>
    </div>
</section>

<section class="blog-section pb-100">
    <div class="container">
        <div class="section-head text-center">
            <h2>Produk <span>Tersedia</span></h2>
            <p>Klik produk untuk melihat galeri foto dan detail toko.</p>
        </div>
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
                        @if($product->photos->count() > 1)
                            <span class="pk-photo-count">{{ $product->photos->count() }} foto</span>
                        @endif
                    </div>
                    <div class="pk-catalog-body">
                        <h3>{{ $product->title }}</h3>
                        <p class="mb-1">{{ $product->fishSpecies?->name }} · <strong>Rp {{ number_format($product->price, 0, ',', '.') }}/{{ $product->price_unit }}</strong></p>
                        <p class="small text-muted mb-0">{{ $product->farmerProfile?->shop_name }} · {{ $product->location_label }}</p>
                    </div>
                </a>
            </div>
            @empty
            <div class="col-12"><div class="pk-product-card text-center">Belum ada produk tayang{{ $filters['q'] || $filters['species'] ? ' untuk filter ini' : '' }}.</div></div>
            @endforelse
        </div>
        <div class="mt-3">{{ $products->links() }}</div>
    </div>
</section>
@endsection
