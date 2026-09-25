@extends('layouts.admin')
@section('title', 'Parameter Kualitas Air')
@section('content')
<div class="pk-help">
    <strong>Parameter air per jenis ikan</strong>
    <p class="mb-1">Setiap jenis ikan punya rentang pH, suhu, dan DO sendiri. Skor kesehatan &amp; rekomendasi mengikuti ikan pada siklus aktif.</p>
    <ul>
        <li><strong>Ideal min–max</strong> — rentang terbaik untuk spesies itu.</li>
        <li><strong>Min–max aman</strong> — di luar ini status biasanya kritis.</li>
        <li><strong>Parameter global</strong> — cadangan jika spesies belum punya data sendiri.</li>
    </ul>
</div>

<div class="card mb-3">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.parameters') }}" class="row g-2 align-items-end">
            <div class="col-md-6">
                <label class="form-label">Pilih jenis ikan</label>
                <select name="species_id" class="form-select" onchange="this.form.submit()">
                    @foreach($species as $sp)
                        <option value="{{ $sp->id }}" @selected($selectedSpeciesId == $sp->id)>{{ $sp->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <div class="form-text">Ubah nilai lalu klik Simpan per baris parameter.</div>
            </div>
        </form>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header">Parameter untuk {{ $species->firstWhere('id', $selectedSpeciesId)?->name ?? '—' }}</div>
    <div class="card-body">
        @forelse($speciesParameters as $parameter)
            <form method="POST" action="{{ route('admin.species-parameters.update', $parameter) }}" class="row g-2 align-items-end border-bottom py-3">
                @csrf
                @method('PUT')
                <div class="col-12 col-md-2">
                    <strong>{{ $parameter->name }}</strong>
                    <div class="small text-muted">{{ $parameter->code }}@if($parameter->unit) · {{ $parameter->unit }}@endif</div>
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label">Ideal min</label>
                    <input type="number" step="any" name="ideal_min" class="form-control" value="{{ $parameter->ideal_min }}">
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label">Ideal max</label>
                    <input type="number" step="any" name="ideal_max" class="form-control" value="{{ $parameter->ideal_max }}">
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label">Min aman</label>
                    <input type="number" step="any" name="min_value" class="form-control" value="{{ $parameter->min_value }}">
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label">Max aman</label>
                    <input type="number" step="any" name="max_value" class="form-control" value="{{ $parameter->max_value }}">
                </div>
                <div class="col-6 col-md-1">
                    <label class="form-label">Bobot</label>
                    <input type="number" name="weight" class="form-control" value="{{ $parameter->weight }}">
                </div>
                <div class="col-6 col-md-1">
                    <div class="form-check mb-2">
                        <input type="checkbox" name="is_active" value="1" class="form-check-input" id="sp-active-{{ $parameter->id }}" @checked($parameter->is_active)>
                        <label class="form-check-label" for="sp-active-{{ $parameter->id }}">Aktif</label>
                    </div>
                    <button class="btn btn-sm btn-primary">Simpan</button>
                </div>
            </form>
        @empty
            <p class="text-muted mb-0">Belum ada parameter untuk spesies ini. Jalankan seeder database.</p>
        @endforelse
    </div>
</div>

<div class="card">
    <div class="card-header">Parameter Global (cadangan)</div>
    <div class="card-body">
        @foreach($globalParameters as $parameter)
            <form method="POST" action="{{ route('admin.parameters.update', $parameter) }}" class="row g-2 align-items-end border-bottom py-3">
                @csrf
                @method('PUT')
                <div class="col-12 col-md-2">
                    <strong>{{ $parameter->name }}</strong>
                    <div class="small text-muted">{{ $parameter->code }}</div>
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label">Ideal min</label>
                    <input type="number" step="any" name="ideal_min" class="form-control" value="{{ $parameter->ideal_min }}">
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label">Ideal max</label>
                    <input type="number" step="any" name="ideal_max" class="form-control" value="{{ $parameter->ideal_max }}">
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label">Min aman</label>
                    <input type="number" step="any" name="min_value" class="form-control" value="{{ $parameter->min_value }}">
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label">Max aman</label>
                    <input type="number" step="any" name="max_value" class="form-control" value="{{ $parameter->max_value }}">
                </div>
                <div class="col-6 col-md-1">
                    <label class="form-label">Bobot</label>
                    <input type="number" name="weight" class="form-control" value="{{ $parameter->weight }}">
                </div>
                <div class="col-6 col-md-1">
                    <div class="form-check mb-2">
                        <input type="checkbox" name="is_active" value="1" class="form-check-input" id="g-active-{{ $parameter->id }}" @checked($parameter->is_active)>
                        <label class="form-check-label" for="g-active-{{ $parameter->id }}">Aktif</label>
                    </div>
                    <button class="btn btn-sm btn-primary">Simpan</button>
                </div>
            </form>
        @endforeach
    </div>
</div>
@endsection
