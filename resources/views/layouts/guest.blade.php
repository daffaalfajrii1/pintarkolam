<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'PintarKolam') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/pintarkolamlogo.png') }}">
    <link rel="stylesheet" href="{{ asset('duralux/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('duralux/vendors/css/vendors.min.css') }}">
    <link rel="stylesheet" href="{{ asset('duralux/css/theme.min.css') }}">
    <style>
        body.pk-auth {
            min-height: 100vh;
            background: linear-gradient(135deg, #0b1e4a 0%, #1746a2 40%, #2f80ed 70%, #56ccf2 100%);
            background-attachment: fixed;
        }
        .pk-auth-wrap {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }
        .pk-auth-card {
            width: 100%;
            max-width: 460px;
            background: rgba(255,255,255,.96);
            border-radius: 1rem;
            box-shadow: 0 24px 60px rgba(7, 24, 66, .35);
            padding: 2rem;
        }
        .pk-auth-brand {
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .pk-auth-brand a {
            display: inline-block;
            text-decoration: none;
        }
        .pk-auth-brand img {
            height: 56px;
            width: auto;
            max-width: 220px;
            object-fit: contain;
        }
        .pk-auth-card .btn-primary {
            background: linear-gradient(90deg, #1746a2, #2f80ed);
            border: 0;
        }
        .pk-auth-card .form-control:focus {
            border-color: #2f80ed;
            box-shadow: 0 0 0 .2rem rgba(47,128,237,.2);
        }
        .pk-auth-links a { color: #1746a2; }
    </style>
</head>
<body class="pk-auth">
    <div class="pk-auth-wrap">
        <div class="pk-auth-card">
            <div class="pk-auth-brand">
                <a href="{{ route('landing') }}">
                    <img src="{{ asset('images/pintarkolamlogo.png') }}" alt="PintarKolam">
                </a>
                <div class="small text-muted mt-1">Budidaya Ikan Rejang Lebong</div>
            </div>
            {{ $slot }}
        </div>
    </div>
    <script src="{{ asset('duralux/vendors/js/vendors.min.js') }}"></script>
</body>
</html>
