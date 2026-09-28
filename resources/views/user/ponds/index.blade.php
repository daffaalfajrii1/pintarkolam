@extends('layouts.user')
@section('title', 'Budidaya — Kolam & Siklus')
@section('content')
<div class="pk-page-head d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <div>
        <h2 class="pk-page-title mb-0">Budidaya</h2>
        <div class="small text-muted">Kelola kolam, siklus, pakan, kematian, biaya, dan QR</div>
    </div>
    <div class="pk-actions">
        <a href="{{ route('user.ponds.create') }}" class="pk-btn pk-btn-primary">Tambah Kolam</a>
    </div>
</div>

<div class="card mb-3">
    <div class="card-body">
        <form method="GET" action="{{ route('user.ponds.index') }}" class="row g-2 align-items-end">
            <div class="col-12 col-md-5">
                <label class="form-label mb-1">Cari kolam</label>
                <input type="search" name="q" value="{{ $search }}" class="form-control" placeholder="Nama kolam, jenis, siklus, atau ikan...">
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label mb-1">Jenis</label>
                <select name="type" class="form-select">
                    <option value="">Semua</option>
                    @foreach($types as $t)
                        <option value="{{ $t }}" @selected($type === $t)>{{ ucfirst($t) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label mb-1">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua</option>
                    @foreach($statuses as $s)
                        <option value="{{ $s }}" @selected($status === $s)>{{ $s }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-3 d-flex gap-2">
                <button type="submit" class="pk-btn pk-btn-primary flex-grow-1">Cari</button>
                @if($search !== '' || $type || $status)
                    <a href="{{ route('user.ponds.index') }}" class="pk-btn pk-btn-outline">Reset</a>
                @endif
            </div>
        </form>
        @if($search !== '' || $type || $status)
            <div class="small text-muted mt-2 mb-0">
                Menampilkan {{ $ponds->total() }} kolam
                @if($search !== '') untuk pencarian “{{ $search }}”@endif
            </div>
        @endif
    </div>
</div>

@forelse($ponds as $pond)
@php
    $cat = $pond->latestHealthScore?->category;
    $isInactive = $pond->status === 'inactive';
@endphp
<div class="card mb-3 {{ $isInactive ? 'pk-card-muted' : '' }}">
    <div class="card-header pk-card-toolbar">
        <div class="pk-card-toolbar__info">
            <a href="{{ route('user.ponds.show', $pond) }}" class="text-decoration-none text-dark fw-semibold">{{ $pond->name }}</a>
            <span class="text-muted small">
                {{ $pond->type }} · {{ $pond->volume_m3 ?? '-' }} m³ · {{ $pond->status }}
                @if($isInactive)<span class="pk-badge pk-badge-warn ms-1">disembunyikan</span>@endif
            </span>
        </div>
        <div class="pk-actions">
            <span class="pk-badge {{ $cat==='kritis'?'pk-badge-danger':($cat==='waspada'?'pk-badge-warn':'pk-badge-ok') }}">
                skor {{ $pond->latestHealthScore?->score ?? '-' }} {{ $cat ?? '-' }}
            </span>
            <a href="{{ route('user.ponds.show', $pond) }}" class="pk-btn pk-btn-primary pk-btn-sm">Detail &amp; QR</a>
            <a href="{{ route('user.ponds.edit', $pond) }}" class="pk-btn pk-btn-outline pk-btn-sm">Ubah</a>
            <a href="{{ route('user.cycles.create', ['pond_id' => $pond->id]) }}" class="pk-btn pk-btn-outline pk-btn-sm">+ Siklus</a>
            @if($isInactive)
                <form method="POST" action="{{ route('user.ponds.activate', $pond) }}" class="d-inline">
                    @csrf
                    <button type="submit" class="pk-btn pk-btn-outline pk-btn-sm">Aktifkan</button>
                </form>
            @else
                <form method="POST" action="{{ route('user.ponds.deactivate', $pond) }}" class="d-inline"
                      onsubmit="return confirm('Nonaktifkan / sembunyikan kolam {{ $pond->name }}? Siklus tetap tersimpan.')">
                    @csrf
                    <button type="submit" class="pk-btn pk-btn-warn pk-btn-sm">Hilangkan</button>
                </form>
            @endif
            <form method="POST" action="{{ route('user.ponds.destroy', $pond) }}" class="d-inline"
                  onsubmit="return confirm('Hapus kolam {{ $pond->name }}?\n\nSemua siklus di kolam ini akan ikut terhapus dan tidak bisa dikembalikan dari daftar.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="pk-btn pk-btn-danger pk-btn-sm">Hapus</button>
            </form>
        </div>
    </div>
    <div class="card-body p-0">
        @if($pond->cycles->count())
        <div class="table-responsive">
            <table class="table table-hover mb-0 pk-table align-middle">
                <thead>
                    <tr>
                        <th>Siklus</th>
                        <th>Ikan</th>
                        <th>Tebar</th>
                        <th>Sisa / Mati</th>
                        <th>Status</th>
                        <th style="min-width:220px"></th>
                    </tr>
                </thead>
                <tbody>
                @foreach($pond->cycles as $cycle)
                @php $cycleInactive = in_array($cycle->status, ['failed', 'completed'], true); @endphp
                <tr class="{{ $cycle->status === 'failed' ? 'table-secondary' : '' }}">
                    <td>{{ $cycle->name }}</td>
                    <td>{{ $cycle->fishSpecies?->name }}</td>
                    <td>{{ $cycle->seed_count }} ekor</td>
                    <td>{{ $cycle->aliveCount() }} / {{ $cycle->totalDeaths() }}</td>
                    <td>
                        <span class="pk-badge {{ $cycle->status === 'failed' ? 'pk-badge-warn' : ($cycle->status === 'completed' ? 'pk-badge-ok' : 'pk-badge-info') }}">
                            {{ $cycle->status === 'failed' ? 'nonaktif' : $cycle->status }}
                        </span>
                    </td>
                    <td class="text-end pe-3">
                        <div class="pk-actions justify-content-end">
                            <a href="{{ route('user.cycles.show', $cycle) }}" class="pk-btn pk-btn-outline pk-btn-sm">Kelola</a>
                            @unless($cycleInactive)
                            <form method="POST" action="{{ route('user.cycles.deactivate', $cycle) }}" class="d-inline"
                                  onsubmit="return confirm('Nonaktifkan siklus {{ $cycle->name }}?\nSiklus tidak lagi dihitung sebagai aktif.')">
                                @csrf
                                <button type="submit" class="pk-btn pk-btn-warn pk-btn-sm">Nonaktifkan</button>
                            </form>
                            @endunless
                            <form method="POST" action="{{ route('user.cycles.destroy', $cycle) }}" class="d-inline"
                                  onsubmit="return confirm('Hapus siklus {{ $cycle->name }}?\nData pakan, air, dan biaya siklus ini ikut terhapus dari daftar.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="pk-btn pk-btn-danger pk-btn-sm">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        @else
            <p class="text-muted mb-0 p-3">Belum ada siklus. <a href="{{ route('user.cycles.create', ['pond_id' => $pond->id]) }}">Buat siklus</a></p>
        @endif
    </div>
</div>
@empty
<div class="card">
    <div class="card-body text-center text-muted">
        @if($search !== '' || $type || $status)
            Tidak ada kolam yang cocok dengan pencarian.
            <div class="mt-2"><a href="{{ route('user.ponds.index') }}" class="pk-btn pk-btn-outline pk-btn-sm">Reset pencarian</a></div>
        @else
            Belum ada kolam. <a href="{{ route('user.ponds.create') }}">Tambah kolam</a>
        @endif
    </div>
</div>
@endforelse

@if($ponds->total() > 0)
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-2 mb-2">
    <div class="small text-muted">
        Halaman {{ $ponds->currentPage() }} dari {{ max(1, $ponds->lastPage()) }}
        · {{ $ponds->total() }} kolam
    </div>
    <div>{{ $ponds->links() }}</div>
</div>
@endif
@endsection
