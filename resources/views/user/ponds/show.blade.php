@extends('layouts.user')
@section('title', $pond->name)
@section('content')
@php $cat = $pond->latestHealthScore?->category; @endphp
<div class="pk-page-head d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <div>
        <h4 class="mb-0">{{ $pond->name }}</h4>
        <div class="small text-muted">{{ $pond->type }} · {{ $pond->volume_m3 ?? '-' }} m³ · status {{ $pond->status }}</div>
    </div>
    <div class="pk-actions">
        <a href="{{ route('user.ponds.edit', $pond) }}" class="pk-btn pk-btn-outline">Ubah</a>
        <a href="{{ route('user.cycles.create', ['pond_id' => $pond->id]) }}" class="pk-btn pk-btn-primary">Tambah Siklus</a>
        @if($pond->status === 'inactive')
            <form method="POST" action="{{ route('user.ponds.activate', $pond) }}" class="d-inline">
                @csrf
                <button type="submit" class="pk-btn pk-btn-outline">Aktifkan</button>
            </form>
        @else
            <form method="POST" action="{{ route('user.ponds.deactivate', $pond) }}" class="d-inline"
                  onsubmit="return confirm('Nonaktifkan / sembunyikan kolam ini?')">
                @csrf
                <button type="submit" class="pk-btn pk-btn-warn">Hilangkan</button>
            </form>
        @endif
        <form method="POST" action="{{ route('user.ponds.destroy', $pond) }}" class="d-inline"
              onsubmit="return confirm('Hapus kolam {{ $pond->name }}?\n\nSemua siklus di kolam ini akan ikut terhapus.')">
            @csrf
            @method('DELETE')
            <button type="submit" class="pk-btn pk-btn-danger">Hapus</button>
        </form>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-header">Indeks Kesehatan Kolam</div>
            <div class="card-body">
                <div class="display-6">{{ $pond->latestHealthScore?->score ?? '-' }}</div>
                <span class="badge {{ $cat==='kritis'?'bg-danger':($cat==='waspada'?'bg-warning text-dark':'bg-success') }}">{{ $cat ?? 'belum ada data' }}</span>
                <p class="mt-3 mb-0 text-muted">Skor dihitung dari input kualitas air pada siklus aktif (baik / waspada / kritis).</p>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header">QR Code Kolam</div>
            <div class="card-body text-center">
                <div id="pond-qr" class="d-inline-block mb-2"></div>
                <div class="small text-break mb-2">{{ $qrUrl }}</div>
                <a href="{{ $qrUrl }}" target="_blank" class="btn btn-sm btn-light-brand">Buka Halaman Publik</a>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">Siklus di Kolam Ini</div>
    <div class="card-body table-responsive">
        <table class="table align-middle">
            <thead><tr><th>Nama</th><th>Ikan</th><th>Tebar</th><th>Sisa</th><th>Mati</th><th>FCR</th><th>Status</th><th></th></tr></thead>
            <tbody>
            @forelse($cycles as $cycle)
            <tr>
                <td>{{ $cycle->name }}</td>
                <td>{{ $cycle->fishSpecies?->name }}</td>
                <td>{{ $cycle->seed_count }}</td>
                <td>{{ $cycle->aliveCount() }}</td>
                <td>{{ $cycle->totalDeaths() }}</td>
                <td>{{ $cycle->fcr() ?? '-' }}</td>
                <td>{{ $cycle->status === 'failed' ? 'nonaktif' : $cycle->status }}</td>
                <td class="text-end pe-3">
                    <div class="pk-actions justify-content-end">
                        <a class="pk-btn pk-btn-outline pk-btn-sm" href="{{ route('user.cycles.show', $cycle) }}">Kelola</a>
                        @unless(in_array($cycle->status, ['failed', 'completed'], true))
                        <form method="POST" action="{{ route('user.cycles.deactivate', $cycle) }}" class="d-inline"
                              onsubmit="return confirm('Nonaktifkan siklus {{ $cycle->name }}?')">
                            @csrf
                            <button type="submit" class="pk-btn pk-btn-warn pk-btn-sm">Nonaktifkan</button>
                        </form>
                        @endunless
                        <form method="POST" action="{{ route('user.cycles.destroy', $cycle) }}" class="d-inline"
                              onsubmit="return confirm('Hapus siklus {{ $cycle->name }}?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="pk-btn pk-btn-danger pk-btn-sm">Hapus</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="8" class="text-muted text-center">Belum ada siklus.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<script>
new QRCode(document.getElementById('pond-qr'), { text: @json($qrUrl), width: 160, height: 160 });
</script>
@endpush
