@php
    $recs = $recommendations ?? collect();
@endphp
@if($recs->isNotEmpty())
<div class="card mb-3">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <span>Rekomendasi Terbaru</span>
        @if(isset($latestWaterAt) && $latestWaterAt)
            <span class="small text-muted">berdasarkan ukur {{ $latestWaterAt->format('d/m/Y H:i') }}</span>
        @endif
    </div>
    <div class="card-body p-0">
        <ul class="list-group list-group-flush">
            @foreach($recs as $rec)
                @php
                    $sevClass = match($rec->severity) {
                        'critical' => 'danger',
                        'warning' => 'warning',
                        default => 'info',
                    };
                    $sevLabel = match($rec->severity) {
                        'critical' => 'Kritis',
                        'warning' => 'Waspada',
                        default => 'Info',
                    };
                @endphp
                <li class="list-group-item">
                    <div class="d-flex justify-content-between align-items-start gap-2 flex-wrap">
                        <div>
                            <div class="fw-semibold">{{ $rec->title }}</div>
                            <div class="small text-muted mt-1">{{ $rec->advice }}</div>
                        </div>
                        <span class="badge text-bg-{{ $sevClass }}">{{ $sevLabel }}</span>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
</div>
@else
<div class="alert alert-light border mb-3">
    Belum ada rekomendasi. Simpan data kualitas air untuk mendapatkan saran otomatis.
</div>
@endif
