@extends('layouts.viscous')

@section('title', $product->title)

@section('content')
<div class="about-title about-title-bg pk-page-hero">
    <div class="d-table"><div class="d-table-cell"><div class="container">
        <div class="about-title-text">
            <h2>{{ $product->title }}</h2>
            <ul>
                <li><a href="{{ route('landing.catalog') }}">Katalog</a></li>
                <li><i class="icofont-rounded-double-right"></i> Detail Produk</li>
            </ul>
        </div>
    </div></div></div>
</div>

<section class="pk-page-section">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-7">
                <div class="pk-product-card pk-gallery h-100">
                    @php
                        $photos = $product->photos;
                        $main = $photos->firstWhere('is_primary', true) ?: $photos->first();
                    @endphp
                    @if($main)
                        <div class="pk-gallery-main mb-3">
                            <img id="pk-gallery-main" src="{{ asset('storage/'.$main->path) }}" alt="{{ $product->title }}">
                            @if($product->availability === 'sold_out')
                                <span class="pk-stock-badge">Stok Habis</span>
                            @endif
                        </div>
                        @if($photos->count() > 1)
                        <div class="pk-gallery-thumbs">
                            @foreach($photos as $photo)
                                <button type="button" class="pk-gallery-thumb {{ $main && $photo->id === $main->id ? 'is-active' : '' }}" data-src="{{ asset('storage/'.$photo->path) }}">
                                    <img src="{{ asset('storage/'.$photo->path) }}" alt="foto {{ $loop->iteration }}">
                                </button>
                            @endforeach
                        </div>
                        @endif
                    @else
                        <div class="pk-gallery-empty">Belum ada foto produk</div>
                    @endif
                </div>
            </div>

            <div class="col-lg-5">
                <div class="pk-product-card h-100">
                    <div class="d-flex flex-wrap gap-2 mb-2">
                        <span class="pk-pill">{{ $product->fishSpecies?->name ?? 'Ikan' }}</span>
                        @if($product->size_label)
                            <span class="pk-pill pk-pill-muted">{{ $product->size_label }}</span>
                        @endif
                        @if($product->availability === 'sold_out')
                            <span class="pk-pill pk-pill-danger">Stok habis</span>
                        @else
                            <span class="pk-pill pk-pill-ok">Tersedia</span>
                        @endif
                    </div>
                    <h1 class="pk-product-title h3 mb-2">{{ $product->title }}</h1>
                    <div class="pk-price mb-3">Rp {{ number_format($product->price, 0, ',', '.') }}<small>/{{ $product->price_unit }}</small></div>
                    @if($product->stock_kg !== null)
                        <p class="mb-2 small text-muted">Stok: {{ rtrim(rtrim(number_format((float)$product->stock_kg, 2, ',', '.'), '0'), ',') }} {{ $product->price_unit }}</p>
                    @endif
                    @if($product->min_order)
                        <p class="mb-2 small text-muted">Min. order: {{ rtrim(rtrim(number_format((float)$product->min_order, 2, ',', '.'), '0'), ',') }} {{ $product->price_unit }}</p>
                    @endif
                    <p class="mb-3">{{ $product->description ?: 'Tidak ada deskripsi.' }}</p>
                    @if($product->whatsapp)
                        <a class="default-btn active-btn {{ $product->availability === 'sold_out' ? 'disabled' : '' }}"
                           href="https://wa.me/{{ preg_replace('/\D+/', '', $product->whatsapp) }}?text={{ urlencode('Halo, saya tertarik dengan '.$product->title) }}"
                           target="_blank" rel="noopener">
                            Hubungi via WhatsApp
                        </a>
                    @endif
                </div>
            </div>
        </div>

        @if($product->farmerProfile)
        @php $shop = $product->farmerProfile; @endphp
        <div class="pk-shop-detail mt-4">
            <div class="row g-0 align-items-stretch">
                <div class="col-md-4">
                    <div class="pk-shop-detail__media">
                        @if($shop->shop_cover_path)
                            <img src="{{ asset('storage/'.$shop->shop_cover_path) }}" alt="cover toko">
                        @elseif($shop->shop_logo_path)
                            <img src="{{ asset('storage/'.$shop->shop_logo_path) }}" alt="logo toko" class="pk-shop-detail__logo-only">
                        @else
                            <div class="pk-catalog-placeholder">{{ $shop->shop_name ?: $shop->business_name }}</div>
                        @endif
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="pk-shop-detail__body">
                        <div class="d-flex gap-3 align-items-center mb-3 flex-wrap">
                            @if($shop->shop_logo_path)
                                <img src="{{ asset('storage/'.$shop->shop_logo_path) }}" alt="logo" class="pk-shop-logo">
                            @else
                                <div class="pk-shop-logo pk-shop-logo--placeholder">{{ strtoupper(substr($shop->shop_name ?: $shop->business_name ?: 'T', 0, 1)) }}</div>
                            @endif
                            <div>
                                <h3 class="pk-gradient-title h4 mb-1">{{ $shop->shop_name ?: $shop->business_name }}</h3>
                                <div class="small text-muted">{{ $shop->owner_name }} · {{ $product->location_label ?: trim(($shop->district ?? '').', '.($shop->regency ?? ''), ', ') }}</div>
                            </div>
                        </div>
                        <p class="mb-3">{{ \Illuminate\Support\Str::limit($shop->shop_description ?: $shop->bio, 220) }}</p>
                        <a href="{{ route('landing.shop', $shop) }}" class="default-btn">Lihat Profil Toko</a>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if($related->count())
        <div class="section-head text-center mt-5 mb-4">
            <h2>Produk <span>Lainnya</span></h2>
            <p>Produk lain dari toko yang sama.</p>
        </div>
        <div class="row">
            @foreach($related as $item)
            <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
                <a href="{{ route('landing.product', $item) }}" class="pk-catalog-card text-decoration-none text-dark d-block h-100">
                    <div class="pk-catalog-thumb">
                        @if($item->photos->first())
                            <img src="{{ asset('storage/'.$item->photos->first()->path) }}" alt="{{ $item->title }}">
                        @else
                            <div class="pk-catalog-placeholder">Tanpa foto</div>
                        @endif
                    </div>
                    <div class="pk-catalog-body">
                        <h3>{{ $item->title }}</h3>
                        <p class="mb-0"><strong>Rp {{ number_format($item->price, 0, ',', '.') }}</strong>/{{ $item->price_unit }}</p>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</section>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.pk-gallery-thumb').forEach(function (btn) {
    btn.addEventListener('click', function () {
        var main = document.getElementById('pk-gallery-main');
        if (!main) return;
        main.src = this.dataset.src;
        document.querySelectorAll('.pk-gallery-thumb').forEach(function (el) { el.classList.remove('is-active'); });
        this.classList.add('is-active');
    });
});
</script>
@endpush
