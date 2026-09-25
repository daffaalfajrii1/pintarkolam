<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'PintarKolam') — Budidaya Ikan Rejang Lebong</title>

    <link rel="stylesheet" href="{{ asset('viscous/assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('viscous/assets/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('viscous/assets/css/owl.theme.default.min.css') }}">
    <link rel="stylesheet" href="{{ asset('viscous/assets/css/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('viscous/assets/css/slick-theme.css') }}">
    <link rel="stylesheet" href="{{ asset('viscous/assets/css/icofont.min.css') }}">
    <link rel="stylesheet" href="{{ asset('viscous/assets/css/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('viscous/assets/css/flaticon.css') }}">
    <link rel="stylesheet" href="{{ asset('viscous/assets/css/meanmenu.css') }}">
    <link rel="stylesheet" href="{{ asset('viscous/assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('viscous/assets/css/dark.css') }}">
    <link rel="stylesheet" href="{{ asset('viscous/assets/css/responsive.css') }}">
    <link rel="icon" type="image/png" href="{{ !empty(($branding['favicon_path'] ?? null)) ? asset('storage/'.$branding['favicon_path']) : asset('images/pintarkolamlogo.png') }}">
    <style>
        .pk-brand-text {
            font-weight: 700;
            font-size: 1.15rem;
            background: linear-gradient(90deg, #0b3d91, #2f80ed 60%, #4facfe);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            margin-left: .45rem;
            vertical-align: middle;
        }
        .navbar-area .navbar-brand,
        .mobile-nav .logo,
        .pk-brand-link {
            display: inline-flex;
            align-items: center;
            text-decoration: none;
            gap: .35rem;
        }
        .pk-brand-logo {
            height: 42px !important;
            width: auto !important;
            max-width: 56px;
            object-fit: contain;
        }
        .navbar-area.is-sticky .pk-brand-text,
        .navbar-area.sticky .pk-brand-text {
            color: #fff;
            background: none;
            -webkit-background-clip: unset;
            background-clip: unset;
        }
        .pk-footer-logo img {
            height: 52px;
            width: auto;
            max-width: 72px;
            object-fit: contain;
            border-radius: 10px;
            background: #fff;
            padding: 4px;
        }
        .pk-footer-brand {
            display: block;
            margin-top: .5rem;
            font-weight: 700;
            color: #fff;
            font-size: 1.05rem;
        }
        .pk-page-section {
            padding-top: 80px;
            padding-bottom: 110px;
        }
        .footer-area {
            margin-top: 0;
            clear: both;
        }
        .pk-shop-detail {
            background: #fff;
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid rgba(23, 70, 162, .1);
            box-shadow: 0 12px 32px rgba(15, 23, 42, .07);
        }
        .pk-shop-detail__media {
            min-height: 220px;
            height: 100%;
            background: linear-gradient(135deg, #e8f2ff, #f8fbff);
        }
        .pk-shop-detail__media img {
            width: 100%;
            height: 100%;
            min-height: 220px;
            object-fit: cover;
            display: block;
        }
        .pk-shop-detail__logo-only {
            object-fit: contain !important;
            padding: 2rem;
            background: #f5f9ff;
        }
        .pk-shop-detail__body {
            padding: 1.5rem 1.6rem;
        }
        .why-choose-section .section-head h2,
        .why-choose-section .section-head h2 span {
            background: linear-gradient(90deg, #ffffff 0%, #d6e8ff 55%, #9ec9ff 100%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent !important;
        }
        .why-choose-section .section-head p {
            color: rgba(255, 255, 255, .9);
        }
        .pk-why-choose-photo img {
            border-radius: 0;
            max-width: 100%;
        }
        .pk-product-card,
        .pk-article-card {
            background: #fff;
            border-radius: 8px;
            padding: 1.25rem;
            height: 100%;
            box-shadow: 0 8px 24px rgba(15, 23, 42, .06);
        }
        .pk-product-card h3,
        .pk-article-card h3 {
            font-size: 1.1rem;
            margin-bottom: .5rem;
        }
        .pk-map-note {
            background: #fff;
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: 0 8px 24px rgba(15, 23, 42, .06);
        }
        .pk-fish-icon { width: 56px; height: 56px; display: block; }
        .service-card.active-service .pk-fish-icon { filter: brightness(1.2); }
        .pk-fish-shapes img { opacity: .55 !important; }
        .service-section .service-shapes { background-image: none !important; }
        .service-card i[class^="flaticon-"],
        .service-card i[class*=" flaticon-"] { display: none !important; }
        .buy-now-btn, .switch-box { display: none !important; }
        .why-choose-img.pk-why-choose-photo {
            background-image: none !important;
            display: flex;
            align-items: flex-end;
            justify-content: center;
            overflow: hidden;
        }
        .why-choose-img.pk-why-choose-photo img {
            width: 100%;
            max-width: 650px;
            height: auto;
            max-height: 750px;
            object-fit: contain;
            object-position: bottom center;
        }

        /* Section titles — blue gradient */
        .section-head h2 {
            font-weight: 800;
            letter-spacing: -.02em;
        }
        .section-head h2,
        .section-head h2 span,
        .pk-gradient-title,
        .pk-gradient-title a,
        .pk-article-card h3 a {
            background: linear-gradient(90deg, #0b3d91 0%, #1d6fe8 50%, #4facfe 100%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent !important;
            text-decoration: none !important;
        }
        .pk-gradient-title,
        .pk-article-card h3 {
            font-weight: 800;
            letter-spacing: -.02em;
        }
        .about-title-text h2,
        .contact-title-text h2 {
            font-weight: 800;
            letter-spacing: -.02em;
            text-shadow: 0 8px 24px rgba(13, 71, 161, .35);
        }
        .pk-page-hero.about-title-bg,
        .pk-page-hero.contact-title-bg {
            background:
                linear-gradient(120deg, rgba(11, 30, 74, .92) 0%, rgba(23, 70, 162, .85) 45%, rgba(47, 128, 237, .78) 100%),
                #1746a2 !important;
            background-size: cover !important;
        }
        .pk-cta-banner img {
            width: 100%;
            max-width: 420px;
            height: auto;
            border-radius: 16px;
            display: block;
            margin: 0 auto;
            box-shadow: 0 18px 40px rgba(23, 70, 162, .18);
        }

        /* Filter / blog news */
        .pk-filter-bar {
            background: #fff;
            border: 1px solid rgba(23, 70, 162, .1);
            border-radius: 14px;
            padding: 1rem 1.15rem;
            box-shadow: 0 8px 24px rgba(15, 23, 42, .05);
        }
        .pk-news-card {
            background: #fff;
            border-radius: 14px;
            overflow: hidden;
            border: 1px solid rgba(23, 70, 162, .08);
            box-shadow: 0 10px 28px rgba(15, 23, 42, .06);
            display: flex;
            flex-direction: column;
        }
        .pk-news-thumb {
            display: block;
            aspect-ratio: 16 / 10;
            background: linear-gradient(135deg, #e8f2ff, #f8fbff);
            overflow: hidden;
        }
        .pk-news-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .pk-news-body { padding: 1.1rem 1.2rem 1.25rem; flex: 1; }
        .pk-news-body h3 { font-size: 1.1rem; margin-bottom: .5rem; line-height: 1.35; }
        .pk-news-meta {
            font-size: .82rem;
            color: #64748b;
            margin-bottom: .45rem;
        }
        .pk-sidebar-card {
            background: #fff;
            border-radius: 14px;
            padding: 1.2rem;
            border: 1px solid rgba(23, 70, 162, .08);
            box-shadow: 0 10px 28px rgba(15, 23, 42, .06);
        }
        .pk-sidebar-list li {
            padding: .65rem 0;
            border-bottom: 1px solid #eef2f7;
        }
        .pk-sidebar-list li:last-child { border-bottom: 0; }
        .pk-sidebar-list a {
            display: block;
            color: #1e293b;
            font-weight: 600;
            text-decoration: none;
            line-height: 1.35;
        }
        .pk-sidebar-list a:hover,
        .pk-sidebar-list a.is-active { color: #1746a2; }
        .pk-sidebar-list span,
        .pk-sidebar-list em {
            display: block;
            font-size: .78rem;
            color: #94a3b8;
            font-style: normal;
            font-weight: 500;
            margin-top: .2rem;
        }

        /* Beranda — service section blue/white gradient */
        .pk-service-blue {
            position: relative;
            background:
                radial-gradient(circle at 12% 20%, rgba(79, 172, 254, .28), transparent 42%),
                radial-gradient(circle at 88% 10%, rgba(23, 70, 162, .18), transparent 40%),
                linear-gradient(180deg, #f7fbff 0%, #e8f2ff 45%, #ffffff 100%);
            overflow: hidden;
        }
        .pk-service-blue .service-card {
            background: rgba(255, 255, 255, .92);
            border: 1px solid rgba(47, 128, 237, .12);
            box-shadow: 0 12px 32px rgba(23, 70, 162, .08);
            transition: transform .25s ease, box-shadow .25s ease;
        }
        .pk-service-blue .service-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 18px 40px rgba(23, 70, 162, .14);
        }
        .pk-service-blue .service-card.active-service {
            background: linear-gradient(160deg, #1746a2 0%, #2f80ed 100%);
            border-color: transparent;
            color: #fff;
        }
        .pk-service-blue .service-card.active-service h3,
        .pk-service-blue .service-card.active-service p { color: #fff; }
        .pk-service-blue .service-card.active-service .default-btn {
            background: #fff;
            color: #1746a2;
        }

        /* Catalog cards */
        .pk-catalog-card {
            background: #fff;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 10px 28px rgba(15, 23, 42, .07);
            border: 1px solid rgba(23, 70, 162, .08);
            transition: transform .22s ease, box-shadow .22s ease;
            height: 100%;
        }
        .pk-catalog-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 16px 36px rgba(23, 70, 162, .14);
        }
        .pk-catalog-thumb {
            position: relative;
            aspect-ratio: 16 / 10;
            background: linear-gradient(135deg, #e8f2ff, #f8fbff);
            overflow: hidden;
        }
        .pk-catalog-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }
        .pk-catalog-placeholder {
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #7a8aa5;
            font-size: .9rem;
        }
        .pk-catalog-body { padding: 1rem 1.15rem 1.2rem; }
        .pk-catalog-body h3 {
            font-size: 1.05rem;
            margin-bottom: .4rem;
            color: #123;
        }
        .pk-photo-count {
            position: absolute;
            right: 10px;
            bottom: 10px;
            background: rgba(11, 30, 74, .75);
            color: #fff;
            font-size: .72rem;
            font-weight: 600;
            padding: .2rem .55rem;
            border-radius: 999px;
        }
        .pk-shop-card__top {
            display: flex;
            gap: .85rem;
            align-items: center;
            padding: 1.15rem;
        }
        .pk-shop-logo {
            width: 56px;
            height: 56px;
            object-fit: cover;
            border-radius: 12px;
            flex-shrink: 0;
        }
        .pk-shop-logo--placeholder {
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #1746a2, #56ccf2);
            color: #fff;
            font-weight: 700;
        }
        .pk-shop-cover {
            max-height: 240px;
            width: 100%;
            object-fit: cover;
        }

        /* Product gallery */
        .pk-gallery-main {
            position: relative;
            border-radius: 12px;
            overflow: hidden;
            background: #eef4ff;
            aspect-ratio: 4 / 3;
        }
        .pk-gallery-main img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }
        .pk-gallery-thumbs {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(72px, 1fr));
            gap: .5rem;
        }
        .pk-gallery-thumb {
            border: 2px solid transparent;
            border-radius: 10px;
            overflow: hidden;
            padding: 0;
            background: #eef4ff;
            aspect-ratio: 1;
            cursor: pointer;
        }
        .pk-gallery-thumb.is-active,
        .pk-gallery-thumb:hover { border-color: #2f80ed; }
        .pk-gallery-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }
        .pk-gallery-empty {
            min-height: 240px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #7a8aa5;
            background: #f3f7ff;
            border-radius: 12px;
        }
        .pk-stock-badge {
            position: absolute;
            top: 12px;
            left: 12px;
            background: #b42318;
            color: #fff;
            font-size: .75rem;
            font-weight: 700;
            padding: .35rem .7rem;
            border-radius: 999px;
        }
        .pk-pill {
            display: inline-flex;
            align-items: center;
            height: 28px;
            padding: 0 10px;
            border-radius: 999px;
            font-size: .75rem;
            font-weight: 600;
            background: #e8f0ff;
            color: #1746a2;
        }
        .pk-pill-muted { background: #f1f5f9; color: #475569; }
        .pk-pill-ok { background: #d1f2e1; color: #0f766e; }
        .pk-pill-danger { background: #fde2e1; color: #b42318; }
        .pk-price {
            font-size: 1.6rem;
            font-weight: 800;
            color: #1746a2;
            line-height: 1.2;
        }
        .pk-price small {
            font-size: .95rem;
            font-weight: 600;
            color: #64748b;
            margin-left: .15rem;
        }
        .default-btn.disabled {
            pointer-events: none;
            opacity: .55;
        }

        @media (max-width: 767.98px) {
            .section-head h2 { font-size: 1.55rem; }
            .pk-price { font-size: 1.35rem; }
            .pk-gallery-thumbs { grid-template-columns: repeat(auto-fill, minmax(64px, 1fr)); }
            .about-title { min-height: 220px; }
            .pk-page-section { padding-top: 60px; padding-bottom: 80px; }
            .pk-brand-logo { height: 36px !important; max-width: 48px; }
            .pk-shop-detail__body { padding: 1.15rem; }
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="loader-content">
        <div class="d-table">
            <div class="d-table-cell">
                <div id="loading-center">
                    <div id="loading-center-absolute">
                        <div class="object" id="object_one"></div>
                        <div class="object" id="object_two"></div>
                        <div class="object" id="object_three"></div>
                        <div class="object" id="object_four"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('landing.partials.header')
    @include('landing.partials.navbar')

    @yield('content')

    @include('landing.partials.footer')

    <div class="top-btn">
        <i class="icofont-scroll-long-up"></i>
    </div>

    <script src="{{ asset('viscous/assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('viscous/assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('viscous/assets/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('viscous/assets/js/slick.min.js') }}"></script>
    <script src="{{ asset('viscous/assets/js/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset('viscous/assets/js/meanmenu.js') }}"></script>
    <script src="{{ asset('viscous/assets/js/jquery.ajaxchimp.min.js') }}"></script>
    <script src="{{ asset('viscous/assets/js/form-validator.min.js') }}"></script>
    <script src="{{ asset('viscous/assets/js/contact-form-script.js') }}"></script>
    <script src="{{ asset('viscous/assets/js/custom.js') }}"></script>
    @stack('scripts')
</body>
</html>
