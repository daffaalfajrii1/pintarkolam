<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan {{ $cycle->name }} — PintarKolam</title>
    <link rel="icon" type="image/png" href="{{ asset('images/pintarkolamlogo.png') }}">
    <style>
        :root {
            --ink: #0f2744;
            --muted: #5b6b7c;
            --line: #d7e0ea;
            --teal: #0f766e;
            --teal-soft: #e6f4f2;
            --navy: #123a5c;
            --ok: #047857;
            --warn: #b45309;
            --danger: #b91c1c;
            --paper: #ffffff;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            color: var(--ink);
            background: #e8eef4;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            font-size: 13px;
            line-height: 1.45;
        }
        .sheet {
            max-width: 900px;
            margin: 20px auto;
            background: var(--paper);
            box-shadow: 0 12px 40px rgba(15, 39, 68, .12);
            border: 1px solid #cfd9e4;
        }
        .actions {
            max-width: 900px;
            margin: 16px auto 0;
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            height: 38px;
            padding: 0 14px;
            border-radius: 8px;
            border: 0;
            cursor: pointer;
            text-decoration: none;
            font-weight: 700;
            font-size: 13px;
        }
        .btn-primary { background: #0f766e; color: #fff; }
        .btn-muted { background: #64748b; color: #fff; }

        .letterhead {
            display: grid;
            grid-template-columns: 72px 1fr auto;
            gap: 14px;
            align-items: center;
            padding: 22px 28px 16px;
            border-bottom: 3px solid var(--navy);
            background: linear-gradient(180deg, #f7fbfa 0%, #fff 70%);
        }
        .letterhead img {
            width: 64px;
            height: 64px;
            object-fit: contain;
        }
        .org-name {
            margin: 0;
            font-size: 20px;
            font-weight: 800;
            color: var(--navy);
            letter-spacing: -.02em;
        }
        .org-sub {
            margin: 2px 0 0;
            color: var(--muted);
            font-size: 12px;
        }
        .doc-badge {
            text-align: right;
            border: 1px solid var(--line);
            border-radius: 10px;
            padding: 8px 12px;
            background: #fff;
            min-width: 150px;
        }
        .doc-badge strong {
            display: block;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: var(--teal);
        }
        .doc-badge span { font-size: 12px; color: var(--muted); }

        .body { padding: 20px 28px 28px; }

        .title-block {
            text-align: center;
            margin: 8px 0 18px;
        }
        .title-block h1 {
            margin: 0;
            font-size: 18px;
            text-transform: uppercase;
            letter-spacing: .04em;
            color: var(--navy);
        }
        .title-block .cycle-name {
            margin-top: 4px;
            font-size: 15px;
            font-weight: 700;
        }
        .title-block .meta {
            margin-top: 4px;
            color: var(--muted);
            font-size: 12px;
        }

        .meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
            font-size: 12.5px;
        }
        .meta-table th,
        .meta-table td {
            border: 1px solid var(--line);
            padding: 8px 10px;
            vertical-align: top;
        }
        .meta-table th {
            width: 22%;
            background: #f4f8f7;
            text-align: left;
            font-weight: 700;
            color: var(--navy);
        }

        .section-title {
            margin: 18px 0 8px;
            padding: 6px 10px;
            background: var(--teal-soft);
            border-left: 4px solid var(--teal);
            font-size: 13px;
            font-weight: 800;
            color: var(--navy);
            text-transform: uppercase;
            letter-spacing: .03em;
        }

        .kpi-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-bottom: 8px;
        }
        .kpi {
            border: 1px solid var(--line);
            border-radius: 10px;
            padding: 12px;
            background: #fff;
        }
        .kpi .label {
            font-size: 11px;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: .04em;
            margin-bottom: 4px;
        }
        .kpi .value {
            font-size: 20px;
            font-weight: 800;
            color: var(--navy);
            line-height: 1.1;
        }
        .kpi .hint { font-size: 11px; color: var(--muted); margin-top: 2px; }

        .finance {
            width: 100%;
            border-collapse: collapse;
            margin-top: 4px;
        }
        .finance th, .finance td {
            border: 1px solid var(--line);
            padding: 9px 10px;
        }
        .finance th {
            background: #f4f8f7;
            text-align: left;
            width: 55%;
        }
        .finance td { text-align: right; font-variant-numeric: tabular-nums; font-weight: 700; }
        .finance tr.total th,
        .finance tr.total td {
            background: #123a5c;
            color: #fff;
        }
        .pos { color: var(--ok); }
        .neg { color: var(--danger); }

        .data {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }
        .data th, .data td {
            border: 1px solid var(--line);
            padding: 7px 8px;
            text-align: left;
        }
        .data th {
            background: #123a5c;
            color: #fff;
            font-weight: 700;
        }
        .data tr:nth-child(even) td { background: #f8fafc; }
        .empty { color: var(--muted); font-style: italic; }

        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
        }
        .badge-ok { background: #d1fae5; color: #065f46; }
        .badge-warn { background: #ffedd5; color: #9a3412; }
        .badge-danger { background: #fee2e2; color: #991b1b; }
        .badge-info { background: #e0f2fe; color: #075985; }

        .rec-list { margin: 0; padding-left: 18px; }
        .rec-list li { margin-bottom: 6px; }

        .sign-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-top: 28px;
        }
        .sign {
            text-align: center;
            min-height: 120px;
        }
        .sign .place {
            color: var(--muted);
            margin-bottom: 48px;
            font-size: 12px;
        }
        .sign .name {
            font-weight: 800;
            border-top: 1px solid var(--ink);
            display: inline-block;
            min-width: 180px;
            padding-top: 6px;
        }
        .sign .role { color: var(--muted); font-size: 12px; }

        .footer {
            margin-top: 22px;
            padding-top: 10px;
            border-top: 1px solid var(--line);
            color: var(--muted);
            font-size: 11px;
            display: flex;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
        }

        @media print {
            body { background: #fff; }
            .actions { display: none !important; }
            .sheet {
                margin: 0;
                box-shadow: none;
                border: 0;
                max-width: none;
            }
            .letterhead { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .section-title, .data th, .finance tr.total th, .finance tr.total td {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            @page { margin: 12mm; }
        }
        @media (max-width: 720px) {
            .letterhead { grid-template-columns: 56px 1fr; }
            .doc-badge { grid-column: 1 / -1; text-align: left; }
            .kpi-grid, .sign-grid { grid-template-columns: 1fr 1fr; }
            .body, .letterhead { padding-left: 16px; padding-right: 16px; }
        }
    </style>
</head>
<body>
@php
    $owner = $cycle->pond?->farmerProfile?->owner_name
        ?: $cycle->user?->farmerProfile?->owner_name
        ?: $cycle->user?->name;
    $shop = $cycle->pond?->farmerProfile?->shop_name
        ?: $cycle->pond?->farmerProfile?->business_name
        ?: $cycle->user?->farmerProfile?->business_name;
    $health = $cycle->pond?->latestHealthScore;
    $cat = $health?->category;
    $badgeClass = match ($cat) {
        'kritis' => 'badge-danger',
        'waspada' => 'badge-warn',
        default => 'badge-ok',
    };
    $district = $cycle->pond?->farmerProfile?->district
        ?: $cycle->user?->farmerProfile?->district
        ?: 'Rejang Lebong';
    $survival = $cycle->seed_count > 0
        ? round(($alive / $cycle->seed_count) * 100, 1)
        : 0;
@endphp

<div class="actions">
    <button class="btn btn-primary" type="button" onclick="window.print()">Cetak / Simpan PDF</button>
    <a class="btn btn-muted" href="{{ route('user.cycles.show', $cycle) }}">Kembali ke Laporan</a>
</div>

<div class="sheet">
    <header class="letterhead">
        <img src="{{ asset('images/pintarkolamlogo.png') }}" alt="PintarKolam">
        <div>
            <h2 class="org-name">PintarKolam</h2>
            <p class="org-sub">Sistem Informasi Budidaya Ikan Air Tawar</p>
            <p class="org-sub">Kabupaten Rejang Lebong · Provinsi Bengkulu</p>
        </div>
        <div class="doc-badge">
            <strong>Dokumen Laporan</strong>
            <span>No. PK-{{ str_pad((string) $cycle->id, 4, '0', STR_PAD_LEFT) }}/{{ $printedAt->format('Y') }}</span>
            <span>{{ $printedAt->format('d/m/Y H:i') }}</span>
        </div>
    </header>

    <div class="body">
        <div class="title-block">
            <h1>Laporan Siklus Budidaya</h1>
            <div class="cycle-name">{{ $cycle->name }}</div>
            <div class="meta">
                Status:
                <span class="badge badge-info">{{ $cycle->status }}</span>
                @if($cat)
                    · Kesehatan kolam:
                    <span class="badge {{ $badgeClass }}">{{ $cat }} {{ $health?->score }}</span>
                @endif
            </div>
        </div>

        <table class="meta-table">
            <tr>
                <th>Pembudidaya</th>
                <td>{{ $owner }}</td>
                <th>Nama usaha / toko</th>
                <td>{{ $shop ?: '—' }}</td>
            </tr>
            <tr>
                <th>Kolam</th>
                <td>{{ $cycle->pond?->name }} ({{ $cycle->pond?->type }})</td>
                <th>Volume kolam</th>
                <td>{{ $cycle->pond?->volume_m3 ? number_format((float) $cycle->pond->volume_m3, 2, ',', '.').' m³' : '—' }}</td>
            </tr>
            <tr>
                <th>Jenis ikan</th>
                <td>{{ $cycle->fishSpecies?->name }}@if($cycle->fishSpecies?->scientific_name) <em>({{ $cycle->fishSpecies->scientific_name }})</em>@endif</td>
                <th>Tanggal tebar</th>
                <td>{{ $cycle->stocking_date?->format('d/m/Y') ?: '—' }}</td>
            </tr>
            <tr>
                <th>Target panen</th>
                <td>{{ $cycle->target_harvest_date?->format('d/m/Y') ?: ($estimate->estimated_harvest_date?->format('d/m/Y') ?: '—') }}</td>
                <th>Target ukuran</th>
                <td>{{ $cycle->target_size_gram ? number_format((float) $cycle->target_size_gram, 0, ',', '.').' gram' : '—' }}</td>
            </tr>
        </table>

        <div class="section-title">Ringkasan Produksi</div>
        <div class="kpi-grid">
            <div class="kpi">
                <div class="label">Tebar awal</div>
                <div class="value">{{ number_format($cycle->seed_count) }}</div>
                <div class="hint">ekor</div>
            </div>
            <div class="kpi">
                <div class="label">Sisa ikan</div>
                <div class="value">{{ number_format($alive) }}</div>
                <div class="hint">survival {{ $survival }}%</div>
            </div>
            <div class="kpi">
                <div class="label">Kematian</div>
                <div class="value">{{ number_format($deaths) }}</div>
                <div class="hint">ekor</div>
            </div>
            <div class="kpi">
                <div class="label">Total pakan</div>
                <div class="value">{{ number_format($totalFeed, 2, ',', '.') }}</div>
                <div class="hint">kg</div>
            </div>
            <div class="kpi">
                <div class="label">FCR</div>
                <div class="value">{{ $fcr ?? '—' }}</div>
                <div class="hint">feed conversion ratio</div>
            </div>
            <div class="kpi">
                <div class="label">Indeks kesehatan</div>
                <div class="value">{{ $health?->score ?? '—' }}</div>
                <div class="hint">{{ $cat ?? 'belum ada data' }}</div>
            </div>
        </div>

        <div class="section-title">Estimasi Panen &amp; Keuangan</div>
        <table class="finance">
            <tr>
                <th>Estimasi jumlah panen</th>
                <td>{{ number_format($estimate->estimated_fish_count ?? 0) }} ekor</td>
            </tr>
            <tr>
                <th>Estimasi bobot panen</th>
                <td>{{ number_format((float) ($estimate->estimated_total_weight_kg ?? 0), 2, ',', '.') }} kg</td>
            </tr>
            <tr>
                <th>Estimasi tanggal panen</th>
                <td>{{ $estimate->estimated_harvest_date?->format('d/m/Y') ?: '—' }}</td>
            </tr>
            <tr>
                <th>Total biaya tercatat</th>
                <td>Rp {{ number_format($totalCost, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Perkiraan / total pendapatan</th>
                <td>Rp {{ number_format($totalRevenue, 0, ',', '.') }}</td>
            </tr>
            <tr class="total">
                <th>Keuntungan bersih (estimasi)</th>
                <td>Rp {{ number_format($netProfit, 0, ',', '.') }}</td>
            </tr>
        </table>

        @if($recommendations->isNotEmpty())
            <div class="section-title">Rekomendasi Terbaru</div>
            <ul class="rec-list">
                @foreach($recommendations as $rec)
                    <li>
                        <strong>{{ $rec->title }}</strong>
                        <span class="badge {{ $rec->severity === 'critical' ? 'badge-danger' : ($rec->severity === 'warning' ? 'badge-warn' : 'badge-info') }}">{{ $rec->severity }}</span>
                        <div>{{ $rec->advice }}</div>
                    </li>
                @endforeach
            </ul>
        @endif

        <div class="section-title">Riwayat Kualitas Air</div>
        <table class="data">
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>pH</th>
                    <th>Suhu (°C)</th>
                    <th>DO (mg/L)</th>
                    <th>Catatan</th>
                </tr>
            </thead>
            <tbody>
            @forelse($waterLogs as $log)
                <tr>
                    <td>{{ $log->measured_at?->format('d/m/Y H:i') }}</td>
                    <td>{{ $log->ph }}</td>
                    <td>{{ $log->temperature_c }}</td>
                    <td>{{ $log->dissolved_oxygen }}</td>
                    <td>{{ $log->notes ?: '—' }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="empty">Belum ada data kualitas air.</td></tr>
            @endforelse
            </tbody>
        </table>

        <div class="section-title">Riwayat Pakan (terbaru)</div>
        <table class="data">
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>Jenis</th>
                    <th>Jumlah (kg)</th>
                    <th>Sisa</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
            @forelse($feedingLogs as $log)
                <tr>
                    <td>{{ $log->fed_at?->format('d/m/Y H:i') }}</td>
                    <td>{{ $log->feed_type ?: '—' }}</td>
                    <td>{{ number_format((float) $log->amount_kg, 3, ',', '.') }}</td>
                    <td>{{ $log->leftover_kg !== null ? number_format((float) $log->leftover_kg, 3, ',', '.') : '—' }}</td>
                    <td>{{ $log->status }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="empty">Belum ada catatan pakan.</td></tr>
            @endforelse
            </tbody>
        </table>

        <div class="section-title">Riwayat Kematian</div>
        <table class="data">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Jumlah</th>
                    <th>Dugaan penyebab</th>
                    <th>Catatan</th>
                </tr>
            </thead>
            <tbody>
            @forelse($mortalityLogs as $log)
                <tr>
                    <td>{{ $log->recorded_at?->format('d/m/Y') }}</td>
                    <td>{{ $log->death_count }} ekor</td>
                    <td>{{ $log->suspected_cause ?: '—' }}</td>
                    <td>{{ $log->notes ?: '—' }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="empty">Belum ada catatan kematian.</td></tr>
            @endforelse
            </tbody>
        </table>

        <div class="sign-grid">
            <div class="sign">
                <div class="place">Mengetahui,</div>
                <div class="name">________________</div>
                <div class="role">Petugas / Penyuluh</div>
            </div>
            <div class="sign">
                <div class="place">{{ $district }}, {{ $printedAt->translatedFormat('d F Y') }}</div>
                <div class="name">{{ $owner }}</div>
                <div class="role">Pembudidaya</div>
            </div>
        </div>

        <div class="footer">
            <span>Dicetak otomatis dari sistem PintarKolam.</span>
            <span>Dokumen ini bersifat ringkasan operasional budidaya.</span>
        </div>
    </div>
</div>
</body>
</html>
