@extends('layouts.user')
@section('title', 'Buat Siklus — '.$pond->name)
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <span>Buat Siklus untuk <strong>{{ $pond->name }}</strong></span>
        <a href="{{ route('user.ponds.show', $pond) }}" class="pk-btn pk-btn-outline pk-btn-sm">Kembali ke Kolam</a>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('user.cycles.store') }}" class="row g-3">
            @csrf
            <input type="hidden" name="pond_id" value="{{ $pond->id }}">

            <div class="col-md-6">
                <label class="form-label">Nama siklus</label>
                <input name="name" class="form-control" required value="{{ old('name') }}" placeholder="Contoh: Siklus Lele September">
            </div>
            <div class="col-md-6">
                <label class="form-label">Kolam</label>
                <input type="text" class="form-control" value="{{ $pond->name }}" disabled>
                <div class="form-text">Siklus otomatis masuk ke kolam ini.</div>
            </div>
            <div class="col-md-6">
                <label class="form-label">Jenis ikan</label>
                <select name="fish_species_id" class="form-select" required>
                    @foreach($species as $sp)
                        <option value="{{ $sp->id }}" @selected(old('fish_species_id') == $sp->id)>{{ $sp->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Tanggal tebar</label>
                <input type="date" name="stocking_date" class="form-control" required value="{{ old('stocking_date', now()->toDateString()) }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Jumlah benih</label>
                <input type="number" name="seed_count" class="form-control" required value="{{ old('seed_count', 1000) }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Ukuran awal (gram)</label>
                <input type="number" step="0.01" name="initial_size_gram" class="form-control" value="{{ old('initial_size_gram') }}" placeholder="Contoh: 5">
            </div>
            <div class="col-md-4">
                <label class="form-label">Target ukuran (gram)</label>
                <input type="number" step="0.01" name="target_size_gram" class="form-control" value="{{ old('target_size_gram') }}" placeholder="Contoh: 150">
            </div>
            <div class="col-md-4">
                <label class="form-label">Target panen</label>
                <input type="date" name="target_harvest_date" class="form-control" value="{{ old('target_harvest_date') }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Asal benih</label>
                <input name="seed_source" class="form-control" value="{{ old('seed_source') }}">
            </div>
            <div class="col-12">
                <label class="form-label">Catatan</label>
                <textarea name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
            </div>
            <div class="col-12">
                <div class="pk-form-actions">
                    <a href="{{ route('user.ponds.show', $pond) }}" class="pk-btn pk-btn-outline">Batal</a>
                    <button class="pk-btn pk-btn-primary" type="submit">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
