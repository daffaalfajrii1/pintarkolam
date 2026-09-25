@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<h2 class="mb-1">Dashboard Admin</h2>
<p class="text-muted mb-4">Ringkasan operasional budidaya, toko, dan antrian moderasi.</p>

<div class="row g-3 mb-4">
    <div class="col-6 col-md-4 col-xl-3">
        <div class="pk-stat"><strong>{{ $stats['cycles_running'] }}</strong><span>Siklus berjalan</span></div>
    </div>
    <div class="col-6 col-md-4 col-xl-3">
        <div class="pk-stat"><strong>{{ $stats['cycles_active'] }}</strong><span>Aktif</span></div>
    </div>
    <div class="col-6 col-md-4 col-xl-3">
        <div class="pk-stat"><strong>{{ $stats['cycles_near'] }}</strong><span>Mendekati panen</span></div>
    </div>
    <div class="col-6 col-md-4 col-xl-3">
        <div class="pk-stat"><strong>{{ $stats['cycles_completed'] }}</strong><span>Siklus selesai</span></div>
    </div>
    <div class="col-6 col-md-4 col-xl-3">
        <div class="pk-stat"><strong>{{ $stats['ponds'] }}</strong><span>Kolam aktif</span></div>
    </div>
    <div class="col-6 col-md-4 col-xl-3">
        <div class="pk-stat"><strong>{{ $stats['farmers'] }}</strong><span>Pembudidaya terverifikasi</span></div>
    </div>
    <div class="col-6 col-md-4 col-xl-3">
        <div class="pk-stat"><strong>{{ $stats['critical'] }}</strong><span>Skor kritis (7 hari)</span></div>
    </div>
    <div class="col-6 col-md-4 col-xl-3">
        <div class="pk-stat"><strong>{{ $stats['waspada'] }}</strong><span>Skor waspada (7 hari)</span></div>
    </div>
    <div class="col-6 col-md-4 col-xl-3">
        <div class="pk-stat"><strong>{{ $stats['products'] }}</strong><span>Produk tayang</span></div>
    </div>
    <div class="col-6 col-md-4 col-xl-3">
        <div class="pk-stat"><strong>{{ $stats['products_pending'] }}</strong><span>Produk pending</span></div>
    </div>
    <div class="col-6 col-md-4 col-xl-3">
        <div class="pk-stat"><strong>{{ $stats['farmers_pending'] }}</strong><span>Toko / verifikasi pending</span></div>
    </div>
    <div class="col-6 col-md-4 col-xl-3">
        <div class="pk-stat"><strong>{{ $stats['shops_suspended'] }}</strong><span>Toko ditangguhkan</span></div>
    </div>
</div>

<div class="card mb-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <strong>Laporan siklus berjalan</strong>
        <a href="{{ route('admin.farmers') }}" class="btn btn-sm btn-light-brand">Lihat Pembudidaya</a>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-striped align-middle mb-0">
            <thead>
                <tr>
                    <th>Pembudidaya</th>
                    <th>Siklus</th>
                    <th>Kolam</th>
                    <th>Ikan</th>
                    <th>Tebar</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            @forelse($runningCycles as $cycle)
                <tr>
                    <td>
                        <strong>{{ $cycle->user?->name }}</strong>
                        <div class="small text-muted">{{ $cycle->user?->farmerProfile?->shop_name }}</div>
                    </td>
                    <td>{{ $cycle->name }}</td>
                    <td>{{ $cycle->pond?->name }}</td>
                    <td>{{ $cycle->fishSpecies?->name }}</td>
                    <td>{{ $cycle->stocking_date?->format('d/m/Y') }} · {{ $cycle->seed_count }} ekor</td>
                    <td><span class="badge bg-{{ $cycle->status === 'near_harvest' ? 'warning' : 'primary' }}">{{ $cycle->status }}</span></td>
                    <td class="text-end pe-3">
                        @if($cycle->user_id)
                            <a href="{{ route('admin.water', ['user_id' => $cycle->user_id, 'cycle_id' => $cycle->id]) }}" class="btn btn-sm btn-light-brand">Air</a>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center text-muted py-4">Tidak ada siklus berjalan.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">Antrian pembudidaya / toko</div>
            <div class="card-body table-responsive p-0">
                <table class="table table-sm align-middle mb-0">
                    <thead><tr><th>Toko</th><th>Status</th><th></th></tr></thead>
                    <tbody>
                    @forelse($pendingFarmers as $farmer)
                        <tr>
                            <td>
                                <strong>{{ $farmer->shop_name ?: $farmer->business_name }}</strong>
                                <div class="small text-muted">{{ $farmer->user?->email }}</div>
                            </td>
                            <td>
                                <span class="badge bg-warning">{{ $farmer->verification_status }}</span>
                                <span class="badge bg-secondary">{{ $farmer->storefront_status }}</span>
                            </td>
                            <td class="text-end pe-3">
                                <a href="{{ route('admin.farmers.show', $farmer) }}" class="btn btn-sm btn-primary">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-center text-muted py-3">Tidak ada antrian.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">Produk pending moderasi</div>
            <div class="card-body table-responsive p-0">
                <table class="table table-sm align-middle mb-0">
                    <thead><tr><th>Produk</th><th>Pemilik</th><th></th></tr></thead>
                    <tbody>
                    @forelse($pendingProducts as $product)
                        <tr>
                            <td><strong>{{ $product->title }}</strong></td>
                            <td class="small">{{ $product->user?->name }}</td>
                            <td class="text-end pe-3">
                                <a href="{{ route('admin.products.show', $product) }}" class="btn btn-sm btn-primary">Review</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-center text-muted py-3">Tidak ada produk menunggu.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
