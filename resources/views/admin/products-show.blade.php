@extends('layouts.admin')
@section('title', 'Detail Produk Katalog')
@section('content')
<div class="row g-3">
    <div class="col-lg-8">
        <div class="card mb-3"><div class="card-body">
            <h3>{{ $product->title }}</h3>
            <p class="text-muted">{{ $product->fishSpecies?->name }} · {{ $product->size_label }} · Stok {{ $product->stock_kg }} {{ $product->price_unit }}</p>
            <p><strong>Rp {{ number_format($product->price,0,',','.') }}/{{ $product->price_unit }}</strong></p>
            <p>{{ $product->description }}</p>
            <p class="mb-0">Lokasi: {{ $product->location_label }} · WA: {{ $product->whatsapp }}</p>
        </div></div>
        <div class="card"><div class="card-body">
            <h5>Foto Produk</h5>
            <div class="row g-2">
                @forelse($product->photos as $photo)
                    <div class="col-md-4"><img src="{{ asset('storage/'.$photo->path) }}" class="img-fluid rounded" alt="foto"></div>
                @empty
                    <div class="col-12 text-muted">Belum ada foto.</div>
                @endforelse
            </div>
        </div></div>
    </div>
    <div class="col-lg-4">
        <div class="card mb-3"><div class="card-body">
            <h5>Profil Toko</h5>
            <p class="mb-1"><strong>{{ $product->farmerProfile?->shop_name ?: '-' }}</strong></p>
            <p class="small text-muted">{{ $product->farmerProfile?->owner_name }} · etalase: {{ $product->farmerProfile?->storefront_status }}</p>
            @if($product->farmerProfile)
                <a href="{{ route('admin.farmers.show', $product->farmerProfile) }}" class="btn btn-sm btn-light-brand">Lihat Toko</a>
            @endif
        </div></div>
        <div class="card"><div class="card-body">
            <h5>Moderasi</h5>
            <p>Status: <strong>{{ $product->moderation_status }}</strong></p>
            @if($product->rejection_reason)
                <div class="alert alert-warning small">{{ $product->rejection_reason }}</div>
            @endif
            @if($product->moderation_status !== 'approved')
            <form method="POST" action="{{ route('admin.products.moderate', $product) }}" class="mb-2">
                @csrf @method('PUT')
                <input type="hidden" name="moderation_status" value="approved">
                <button class="btn btn-success w-100">Setujui & Rilis</button>
            </form>
            <form method="POST" action="{{ route('admin.products.moderate', $product) }}">
                @csrf @method('PUT')
                <input type="hidden" name="moderation_status" value="rejected">
                <textarea name="rejection_reason" class="form-control mb-2" rows="3" placeholder="Alasan penolakan" required></textarea>
                <button class="btn btn-outline-danger w-100">Tolak</button>
            </form>
            @endif
        </div></div>
    </div>
</div>
@endsection
