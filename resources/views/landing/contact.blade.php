@extends('layouts.viscous')

@section('title', 'Kontak PintarKolam')

@section('content')
<div class="contact-title contact-title-bg pk-page-hero">
    <div class="d-table">
        <div class="d-table-cell">
            <div class="container">
                <div class="contact-title-text">
                    <h2>Kontak</h2>
                    <ul>
                        <li><a href="{{ route('landing') }}">Beranda</a></li>
                        <li>
                            <i class="icofont-rounded-double-right"></i>
                            Kontak
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="contact-section pt-100">
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
                            <h2>Hubungi <span>Tim Kami</span></h2>
                            <p>Pintar Kolam — sistem informasi budidaya ikan air tawar.</p>
                        </div>
                        <ul class="find-us list-unstyled mb-4">
                            <li class="mb-3">
                                <i class="icofont-location-pin"></i>
                                Kabupaten Rejang Lebong, Bengkulu
                            </li>
                            <li class="mb-3">
                                <i class="icofont-ui-message"></i>
                                <a href="mailto:admin@pintarkolam.id">admin@pintarkolam.id</a>
                            </li>
                            <li class="mb-3">
                                <i class="icofont-globe"></i>
                                pintarkolam.rejanglebongkab.go.id
                            </li>
                        </ul>
                        <div class="theme-button">
                            <a href="{{ route('register') }}" class="default-btn active-btn">Daftar Akun</a>
                            <a href="{{ route('login') }}" class="default-btn">Masuk</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<section class="service-section pt-70 pb-100">
    <div class="container">
        <div class="section-head text-center">
            <h2>Kirim <span>Pesan</span></h2>
            <p>Formulir kontak publik (placeholder). Integrasi email/ticket dapat ditambahkan kemudian.</p>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="contact-form">
                    <form action="#" method="post" onsubmit="return false;">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" name="name" class="form-control" placeholder="Nama Anda" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="email" name="email" class="form-control" placeholder="Email Anda" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <textarea name="message" class="form-control" rows="5" placeholder="Pesan Anda" required></textarea>
                                </div>
                            </div>
                            <div class="col-12 text-center">
                                <button type="submit" class="default-btn page-btn">Kirim Pesan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
