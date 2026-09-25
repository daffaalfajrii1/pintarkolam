<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $pond->name }} — PintarKolam</title>
    <link rel="icon" type="image/png" href="{{ asset('images/pintarkolamlogo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=Fraunces:opsz,wght@9..144,600;9..144,700&display=swap" rel="stylesheet">
    <style>
        :root {
            --pk-deep: #0a2f4a;
            --pk-teal: #0f766e;
            --pk-sky: #38bdf8;
            --pk-sand: #f4f7f5;
            --pk-ink: #12263a;
            --pk-muted: #5b6b7c;
            --pk-ok: #059669;
            --pk-warn: #d97706;
            --pk-danger: #dc2626;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            font-family: "DM Sans", system-ui, sans-serif;
            color: var(--pk-ink);
            background:
                radial-gradient(1200px 500px at 10% -10%, rgba(56,189,248,.28), transparent 55%),
                radial-gradient(900px 420px at 100% 0%, rgba(15,118,110,.18), transparent 50%),
                linear-gradient(165deg, #e8f4f8 0%, var(--pk-sand) 45%, #eef6f3 100%);
        }
        .pk-public {
            max-width: 720px;
            margin: 0 auto;
            padding: 1.25rem 1rem 2.5rem;
        }
        .pk-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 1.25rem;
        }
        .pk-brand {
            display: inline-flex;
            align-items: center;
            gap: .55rem;
            text-decoration: none;
            color: var(--pk-deep);
            font-weight: 700;
        }
        .pk-brand img { height: 36px; width: auto; display: block; }
        .pk-back {
            color: var(--pk-muted);
            text-decoration: none;
            font-size: .9rem;
        }
        .pk-back:hover { color: var(--pk-teal); }

        .pk-hero {
            position: relative;
            overflow: hidden;
            border-radius: 1.35rem;
            padding: 1.5rem 1.35rem 1.35rem;
            background:
                linear-gradient(135deg, rgba(10,47,74,.94), rgba(15,118,110,.88)),
                url("{{ asset('images/ss.png') }}") center/cover;
            color: #fff;
            box-shadow: 0 18px 40px rgba(10, 47, 74, .22);
            margin-bottom: 1rem;
        }
        .pk-hero::after {
            content: "";
            position: absolute;
            inset: auto -20% -40% auto;
            width: 220px; height: 220px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(56,189,248,.35), transparent 70%);
            pointer-events: none;
        }
        .pk-hero h1 {
            font-family: Fraunces, Georgia, serif;
            font-size: clamp(1.6rem, 4vw, 2.1rem);
            margin: 0 0 .35rem;
            letter-spacing: -.02em;
            position: relative;
            z-index: 1;
        }
        .pk-hero .meta {
            opacity: .9;
            font-size: .95rem;
            margin-bottom: 1.1rem;
            position: relative;
            z-index: 1;
        }
        .pk-score-row {
            display: flex;
            flex-wrap: wrap;
            gap: .85rem;
            align-items: stretch;
            position: relative;
            z-index: 1;
        }
        .pk-score {
            background: rgba(255,255,255,.12);
            border: 1px solid rgba(255,255,255,.18);
            backdrop-filter: blur(6px);
            border-radius: 1rem;
            padding: .85rem 1rem;
            min-width: 140px;
        }
        .pk-score strong {
            display: block;
            font-size: 2rem;
            line-height: 1;
            font-weight: 700;
        }
        .pk-score span { font-size: .8rem; opacity: .85; }
        .pk-badge {
            display: inline-flex;
            align-items: center;
            height: 32px;
            padding: 0 12px;
            border-radius: 999px;
            font-size: .8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .03em;
            align-self: center;
        }
        .pk-badge-ok { background: #d1fae5; color: #065f46; }
        .pk-badge-warn { background: #ffedd5; color: #9a3412; }
        .pk-badge-danger { background: #fee2e2; color: #991b1b; }

        .pk-panel {
            background: #fff;
            border-radius: 1.15rem;
            box-shadow: 0 10px 28px rgba(15, 23, 42, .07);
            border: 1px solid rgba(15, 23, 42, .04);
            margin-bottom: 1rem;
            overflow: hidden;
        }
        .pk-panel-head {
            padding: .95rem 1.15rem;
            border-bottom: 1px solid #eef2f6;
            font-weight: 700;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: .5rem;
        }
        .pk-panel-body { padding: 1.05rem 1.15rem; }

        .pk-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: .65rem;
            margin-bottom: 1rem;
        }
        .pk-stat {
            background: #f5faf8;
            border-radius: .85rem;
            padding: .75rem .7rem;
            text-align: center;
        }
        .pk-stat strong { display: block; font-size: 1.05rem; color: var(--pk-deep); }
        .pk-stat span { font-size: .72rem; color: var(--pk-muted); }

        .pk-cycle {
            border: 1px solid #e8eef3;
            border-radius: 1rem;
            padding: 1rem;
            margin-bottom: .75rem;
            background: linear-gradient(180deg, #fff, #f9fcfb);
        }
        .pk-cycle:last-child { margin-bottom: 0; }
        .pk-cycle h3 {
            margin: 0 0 .25rem;
            font-size: 1.05rem;
            font-family: Fraunces, Georgia, serif;
        }
        .pk-cycle .sub { color: var(--pk-muted); font-size: .88rem; margin-bottom: .75rem; }
        .pk-cycle-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: .55rem .75rem;
            font-size: .88rem;
        }
        .pk-cycle-grid dt { color: var(--pk-muted); font-size: .75rem; margin: 0; }
        .pk-cycle-grid dd { margin: 0; font-weight: 600; color: var(--pk-deep); }

        .pk-cta {
            display: flex;
            flex-wrap: wrap;
            gap: .6rem;
            margin-top: 1rem;
        }
        .pk-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            height: 42px;
            padding: 0 1rem;
            border-radius: .75rem;
            font-weight: 700;
            font-size: .9rem;
            text-decoration: none;
            border: 0;
        }
        .pk-btn-primary {
            background: linear-gradient(90deg, #0f766e, #0ea5a4);
            color: #fff;
        }
        .pk-btn-ghost {
            background: #eef6f4;
            color: var(--pk-teal);
        }
        .pk-foot {
            text-align: center;
            color: var(--pk-muted);
            font-size: .8rem;
            margin-top: 1.5rem;
        }
        @media (max-width: 560px) {
            .pk-stats { grid-template-columns: 1fr 1fr; }
            .pk-cycle-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
@php
    $owner = $pond->farmerProfile?->shop_name
        ?: $pond->farmerProfile?->business_name
        ?: $pond->user?->name;
    $cat = $pond->latestHealthScore?->category;
    $score = $pond->latestHealthScore?->score;
    $badgeClass = match ($cat) {
        'kritis' => 'pk-badge-danger',
        'waspada' => 'pk-badge-warn',
        default => 'pk-badge-ok',
    };
    $wa = $pond->farmerProfile?->whatsapp;
    $waUrl = $wa ? 'https://wa.me/'.preg_replace('/\D+/', '', $wa) : null;
@endphp
<div class="pk-public">
    <div class="pk-top">
        <a class="pk-brand" href="{{ route('landing') }}">
            <img src="{{ asset('images/pintarkolamlogo.png') }}" alt="PintarKolam">
            <span>PintarKolam</span>
        </a>
        <a class="pk-back" href="{{ route('landing') }}">← Beranda</a>
    </div>

    <section class="pk-hero">
        <h1>{{ $pond->name }}</h1>
        <div class="meta">
            {{ $owner }}
            · {{ ucfirst($pond->type) }}
            @if($pond->volume_m3) · {{ number_format((float) $pond->volume_m3, 0) }} m³ @endif
            @if($pond->farmerProfile?->district) · {{ $pond->farmerProfile->district }} @endif
        </div>
        <div class="pk-score-row">
            <div class="pk-score">
                <strong>{{ $score ?? '—' }}</strong>
                <span>Indeks kesehatan kolam</span>
            </div>
            @if($cat)
                <span class="pk-badge {{ $badgeClass }}">{{ $cat }}</span>
            @endif
        </div>
    </section>

    <div class="pk-stats">
        <div class="pk-stat">
            <strong>{{ $pond->cycles->count() }}</strong>
            <span>Siklus aktif</span>
        </div>
        <div class="pk-stat">
            <strong>{{ $pond->cycles->sum(fn ($c) => $c->aliveCount()) }}</strong>
            <span>Estimasi sisa ikan</span>
        </div>
        <div class="pk-stat">
            <strong>{{ ucfirst($pond->status) }}</strong>
            <span>Status kolam</span>
        </div>
    </div>

    <section class="pk-panel">
        <div class="pk-panel-head">
            <span>Siklus aktif</span>
        </div>
        <div class="pk-panel-body">
            @forelse($pond->cycles as $cycle)
                @php $est = $cycle->harvestEstimate; @endphp
                <article class="pk-cycle">
                    <h3>{{ $cycle->name }}</h3>
                    <div class="sub">{{ $cycle->fishSpecies?->name ?? 'Ikan' }} · status {{ $cycle->status }}</div>
                    <dl class="pk-cycle-grid">
                        <div>
                            <dt>Sisa ikan</dt>
                            <dd>{{ number_format($cycle->aliveCount()) }} ekor</dd>
                        </div>
                        <div>
                            <dt>FCR</dt>
                            <dd>{{ $cycle->fcr() ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt>Perkiraan panen</dt>
                            <dd>
                                @if($est?->estimated_harvest_date)
                                    {{ $est->estimated_harvest_date->format('d M Y') }}
                                @elseif($cycle->target_harvest_date)
                                    {{ $cycle->target_harvest_date->format('d M Y') }}
                                @else
                                    —
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt>Proyeksi hasil</dt>
                            <dd>
                                @if($est)
                                    ±{{ number_format((float) $est->estimated_total_weight_kg, 1) }} kg
                                    ({{ number_format($est->estimated_fish_count) }} ekor)
                                @else
                                    —
                                @endif
                            </dd>
                        </div>
                    </dl>
                </article>
            @empty
                <p style="color:var(--pk-muted);margin:0">Belum ada siklus aktif di kolam ini.</p>
            @endforelse

            <div class="pk-cta">
                @if($waUrl)
                    <a class="pk-btn pk-btn-primary" href="{{ $waUrl }}" target="_blank" rel="noopener">Hubungi via WhatsApp</a>
                @endif
                <a class="pk-btn pk-btn-ghost" href="{{ route('landing.catalog') }}">Lihat katalog</a>
            </div>
        </div>
    </section>

    <p class="pk-foot">Dipantau dengan PintarKolam · Budidaya ikan Rejang Lebong</p>
</div>
</body>
</html>
