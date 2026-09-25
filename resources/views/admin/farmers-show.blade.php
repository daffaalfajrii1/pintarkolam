@extends('layouts.admin')
@section('title', 'Detail Pembudidaya')
@section('content')
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
    <div>
        <a href="{{ route('admin.farmers') }}" class="small text-decoration-none">← Kembali ke daftar</a>
        <h2 class="mb-0 mt-1">{{ $farmer->owner_name ?: $farmer->user?->name }}</h2>
        <div class="small text-muted">{{ $farmer->shop_name ?: $farmer->business_name }} · {{ $farmer->user?->email }}</div>
    </div>
    <div class="d-flex gap-2">
        <span class="badge bg-info">{{ $ponds->count() }} kolam</span>
        <span class="badge bg-primary">{{ $cycles->count() }} siklus</span>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-lg-8">
        <div class="card mb-3">
            @if($farmer->shop_cover_path)
                <img src="{{ asset('storage/'.$farmer->shop_cover_path) }}" class="card-img-top" style="max-height:200px;object-fit:cover" alt="cover">
            @endif
            <div class="card-body">
                <div class="d-flex gap-3 align-items-center mb-3">
                    @if($farmer->shop_logo_path)
                        <img src="{{ asset('storage/'.$farmer->shop_logo_path) }}" alt="logo" style="width:64px;height:64px;object-fit:cover;border-radius:12px">
                    @endif
                    <div>
                        <h4 class="mb-0">{{ $farmer->shop_name ?: $farmer->business_name }}</h4>
                        <div class="text-muted">{{ $farmer->whatsapp }}</div>
                    </div>
                </div>
                <p>{{ $farmer->shop_description ?: $farmer->bio }}</p>
                <p class="mb-0 small text-muted">{{ $farmer->address }}, {{ $farmer->village }}, {{ $farmer->district }}, {{ $farmer->regency }}</p>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <strong>Kolam ({{ $ponds->count() }})</strong>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-striped mb-0 align-middle">
                    <thead><tr><th>Nama</th><th>Jenis</th><th>Status</th><th>Skor</th></tr></thead>
                    <tbody>
                    @forelse($ponds as $pond)
                        <tr>
                            <td>{{ $pond->name }}</td>
                            <td>{{ $pond->type }}</td>
                            <td>{{ $pond->status }}</td>
                            <td>{{ $pond->latestHealthScore?->score ?? '-' }} {{ $pond->latestHealthScore?->category }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-muted">Belum ada kolam.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                <strong>Siklus ({{ $cycles->count() }})</strong>
                @if($farmer->user_id)
                    <a href="{{ route('admin.water', ['user_id' => $farmer->user_id]) }}" class="btn btn-sm btn-light-brand">Lihat Kualitas Air</a>
                @endif
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-striped mb-0 align-middle">
                    <thead><tr><th>Nama Siklus</th><th>Kolam</th><th>Ikan</th><th>Tebar</th><th>Status</th></tr></thead>
                    <tbody>
                    @forelse($cycles as $cycle)
                        <tr>
                            <td>{{ $cycle->name }}</td>
                            <td>{{ $cycle->pond?->name }}</td>
                            <td>{{ $cycle->fishSpecies?->name }}</td>
                            <td>{{ $cycle->stocking_date?->format('d/m/Y') }} ({{ $cycle->seed_count }} ekor)</td>
                            <td><span class="badge bg-{{ $cycle->status === 'active' ? 'primary' : 'secondary' }}">{{ $cycle->status }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted">Belum ada siklus.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><strong>Produk Toko</strong></div>
            <div class="card-body">
                <ul class="mb-0">
                    @forelse($farmer->products as $product)
                        <li><a href="{{ route('admin.products.show', $product) }}">{{ $product->title }}</a> — {{ $product->moderation_status }}</li>
                    @empty
                        <li class="text-muted">Belum ada produk.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <h5>Moderasi</h5>
                <p class="mb-3">
                    Verifikasi: <strong>{{ $farmer->verification_status }}</strong><br>
                    Etalase: <strong>{{ $farmer->storefront_status }}</strong>
                </p>
                @if($farmer->storefront_rejection_reason)
                    <div class="alert alert-warning small">{{ $farmer->storefront_rejection_reason }}</div>
                @endif

                @if($farmer->storefront_status !== 'approved')
                    <form method="POST" action="{{ route('admin.farmers.verify', $farmer) }}" class="mb-2">
                        @csrf @method('PUT')
                        <input type="hidden" name="verification_status" value="approved">
                        <input type="hidden" name="storefront_status" value="approved">
                        <button class="btn btn-success w-100" type="submit">Setujui Profil &amp; Toko</button>
                    </form>

                    @if(! in_array($farmer->storefront_status, ['suspended'], true))
                        <form method="POST" action="{{ route('admin.farmers.verify', $farmer) }}">
                            @csrf @method('PUT')
                            <input type="hidden" name="storefront_status" value="rejected">
                            <textarea name="storefront_rejection_reason" class="form-control mb-2" rows="3" placeholder="Alasan penolakan toko" required></textarea>
                            <button class="btn btn-outline-danger w-100" type="submit">Tolak Toko</button>
                        </form>
                    @endif

                    @if($farmer->storefront_status === 'suspended')
                        <p class="small text-muted mb-2">Toko sedang ditangguhkan. Setujui ulang untuk mengaktifkan etalase.</p>
                    @endif
                @else
                    <div class="alert alert-success small mb-3">Toko sudah disetujui. Gunakan tangguhkan atau reset jika perlu.</div>

                    <form method="POST" action="{{ route('admin.farmers.verify', $farmer) }}" class="mb-2">
                        @csrf @method('PUT')
                        <input type="hidden" name="storefront_status" value="suspended">
                        <textarea name="storefront_rejection_reason" class="form-control mb-2" rows="2" placeholder="Alasan penangguhan (opsional)"></textarea>
                        <button class="btn btn-warning w-100" type="submit">Tangguhkan Toko</button>
                    </form>

                    <form method="POST" action="{{ route('admin.farmers.verify', $farmer) }}" onsubmit="return confirm('Reset status toko ke pending?')">
                        @csrf @method('PUT')
                        <input type="hidden" name="storefront_status" value="pending">
                        <input type="hidden" name="storefront_rejection_reason" value="">
                        <button class="btn btn-outline-secondary w-100" type="submit">Reset Toko</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
