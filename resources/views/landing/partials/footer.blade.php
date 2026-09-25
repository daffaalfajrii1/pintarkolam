@php
    $siteName = $branding['site_name'] ?? 'PintarKolam';
    $footerLogo = ! empty($branding['logo_path'] ?? null)
        ? asset('storage/'.$branding['logo_path'])
        : asset('images/pintarkolamlogo.png');
@endphp
<footer class="footer-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-6">
                <div class="footer-widget">
                    <div class="logo pk-footer-logo">
                        <a href="{{ route('landing') }}" class="pk-brand-link">
                            <img src="{{ $footerLogo }}" alt="{{ $siteName }}">
                            <span class="pk-footer-brand">{{ $siteName }}</span>
                        </a>
                    </div>
                    <p>{{ $footer['about'] ?? 'Pintar Kolam — sistem informasi budidaya ikan air tawar. Pantau air, atur pakan, panen lebih tepat.' }}</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="footer-widget pl-40">
                    <h3>Fitur</h3>
                    <ul>
                        <li><i class="icofont-simple-right"></i><a href="{{ route('landing') }}#fitur">Rekomendasi Air</a></li>
                        <li><i class="icofont-simple-right"></i><a href="{{ route('landing') }}#fitur">Indeks Kesehatan Kolam</a></li>
                        <li><i class="icofont-simple-right"></i><a href="{{ route('landing') }}#fitur">Estimasi Panen</a></li>
                        <li><i class="icofont-simple-right"></i><a href="{{ route('landing.catalog') }}">Katalog Lokal</a></li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="footer-widget pl-40">
                    <h3>Tautan Cepat</h3>
                    <ul>
                        <li><i class="icofont-simple-right"></i><a href="{{ route('landing') }}">Beranda</a></li>
                        <li><i class="icofont-simple-right"></i><a href="{{ route('landing.about') }}">Tentang</a></li>
                        <li><i class="icofont-simple-right"></i><a href="{{ route('landing.blog') }}">Artikel</a></li>
                        <li><i class="icofont-simple-right"></i><a href="{{ route('landing.contact') }}">Kontak</a></li>
                        <li><i class="icofont-simple-right"></i><a href="{{ route('login') }}">Masuk</a></li>
                        <li><i class="icofont-simple-right"></i><a href="{{ route('register') }}">Daftar</a></li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="footer-widget">
                    <h3>Hubungi Kami</h3>
                    <p class="find-text">Pintar Kolam — sistem informasi budidaya ikan air tawar.</p>
                    <ul class="find-us">
                        <li>
                            <i class="icofont-location-pin"></i>
                            {{ $header['location'] ?? 'Kabupaten Rejang Lebong, Bengkulu' }}
                        </li>
                        <li>
                            <i class="icofont-ui-message"></i>
                            <a href="mailto:{{ $footer['email'] ?? 'admin@pintarkolam.id' }}">{{ $footer['email'] ?? 'admin@pintarkolam.id' }}</a>
                        </li>
                        <li>
                            <i class="icofont-globe"></i>
                            {{ $footer['domain'] ?? 'pintarkolam.rejanglebongkab.go.id' }}
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="bottom-footer">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="footer-social">
                        <ul>
                            <li><a href="{{ $header['facebook'] ?? '#' }}" target="_blank" rel="noopener"><i class="icofont-facebook"></i></a></li>
                            <li><a href="{{ $header['instagram'] ?? '#' }}" target="_blank" rel="noopener"><i class="icofont-instagram"></i></a></li>
                            <li><a href="{{ $header['youtube'] ?? '#' }}" target="_blank" rel="noopener"><i class="icofont-youtube-play"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="copyright-text text-end">
                        <p>&copy; {{ date('Y') }} {{ $footer['copyright'] ?? 'Pintar Kolam' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
