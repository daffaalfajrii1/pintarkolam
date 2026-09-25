@extends('layouts.user')
@section('title', 'Edit Produk')
@section('content')
<div class="pk-page-head mb-3">
    <h2 class="pk-page-title mb-1">Edit Produk</h2>
    <div class="small text-muted">{{ $product->title }} · produk approved tetap bisa diubah</div>
</div>

@if($product->photos->count())
<div class="card mb-3">
    <div class="card-header">Galeri foto ({{ $product->photos->count() }}/8)</div>
    <div class="card-body">
        <div class="d-flex flex-wrap gap-2">
            @foreach($product->photos as $photo)
                <div class="position-relative border rounded overflow-hidden" style="width:96px;height:96px">
                    <img src="{{ asset('storage/'.$photo->path) }}" alt="" style="width:100%;height:100%;object-fit:cover">
                    <form method="POST" action="{{ route('user.products.photos.destroy', [$product, $photo]) }}" class="position-absolute top-0 end-0 m-1" onsubmit="return confirm('Hapus foto?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger py-0 px-1" title="Hapus">×</button>
                    </form>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endif

<form method="POST" action="{{ route('user.products.update', $product) }}" enctype="multipart/form-data" class="card">
    @csrf @method('PUT')
    <div class="card-body row g-3">
        <div class="col-md-6">
            <label class="form-label">Jenis Ikan</label>
            <select name="fish_species_id" class="form-select" required>
                @foreach($species as $item)
                    <option value="{{ $item->id }}" @selected(old('fish_species_id', $product->fish_species_id) == $item->id)>{{ $item->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label">Judul Produk</label>
            <input name="title" class="form-control" value="{{ old('title', $product->title) }}" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Ukuran</label>
            <input name="size_label" class="form-control" value="{{ old('size_label', $product->size_label) }}" placeholder="mis. 3-4 ons">
        </div>
        <div class="col-md-4">
            <label class="form-label">Stok ({{ $product->price_unit }})</label>
            <input type="number" step="0.01" name="stock_kg" class="form-control" value="{{ old('stock_kg', $product->stock_kg) }}">
        </div>
        <div class="col-md-4">
            <label class="form-label">Status Stok</label>
            <select name="availability" class="form-select" required>
                <option value="available" @selected(old('availability', $product->availability) === 'available')>Tersedia</option>
                <option value="sold_out" @selected(old('availability', $product->availability) === 'sold_out')>Stok habis / terjual</option>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">Satuan Harga</label>
            <select name="price_unit" class="form-select">
                <option value="kg" @selected(old('price_unit', $product->price_unit) === 'kg')>kg</option>
                <option value="pcs" @selected(old('price_unit', $product->price_unit) === 'pcs')>pcs</option>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">Harga</label>
            <input type="number" step="0.01" name="price" class="form-control" value="{{ old('price', $product->price) }}" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Min. Order</label>
            <input type="number" step="0.01" name="min_order" class="form-control" value="{{ old('min_order', $product->min_order) }}">
        </div>
        <div class="col-md-6">
            <label class="form-label">WhatsApp</label>
            <input name="whatsapp" class="form-control" value="{{ old('whatsapp', $product->whatsapp) }}">
        </div>
        <div class="col-md-6">
            <label class="form-label">Lokasi Label</label>
            <input name="location_label" class="form-control" value="{{ old('location_label', $product->location_label) }}">
        </div>
        <div class="col-12">
            <label class="form-label">Deskripsi</label>
            <textarea name="description" class="form-control" rows="4">{{ old('description', $product->description) }}</textarea>
        </div>
        <div class="col-12">
            <label class="form-label">Tambah Foto (maks. 8 total)</label>
            <input type="file" name="photos[]" class="form-control" accept="image/*" multiple>
            <div class="form-text">Foto saat ini: {{ $product->photos->count() }}/8</div>
        </div>
        <div class="col-12">
            <div class="pk-form-actions">
                <a href="{{ route('user.products.index') }}" class="pk-btn pk-btn-outline">Batal</a>
                <button class="pk-btn pk-btn-primary" type="submit">Simpan</button>
            </div>
        </div>
    </div>
</form>
@endsection
