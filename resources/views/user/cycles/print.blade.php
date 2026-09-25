<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan {{ $cycle->name }}</title>
    <style>
        body { font-family: Arial, sans-serif; color: #111; margin: 24px; }
        h1 { margin: 0 0 4px; font-size: 22px; }
        .muted { color: #666; font-size: 13px; }
        .grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin: 18px 0; }
        .box { border: 1px solid #ddd; border-radius: 8px; padding: 12px; }
        .box strong { display: block; font-size: 20px; margin-bottom: 4px; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border: 1px solid #ddd; padding: 8px; font-size: 12px; text-align: left; }
        th { background: #f5f5f5; }
        .actions { margin-bottom: 16px; }
        .btn { display: inline-block; padding: 8px 14px; background: #3454d1; color: #fff; text-decoration: none; border-radius: 6px; border: 0; cursor: pointer; }
        @media print { .actions { display: none; } body { margin: 0; } }
    </style>
</head>
<body>
<div class="actions">
    <button class="btn" onclick="window.print()">Cetak / Simpan PDF</button>
    <a class="btn" href="{{ route('user.cycles.show', $cycle) }}" style="background:#64748b">Kembali</a>
</div>

<h1>Laporan Siklus: {{ $cycle->name }}</h1>
<div class="muted">{{ $cycle->pond?->name }} · {{ $cycle->fishSpecies?->name }} · {{ $cycle->user?->name }} · dicetak {{ now()->format('d/m/Y H:i') }}</div>

<div class="grid">
    <div class="box"><strong>{{ $cycle->seed_count }}</strong>Tebar awal</div>
    <div class="box"><strong>{{ $alive }}</strong>Sisa ikan</div>
    <div class="box"><strong>{{ $deaths }}</strong>Kematian</div>
    <div class="box"><strong>{{ number_format($totalFeed, 2, ',', '.') }} kg</strong>Total pakan</div>
    <div class="box"><strong>{{ $fcr ?? '-' }}</strong>FCR</div>
    <div class="box"><strong>{{ $cycle->pond?->latestHealthScore?->score ?? '-' }}</strong>Indeks kesehatan</div>
</div>

<div class="box" style="margin-bottom:16px">
    <div>Estimasi panen: {{ $estimate->estimated_fish_count }} ekor · {{ $estimate->estimated_total_weight_kg }} kg</div>
    <div>Total biaya: Rp {{ number_format($totalCost, 0, ',', '.') }}</div>
    <div>Perkiraan pendapatan: Rp {{ number_format((float) $cycle->estimated_revenue, 0, ',', '.') }}</div>
    <div>Keuntungan bersih: Rp {{ number_format($netProfit, 0, ',', '.') }}</div>
</div>

<h3>Riwayat Kualitas Air</h3>
<table>
    <thead><tr><th>Waktu</th><th>pH</th><th>Suhu</th><th>DO</th></tr></thead>
    <tbody>
    @forelse($waterLogs as $log)
        <tr>
            <td>{{ $log->measured_at?->format('d/m/Y H:i') }}</td>
            <td>{{ $log->ph }}</td>
            <td>{{ $log->temperature_c }}</td>
            <td>{{ $log->dissolved_oxygen }}</td>
        </tr>
    @empty
        <tr><td colspan="4">Belum ada data</td></tr>
    @endforelse
    </tbody>
</table>
<script>window.addEventListener('load', () => setTimeout(() => window.print(), 400));</script>
</body>
</html>
