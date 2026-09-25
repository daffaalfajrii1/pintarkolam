@extends('layouts.user')

@section('title', 'Beranda')

@section('content')
<div class="pk-page-head mb-3">
    <h2 class="pk-page-title mb-1">Beranda</h2>
    <div class="small text-muted">Ringkasan kondisi kolam dan rekomendasi hari ini</div>
</div>
<div class="row g-3 mb-4">
    <div class="col-md-3"><div class="pk-stat"><strong>{{ $pondsCount }}</strong>Kolam</div></div>
    <div class="col-md-3"><div class="pk-stat"><strong>{{ $cyclesCount }}</strong>Siklus aktif</div></div>
    <div class="col-md-3"><div class="pk-stat"><strong>{{ $alertCounts['waspada'] }}</strong>Waspada</div></div>
    <div class="col-md-3"><div class="pk-stat"><strong>{{ $alertCounts['kritis'] }}</strong>Kritis</div></div>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-4">
        <div class="card pk-chart-card h-100">
            <div class="card-header">Grafik pH</div>
            <div class="card-body"><canvas id="dashPh"></canvas></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card pk-chart-card h-100">
            <div class="card-header">Grafik Suhu (°C)</div>
            <div class="card-body"><canvas id="dashTemp"></canvas></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card pk-chart-card h-100">
            <div class="card-header">Grafik DO (mg/L)</div>
            <div class="card-body"><canvas id="dashDo"></canvas></div>
        </div>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-lg-5">
        <div class="card h-100">
            <div class="card-header">Rekomendasi Terbaru</div>
            <div class="card-body">
                @forelse($recommendations as $rec)
                    <div class="border-bottom py-2">
                        <div class="d-flex justify-content-between gap-2">
                            <strong>{{ $rec->title }}</strong>
                            <span class="badge bg-{{ $rec->severity==='critical'?'danger':($rec->severity==='warning'?'warning':'info') }}">{{ $rec->severity }}</span>
                        </div>
                        <div class="small text-muted">{{ $rec->cycle?->name }} · {{ $rec->created_at?->format('d/m/Y H:i') }}</div>
                        <div class="small">{{ $rec->advice }}</div>
                    </div>
                @empty
                    <p class="text-muted mb-0">Belum ada rekomendasi. Input kualitas air pada siklus untuk menghasilkan saran.</p>
                @endforelse
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="row g-3">
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between"><span>Kolam</span><a href="{{ route('user.ponds.index') }}">Kelola</a></div>
                    <div class="card-body">
                        @forelse($ponds as $pond)
                            @php $cat = $pond->latestHealthScore?->category; @endphp
                            <div class="border-bottom py-2 d-flex justify-content-between">
                                <div>
                                    <a href="{{ route('user.ponds.show', $pond) }}"><strong>{{ $pond->name }}</strong></a>
                                    <div class="small text-muted">{{ $pond->status }}</div>
                                </div>
                                <div class="text-end">
                                    <strong>{{ $pond->latestHealthScore?->score ?? '-' }}</strong>
                                    @if($cat)<div class="small">{{ $cat }}</div>@endif
                                </div>
                            </div>
                        @empty
                            <p class="text-muted mb-0">Belum ada kolam.</p>
                        @endforelse
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between"><span>Siklus</span><a href="{{ route('user.cycles.create') }}">Tambah</a></div>
                    <div class="card-body">
                        @forelse($cycles as $cycle)
                            <div class="border-bottom py-2">
                                <a href="{{ route('user.cycles.show', $cycle) }}"><strong>{{ $cycle->name }}</strong></a>
                                <div class="small text-muted">{{ $cycle->pond?->name }} · {{ $cycle->status }}</div>
                            </div>
                        @empty
                            <p class="text-muted mb-0">Belum ada siklus.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const dash = @json($chart);
function makeLine(id, data, color) {
    new Chart(document.getElementById(id), {
        type: 'line',
        data: { labels: dash.labels, datasets: [{ data, borderColor: color, backgroundColor: color + '22', tension: .3, fill: true }] },
        options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: false } } }
    });
}
makeLine('dashPh', dash.ph, '#0f766e');
makeLine('dashTemp', dash.temp, '#f59e0b');
makeLine('dashDo', dash.do, '#3b82f6');
</script>
@endpush
