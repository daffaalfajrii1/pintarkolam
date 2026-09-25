@extends('layouts.admin')
@section('title', 'Kolam')
@section('content')
<h2 class="mb-3">Data Kolam</h2>
<div class="card"><div class="card-body table-responsive">
<table class="table table-striped">
<thead><tr><th>Nama</th><th>Pemilik</th><th>Jenis</th><th>Status</th><th>Skor</th></tr></thead>
<tbody>
@foreach($ponds as $pond)
<tr>
<td>{{ $pond->name }}</td>
<td>{{ $pond->user?->name }}</td>
<td>{{ $pond->type }}</td>
<td>{{ $pond->status }}</td>
<td>{{ $pond->latestHealthScore?->score ?? '-' }} {{ $pond->latestHealthScore?->category }}</td>
</tr>
@endforeach
</tbody>
</table>
{{ $ponds->links() }}
</div></div>
@endsection
