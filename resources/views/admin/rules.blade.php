@extends('layouts.admin')
@section('title', 'Aturan Rekomendasi')
@section('content')
<div class="pk-help">
    <strong>Cara mengisi Aturan Rekomendasi</strong>
    <p class="mb-1">Aturan dikelompokkan per jenis ikan. Jika spesies punya aturan sendiri, sistem memakai itu (bukan global).</p>
    <ol>
        <li><strong>Jenis ikan</strong> — pilih spesies, atau kosongkan untuk aturan global.</li>
        <li><strong>Parameter</strong> — <code>ph</code>, <code>temperature</code>, atau <code>do</code>.</li>
        <li><strong>Min / Max trigger</strong> — rentang nilai yang memicu saran.</li>
        <li><strong>Severity</strong> — info / warning / critical.</li>
    </ol>
</div>

<div class="row g-3 mb-3">
    <div class="col-12 col-lg-4">
        <div class="card h-100">
            <div class="card-header">Tambah Aturan</div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.rules.store') }}">
                    @csrf
                    <div class="mb-2">
                        <label class="form-label">Jenis ikan</label>
                        <select name="fish_species_id" class="form-select">
                            <option value="">Global (semua ikan)</option>
                            @foreach($species as $sp)
                                <option value="{{ $sp->id }}" @selected($selectedSpeciesId == $sp->id)>{{ $sp->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Parameter</label>
                        <select name="parameter_code" class="form-select">
                            <option value="ph">ph — Keasaman air</option>
                            <option value="temperature">temperature — Suhu (°C)</option>
                            <option value="do">do — Oksigen terlarut (mg/L)</option>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Judul <span class="text-danger">*</span></label>
                        <input name="title" class="form-control" placeholder="Contoh: DO rendah untuk Lele" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Saran <span class="text-danger">*</span></label>
                        <textarea name="advice" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Severity</label>
                        <select name="severity" class="form-select">
                            <option value="info">info</option>
                            <option value="warning">warning</option>
                            <option value="critical">critical</option>
                        </select>
                    </div>
                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <label class="form-label">Min trigger</label>
                            <input type="number" step="any" name="min_value" class="form-control">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Max trigger</label>
                            <input type="number" step="any" name="max_value" class="form-control">
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Prioritas</label>
                        <input type="number" name="priority" class="form-control" value="50" min="1">
                    </div>
                    <button class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-8">
        <div class="card mb-3">
            <div class="card-body">
                <form method="GET" class="row g-2 align-items-end">
                    <div class="col-md-8">
                        <label class="form-label">Filter jenis ikan</label>
                        <select name="species_id" class="form-select" onchange="this.form.submit()">
                            <option value="">Semua (dikelompokkan per ikan)</option>
                            @foreach($species as $sp)
                                <option value="{{ $sp->id }}" @selected($selectedSpeciesId == $sp->id)>{{ $sp->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        @if($selectedSpeciesId)
                            <a href="{{ route('admin.rules') }}" class="btn btn-outline-secondary w-100">Reset filter</a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        @forelse($groups as $group)
            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <strong>{{ $group['label'] }}</strong>
                    <span class="badge text-bg-secondary">{{ $group['rules']->count() }} aturan</span>
                </div>
                <div class="card-body p-0 table-responsive">
                    <table class="table table-striped align-middle mb-0">
                        <thead>
                            <tr>
                                <th style="width:80px">Prioritas</th>
                                <th style="width:110px">Param</th>
                                <th>Judul &amp; Saran</th>
                                <th style="width:140px">Range</th>
                                <th style="width:100px">Severity</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($group['rules'] as $rule)
                                <tr>
                                    <td>{{ $rule->priority }}</td>
                                    <td><code>{{ $rule->parameter_code }}</code></td>
                                    <td>
                                        <div class="fw-semibold">{{ $rule->title }}</div>
                                        <div class="small text-muted">{{ Str::limit($rule->advice, 90) }}</div>
                                    </td>
                                    <td>{{ $rule->min_value ?? '—' }} – {{ $rule->max_value ?? '—' }}</td>
                                    <td>
                                        @php
                                            $sevClass = match($rule->severity) {
                                                'critical' => 'danger',
                                                'warning' => 'warning',
                                                default => 'info',
                                            };
                                        @endphp
                                        <span class="badge text-bg-{{ $sevClass }}">{{ $rule->severity }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-muted p-3">Belum ada aturan untuk kelompok ini.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @empty
            <div class="alert alert-light border">Belum ada aturan rekomendasi.</div>
        @endforelse

        @if($paginator)
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-2">
                <div class="small text-muted">
                    Halaman {{ $paginator->currentPage() }} dari {{ $paginator->lastPage() }}
                    · {{ $paginator->total() }} jenis ikan
                </div>
                <div>{{ $paginator->links() }}</div>
            </div>
        @endif
    </div>
</div>
@endsection
