@extends('layouts.admin')
@section('title', 'Pembudidaya')
@section('content')
<div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
    <div>
        <h2 class="mb-1">Pembudidaya</h2>
        <div class="small text-muted">Kolam &amp; siklus dikelompokkan per pembudidaya</div>
    </div>
</div>

<form method="GET" action="{{ route('admin.farmers') }}" class="card mb-3">
    <div class="card-body row g-2 align-items-end">
        <div class="col-md-9">
            <label class="form-label small mb-1">Cari pembudidaya</label>
            <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="Nama, email, toko, kecamatan...">
        </div>
        <div class="col-md-3">
            <button class="btn btn-primary w-100" type="submit">Cari</button>
        </div>
        @if($q)
            <div class="col-12"><a href="{{ route('admin.farmers') }}" class="small">Reset pencarian</a></div>
        @endif
    </div>
</form>

<div class="card">
    <div class="card-body table-responsive p-0">
        <table class="table table-striped align-middle mb-0">
            <thead>
                <tr>
                    <th>Pembudidaya</th>
                    <th>Toko</th>
                    <th>Kolam</th>
                    <th>Siklus</th>
                    <th>Verifikasi</th>
                    <th>Etalase</th>
                    <th class="text-end pe-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
            @forelse($farmers as $farmer)
                @php
                    $pondCount = $farmer->user?->ponds_count ?? $farmer->ponds_count ?? 0;
                    $cycleCount = $farmer->user?->cycles_count ?? 0;
                @endphp
                <tr>
                    <td>
                        <strong>{{ $farmer->owner_name ?: $farmer->user?->name }}</strong>
                        <div class="small text-muted">{{ $farmer->user?->email }}</div>
                    </td>
                    <td>
                        {{ $farmer->shop_name ?: $farmer->business_name ?: '—' }}
                        <div class="small text-muted">{{ $farmer->district }}</div>
                    </td>
                    <td><span class="badge bg-info">{{ $pondCount }}</span></td>
                    <td><span class="badge bg-primary">{{ $cycleCount }}</span></td>
                    <td>
                        <span class="badge bg-{{ $farmer->verification_status === 'approved' ? 'success' : ($farmer->verification_status === 'rejected' ? 'danger' : 'warning') }}">
                            {{ $farmer->verification_status }}
                        </span>
                    </td>
                    <td>
                        <span class="badge bg-{{ $farmer->storefront_status === 'approved' ? 'success' : ($farmer->storefront_status === 'suspended' ? 'warning' : ($farmer->storefront_status === 'rejected' ? 'danger' : 'secondary')) }}">
                            {{ $farmer->storefront_status }}
                        </span>
                    </td>
                    <td class="text-end pe-3">
                        <a href="{{ route('admin.farmers.show', $farmer) }}" class="btn btn-sm btn-primary">Detail</a>
                        @if($farmer->user_id)
                            <a href="{{ route('admin.water', ['user_id' => $farmer->user_id]) }}" class="btn btn-sm btn-light-brand">Kualitas Air</a>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center text-muted py-4">Belum ada pembudidaya{{ $q ? ' untuk pencarian ini' : '' }}.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($farmers->hasPages())
        <div class="card-footer">{{ $farmers->links() }}</div>
    @endif
</div>
@endsection
