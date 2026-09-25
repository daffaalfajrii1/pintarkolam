@extends('layouts.user')
@section('title', 'Pakan')
@section('content')
@include('user.cycles._nav')
@php
    $feedTypes = ['Pelet', 'Pelet terapung', 'Pelet tenggelam', 'Cacing', 'Keong', 'Dedak', 'Ampas tahu', 'Hijauan', 'Campuran'];
@endphp

<div class="row g-3">
    <div class="col-lg-6">
        <div class="card mb-3">
            <div class="card-header">Jadwal Pakan</div>
            <div class="card-body">
                @forelse($cycle->feedingSchedules as $schedule)
                    <div class="border rounded p-3 mb-2">
                        <form method="POST" action="{{ route('user.cycles.schedules.update', [$cycle, $schedule]) }}" class="row g-2 align-items-end">
                            @csrf @method('PUT')
                            <div class="col-6 col-md-3">
                                <label class="form-label">Jam</label>
                                <input type="time" name="feed_time" class="form-control" value="{{ substr($schedule->feed_time,0,5) }}" required>
                            </div>
                            <div class="col-6 col-md-3">
                                <label class="form-label">Jenis</label>
                                <select name="feed_type" class="form-select" required>
                                    @foreach($feedTypes as $type)
                                        <option value="{{ $type }}" @selected(old('feed_type', $schedule->feed_type) === $type)>{{ $type }}</option>
                                    @endforeach
                                    @if($schedule->feed_type && ! in_array($schedule->feed_type, $feedTypes, true))
                                        <option value="{{ $schedule->feed_type }}" selected>{{ $schedule->feed_type }}</option>
                                    @endif
                                </select>
                            </div>
                            <div class="col-6 col-md-2">
                                <label class="form-label">kg</label>
                                <input type="number" step="0.001" name="amount_kg" class="form-control" value="{{ $schedule->amount_kg }}">
                            </div>
                            <div class="col-6 col-md-2">
                                <label class="form-label">Status</label>
                                <select name="is_active" class="form-select">
                                    <option value="1" @selected($schedule->is_active)>Aktif</option>
                                    <option value="0" @selected(!$schedule->is_active)>Nonaktif</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-2">
                                <button class="pk-btn pk-btn-primary pk-btn-sm w-100" type="submit">Simpan</button>
                            </div>
                        </form>
                        <form method="POST" action="{{ route('user.cycles.schedules.destroy', [$cycle, $schedule]) }}" class="mt-2" onsubmit="return confirm('Hapus jadwal ini?')">
                            @csrf @method('DELETE')
                            <button class="pk-btn pk-btn-outline pk-btn-sm" type="submit">Hapus jadwal</button>
                        </form>
                    </div>
                @empty
                    <p class="text-muted">Belum ada jadwal.</p>
                @endforelse

                <hr>
                <h6 class="mb-2">Tambah Jadwal</h6>
                <form method="POST" action="{{ route('user.cycles.schedules.store', $cycle) }}" class="row g-2">
                    @csrf
                    <div class="col-md-3">
                        <label class="form-label">Jam</label>
                        <input type="time" name="feed_time" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Jenis</label>
                        <select name="feed_type" class="form-select" required>
                            @foreach($feedTypes as $type)
                                <option value="{{ $type }}" @selected(old('feed_type', 'Pelet') === $type)>{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Jumlah (kg)</label>
                        <input type="number" step="0.001" name="amount_kg" class="form-control">
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button class="pk-btn pk-btn-primary" type="submit">Tambah</button>
                    </div>
                </form>
                <p class="small text-muted mt-3 mb-0">Notifikasi muncul di menu Notifikasi saat jam pakan tiba (±5 menit).</p>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card mb-3">
            <div class="card-header">Catat Pakan Harian</div>
            <div class="card-body">
                <form method="POST" action="{{ route('user.cycles.feeding.store', $cycle) }}" class="row g-3">
                    @csrf
                    <div class="col-md-6">
                        <label class="form-label">Jumlah (kg)</label>
                        <input type="number" step="0.001" name="amount_kg" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Sisa pakan (kg)</label>
                        <input type="number" step="0.001" name="leftover_kg" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Jenis</label>
                        <select name="feed_type" class="form-select" required>
                            @foreach($feedTypes as $type)
                                <option value="{{ $type }}" @selected(old('feed_type', 'Pelet') === $type)>{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="done">Selesai</option>
                            <option value="partial">Sebagian</option>
                            <option value="skipped">Dilewati</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Catatan</label>
                        <textarea name="notes" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="col-12">
                        <div class="pk-form-actions">
                            <button class="pk-btn pk-btn-primary" type="submit">Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">Riwayat Pakan · FCR {{ $cycle->fcr() ?? '-' }}</div>
            <div class="card-body p-0 table-responsive">
                <table class="table pk-table mb-0">
                    <thead><tr><th>Waktu</th><th>Jenis</th><th>kg</th><th>Sisa</th><th>Status</th></tr></thead>
                    <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td>{{ $log->fed_at?->format('d/m/Y H:i') }}</td>
                            <td>{{ $log->feed_type }}</td>
                            <td>{{ $log->amount_kg }}</td>
                            <td>{{ $log->leftover_kg ?? '-' }}</td>
                            <td>{{ $log->status }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted">Belum ada riwayat.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            @if($logs->hasPages())<div class="card-footer">{{ $logs->links() }}</div>@endif
        </div>
    </div>
</div>
@endsection
