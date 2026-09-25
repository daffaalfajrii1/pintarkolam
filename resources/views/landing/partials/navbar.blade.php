@php
    $siteName = $branding['site_name'] ?? 'PintarKolam';
    $hasAdminLogo = ! empty($branding['logo_path'] ?? null);
    $logo = $hasAdminLogo
        ? asset('storage/'.$branding['logo_path'])
        : asset('images/pintarkolamlogo.png');
@endphp
<div class="navbar-area">
    <div class="mobile-nav">
        <a href="{{ route('landing') }}" class="logo pk-brand-link">
            <img src="{{ $logo }}" class="main-logo pk-brand-logo" alt="{{ $siteName }}">
            <img src="{{ $logo }}" class="white-logo pk-brand-logo" alt="{{ $siteName }}">
            <span class="pk-brand-text">{{ $siteName }}</span>
        </a>
    </div>

    <div class="main-nav">
        <div class="container">
            <nav class="navbar navbar-expand-md navbar-light">
                <a class="navbar-brand pk-brand-link" href="{{ route('landing') }}">
                    <img src="{{ $logo }}" class="main-logo pk-brand-logo" alt="{{ $siteName }}">
                    <img src="{{ $logo }}" class="white-logo pk-brand-logo" alt="{{ $siteName }}">
                    <span class="pk-brand-text">{{ $siteName }}</span>
                </a>
                <div class="collapse navbar-collapse mean-menu" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a href="{{ route('landing') }}" class="nav-link {{ request()->routeIs('landing') ? 'active' : '' }}">Beranda</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('landing.about') }}" class="nav-link {{ request()->routeIs('landing.about') ? 'active' : '' }}">Tentang</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('landing') }}#fitur" class="nav-link">Fitur</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('landing.catalog') }}" class="nav-link {{ request()->routeIs('landing.catalog') || request()->routeIs('landing.shop') || request()->routeIs('landing.product') ? 'active' : '' }}">Katalog</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('landing.blog') }}" class="nav-link {{ request()->routeIs('landing.blog') || request()->routeIs('landing.article') ? 'active' : '' }}">Artikel</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('landing.contact') }}" class="nav-link {{ request()->routeIs('landing.contact') ? 'active' : '' }}">Kontak</a>
                        </li>
                        @auth
                            <li class="nav-item">
                                <a href="{{ route('dashboard') }}" class="nav-link">Dashboard</a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a href="{{ route('login') }}" class="nav-link">Masuk</a>
                            </li>
                        @endauth
                    </ul>
                    <div class="navbar-button">
                        @auth
                            <a href="{{ route('dashboard') }}">Buka Aplikasi</a>
                        @else
                            <a href="{{ route('register') }}">Daftar Sekarang</a>
                        @endauth
                    </div>
                </div>
            </nav>
        </div>
    </div>
</div>
