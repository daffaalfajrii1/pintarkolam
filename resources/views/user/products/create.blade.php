@extends('layouts.user')
@section('title', 'Tambah Produk')
@section('content')
<div class="pk-page-head mb-3">
    <h2 class="pk-page-title mb-1">Tambah Produk</h2>
    <div class="small text-muted">Kirim ke moderasi admin sebelum tayang di katalog publik</div>
</div>

<form method="POST" action="{{ route('user.products.store') }}" enctype="multipart/form-data" class="card">
@csrf
<div class="card-body row g-3">
    <div class="col-md-6">
        <label class="form-label">Jenis Ikan</label>
        <select name="fish_species_id" class="form-select" required>
            @foreach($species as $item)
                <option value="{{ $item->id }}" @selected(old('fish_species_id') == $item->id)>{{ $item->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Judul Produk</label>
        <input name="title" class="form-control" value="{{ old('title') }}" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">Ukuran</label>
        <input name="size_label" class="form-control" value="{{ old('size_label') }}" placeholder="mis. 3-4 ons">
    </div>
    <div class="col-md-4">
        <label class="form-label">Stok (kg/pcs)</label>
        <input type="number" step="0.01" name="stock_kg" class="form-control" value="{{ old('stock_kg') }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">Satuan Harga</label>
        <select name="price_unit" class="form-select">
            <option value="kg" @selected(old('price_unit', 'kg') === 'kg')>kg</option>
            <option value="pcs" @selected(old('price_unit') === 'pcs')>pcs</option>
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">Harga</label>
        <input type="number" step="0.01" name="price" class="form-control" value="{{ old('price') }}" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">Min. Order</label>
        <input type="number" step="0.01" name="min_order" class="form-control" value="{{ old('min_order') }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">WhatsApp</label>
        <input name="whatsapp" class="form-control" value="{{ old('whatsapp', $shop->whatsapp) }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">Lokasi Label</label>
        <input name="location_label" class="form-control" value="{{ old('location_label', trim(($shop->district ?? '').', '.($shop->regency ?? ''), ', ')) }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">Foto Produk (bisa banyak, maks. 8)</label>
        <input type="file" name="photos[]" class="form-control" accept="image/*" multiple>
    </div>
    <div class="col-12">
        <label class="form-label">Deskripsi</label>
        <textarea name="description" class="form-control" rows="4">{{ old('description') }}</textarea>
    </div>
    <div class="col-12">
        <div class="pk-form-actions">
            <a href="{{ route('user.products.index') }}" class="pk-btn pk-btn-outline">Batal</a>
            <button class="pk-btn pk-btn-primary" type="submit">Kirim untuk Moderasi</button>
        </div>
    </div>
</div>
</form>
@endsection
