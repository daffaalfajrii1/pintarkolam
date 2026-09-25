@extends('layouts.user')
@section('title', 'Siklus Budidaya')
@section('content')
<div class="d-flex justify-content-between mb-3">
    <h4 class="mb-0">Siklus Budidaya</h4>
    <a href="{{ route('user.cycles.create') }}" class="btn btn-primary">Buat Siklus</a>
</div>
<div class="card"><div class="card-body table-responsive">
<table class="table table-striped">
<thead><tr><th>Nama</th><th>Kolam</th><th>Ikan</th><th>Tebar</th><th>Status</th><th></th></tr></thead>
<tbody>
@foreach($cycles as $cycle)
<tr>
<td>{{ $cycle->name }}</td>
<td>{{ $cycle->pond?->name }}</td>
<td>{{ $cycle->fishSpecies?->name }}</td>
<td>{{ $cycle->stocking_date?->format('d/m/Y') }} ({{ $cycle->seed_count }})</td>
<td>{{ $cycle->status }}</td>
<td><a href="{{ route('user.cycles.show', $cycle) }}" class="btn btn-sm btn-light">Detail</a></td>
</tr>
@endforeach
</tbody>
</table>
{{ $cycles->links() }}
</div></div>
@endsection
