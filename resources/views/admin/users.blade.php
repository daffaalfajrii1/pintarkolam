@extends('layouts.admin')
@section('title', 'Pengguna')
@section('content')
<h2 class="mb-3">Manajemen Pengguna</h2>
<div class="card"><div class="card-body table-responsive">
<table class="table table-striped">
<thead><tr><th>Nama</th><th>Email</th><th>Role</th><th>Status</th></tr></thead>
<tbody>
@foreach($users as $user)
<tr>
<td>{{ $user->name }}</td>
<td>{{ $user->email }}</td>
<td>{{ $user->getRoleNames()->join(', ') }}</td>
<td>{{ $user->is_active ? 'Aktif' : 'Nonaktif' }}</td>
</tr>
@endforeach
</tbody>
</table>
{{ $users->links() }}
</div></div>
@endsection
