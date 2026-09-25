@extends('layouts.admin')
@section('title', 'Monitoring Kualitas Air')
@section('content')
<div class="row g-3 mb-3">
    <div class="col-md-5">
        <form method="GET" class="card card-body">
            <label class="form-label">Pembudidaya</label>
            <select name="user_id" class="form-select" onchange="this.form.submit()">
                @foreach($users as $user)
                    <option value="{{ $user->id }}" @selected(($selectedUser?->id) === $user->id)>
                        {{ $user->name }} @if($user->farmerProfile) — {{ $user->farmerProfile->shop_name ?: $user->farmerProfile->business_name }} @endif
                    </option>
                @endforeach
            </select>
        </form>
    </div>
    <div class="col-md-7">
        @if($selectedUser)
        <div class="card card-body">
            <strong>{{ $selectedUser->name }}</strong>
            <div class="small text-muted">{{ $selectedUser->farmerProfile?->shop_name ?: $selectedUser->farmerProfile?->business_name }} · {{ $selectedUser->farmerProfile?->district }}</div>
            <div class="mt-2 d-flex flex-wrap gap-2">
                <a href="{{ route('admin.water', ['user_id' => $selectedUser->id]) }}" class="btn btn-sm {{ !request('cycle_id') ? 'btn-primary' : 'btn-light' }}">Semua siklus</a>
                @foreach($cycles as $cycle)
                    <a href="{{ route('admin.water', ['user_id' => $selectedUser->id, 'cycle_id' => $cycle->id]) }}" class="btn btn-sm {{ request('cycle_id') == $cycle->id ? 'btn-primary' : 'btn-light' }}">
                        {{ $cycle->name }} ({{ $cycle->status }})
                    </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

@if($logs)
<div class="card"><div class="card-body table-responsive">
<table class="table table-striped">
<thead><tr><th>Waktu</th><th>Kolam</th><th>Siklus</th><th>pH</th><th>Suhu</th><th>DO</th><th>Catatan</th></tr></thead>
<tbody>
@forelse($logs as $log)
<tr>
    <td>{{ $log->measured_at }}</td>
    <td>{{ $log->pond?->name }}</td>
    <td>{{ $log->cycle?->name }}</td>
    <td>{{ $log->ph }}</td>
    <td>{{ $log->temperature_c }}</td>
    <td>{{ $log->dissolved_oxygen }}</td>
    <td>{{ $log->notes }}</td>
</tr>
@empty
<tr><td colspan="7" class="text-center text-muted">Belum ada data kualitas air untuk pembudidaya ini.</td></tr>
@endforelse
</tbody>
</table>
{{ $logs->links() }}
</div></div>
@endif
@endsection
