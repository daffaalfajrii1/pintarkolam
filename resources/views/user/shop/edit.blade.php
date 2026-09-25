@extends('layouts.user')
@section('title', 'Profil Toko')
@section('content')
<div class="pk-page-head mb-3">
    <h2 class="pk-page-title mb-1">Profil Toko</h2>
    <div class="small text-muted">Lengkapi profil sebelum menjual di katalog</div>
</div>
<div class="alert alert-info">
    Alur katalog: <strong>lengkapi profil toko → disetujui admin → baru bisa jualan → produk di-accept admin</strong>.
</div>
@if($profile->exists)
<div class="mb-3">
    Status verifikasi: <span class="badge bg-secondary">{{ $profile->verification_status }}</span>
    · Etalase: <span class="badge bg-secondary">{{ $profile->storefront_status }}</span>
</div>
@if($profile->storefront_rejection_reason)
    <div class="alert alert-warning">Ditolak: {{ $profile->storefront_rejection_reason }}</div>
@endif
@endif

<form method="POST" action="{{ route('user.shop.update') }}" enctype="multipart/form-data" class="card">
@csrf @method('PUT')
<div class="card-body row g-3">
    <div class="col-md-6"><label class="form-label">Nama Usaha</label><input name="business_name" class="form-control" value="{{ old('business_name', $profile->business_name) }}" required></div>
    <div class="col-md-6"><label class="form-label">Nama Toko (etalase)</label><input name="shop_name" class="form-control" value="{{ old('shop_name', $profile->shop_name) }}" required></div>
    <div class="col-md-6"><label class="form-label">Nama Pemilik</label><input name="owner_name" class="form-control" value="{{ old('owner_name', $profile->owner_name) }}" required></div>
    <div class="col-md-6"><label class="form-label">WhatsApp</label><input name="whatsapp" class="form-control" value="{{ old('whatsapp', $profile->whatsapp) }}" required></div>
    <div class="col-12"><label class="form-label">Alamat</label><textarea name="address" class="form-control" rows="2" required>{{ old('address', $profile->address) }}</textarea></div>
    <div class="col-md-4"><label class="form-label">Desa</label><input name="village" class="form-control" value="{{ old('village', $profile->village) }}"></div>
    <div class="col-md-4"><label class="form-label">Kecamatan</label><input name="district" class="form-control" value="{{ old('district', $profile->district) }}" required></div>
    <div class="col-md-4"><label class="form-label">Kabupaten</label><input name="regency" class="form-control" value="{{ old('regency', $profile->regency ?: 'Rejang Lebong') }}"></div>
    <div class="col-md-6"><label class="form-label">Provinsi</label><input name="province" class="form-control" value="{{ old('province', $profile->province ?: 'Bengkulu') }}"></div>
    <div class="col-md-6"><label class="form-label">Bio singkat</label><input name="bio" class="form-control" value="{{ old('bio', $profile->bio) }}"></div>
    <div class="col-12"><label class="form-label">Deskripsi Toko</label><textarea name="shop_description" class="form-control" rows="4" required>{{ old('shop_description', $profile->shop_description) }}</textarea></div>
    <div class="col-md-6"><label class="form-label">Logo Toko</label><input type="file" name="shop_logo" class="form-control" accept="image/*"></div>
    <div class="col-md-6"><label class="form-label">Cover Toko</label><input type="file" name="shop_cover" class="form-control" accept="image/*"></div>
    <div class="col-12"><button class="btn btn-primary">Simpan & Ajukan Persetujuan</button></div>
</div>
</form>
@endsection
