@extends('layouts.viscous')

@section('title', 'Tentang PintarKolam')

@section('content')
<div class="about-title about-title-bg pk-page-hero">
    <div class="d-table">
        <div class="d-table-cell">
            <div class="container">
                <div class="about-title-text">
                    <h2>Tentang Kami</h2>
                    <ul>
                        <li><a href="{{ route('landing') }}">Beranda</a></li>
                        <li>
                            <i class="icofont-rounded-double-right"></i>
                            Tentang
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<section class="about-style-two about-style-three pt-100 pb-70">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-lg-6 p-0">
                <div class="about-img">
                    <img src="{{ asset('viscous/assets/img/about-two.png') }}" alt="Tentang PintarKolam">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="about-text">
                    <div class="section-head">
                        <h2>Apa itu <span>PintarKolam</span>?</h2>
                        <p>PintarKolam adalah sistem informasi budidaya ikan air tawar yang membantu pembudidaya di Kabupaten Rejang Lebong memantau kualitas air, mengatur pakan, mengestimasi panen, dan memasarkan hasil melalui katalog lokal.</p>
                    </div>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="icofont-check-circled text-success"></i> Pencatatan kolam & siklus budidaya</li>
                        <li class="mb-2"><i class="icofont-check-circled text-success"></i> Rekomendasi kualitas air berbasis aturan</li>
                        <li class="mb-2"><i class="icofont-check-circled text-success"></i> Indeks kesehatan kolam yang transparan</li>
                        <li class="mb-2"><i class="icofont-check-circled text-success"></i> Katalog produk untuk buyer lokal</li>
                    </ul>
                    <div class="theme-button mt-3">
                        <a href="{{ route('register') }}" class="default-btn active-btn">Daftar Sekarang</a>
                        <a href="{{ route('landing.contact') }}" class="default-btn">Kontak</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="service-section pb-100">
    <div class="container">
        <div class="section-head text-center">
            <h2>Untuk Siapa <span>PintarKolam</span>?</h2>
        </div>
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="service-card h-100">
                    <i class="flaticon-medal"></i>
                    <h3>Pembudidaya</h3>
                    <p>Kelola kolam, catat kualitas air, terima rekomendasi, dan publikasikan hasil panen.</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="service-card active-service h-100">
                    <i class="flaticon-credit-card"></i>
                    <h3>Buyer</h3>
                    <p>Temukan ikan lokal berkualitas dan hubungi pembudidaya terverifikasi secara langsung.</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="service-card h-100">
                    <i class="flaticon-icon-1584892"></i>
                    <h3>Pemerintah Daerah</h3>
                    <p>Monitor data budidaya, dukung edukasi, dan perkuat ekonomi perikanan lokal.</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
