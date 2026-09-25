<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $pond->name }} — PintarKolam</title>
    <link rel="icon" type="image/png" href="{{ asset('images/pintarkolamlogo.png') }}">
    <link rel="stylesheet" href="{{ asset('duralux/css/bootstrap.min.css') }}">
    <style>
        body { background: linear-gradient(180deg, #e8f6f2, #f7fafc); min-height: 100vh; }
        .wrap { max-width: 720px; margin: 2rem auto; padding: 0 1rem; }
        .card { border: 0; border-radius: 1rem; box-shadow: 0 10px 30px rgba(15,23,42,.08); }
    </style>
</head>
<body>
<div class="wrap">
    <div class="mb-3"><a href="{{ route('landing') }}">← PintarKolam</a></div>
    <div class="card mb-3"><div class="card-body">
        <h2 class="mb-1">{{ $pond->name }}</h2>
        <p class="text-muted mb-2">{{ $pond->farmerProfile?->shop_name ?: $pond->user?->name }} · {{ $pond->type }}</p>
        @php $cat = $pond->latestHealthScore?->category; @endphp
        <div class="d-flex gap-3 align-items-center">
            <div>
                <div class="fs-3 fw-bold">{{ $pond->latestHealthScore?->score ?? '-' }}</div>
                <div class="small text-muted">Indeks kesehatan</div>
            </div>
            @if($cat)
                <span class="badge {{ $cat==='kritis'?'bg-danger':($cat==='waspada'?'bg-warning text-dark':'bg-success') }}">{{ $cat }}</span>
            @endif
        </div>
    </div></div>

    <div class="card"><div class="card-header">Siklus Aktif</div><div class="card-body">
        @forelse($pond->cycles as $cycle)
            <div class="border-bottom py-2">
                <strong>{{ $cycle->name }}</strong>
                <div class="small text-muted">{{ $cycle->fishSpecies?->name }} · sisa {{ $cycle->aliveCount() }} ekor · FCR {{ $cycle->fcr() ?? '-' }}</div>
            </div>
        @empty
            <p class="text-muted mb-0">Belum ada siklus aktif.</p>
        @endforelse
    </div></div>
</div>
</body>
</html>
