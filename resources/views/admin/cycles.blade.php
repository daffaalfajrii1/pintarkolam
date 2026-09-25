@extends('layouts.admin')
@section('title', 'Siklus Aktif per Pembudidaya')
@section('content')
<h2 class="mb-3">Siklus Budidaya (dikelompokkan per user)</h2>
@foreach($users as $user)
<div class="card mb-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <strong>{{ $user->name }}</strong>
            <div class="small text-muted">{{ $user->farmerProfile?->shop_name ?: $user->farmerProfile?->business_name }} · {{ $user->email }}</div>
        </div>
        <a href="{{ route('admin.water', ['user_id' => $user->id]) }}" class="btn btn-sm btn-light-brand">Lihat Kualitas Air</a>
    </div>
    <div class="card-body table-responsive">
        <table class="table table-sm mb-0">
            <thead><tr><th>Nama Siklus</th><th>Kolam</th><th>Ikan</th><th>Tebar</th><th>Status</th></tr></thead>
            <tbody>
            @foreach($user->cycles as $cycle)
            <tr>
                <td>{{ $cycle->name }}</td>
                <td>{{ $cycle->pond?->name }}</td>
                <td>{{ $cycle->fishSpecies?->name }}</td>
                <td>{{ $cycle->stocking_date?->format('d/m/Y') }} ({{ $cycle->seed_count }} ekor)</td>
                <td><span class="badge bg-primary">{{ $cycle->status }}</span></td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endforeach
{{ $users->links() }}
@endsection
