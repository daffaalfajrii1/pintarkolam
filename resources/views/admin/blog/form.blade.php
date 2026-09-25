@extends('layouts.admin')
@section('title', $article->exists ? 'Edit Artikel' : 'Tulis Artikel')
@section('content')
<form method="POST" action="{{ $article->exists ? route('admin.blog.update', $article) : route('admin.blog.store') }}" enctype="multipart/form-data" class="card">
@csrf
@if($article->exists) @method('PUT') @endif
<div class="card-body">
    <div class="mb-3">
        <label class="form-label">Judul</label>
        <input type="text" name="title" class="form-control" value="{{ old('title', $article->title) }}" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Ringkasan</label>
        <textarea name="excerpt" class="form-control" rows="2">{{ old('excerpt', $article->excerpt) }}</textarea>
    </div>
    <div class="mb-3">
        <label class="form-label">Cover</label>
        <input type="file" name="cover" class="form-control" accept="image/*">
        @if($article->cover_path)
            <img src="{{ asset('storage/'.$article->cover_path) }}" class="mt-2" style="max-height:80px">
        @endif
    </div>
    <div class="mb-3">
        <label class="form-label">Isi Artikel</label>
        <textarea name="body" id="body" class="form-control" rows="12">{{ old('body', $article->body) }}</textarea>
    </div>
    <div class="form-check mb-3">
        <input class="form-check-input" type="checkbox" name="is_published" value="1" id="is_published" @checked(old('is_published', $article->is_published))>
        <label class="form-check-label" for="is_published">Publikasikan</label>
    </div>
    <button class="btn btn-primary">Simpan</button>
    <a href="{{ route('admin.blog.index') }}" class="btn btn-light">Batal</a>
</div>
</form>
@endsection
@push('scripts')
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const textarea = document.getElementById('body');
    const editor = document.createElement('div');
    editor.id = 'quill-editor';
    editor.style.minHeight = '320px';
    editor.innerHTML = textarea.value || '';
    textarea.style.display = 'none';
    textarea.parentNode.insertBefore(editor, textarea);
    const quill = new Quill('#quill-editor', {
        theme: 'snow',
        modules: { toolbar: [['bold','italic','underline'],[{'list':'ordered'},{'list':'bullet'}],['link','image'],['clean']] }
    });
    textarea.closest('form').addEventListener('submit', function () {
        textarea.value = quill.root.innerHTML;
    });
});
</script>
@endpush
