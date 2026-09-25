<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — PintarKolam</title>
    <link rel="icon" type="image/png" href="{{ asset('images/pintarkolamlogo.png') }}">
    <link rel="stylesheet" href="{{ asset('duralux/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('duralux/vendors/css/vendors.min.css') }}">
    <link rel="stylesheet" href="{{ asset('duralux/css/theme.min.css') }}">
    <style>
        .pk-stat { background:#fff; border-radius:12px; padding:1.25rem; border:1px solid rgba(0,0,0,.05); height:100%; }
        .pk-stat strong { display:block; font-size:1.75rem; color:#0f766e; }
        .nxl-navigation .nxl-link.active, .nxl-navigation .nxl-link:hover { color:#0f766e; }
        .logo-text { font-weight:700; color:#0f766e; padding:.85rem 1rem; display:inline-block; }
        .m-header { overflow: visible !important; }
        .m-header .b-brand { display:flex; align-items:center; padding:.65rem 1rem; }
        .pk-sidebar-logo { height:40px; width:auto; max-width:160px; object-fit:contain; display:block; }

        /* Header dropdown: jangan terpotong di atas / samping */
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
        .nxl-header .header-left {
            min-width: 0;
            flex: 1 1 auto;
        }
        .nxl-header .header-left > div:last-child {
            min-width: 0;
        }
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
        .pk-user-menu {
            position: relative;
        }
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
        .pk-help {
            background: #f0f7ff;
            border: 1px solid #cfe2ff;
            border-radius: 10px;
            padding: .9rem 1rem;
            font-size: .875rem;
            color: #1e3a5f;
            margin-bottom: 1rem;
        }
        .pk-help strong { color: #0f3d91; }
        .pk-help ol, .pk-help ul { margin: .4rem 0 0; padding-left: 1.15rem; }
        .pk-help li { margin-bottom: .25rem; }
        .pk-help .pk-ex { color: #64748b; font-size: .8125rem; }
        @media (max-width: 575.98px) {
            .nxl-header .header-wrapper { padding: 0 14px; }
            .nxl-header .header-left .small { display: none; }
            .nxl-header .header-left strong { max-width: 36vw; font-size: .95rem; }
            .pk-user-menu .dropdown-toggle { max-width: 38vw; font-size: .8rem; }
            .pk-user-menu .dropdown-menu { min-width: 190px; }
        }
    </style>
    @stack('styles')
</head>
<body>
<nav class="nxl-navigation">
    <div class="navbar-wrapper">
        <div class="m-header">
            <a href="{{ route('admin.dashboard') }}" class="b-brand">
                <img src="{{ asset('images/pintarkolamlogo.png') }}" alt="PintarKolam" class="pk-sidebar-logo">
            </a>
        </div>
        <div class="navbar-content">
            <ul class="nxl-navbar">
                <li class="nxl-item nxl-caption"><label>Admin</label></li>
                <li class="nxl-item">
                    <a href="{{ route('admin.dashboard') }}" class="nxl-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <span class="nxl-micon"><i class="feather-airplay"></i></span>
                        <span class="nxl-mtext">Dashboard</span>
                    </a>
                </li>
                <li class="nxl-item">
                    <a href="{{ route('admin.users') }}" class="nxl-link {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                        <span class="nxl-micon"><i class="feather-users"></i></span>
                        <span class="nxl-mtext">Pengguna</span>
                    </a>
                </li>
                <li class="nxl-item">
                    <a href="{{ route('admin.farmers') }}" class="nxl-link {{ request()->routeIs('admin.farmers*') || request()->routeIs('admin.ponds') || request()->routeIs('admin.cycles') ? 'active' : '' }}">
                        <span class="nxl-micon"><i class="feather-user-check"></i></span>
                        <span class="nxl-mtext">Pembudidaya</span>
                    </a>
                </li>
                <li class="nxl-item">
                    <a href="{{ route('admin.water') }}" class="nxl-link {{ request()->routeIs('admin.water') ? 'active' : '' }}">
                        <span class="nxl-micon"><i class="feather-droplet"></i></span>
                        <span class="nxl-mtext">Kualitas Air</span>
                    </a>
                </li>
                <li class="nxl-item">
                    <a href="{{ route('admin.species') }}" class="nxl-link {{ request()->routeIs('admin.species') ? 'active' : '' }}">
                        <span class="nxl-micon"><i class="feather-box"></i></span>
                        <span class="nxl-mtext">Jenis Ikan</span>
                    </a>
                </li>
                <li class="nxl-item">
                    <a href="{{ route('admin.parameters') }}" class="nxl-link {{ request()->routeIs('admin.parameters') ? 'active' : '' }}">
                        <span class="nxl-micon"><i class="feather-sliders"></i></span>
                        <span class="nxl-mtext">Parameter Air</span>
                    </a>
                </li>
                <li class="nxl-item">
                    <a href="{{ route('admin.rules') }}" class="nxl-link {{ request()->routeIs('admin.rules') ? 'active' : '' }}">
                        <span class="nxl-micon"><i class="feather-list"></i></span>
                        <span class="nxl-mtext">Aturan Rekomendasi</span>
                    </a>
                </li>
                <li class="nxl-item">
                    <a href="{{ route('admin.products') }}" class="nxl-link {{ request()->routeIs('admin.products*') ? 'active' : '' }}">
                        <span class="nxl-micon"><i class="feather-shopping-bag"></i></span>
                        <span class="nxl-mtext">Katalog</span>
                    </a>
                </li>
                <li class="nxl-item">
                    <a href="{{ route('admin.blog.index') }}" class="nxl-link {{ request()->routeIs('admin.blog*') ? 'active' : '' }}">
                        <span class="nxl-micon"><i class="feather-edit-3"></i></span>
                        <span class="nxl-mtext">Blog</span>
                    </a>
                </li>
                <li class="nxl-item">
                    <a href="{{ route('admin.settings.edit') }}" class="nxl-link {{ request()->routeIs('admin.settings*') ? 'active' : '' }}">
                        <span class="nxl-micon"><i class="feather-settings"></i></span>
                        <span class="nxl-mtext">Pengaturan Website</span>
                    </a>
                </li>
                <li class="nxl-item nxl-caption"><label>Lainnya</label></li>
                <li class="nxl-item">
                    <a href="{{ route('landing') }}" class="nxl-link" target="_blank">
                        <span class="nxl-micon"><i class="feather-external-link"></i></span>
                        <span class="nxl-mtext">Landing Page</span>
                    </a>
                </li>
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
            <div class="nxl-navigation-toggle">
                <a href="javascript:void(0);" id="menu-mini-button"><i class="feather-align-left"></i></a>
                <a href="javascript:void(0);" id="menu-expend-button" style="display:none"><i class="feather-arrow-right"></i></a>
            </div>
            <div>
                <strong>@yield('title', 'Dashboard')</strong>
                <div class="small text-muted">Panel Admin PintarKolam</div>
            </div>
        </div>
        <div class="header-right ms-auto">
            <div class="d-flex align-items-center gap-3">
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
            <div class="alert alert-danger">
                <ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif
        @yield('content')
    </div>
</main>

<script src="{{ asset('duralux/vendors/js/vendors.min.js') }}"></script>
<script src="{{ asset('duralux/js/common-init.min.js') }}"></script>
@stack('scripts')
</body>
</html>
