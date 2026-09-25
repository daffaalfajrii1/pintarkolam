@extends('layouts.admin')
@section('title', 'Jenis Ikan')
@section('content')
<div class="pk-help">
    <strong>Cara mengisi Jenis Ikan</strong>
    <ol>
        <li><strong>Nama</strong> — nama lokal yang dipakai pembudidaya (contoh: Nila, Lele Dumbo).</li>
        <li><strong>Nama ilmiah</strong> — opsional, untuk referensi (contoh: <em>Oreochromis niloticus</em>).</li>
        <li><strong>Hari panen tipikal</strong> — estimasi lama budidaya sampai siap panen (hari). Dipakai sebagai default saat buat siklus.</li>
        <li><strong>Berat tipikal (gram)</strong> — target berat panen per ekor. Contoh nila ±250 g, lele ±150 g.</li>
        <li><strong>Deskripsi</strong> — catatan singkat karakteristik / tips budidaya.</li>
    </ol>
    <p class="pk-ex mb-0 mt-2">Slug dibuat otomatis dari nama. Pastikan nama unik agar tidak bentrok di katalog &amp; siklus.</p>
</div>

<div class="row g-3">
    <div class="col-12 col-lg-4">
        <div class="card">
            <div class="card-header">Tambah Jenis Ikan</div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.species.store') }}">
                    @csrf
                    <div class="mb-2">
                        <label class="form-label">Nama <span class="text-danger">*</span></label>
                        <input name="name" class="form-control" placeholder="Contoh: Gurame" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Nama ilmiah</label>
                        <input name="scientific_name" class="form-control" placeholder="Contoh: Osphronemus goramy">
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Hari panen tipikal</label>
                        <input type="number" name="typical_harvest_days" class="form-control" placeholder="Contoh: 180" min="1">
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Berat tipikal (gram)</label>
                        <input type="number" step="0.01" name="typical_harvest_weight_gram" class="form-control" placeholder="Contoh: 500">
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Karakteristik singkat jenis ikan..."></textarea>
                    </div>
                    <button class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-8">
        <div class="card">
            <div class="card-body table-responsive">
                <table class="table table-striped align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Slug</th>
                            <th>Hari</th>
                            <th>Berat</th>
                            <th>Aktif</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($species as $sp)
                            <tr>
                                <td>
                                    <strong>{{ $sp->name }}</strong>
                                    @if($sp->scientific_name)
                                        <div class="small text-muted"><em>{{ $sp->scientific_name }}</em></div>
                                    @endif
                                </td>
                                <td>{{ $sp->slug }}</td>
                                <td>{{ $sp->typical_harvest_days ?? '—' }}</td>
                                <td>{{ $sp->typical_harvest_weight_gram ?? '—' }}</td>
                                <td>{{ $sp->is_active ? 'Ya' : 'Tidak' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-muted">Belum ada data jenis ikan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-3">{{ $species->links() }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
