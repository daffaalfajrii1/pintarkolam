@extends('layouts.viscous')

@section('title', 'Artikel & Berita')

@section('content')
<div class="about-title about-title-bg pk-page-hero">
    <div class="d-table"><div class="d-table-cell"><div class="container">
        <div class="about-title-text">
            <h2>Artikel & Berita</h2>
            <ul>
                <li><a href="{{ route('landing') }}">Beranda</a></li>
                <li><i class="icofont-rounded-double-right"></i> Artikel</li>
            </ul>
        </div>
    </div></div></div>
</div>

<section class="blog-section pk-page-section">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-8">
                <form method="GET" action="{{ route('landing.blog') }}" class="pk-filter-bar mb-4">
                    <div class="row g-2 align-items-end">
                        <div class="col-md-7">
                            <label class="form-label small mb-1">Cari artikel</label>
                            <input type="text" name="q" value="{{ $filters['q'] }}" class="form-control" placeholder="Judul, tip budidaya, kualitas air...">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small mb-1">Urutkan</label>
                            <select name="sort" class="form-select">
                                <option value="newest" @selected($filters['sort'] === 'newest')>Terbaru</option>
                                <option value="oldest" @selected($filters['sort'] === 'oldest')>Terlama</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button class="default-btn page-btn w-100" type="submit">Cari</button>
                        </div>
                    </div>
                    @if($filters['month'])
                        <input type="hidden" name="month" value="{{ $filters['month'] }}">
                    @endif
                </form>

                @if($filters['q'] || $filters['month'])
                    <div class="mb-3 small text-muted">
                        Hasil untuk
                        @if($filters['q']) “{{ $filters['q'] }}” @endif
                        @if($filters['month']) · bulan {{ \Carbon\Carbon::createFromFormat('Y-m', $filters['month'])->translatedFormat('F Y') }} @endif
                        — {{ $articles->total() }} artikel
                        <a href="{{ route('landing.blog') }}" class="ms-2">Reset</a>
                    </div>
                @endif

                <div class="row">
                    @forelse($articles as $article)
                    <div class="col-md-6 mb-4">
                        <article class="pk-news-card h-100">
                            <a href="{{ route('landing.article', $article) }}" class="pk-news-thumb">
                                @if($article->cover_path)
                                    <img src="{{ asset('storage/'.$article->cover_path) }}" alt="{{ $article->title }}">
                                @else
                                    <div class="pk-catalog-placeholder">Artikel</div>
                                @endif
                            </a>
                            <div class="pk-news-body">
                                <div class="pk-news-meta">
                                    <i class="icofont-calendar"></i>
                                    {{ optional($article->published_at)->translatedFormat('d M Y') ?? '-' }}
                                </div>
                                <h3 class="pk-gradient-title">
                                    <a href="{{ route('landing.article', $article) }}">{{ $article->title }}</a>
                                </h3>
                                <p>{{ \Illuminate\Support\Str::limit($article->excerpt ?: strip_tags($article->body), 110) }}</p>
                                <a href="{{ route('landing.article', $article) }}" class="blog-btn">Baca <i class="icofont-rounded-right"></i></a>
                            </div>
                        </article>
                    </div>
                    @empty
                    <div class="col-12">
                        <div class="pk-product-card text-center">Belum ada artikel yang cocok.</div>
                    </div>
                    @endforelse
                </div>

                <div class="mt-2">{{ $articles->links() }}</div>
            </div>

            <div class="col-lg-4">
                <aside class="pk-sidebar">
                    <div class="pk-sidebar-card mb-3">
                        <h4 class="pk-gradient-title h5 mb-3">Artikel Terbaru</h4>
                        <ul class="pk-sidebar-list list-unstyled mb-0">
                            @forelse($recent as $item)
                            <li>
                                <a href="{{ route('landing.article', $item) }}">{{ $item->title }}</a>
                                <span>{{ optional($item->published_at)->format('d/m/Y') }}</span>
                            </li>
                            @empty
                            <li class="text-muted">Belum ada artikel.</li>
                            @endforelse
                        </ul>
                    </div>

                    <div class="pk-sidebar-card">
                        <h4 class="pk-gradient-title h5 mb-3">Filter Bulan</h4>
                        <ul class="pk-sidebar-list list-unstyled mb-0">
                            <li>
                                <a href="{{ route('landing.blog', array_filter(['q' => $filters['q'] ?: null, 'sort' => $filters['sort']])) }}"
                                   class="{{ ! $filters['month'] ? 'is-active' : '' }}">Semua</a>
                            </li>
                            @foreach($months as $row)
                                @php $label = \Carbon\Carbon::createFromFormat('Y-m', $row->ym)->translatedFormat('F Y'); @endphp
                                <li>
                                    <a href="{{ route('landing.blog', array_filter(['q' => $filters['q'] ?: null, 'sort' => $filters['sort'], 'month' => $row->ym])) }}"
                                       class="{{ $filters['month'] === $row->ym ? 'is-active' : '' }}">
                                        {{ $label }} <em>({{ $row->total }})</em>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</section>
@endsection
