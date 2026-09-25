<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function index()
    {
        return view('admin.blog.index', [
            'articles' => Article::with('author')->latest()->paginate(15),
        ]);
    }

    public function create()
    {
        return view('admin.blog.form', ['article' => new Article]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['user_id'] = $request->user()->id;
        $data['slug'] = $this->uniqueSlug($data['title']);
        $data['is_published'] = $request->boolean('is_published');
        $data['published_at'] = $data['is_published'] ? now() : null;

        if ($request->hasFile('cover')) {
            $data['cover_path'] = $request->file('cover')->store('uploads/articles', 'public');
        }

        Article::create($data);

        return redirect()->route('admin.blog.index')->with('status', 'Artikel dibuat.');
    }

    public function edit(Article $blog)
    {
        return view('admin.blog.form', ['article' => $blog]);
    }

    public function update(Request $request, Article $blog)
    {
        $data = $this->validated($request);
        $data['is_published'] = $request->boolean('is_published');
        if ($data['is_published'] && ! $blog->published_at) {
            $data['published_at'] = now();
        }
        if (! $data['is_published']) {
            $data['published_at'] = null;
        }
        if ($request->hasFile('cover')) {
            $data['cover_path'] = $request->file('cover')->store('uploads/articles', 'public');
        }

        $blog->update($data);

        return redirect()->route('admin.blog.index')->with('status', 'Artikel diperbarui.');
    }

    public function destroy(Article $blog)
    {
        $blog->delete();

        return back()->with('status', 'Artikel dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'body' => ['required', 'string'],
            'cover' => ['nullable', 'image', 'max:4096'],
            'is_published' => ['sometimes', 'boolean'],
        ]);
    }

    private function uniqueSlug(string $title): string
    {
        $base = Str::slug($title) ?: 'artikel';
        $slug = $base;
        $i = 1;
        while (Article::withTrashed()->where('slug', $slug)->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }
}
