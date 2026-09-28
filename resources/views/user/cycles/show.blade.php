@extends('layouts.user')
@section('title', $cycle->name.' — Laporan')
@section('content')
@include('user.cycles._nav')
@php $health = $cycle->pond?->latestHealthScore; @endphp

@include('user.cycles._recommendations')

<div class="row g-3 mb-3">
    <div class="col-md-2"><div class="pk-stat"><strong>{{ $cycle->seed_count }}</strong>Tebar</div></div>
    <div class="col-md-2"><div class="pk-stat"><strong>{{ $alive }}</strong>Sisa ikan</div></div>
    <div class="col-md-2"><div class="pk-stat"><strong>{{ $deaths }}</strong>Kematian</div></div>
    <div class="col-md-2"><div class="pk-stat"><strong>{{ number_format($totalFeed, 2, ',', '.') }}</strong>Pakan (kg)</div></div>
    <div class="col-md-2"><div class="pk-stat"><strong>{{ $fcr ?? '-' }}</strong>FCR</div></div>
    <div class="col-md-2"><div class="pk-stat"><strong>{{ $health?->score ?? '-' }}</strong>{{ $health?->category ?? 'skor' }}</div></div>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-4">
        <div class="card pk-chart-card h-100">
            <div class="card-header">Grafik pH</div>
            <div class="card-body"><canvas id="chartPh"></canvas></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card pk-chart-card h-100">
            <div class="card-header">Grafik Suhu (°C)</div>
            <div class="card-body"><canvas id="chartTemp"></canvas></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card pk-chart-card h-100">
            <div class="card-header">Grafik DO (mg/L)</div>
            <div class="card-body"><canvas id="chartDo"></canvas></div>
        </div>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-4">
        <div class="card pk-chart-card h-100">
            <div class="card-header">Kematian</div>
            <div class="card-body"><canvas id="chartMort"></canvas></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card pk-chart-card h-100">
            <div class="card-header">Berat rata-rata (gram)</div>
            <div class="card-body"><canvas id="chartWeight"></canvas></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card pk-chart-card h-100">
            <div class="card-header">Penggunaan pakan (kg)</div>
            <div class="card-body"><canvas id="chartFeed"></canvas></div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-4">
        <div class="card h-100 border-0 shadow-sm" style="border-top:3px solid #0f766e!important">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <span class="fw-bold">Ringkasan Resmi</span>
                <a href="{{ route('user.cycles.print', $cycle) }}" target="_blank" class="pk-btn pk-btn-primary pk-btn-sm">Cetak PDF</a>
            </div>
            <div class="card-body">
                <div class="small text-muted mb-2">Estimasi berdasarkan jenis ikan &amp; data siklus</div>
                <table class="table table-sm mb-0">
                    <tr>
                        <td class="text-muted border-0 ps-0">Estimasi panen</td>
                        <td class="text-end border-0 pe-0 fw-semibold">{{ number_format($estimate->estimated_fish_count) }} ekor · {{ number_format((float)$estimate->estimated_total_weight_kg, 2, ',', '.') }} kg</td>
                    </tr>
                    <tr>
                        <td class="text-muted ps-0">Tanggal panen</td>
                        <td class="text-end pe-0 fw-semibold">{{ $estimate->estimated_harvest_date?->format('d/m/Y') ?: '—' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted ps-0">Nilai estimasi</td>
                        <td class="text-end pe-0 fw-semibold pk-money">Rp {{ number_format($estimate->estimated_value, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted ps-0">Total biaya</td>
                        <td class="text-end pe-0 fw-semibold pk-money">Rp {{ number_format($totalCost, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted ps-0 border-0">Keuntungan bersih</td>
                        <td class="text-end pe-0 border-0 fw-bold pk-money {{ $netProfit >= 0 ? 'text-success' : 'text-danger' }}">Rp {{ number_format($netProfit, 0, ',', '.') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Riwayat Air</span>
                <a href="{{ route('user.cycles.water', $cycle) }}" class="pk-btn pk-btn-outline pk-btn-sm">Kelola Air</a>
            </div>
            <div class="card-body p-0 table-responsive">
                <table class="table pk-table mb-0">
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
                        <tr><td colspan="4" class="text-center text-muted">Belum ada data.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const chart = @json($chart);
function lineChart(id, label, data, color) {
    new Chart(document.getElementById(id), {
        type: 'line',
        data: { labels: chart.labels, datasets: [{ label, data, borderColor: color, backgroundColor: color+'22', tension: .3, fill: true }] },
        options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: false } } }
    });
}
lineChart('chartPh', 'pH', chart.ph, '#0f766e');
lineChart('chartTemp', 'Suhu', chart.temp, '#f59e0b');
lineChart('chartDo', 'DO', chart.do, '#3b82f6');
new Chart(document.getElementById('chartMort'), {
    type: 'bar',
    data: { labels: chart.mortality_labels, datasets: [{ data: chart.mortality, backgroundColor: '#ef4444' }] },
    options: { plugins: { legend: { display: false } } }
});
new Chart(document.getElementById('chartWeight'), {
    type: 'line',
    data: { labels: chart.weight_labels, datasets: [{ data: chart.weight, borderColor: '#8b5cf6', tension: .3 }] },
    options: { plugins: { legend: { display: false } } }
});
new Chart(document.getElementById('chartFeed'), {
    type: 'bar',
    data: { labels: chart.feed_labels, datasets: [{ data: chart.feed, backgroundColor: '#14b8a6' }] },
    options: { plugins: { legend: { display: false } } }
});
</script>
@endpush
