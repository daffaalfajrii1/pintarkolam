@extends('layouts.user')
@section('title', 'Pertumbuhan')
@section('content')
@include('user.cycles._nav')

<div class="row g-3">
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header">Catat Pertumbuhan</div>
            <div class="card-body">
                <form method="POST" action="{{ route('user.cycles.growth.store', $cycle) }}" class="row g-3">
                    @csrf
                    <div class="col-md-6"><label class="form-label">Berat rata-rata (gram)</label><input type="number" step="0.01" name="avg_weight_gram" class="form-control" required></div>
                    <div class="col-md-6"><label class="form-label">Panjang rata-rata (cm)</label><input type="number" step="0.01" name="avg_length_cm" class="form-control"></div>
                    <div class="col-md-6"><label class="form-label">Jumlah sampel</label><input type="number" name="sample_count" class="form-control"></div>
                    <div class="col-md-6"><label class="form-label">Tanggal</label><input type="date" name="sampled_at" class="form-control" value="{{ now()->toDateString() }}"></div>
                    <div class="col-12"><label class="form-label">Catatan</label><textarea name="notes" class="form-control" rows="2"></textarea></div>
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
            <div class="card-header">Riwayat Pertumbuhan</div>
            <div class="card-body p-0 table-responsive">
                <table class="table pk-table mb-0">
                    <thead><tr><th>Tanggal</th><th>Berat (g)</th><th>Panjang (cm)</th><th>Sampel</th><th>Estimasi hidup</th></tr></thead>
                    <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td>{{ $log->sampled_at?->format('d/m/Y') }}</td>
                            <td>{{ $log->avg_weight_gram }}</td>
                            <td>{{ $log->avg_length_cm ?? '-' }}</td>
                            <td>{{ $log->sample_count ?? '-' }}</td>
                            <td>{{ $log->estimated_alive ?? '-' }}</td>
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
