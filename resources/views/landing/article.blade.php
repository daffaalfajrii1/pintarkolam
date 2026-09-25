@extends('layouts.viscous')

@section('title', $article->title)

@section('content')
<div class="about-title about-title-bg pk-page-hero">
    <div class="d-table"><div class="d-table-cell"><div class="container">
        <div class="about-title-text">
            <h2 class="pk-hero-title-wrap">{{ $article->title }}</h2>
            <ul>
                <li><a href="{{ route('landing') }}">Beranda</a></li>
                <li><a href="{{ route('landing.blog') }}">Artikel</a></li>
                <li><i class="icofont-rounded-double-right"></i> Detail</li>
            </ul>
        </div>
    </div></div></div>
</div>

<section class="pk-page-section">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-8">
                <article class="pk-product-card">
                    @if($article->cover_path)
                        <img src="{{ asset('storage/'.$article->cover_path) }}" class="img-fluid rounded mb-4 w-100" style="max-height:420px;object-fit:cover" alt="{{ $article->title }}">
                    @endif
                    <h1 class="pk-gradient-title h2 mb-2">{{ $article->title }}</h1>
                    <p class="text-muted mb-4">
                        <i class="icofont-calendar"></i>
                        {{ optional($article->published_at)->translatedFormat('d F Y') ?? '-' }}
                    </p>
                    <div class="article-body">{!! $article->body !!}</div>
                    <div class="mt-4 pt-2">
                        <a href="{{ route('landing.blog') }}" class="default-btn">← Kembali ke Blog</a>
                    </div>
                </article>
            </div>
            <div class="col-lg-4">
                <aside class="pk-sidebar">
                    <div class="pk-sidebar-card">
                        <h4 class="pk-gradient-title h5 mb-3">Artikel Lainnya</h4>
                        <ul class="pk-sidebar-list list-unstyled mb-0">
                            @forelse($recent as $item)
                            <li>
                                <a href="{{ route('landing.article', $item) }}">{{ $item->title }}</a>
                                <span>{{ optional($item->published_at)->format('d/m/Y') }}</span>
                            </li>
                            @empty
                            <li class="text-muted">Belum ada artikel lain.</li>
                            @endforelse
                        </ul>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</section>
@endsection
