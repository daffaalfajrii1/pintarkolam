@extends('layouts.user')
@section('title', 'Edit Kolam')
@section('content')
<div class="card"><div class="card-body">
<form method="POST" action="{{ route('user.ponds.update', $pond) }}" class="row g-3">@csrf @method('PUT')
<div class="col-md-6"><label class="form-label">Nama</label><input name="name" class="form-control" required value="{{ old('name', $pond->name) }}"></div>
<div class="col-md-6"><label class="form-label">Jenis</label>
<select name="type" class="form-select" required>
@foreach(['beton','terpal','tanah','bioflok','lainnya'] as $t)
<option value="{{ $t }}" @selected(old('type', $pond->type)===$t)>{{ ucfirst($t) }}</option>
@endforeach
</select></div>
<div class="col-md-4"><label class="form-label">Luas (m²)</label><input type="number" step="0.01" name="area_m2" class="form-control" value="{{ old('area_m2', $pond->area_m2) }}"></div>
<div class="col-md-4"><label class="form-label">Kedalaman (m)</label><input type="number" step="0.01" name="depth_m" class="form-control" value="{{ old('depth_m', $pond->depth_m) }}"></div>
<div class="col-md-4"><label class="form-label">Status</label>
<select name="status" class="form-select">
@foreach(['active','inactive','maintenance'] as $s)
<option value="{{ $s }}" @selected(old('status', $pond->status)===$s)>{{ $s }}</option>
@endforeach
</select></div>
<div class="col-md-4"><label class="form-label">Latitude</label><input type="number" step="any" name="latitude" class="form-control" value="{{ old('latitude', $pond->latitude) }}"></div>
<div class="col-md-4"><label class="form-label">Longitude</label><input type="number" step="any" name="longitude" class="form-control" value="{{ old('longitude', $pond->longitude) }}"></div>
<div class="col-md-4 form-check mt-4"><input type="checkbox" name="hide_exact_location" value="1" class="form-check-input" id="hide" @checked(old('hide_exact_location', $pond->hide_exact_location))><label for="hide" class="form-check-label">Sembunyikan koordinat publik</label></div>
<div class="col-12"><label class="form-label">Catatan</label><textarea name="notes" class="form-control" rows="3">{{ old('notes', $pond->notes) }}</textarea></div>
<div class="col-12">
    <div class="pk-form-actions">
        <a href="{{ route('user.ponds.show', $pond) }}" class="pk-btn pk-btn-outline">Batal</a>
        <button class="pk-btn pk-btn-primary" type="submit">Simpan</button>
    </div>
</div>
</form>
</div></div>
@endsection
