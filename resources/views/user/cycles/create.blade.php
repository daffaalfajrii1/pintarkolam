@extends('layouts.user')
@section('title', 'Buat Siklus — '.$pond->name)
@section('content')
@php
    $speciesMeta = $species->mapWithKeys(fn ($sp) => [
        $sp->id => [
            'name' => $sp->name,
            'days' => (int) ($sp->typical_harvest_days ?? 120),
            'weight' => (float) ($sp->typical_harvest_weight_gram ?? 250),
        ],
    ]);
@endphp
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <span>Buat Siklus untuk <strong>{{ $pond->name }}</strong></span>
        <a href="{{ route('user.ponds.show', $pond) }}" class="pk-btn pk-btn-outline pk-btn-sm">Kembali ke Kolam</a>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('user.cycles.store') }}" class="row g-3" id="cycle-create-form">
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
                <select name="fish_species_id" id="fish_species_id" class="form-select" required>
                    @foreach($species as $sp)
                        <option value="{{ $sp->id }}" @selected(old('fish_species_id') == $sp->id)>
                            {{ $sp->name }}
                            @if($sp->typical_harvest_days)
                                — ±{{ $sp->typical_harvest_days }} hari · {{ number_format($sp->typical_harvest_weight_gram, 0) }} g
                            @endif
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Tanggal tebar</label>
                <input type="date" name="stocking_date" id="stocking_date" class="form-control" required value="{{ old('stocking_date', now()->toDateString()) }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Jumlah benih</label>
                <input type="number" name="seed_count" id="seed_count" class="form-control" required value="{{ old('seed_count', 1000) }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Ukuran awal (gram)</label>
                <input type="number" step="0.01" name="initial_size_gram" class="form-control" value="{{ old('initial_size_gram', 5) }}" placeholder="Contoh: 5">
            </div>
            <div class="col-md-4">
                <label class="form-label">Target ukuran (gram)</label>
                <input type="number" step="0.01" name="target_size_gram" id="target_size_gram" class="form-control" value="{{ old('target_size_gram') }}" placeholder="Otomatis dari jenis ikan">
            </div>
            <div class="col-md-4">
                <label class="form-label">Target panen</label>
                <input type="date" name="target_harvest_date" id="target_harvest_date" class="form-control" value="{{ old('target_harvest_date') }}">
                <div class="form-text">Otomatis dari hari tipikal jenis ikan.</div>
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
                <div class="alert alert-info border-0 mb-0" id="harvest-preview">
                    <strong>Perkiraan panen</strong>
                    <div class="small mt-1" id="harvest-preview-text">Pilih jenis ikan untuk melihat proyeksi.</div>
                </div>
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

@push('scripts')
<script>
(function () {
    const meta = @json($speciesMeta);
    const speciesEl = document.getElementById('fish_species_id');
    const stockEl = document.getElementById('stocking_date');
    const seedEl = document.getElementById('seed_count');
    const targetWeightEl = document.getElementById('target_size_gram');
    const targetDateEl = document.getElementById('target_harvest_date');
    const previewEl = document.getElementById('harvest-preview-text');
    const survival = 0.9;

    function addDays(dateStr, days) {
        const d = new Date(dateStr + 'T00:00:00');
        d.setDate(d.getDate() + days);
        const y = d.getFullYear();
        const m = String(d.getMonth() + 1).padStart(2, '0');
        const day = String(d.getDate()).padStart(2, '0');
        return `${y}-${m}-${day}`;
    }

    function formatId(dateStr) {
        const [y, m, d] = dateStr.split('-');
        return `${d}/${m}/${y}`;
    }

    function refresh(forceFill) {
        const sp = meta[speciesEl.value];
        if (!sp) return;

        if (forceFill || !targetWeightEl.value) {
            targetWeightEl.value = sp.weight;
        }
        if (forceFill || !targetDateEl.dataset.manual) {
            targetDateEl.value = addDays(stockEl.value, sp.days);
        }

        const seeds = parseInt(seedEl.value || '0', 10);
        const weight = parseFloat(targetWeightEl.value || sp.weight);
        const harvestDate = targetDateEl.value || addDays(stockEl.value, sp.days);
        const projected = Math.round(seeds * survival);
        const kg = ((projected * weight) / 1000).toFixed(1);
        const value = Math.round(kg * 28000);

        previewEl.innerHTML =
            `<strong>${sp.name}</strong>: panen sekitar <strong>${formatId(harvestDate)}</strong>` +
            ` (±${sp.days} hari) · proyeksi <strong>${projected.toLocaleString('id-ID')} ekor</strong>` +
            ` · <strong>${Number(kg).toLocaleString('id-ID')} kg</strong>` +
            ` · nilai ± Rp ${value.toLocaleString('id-ID')}` +
            ` <span class="text-muted">(asumsi survival ${Math.round(survival * 100)}%)</span>`;
    }

    speciesEl.addEventListener('change', () => refresh(true));
    stockEl.addEventListener('change', () => refresh(true));
    seedEl.addEventListener('input', () => refresh(false));
    targetWeightEl.addEventListener('input', () => refresh(false));
    targetDateEl.addEventListener('change', () => {
        targetDateEl.dataset.manual = '1';
        refresh(false);
    });

    refresh(true);
})();
</script>
@endpush
