@extends('layouts.user')
@section('title', 'Biaya & Keuntungan')
@section('content')
@include('user.cycles._nav')

<div class="row g-3">
    <div class="col-lg-5">
        <div class="card mb-3">
            <div class="card-header">Tambah Catatan (satu per satu)</div>
            <div class="card-body">
                <form method="POST" action="{{ route('user.cycles.costs.store', $cycle) }}" class="row g-3">
                    @csrf
                    <div class="col-12">
                        <label class="form-label">Jenis</label>
                        <select name="category" class="form-select" required>
                            @foreach($categories as $key => $label)
                                <option value="{{ $key }}" @selected(old('category') === $key)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nominal</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" step="1" min="1" name="amount" class="form-control" value="{{ old('amount') }}" required placeholder="contoh: 150000">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tanggal</label>
                        <input type="date" name="recorded_at" class="form-control" value="{{ old('recorded_at', now()->toDateString()) }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Keterangan (opsional)</label>
                        <input type="text" name="notes" class="form-control" value="{{ old('notes') }}" placeholder="mis. beli pelet 10 kg">
                    </div>
                    <div class="col-12">
                        <div class="pk-form-actions">
                            <button class="pk-btn pk-btn-primary" type="submit">Tambah</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">Ringkasan</div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="small text-muted">Total biaya</div>
                    <div class="fs-4 pk-money">Rp {{ number_format($totalCost, 0, ',', '.') }}</div>
                </div>
                <div class="mb-3">
                    <div class="small text-muted">Total pendapatan</div>
                    <div class="fs-5 pk-money">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
                </div>
                <div class="mb-3">
                    <div class="small text-muted">Keuntungan bersih</div>
                    <div class="fs-4 pk-money {{ $netProfit >= 0 ? 'text-success' : 'text-danger' }}">Rp {{ number_format($netProfit, 0, ',', '.') }}</div>
                </div>
                <hr>
                <div class="small text-muted mb-2">Per jenis</div>
                @forelse($categories as $key => $label)
                    @if(($byCategory[$key] ?? 0) > 0)
                        <div class="d-flex justify-content-between small py-1 border-bottom">
                            <span>{{ $label }}</span>
                            <strong class="pk-money">Rp {{ number_format((float) $byCategory[$key], 0, ',', '.') }}</strong>
                        </div>
                    @endif
                @empty
                @endforelse
                @if($byCategory->isEmpty())
                    <div class="text-muted small">Belum ada catatan.</div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="card">
            <div class="card-header">Daftar Catatan Biaya / Pendapatan</div>
            <div class="card-body p-0 table-responsive">
                <table class="table pk-table mb-0 align-middle">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Jenis</th>
                            <th>Keterangan</th>
                            <th class="text-end">Nominal</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($entries as $entry)
                        <tr>
                            <td>{{ $entry->recorded_at?->format('d/m/Y') }}</td>
                            <td>
                                <span class="pk-badge {{ $entry->isRevenue() ? 'pk-badge-ok' : 'pk-badge-info' }}">
                                    {{ $entry->categoryLabel() }}
                                </span>
                            </td>
                            <td>{{ $entry->notes ?: '-' }}</td>
                            <td class="text-end pk-money {{ $entry->isRevenue() ? 'text-success' : '' }}">
                                {{ $entry->isRevenue() ? '+' : '-' }} Rp {{ number_format((float) $entry->amount, 0, ',', '.') }}
                            </td>
                            <td class="text-end pe-3">
                                <form method="POST" action="{{ route('user.cycles.costs.destroy', [$cycle, $entry]) }}" onsubmit="return confirm('Hapus catatan ini?')">
                                    @csrf @method('DELETE')
                                    <button class="pk-btn pk-btn-outline pk-btn-sm" type="submit">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted">Belum ada catatan. Tambahkan satu per satu dari form kiri.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            @if($entries->hasPages())
                <div class="card-footer">{{ $entries->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection
