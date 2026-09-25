@extends('layouts.admin')
@section('title', 'Blog')
@section('content')
<div class="d-flex justify-content-between mb-3">
    <h2 class="mb-0">Artikel Blog</h2>
    <a href="{{ route('admin.blog.create') }}" class="btn btn-primary">Tulis Artikel</a>
</div>
<div class="card"><div class="card-body table-responsive">
<table class="table table-striped align-middle">
<thead><tr><th>Judul</th><th>Penulis</th><th>Status</th><th>Terbit</th><th></th></tr></thead>
<tbody>
@foreach($articles as $article)
<tr>
    <td>{{ $article->title }}</td>
    <td>{{ $article->author?->name }}</td>
    <td>{{ $article->is_published ? 'Tayang' : 'Draft' }}</td>
    <td>{{ optional($article->published_at)->format('d/m/Y H:i') ?? '-' }}</td>
    <td class="text-end">
        <a href="{{ route('admin.blog.edit', $article) }}" class="btn btn-sm btn-light-brand">Edit</a>
        <form method="POST" action="{{ route('admin.blog.destroy', $article) }}" class="d-inline" onsubmit="return confirm('Hapus artikel?')">
            @csrf @method('DELETE')
            <button class="btn btn-sm btn-outline-danger">Hapus</button>
        </form>
    </td>
</tr>
@endforeach
</tbody>
</table>
{{ $articles->links() }}
</div></div>
@endsection
