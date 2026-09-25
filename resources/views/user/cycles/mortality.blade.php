@extends('layouts.user')
@section('title', 'Kematian Ikan')
@section('content')
@include('user.cycles._nav')

<div class="row g-3">
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header">Catat Kematian</div>
            <div class="card-body">
                <div class="alert alert-light border mb-3">
                    Sisa ikan: <strong>{{ $alive }}</strong> dari {{ $cycle->seed_count }} (mati {{ $deaths }})
                </div>
                <form method="POST" action="{{ route('user.cycles.mortality.store', $cycle) }}" class="row g-3">
                    @csrf
                    <div class="col-md-6"><label class="form-label">Jumlah mati</label><input type="number" name="death_count" class="form-control" min="1" required></div>
                    <div class="col-md-6"><label class="form-label">Tanggal</label><input type="date" name="recorded_at" class="form-control" value="{{ now()->toDateString() }}"></div>
                    <div class="col-12"><label class="form-label">Penyebab dugaan</label><input name="suspected_cause" class="form-control"></div>
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
            <div class="card-header">Riwayat Kematian</div>
            <div class="card-body p-0 table-responsive">
                <table class="table pk-table mb-0">
                    <thead><tr><th>Tanggal</th><th>Jumlah</th><th>Sebab</th><th>Catatan</th></tr></thead>
                    <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td>{{ $log->recorded_at?->format('d/m/Y') }}</td>
                            <td>{{ $log->death_count }}</td>
                            <td>{{ $log->suspected_cause }}</td>
                            <td>{{ $log->notes }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-muted">Belum ada data.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            @if($logs->hasPages())<div class="card-footer">{{ $logs->links() }}</div>@endif
        </div>
    </div>
</div>
@endsection
