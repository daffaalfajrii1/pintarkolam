@extends('layouts.user')
@section('title', 'Kualitas Air')
@section('content')
@include('user.cycles._nav')

@include('user.cycles._recommendations')

<div class="row g-3">
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header">Input Kualitas Air</div>
            <div class="card-body">
                <form method="POST" action="{{ route('user.cycles.water.store', $cycle) }}" class="row g-3">
                    @csrf
                    <div class="col-md-6"><label class="form-label">pH</label><input type="number" step="0.01" name="ph" class="form-control" required value="{{ old('ph', 7.2) }}"></div>
                    <div class="col-md-6"><label class="form-label">Suhu (°C)</label><input type="number" step="0.01" name="temperature_c" class="form-control" required value="{{ old('temperature_c', 28) }}"></div>
                    <div class="col-md-6"><label class="form-label">DO (mg/L)</label><input type="number" step="0.01" name="dissolved_oxygen" class="form-control" required value="{{ old('dissolved_oxygen', 5.5) }}"></div>
                    <div class="col-md-6"><label class="form-label">Waktu ukur</label><input type="datetime-local" name="measured_at" class="form-control" value="{{ old('measured_at', now()->format('Y-m-d\\TH:i')) }}"></div>
                    <div class="col-md-6"><label class="form-label">Kondisi visual</label><input name="visual_condition" class="form-control" value="{{ old('visual_condition', 'jernih') }}"></div>
                    <div class="col-md-6"><label class="form-label">Bau</label><input name="odor" class="form-control" value="{{ old('odor', 'normal') }}"></div>
                    <div class="col-12"><label class="form-label">Catatan</label><textarea name="notes" class="form-control" rows="2">{{ old('notes') }}</textarea></div>
                    <div class="col-12">
                        <div class="pk-form-actions">
                            <a href="{{ route('user.cycles.show', $cycle) }}" class="pk-btn pk-btn-outline">Batal</a>
                            <button class="pk-btn pk-btn-primary" type="submit">Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header">Riwayat Air</div>
            <div class="card-body p-0 table-responsive">
                <table class="table pk-table mb-0">
                    <thead><tr><th>Waktu</th><th>pH</th><th>Suhu</th><th>DO</th><th>Catatan</th></tr></thead>
                    <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td>{{ $log->measured_at?->format('d/m/Y H:i') }}</td>
                            <td>{{ $log->ph }}</td>
                            <td>{{ $log->temperature_c }}</td>
                            <td>{{ $log->dissolved_oxygen }}</td>
                            <td>{{ $log->notes }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted">Belum ada data.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            @if($logs->hasPages())<div class="card-footer">{{ $logs->links() }}</div>@endif
        </div>
    </div>
</div>
@endsection
