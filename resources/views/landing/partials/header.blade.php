<div class="header-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8 col-md-12">
                <div class="header-widget">
                    <ul>
                        <li>
                            <i class="icofont-clock-time"></i>
                            {{ $header['hours'] ?? 'Senin - Jumat : 08:00 - 16:00' }}
                        </li>
                        <li>
                            <i class="icofont-location-pin"></i>
                            {{ $header['location'] ?? 'Kabupaten Rejang Lebong, Bengkulu' }}
                        </li>
                        <li>
                            <i class="icofont-phone"></i>
                            <a href="mailto:{{ $header['phone'] ?? 'admin@pintarkolam.id' }}">{{ $header['phone'] ?? 'admin@pintarkolam.id' }}</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-md-4">
                <div class="header-social text-end">
                    <ul>
                        <li><a href="{{ $header['facebook'] ?? 'https://www.facebook.com/' }}" target="_blank" rel="noopener"><i class="icofont-facebook"></i></a></li>
                        <li><a href="{{ $header['instagram'] ?? 'https://www.instagram.com/' }}" target="_blank" rel="noopener"><i class="icofont-instagram"></i></a></li>
                        <li><a href="{{ $header['youtube'] ?? 'https://www.youtube.com/' }}" target="_blank" rel="noopener"><i class="icofont-youtube-play"></i></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
