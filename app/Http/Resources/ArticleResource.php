<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ArticleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt,
            'body' => $this->when($request->routeIs('*.blog.show') || $request->route('post'), $this->body),
            'cover_url' => $this->cover_path ? Storage::disk('public')->url($this->cover_path) : null,
            'published_at' => $this->published_at?->toISOString(),
        ];
    }
}
