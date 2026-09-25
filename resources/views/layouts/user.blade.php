<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — PintarKolam</title>
    <link rel="icon" type="image/png" href="{{ asset('images/pintarkolamlogo.png') }}">
    <link rel="stylesheet" href="{{ asset('duralux/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('duralux/vendors/css/vendors.min.css') }}">
    <link rel="stylesheet" href="{{ asset('duralux/css/theme.min.css') }}">
    <style>
        .pk-stat { background:#fff; border-radius:12px; padding:1.25rem; border:1px solid rgba(0,0,0,.05); height:100%; }
        .pk-stat strong { display:block; font-size:1.75rem; color:#0f766e; }
        .logo-text { font-weight:700; color:#0f766e; padding:.85rem 1rem; display:inline-block; }
        .m-header { overflow: visible !important; }
        .m-header .b-brand { display:flex; align-items:center; padding:.65rem 1rem; }
        .pk-sidebar-logo { height:40px; width:auto; max-width:160px; object-fit:contain; display:block; }

        .nxl-header,
        .nxl-header .header-wrapper,
        .nxl-header .header-left,
        .nxl-header .header-right {
            overflow: visible !important;
        }
        .nxl-header .header-wrapper {
            align-items: center;
            min-height: 72px;
            gap: .75rem;
        }
        .nxl-header .header-left { min-width: 0; flex: 1 1 auto; }
        .nxl-header .header-left strong {
            display: block;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 42vw;
        }
        .nxl-header .header-right {
            flex: 0 0 auto;
            position: relative;
            z-index: 1030;
        }
        .pk-user-menu { position: relative; }
        .pk-user-menu .dropdown-toggle {
            max-width: 42vw;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .pk-user-menu .dropdown-toggle::after { margin-left:.4rem; }
        /* Override tema: header ul { display:inline-flex } bikin menu selalu nyala horizontal */
        .nxl-header .header-wrapper .pk-user-menu .dropdown-menu {
            display: none !important;
            flex-direction: column;
            position: absolute !important;
            inset: auto 0 auto auto !important;
            top: calc(100% + 8px) !important;
            left: auto !important;
            right: 0 !important;
            transform: none !important;
            margin: 0 !important;
            min-width: 210px;
            padding: .35rem 0;
            z-index: 1080;
            box-shadow: 0 12px 28px rgba(15, 23, 42, .14);
        }
        .nxl-header .header-wrapper .pk-user-menu .dropdown-menu.show {
            display: block !important;
        }
        .nxl-header .header-wrapper .pk-user-menu .dropdown-menu .dropdown-item {
            display: block;
            width: 100%;
            white-space: nowrap;
        }
        .pk-user-menu .dropdown-item { font-size:.875rem; }
        .pk-user-menu .dropdown-item form { margin:0; }
        .pk-user-menu .dropdown-item button {
            background:none; border:0; padding:0; width:100%; text-align:left;
            color:inherit; font:inherit; cursor:pointer;
        }
        @media (max-width: 575.98px) {
            .nxl-header .header-wrapper { padding: 0 14px; }
            .nxl-header .header-left .small { display: none; }
            .nxl-header .header-left strong { max-width: 36vw; font-size: .95rem; }
            .pk-user-menu .dropdown-toggle { max-width: 38vw; font-size: .8rem; }
        }
        .nxl-link.active { color:#0f766e; }
        .badge-baik { background:#198754; }
        .badge-waspada { background:#fd7e14; }
        .badge-kritis { background:#dc3545; }

        .pk-actions { display:flex; align-items:center; flex-wrap:wrap; gap:.5rem; }
        .pk-btn {
            display:inline-flex; align-items:center; justify-content:center;
            height:36px; padding:0 14px; border-radius:8px;
            font-size:.875rem; font-weight:600; line-height:1;
            text-decoration:none !important; border:1px solid transparent;
            white-space:nowrap; width:auto !important; min-width:0;
            box-sizing:border-box; vertical-align:middle;
        }
        .pk-btn-sm { height:32px; padding:0 12px; font-size:.8125rem; }
        .pk-btn-primary { background:#3454d1; color:#fff !important; border-color:#3454d1; }
        .pk-btn-primary:hover { background:#2a45b0; color:#fff !important; }
        .pk-btn-outline { background:#fff; color:#3454d1 !important; border-color:#c9d3f5; }
        .pk-btn-outline:hover { background:#eef2ff; color:#3454d1 !important; }
        .pk-btn-warn { background:#fff7ed; color:#c2410c !important; border-color:#fdba74; }
        .pk-btn-warn:hover { background:#ffedd5; color:#9a3412 !important; }
        .pk-btn-danger { background:#fef2f2; color:#b91c1c !important; border-color:#fca5a5; }
        .pk-btn-danger:hover { background:#fee2e2; color:#991b1b !important; }
        .pk-card-muted { opacity: .78; border-style: dashed; }
        .pk-badge {
            display:inline-flex; align-items:center; height:28px; padding:0 10px;
            border-radius:999px; font-size:.75rem; font-weight:600; white-space:nowrap;
        }
        .pk-badge-ok { background:#d1f2e1; color:#0f766e; }
        .pk-badge-warn { background:#ffe8cc; color:#b45309; }
        .pk-badge-danger { background:#fde2e1; color:#b42318; }
        .pk-badge-info { background:#e8eeff; color:#3454d1; }
        .pk-card-toolbar {
            display:flex; justify-content:space-between; align-items:center;
            flex-wrap:wrap; gap:.75rem;
        }
        .pk-card-toolbar__info { display:flex; flex-direction:column; gap:.15rem; }
        .pk-table th, .pk-table td { vertical-align:middle; }
        .pk-table .pk-btn { width:auto !important; }
        .pk-form-actions {
            display:flex; align-items:center; justify-content:flex-end; gap:.5rem;
            flex-wrap:wrap; margin-top:1rem; padding-top:1rem; border-top:1px solid #eef1f6;
        }
        .pk-form-actions .pk-btn { min-width:110px; }
        .pk-module-nav {
            display:flex; flex-wrap:wrap; gap:.5rem;
            background:#fff; border:1px solid #eef1f6; border-radius:12px; padding:.75rem;
        }
        .pk-module-link {
            display:inline-flex; align-items:center; height:34px; padding:0 14px;
            border-radius:8px; text-decoration:none !important; font-weight:600; font-size:.85rem;
            color:#4b5565; background:#f5f7fb;
        }
        .pk-module-link:hover { background:#eef2ff; color:#3454d1; }
        .pk-module-link.active { background:#3454d1; color:#fff !important; }
        .pk-money { font-variant-numeric: tabular-nums; }
        .pk-chart-card canvas { max-height:220px; }

        .pk-page-title {
            font-size: clamp(1.35rem, 2.5vw, 1.85rem);
            font-weight: 800;
            letter-spacing: -.02em;
            background: linear-gradient(90deg, #1746a2 0%, #2f80ed 55%, #56ccf2 100%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            line-height: 1.25;
        }
        .header-left strong {
            display: inline-block;
            font-size: clamp(1rem, 2vw, 1.15rem);
            font-weight: 800;
            background: linear-gradient(90deg, #1746a2, #2f80ed 60%, #56ccf2);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        .pk-page-head { width: 100%; }
        @media (max-width: 767.98px) {
            .header-wrapper {
                flex-wrap: wrap;
                gap: .75rem;
                padding-top: .75rem;
                padding-bottom: .75rem;
            }
            .header-right { width: 100%; }
            .header-right .d-flex { justify-content: space-between; width: 100%; }
            .nxl-content { padding-left: .75rem; padding-right: .75rem; }
            .pk-actions { width: 100%; }
            .pk-actions .pk-btn { flex: 1 1 auto; }
            .table-responsive { margin: 0 -0.25rem; }
        }
    </style>
    @stack('styles')
</head>
<body>
<nav class="nxl-navigation">
    <div class="navbar-wrapper">
        <div class="m-header">
            <a href="{{ route('dashboard') }}" class="b-brand">
                <img src="{{ asset('images/pintarkolamlogo.png') }}" alt="PintarKolam" class="pk-sidebar-logo">
            </a>
        </div>
        <div class="navbar-content">
            <ul class="nxl-navbar">
                <li class="nxl-item nxl-caption"><label>Menu</label></li>
                <li class="nxl-item"><a class="nxl-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}"><span class="nxl-micon"><i class="feather-home"></i></span><span class="nxl-mtext">Beranda</span></a></li>
                <li class="nxl-item"><a class="nxl-link {{ request()->routeIs('user.notifications*') ? 'active' : '' }}" href="{{ route('user.notifications.index') }}"><span class="nxl-micon"><i class="feather-bell"></i></span><span class="nxl-mtext">Notifikasi @if(auth()->user()->unreadNotifications->count())<span class="badge bg-danger">{{ auth()->user()->unreadNotifications->count() }}</span>@endif</span></a></li>
                @role('pembudidaya|admin')
                <li class="nxl-item"><a class="nxl-link {{ request()->routeIs('user.shop*') ? 'active' : '' }}" href="{{ route('user.shop.edit') }}"><span class="nxl-micon"><i class="feather-shopping-cart"></i></span><span class="nxl-mtext">Profil Toko</span></a></li>
                <li class="nxl-item"><a class="nxl-link {{ request()->routeIs('user.ponds*') || request()->routeIs('user.cycles*') ? 'active' : '' }}" href="{{ route('user.ponds.index') }}"><span class="nxl-micon"><i class="feather-map"></i></span><span class="nxl-mtext">Budidaya</span></a></li>
                <li class="nxl-item"><a class="nxl-link {{ request()->routeIs('user.products*') ? 'active' : '' }}" href="{{ route('user.products.index') }}"><span class="nxl-micon"><i class="feather-shopping-bag"></i></span><span class="nxl-mtext">Katalog Saya</span></a></li>
                @endrole
                <li class="nxl-item"><a class="nxl-link" href="{{ route('landing.catalog') }}"><span class="nxl-micon"><i class="feather-grid"></i></span><span class="nxl-mtext">Katalog Publik</span></a></li>
                <li class="nxl-item"><a class="nxl-link {{ request()->routeIs('profile.*') ? 'active' : '' }}" href="{{ route('profile.edit') }}"><span class="nxl-micon"><i class="feather-user"></i></span><span class="nxl-mtext">Profil</span></a></li>
            </ul>
        </div>
    </div>
</nav>

<header class="nxl-header">
    <div class="header-wrapper">
        <div class="header-left d-flex align-items-center gap-4">
            <a href="javascript:void(0);" class="nxl-head-mobile-toggler" id="mobile-collapse">
                <div class="hamburger hamburger--arrowturn"><div class="hamburger-box"><div class="hamburger-inner"></div></div></div>
            </a>
            <div>
                <strong>@yield('title', 'Dashboard')</strong>
                <div class="small text-muted">Dashboard Pengguna</div>
            </div>
        </div>
        <div class="header-right ms-auto">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('user.notifications.index') }}" class="btn btn-sm btn-light"><i class="feather-bell"></i></a>
                <div class="dropdown pk-user-menu">
                    <button class="btn btn-sm btn-light-brand dropdown-toggle" type="button" data-bs-toggle="dropdown" data-bs-display="static" data-bs-auto-close="true" aria-expanded="false">
                        {{ auth()->user()->name }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('profile.edit') }}#edit-profil">Edit Profil</a></li>
                        <li><a class="dropdown-item" href="{{ route('profile.edit') }}#ubah-password">Ubah Password</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">Keluar</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</header>

<main class="nxl-container">
    <div class="nxl-content">
        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif
        @yield('content')
    </div>
</main>
<script src="{{ asset('duralux/vendors/js/vendors.min.js') }}"></script>
<script src="{{ asset('duralux/js/common-init.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
@stack('scripts')
</body>
</html>
