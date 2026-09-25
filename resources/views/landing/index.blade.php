@extends('layouts.viscous')

@section('title', $hero['title'] ?? 'PintarKolam')

@section('content')
{{-- Hero / Home Slider --}}
@php $slides = $carousel['slides'] ?? []; @endphp
<div class="home-section">
    <div class="home-slider-area owl-carousel owl-theme">
        @if(count($slides))
            @foreach($slides as $i => $slide)
            <div class="home-slider-item {{ $i === 0 ? 'items-bg1' : ($i === 1 ? 'items-bg2' : 'items-bg3') }}" @if(!empty($slide['image'])) style="background-image:url('{{ asset('storage/'.$slide['image']) }}')" @endif>
                <div class="d-table"><div class="d-table-cell"><div class="container">
                    <div class="home-text">
                        <h1>{{ $slide['title'] ?: ($hero['title'] ?? 'PintarKolam') }}</h1>
                        <p>{{ $slide['text'] ?: ($hero['tagline'] ?? '') }}</p>
                        <div class="theme-button">
                            <a href="{{ route('register') }}" class="default-btn active-btn">Daftar Sekarang</a>
                            <a href="{{ route('landing.catalog') }}" class="default-btn">Katalog</a>
                        </div>
                    </div>
                </div></div></div>
            </div>
            @endforeach
        @else
        <div class="home-slider-item items-bg1">
            <div class="d-table">
                <div class="d-table-cell">
                    <div class="container">
                        <div class="home-text">
                            <h1>{{ $hero['title'] ?? 'PintarKolam' }}</h1>
                            <p>{{ $hero['tagline'] ?? 'Pantau Air, Atur Pakan, Panen Lebih Tepat' }}</p>
                            <div class="theme-button">
                                <a href="{{ route('register') }}" class="default-btn active-btn">Daftar Sekarang</a>
                                <a href="#cara-kerja" class="default-btn">Cara Kerja</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="home-slider-item items-bg2">
            <div class="d-table">
                <div class="d-table-cell">
                    <div class="container">
                        <div class="home-text">
                            <h1>Smart Water Recommendation</h1>
                            <p>{{ $hero['subtitle'] ?? 'Sistem informasi budidaya ikan air tawar Rejang Lebong untuk pantau kualitas air dan ambil keputusan tepat.' }}</p>
                            <div class="theme-button">
                                <a href="#fitur" class="default-btn active-btn">Lihat Fitur</a>
                                <a href="{{ route('landing.about') }}" class="default-btn">Tentang Kami</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="home-slider-item items-bg3">
            <div class="d-table">
                <div class="d-table-cell">
                    <div class="container">
                        <div class="home-text">
                            <h1>Katalog Ikan Lokal</h1>
                            <p>Buyer menemukan produk pembudidaya terverifikasi di Rejang Lebong dan menghubungi langsung via WhatsApp.</p>
                            <div class="theme-button">
                                <a href="{{ route('landing.catalog') }}" class="default-btn active-btn">Lihat Katalog</a>
                                <a href="{{ route('register') }}" class="default-btn">Jadi Buyer</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

{{-- Masalah kualitas air + Fitur (Service cards) --}}
<section class="service-section pk-service-blue pt-100 pb-70" id="fitur">
    <div class="container">
        <div class="section-head text-center">
            <h2>Masalah Kualitas Air & <span>Solusi PintarKolam</span></h2>
            <p>Gagal panen sering terjadi karena pH, suhu, dan oksigen terlarut tidak dipantau rutin. PintarKolam membantu pencatatan dan rekomendasi tindakan yang mudah dipahami.</p>
        </div>

        <div class="row">
            <div class="col-lg-4 col-md-6 col-sm-6">
                <div class="service-card">
                    <img src="{{ asset('viscous/assets/img/fish/1.svg') }}" alt="ikan" class="pk-fish-icon mb-3">
                    <h3>Smart Water Recommendation</h3>
                    <p>Ubah data pH, suhu, dan DO menjadi saran tindakan yang jelas untuk menjaga kolam tetap sehat.</p>
                    <div class="theme-button">
                        <a href="{{ route('register') }}" class="default-btn">Mulai</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6">
                <div class="service-card active-service">
                    <img src="{{ asset('viscous/assets/img/fish/2.svg') }}" alt="ikan" class="pk-fish-icon mb-3">
                    <h3>Indeks Kesehatan Kolam</h3>
                    <p>Skor 0–100 dengan faktor penyebab yang transparan agar pembudidaya tahu prioritas perbaikan.</p>
                    <div class="theme-button">
                        <a href="{{ route('register') }}" class="default-btn">Mulai</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6">
                <div class="service-card">
                    <img src="{{ asset('viscous/assets/img/fish/3.svg') }}" alt="ikan" class="pk-fish-icon mb-3">
                    <h3>Estimasi Panen</h3>
                    <p>Perkiraan waktu panen, jumlah, dan nilai jual berdasarkan data siklus dan pertumbuhan.</p>
                    <div class="theme-button">
                        <a href="{{ route('register') }}" class="default-btn">Mulai</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6">
                <div class="service-card">
                    <img src="{{ asset('viscous/assets/img/fish/4.svg') }}" alt="ikan" class="pk-fish-icon mb-3">
                    <h3>Manajemen Kolam</h3>
                    <p>Catat profil kolam, foto, GPS, dan status agar data budidaya terorganisir di satu tempat.</p>
                    <div class="theme-button">
                        <a href="{{ route('register') }}" class="default-btn">Mulai</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6">
                <div class="service-card">
                    <img src="{{ asset('viscous/assets/img/fish/5.svg') }}" alt="ikan" class="pk-fish-icon mb-3">
                    <h3>Katalog Lokal</h3>
                    <p>Tampilkan hasil panen ke buyer lokal. Hubungi pembudidaya langsung tanpa perantara rumit.</p>
                    <div class="theme-button">
                        <a href="{{ route('landing.catalog') }}" class="default-btn">Lihat</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6">
                <div class="service-card">
                    <img src="{{ asset('viscous/assets/img/fish/1.svg') }}" alt="ikan" class="pk-fish-icon mb-3">
                    <h3>Artikel Edukasi</h3>
                    <p>Materi praktis budidaya air tawar yang disusun untuk pembudidaya Rejang Lebong.</p>
                    <div class="theme-button">
                        <a href="{{ route('landing.blog') }}" class="default-btn">Baca</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Why choose --}}
@php
    $whyImage = !empty($sections['why_choose_image'] ?? null)
        ? asset('storage/'.$sections['why_choose_image'])
        : asset('images/ss.png');
    $whyBg = !empty($sections['why_choose_bg'] ?? null)
        ? asset('storage/'.$sections['why_choose_bg'])
        : null;
@endphp
<section class="why-choose-section why-choose-bg" @if($whyBg) style="background-image:url('{{ $whyBg }}')" @endif>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6 why-choose-img pk-why-choose-photo">
                <img src="{{ $whyImage }}" alt="Kenapa PintarKolam">
            </div>
            <div class="col-lg-5 offset-lg-6 offset-md-0">
                <div class="why-choose-text">
                    <div class="section-head">
                        <h2>Kenapa PintarKolam?</h2>
                        <p>Dirancang khusus untuk budidaya ikan air tawar bersama Pintar Kolam.</p>
                    </div>
                </div>
                <div class="why-choose-accordian">
                    <div class="accordion" id="accordionExample">
                        <div class="card">
                            <div class="card-header" id="headingOne">
                                <h2 class="mb-0">
                                    <a href="#collapseOne" class="btn" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        Rekomendasi berbasis data
                                    </a>
                                </h2>
                            </div>
                            <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                <div class="card-body">
                                    Setiap pencatatan kualitas air dianalisis dengan aturan yang dapat dikelola admin, sehingga saran tindakan relevan dengan kondisi kolam.
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header" id="headingTwo">
                                <h2 class="mb-0">
                                    <a href="#collapseTwo" class="btn collapsed" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        Mudah dipakai di lapangan
                                    </a>
                                </h2>
                            </div>
                            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                                <div class="card-body">
                                    Alur kerja sederhana: daftar kolam, catat air & pakan, terima rekomendasi, lalu tampilkan hasil di katalog.
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header" id="headingThree">
                                <h2 class="mb-0">
                                    <a href="#collapseThree" class="btn collapsed" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                        Fokus hasil panen
                                    </a>
                                </h2>
                            </div>
                            <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                                <div class="card-body">
                                    Dirancang Pintar Kolam untuk memperkuat ekonomi lokal melalui digitalisasi budidaya.
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header" id="headingFour">
                                <h2 class="mb-0">
                                    <a href="#collapseFour" class="btn collapsed" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                        Bridging buyer & pembudidaya
                                    </a>
                                </h2>
                            </div>
                            <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-bs-parent="#accordionExample">
                                <div class="card-body">
                                    Katalog publik membantu buyer menemukan ikan lokal dan menghubungi pembudidaya terverifikasi.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="why-choose-contact">
                        <div class="theme-button mt-3">
                            <a href="{{ route('register') }}" class="default-btn">Daftar Gratis</a>
                        </div>
                        <p class="mt-2">Gabung sebagai pembudidaya atau buyer</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="why-choose-shape">
            <img src="{{ asset('viscous/assets/img/why-choose/shape-1.png') }}" alt="shape">
        </div>
    </div>
</section>

{{-- Cara kerja / Process --}}
<div class="process-section pb-70" id="cara-kerja">
    <div class="container">
        <div class="section-head text-center pt-100">
            <h2>Cara <span>Kerja</span></h2>
            <p>Empat langkah sederhana untuk mengelola budidaya dengan PintarKolam.</p>
        </div>
        <div class="row">
            <div class="col-md-3 col-sm-6">
                <div class="process-card">
                    <i class="icofont-touch"></i>
                    <h3>1. Daftar Kolam</h3>
                    <p>Buat akun, lengkapi profil, dan daftarkan kolam serta siklus budidaya.</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="process-card">
                    <i class="icofont-water-drop"></i>
                    <h3>2. Catat Data</h3>
                    <p>Input kualitas air, pakan, dan aktivitas harian secara rutin.</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="process-card">
                    <i class="icofont-light-bulb"></i>
                    <h3>3. Terima Saran</h3>
                    <p>Dapatkan rekomendasi air, skor kesehatan, dan estimasi panen.</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="process-card">
                    <i class="icofont-cart-alt"></i>
                    <h3>4. Tampilkan Katalog</h3>
                    <p>Publikasikan hasil panen agar buyer lokal mudah menghubungi Anda.</p>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Statistik / Counter --}}
<div class="counter-section pt-100">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-5">
                <div class="offer-text">
                    <h2>Dampak <span>Program</span> PintarKolam</h2>
                    <div class="theme-button">
                        <a href="{{ route('register') }}" class="default-btn">Gabung Sekarang</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="counter-area">
                    <div class="row">
                        <div class="col-lg-5 col-md-4 col-6 offset-lg-1">
                            <div class="counter-text">
                                <h2><span class="counter">{{ $stats['farmers'] ?? 0 }}</span></h2>
                                <p>Pembudidaya Terverifikasi</p>
                            </div>
                        </div>
                        <div class="col-lg-5 col-md-4 col-6">
                            <div class="counter-text">
                                <h2><span class="counter">{{ $stats['ponds'] ?? 0 }}</span></h2>
                                <p>Kolam Aktif</p>
                            </div>
                        </div>
                        <div class="col-lg-5 col-md-4 col-6 offset-lg-1">
                            <div class="counter-text">
                                <h2><span class="counter">{{ $stats['products'] ?? 0 }}</span></h2>
                                <p>Produk Tersedia</p>
                            </div>
                        </div>
                        <div class="col-lg-5 col-md-4 col-6">
                            <div class="counter-text">
                                <h2><span class="counter">{{ $articles->count() }}</span></h2>
                                <p>Artikel Edukasi</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="counter-shape">
            <img src="{{ asset('viscous/assets/img/counter/1.png') }}" alt="shape">
            <img src="{{ asset('viscous/assets/img/counter/2.png') }}" alt="shape">
            <img src="{{ asset('viscous/assets/img/counter/3.png') }}" alt="shape">
            <img src="{{ asset('viscous/assets/img/counter/4.png') }}" alt="shape">
            <img src="{{ asset('viscous/assets/img/counter/5.png') }}" alt="shape">
            <img src="{{ asset('viscous/assets/img/counter/6.png') }}" alt="shape">
            <img src="{{ asset('viscous/assets/img/counter/7.png') }}" alt="shape">
            <img src="{{ asset('viscous/assets/img/counter/8.png') }}" alt="shape">
        </div>
    </div>
</div>

{{-- Produk unggulan --}}
<section class="team-section pt-100 pb-70" id="katalog">
    <div class="container">
        <div class="section-head text-center">
            <h2>Produk <span>Unggulan</span></h2>
            <p>Katalog hasil budidaya lokal dari pembudidaya terverifikasi di Rejang Lebong.</p>
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
                        </div>
                        <div class="pk-catalog-body">
                            <h3>{{ $product->title }}</h3>
                            <p class="mb-1">{{ $product->fishSpecies?->name ?? 'Ikan air tawar' }}</p>
                            <p class="mb-1"><strong>Rp {{ number_format($product->price, 0, ',', '.') }}</strong>/{{ $product->price_unit }}</p>
                            <p class="mb-0 text-muted small">{{ $product->farmerProfile?->shop_name ?: $product->location_label }}</p>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-12">
                    <div class="pk-product-card text-center">
                        <p class="mb-0">Belum ada produk publik. Data akan muncul setelah pembudidaya mempublikasikan hasil panen.</p>
                    </div>
                </div>
            @endforelse
        </div>
        <div class="text-center mt-3">
            <a href="{{ route('landing.catalog') }}" class="default-btn active-btn">Lihat Semua Katalog</a>
        </div>
    </div>
</section>

{{-- Artikel --}}
<section class="blog-section pt-100 pb-70" id="artikel">
    <div class="container">
        <div class="section-head text-center">
            <h2>Artikel <span>Edukasi</span></h2>
            <p>Tips dan panduan praktis budidaya ikan air tawar.</p>
        </div>
        <div class="row">
            @forelse($articles as $article)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="pk-article-card">
                        <h3 class="pk-gradient-title"><a href="{{ route('landing.article', $article) }}">{{ $article->title }}</a></h3>
                        <ul class="list-unstyled mb-2">
                            <li>
                                <i class="icofont-calendar"></i>
                                {{ optional($article->published_at)->format('d M Y') ?? '-' }}
                            </li>
                        </ul>
                        <p>{{ $article->excerpt }}</p>
                        <a href="{{ route('landing.article', $article) }}" class="blog-btn">Baca <i class="icofont-rounded-right"></i></a>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="pk-article-card text-center">
                        <p class="mb-0">Artikel edukasi akan ditampilkan di sini.</p>
                    </div>
                </div>
            @endforelse
        </div>
        <div class="text-center mt-3">
            <a href="{{ route('landing.blog') }}" class="default-btn active-btn">Lihat Semua Artikel</a>
        </div>
    </div>
</section>
<section class="service-section pb-70" id="peta">
    <div class="container">
        <div class="section-head text-center">
            <h2>Peta <span>Sebaran</span></h2>
            <p>Lokasi publik menggunakan titik perkiraan agar koordinat lengkap tetap aman.</p>
        </div>
        <div class="pk-map-note text-center">
            <p class="mb-2">Data peta sebaran pembudidaya tersedia melalui API publik.</p>
            <code>/api/v1/map/ponds</code>
        </div>
    </div>
</section>

{{-- CTA + Contact --}}
<div class="contact-section" id="kontak">
    <div class="container">
        <div class="contact-area">
            <div class="row align-items-center">
                <div class="col-lg-6 col-md-6">
                    <div class="contact-img pk-cta-banner">
                        <img src="{{ asset('images/ss.png') }}" alt="PintarKolam">
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="contact-text">
                        <div class="section-head">
                            <h2>Siap <span>Bergabung?</span></h2>
                            <p>Daftar sebagai pembudidaya untuk mencatat budidaya, atau sebagai buyer untuk menemukan ikan lokal.</p>
                        </div>
                        <div class="theme-button mb-4">
                            <a href="{{ route('register') }}" class="default-btn active-btn">Mulai Sekarang</a>
                            <a href="{{ route('landing.contact') }}" class="default-btn">Hubungi Kami</a>
                        </div>
                        <p class="mb-0"><strong>Pintar Kolam</strong><br>Sistem informasi budidaya ikan air tawar.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
