@extends('layouts.frontend')

@section('title', 'Portal Berita - Berita Terkini Indonesia')

@php
    $readingTime = fn ($post) => max(1, ceil(str_word_count(strip_tags($post->content)) / 200));
@endphp

@section('content')
    <section class="breaking-news pro-breaking">
        <div class="container breaking-wrapper">
            <strong class="breaking-title">Breaking News</strong>
            <div class="breaking-marquee" aria-label="Berita terbaru">
                <div class="breaking-track">
                    @forelse($breaking as $post)
                        <a href="{{ route('posts.show', $post) }}">{{ $post->title }}</a>
                    @empty
                        <span>Belum ada berita terbaru.</span>
                    @endforelse
                </div>
            </div>
        </div>
    </section>

    @if($headline)
        <section class="portal-hero">
            <div class="container portal-hero-grid">
                <article class="hero-main">
                    <img src="{{ $headline->thumbnail_url }}" alt="{{ $headline->title }}">
                    <div class="hero-main-overlay">
                        <span class="category">{{ $headline->category->name }}</span>
                        <h1>{{ $headline->title }}</h1>
                        <p>{{ $headline->excerpt ?: str($headline->content)->stripTags()->limit(170) }}</p>
                        <div class="news-meta">
                            <span><i class="fa-regular fa-calendar"></i> {{ $headline->published_at?->translatedFormat('d F Y') }}</span>
                            <span><i class="fa-regular fa-clock"></i> {{ $readingTime($headline) }} menit baca</span>
                        </div>
                        <a href="{{ route('posts.show', $headline) }}" class="read-btn">Baca Selengkapnya</a>
                    </div>
                </article>

                <aside class="hero-side">
                    <h2>Trending News</h2>
                    @foreach($trending as $post)
                        <a class="rank-card" href="{{ route('posts.show', $post) }}">
                            <strong>{{ $loop->iteration }}</strong>
                            <img src="{{ $post->thumbnail_url }}" alt="{{ $post->title }}">
                            <span>{{ $post->title }}</span>
                        </a>
                    @endforeach
                </aside>
            </div>
        </section>
    @endif

    <section class="container portal-layout">
        <div class="portal-main">
            <div class="section-heading">
                <h2>Berita Terbaru</h2>
                <a href="{{ route('search') }}">Lihat Semua</a>
            </div>
            <div class="modern-news-grid">
                @foreach($latest as $post)
                    <article class="modern-card">
                        <a href="{{ route('posts.show', $post) }}">
                            <img src="{{ $post->thumbnail_url }}" alt="{{ $post->title }}">
                        </a>
                        <div class="modern-card-body">
                            <span>{{ $post->category->name }}</span>
                            <h3><a href="{{ route('posts.show', $post) }}">{{ $post->title }}</a></h3>
                            <p>{{ $post->excerpt ?: str($post->content)->stripTags()->limit(110) }}</p>
                            <div class="news-meta">
                                <small>{{ $post->published_at?->translatedFormat('d F Y') }}</small>
                                <small>{{ $readingTime($post) }} menit baca</small>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="section-heading mt-section">
                <h2>Berita Populer</h2>
            </div>
            <div class="popular-stack">
                @foreach($popular as $post)
                    <a class="popular-card pro-popular-card" href="{{ route('posts.show', $post) }}">
                        <img src="{{ $post->thumbnail_url }}" alt="{{ $post->title }}">
                        <div>
                            <span>{{ $post->category->name }}</span>
                            <h3>{{ $post->title }}</h3>
                            <p>{{ number_format($post->views, 0, ',', '.') }} dibaca | {{ $post->published_at?->translatedFormat('d F Y') }}</p>
                        </div>
                    </a>
                @endforeach
            </div>

            @foreach($categorySections as $category)
                <div class="category-news-section">
                    <div class="section-heading">
                        <h2>{{ $category->name }}</h2>
                        <a href="{{ route('category.show', $category) }}">Lihat Kategori</a>
                    </div>
                    <div class="category-news-grid">
                        @foreach($category->publishedPosts as $post)
                            <article class="category-mini-card">
                                <a href="{{ route('posts.show', $post) }}">
                                    <img src="{{ $post->thumbnail_url }}" alt="{{ $post->title }}">
                                    <h3>{{ $post->title }}</h3>
                                    <small>{{ $post->published_at?->translatedFormat('d F Y') }}</small>
                                </a>
                            </article>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <aside class="portal-sidebar">
            <div class="sidebar-widget">
                <h3>Editor's Pick</h3>
                @foreach($editorsPick as $post)
                    <a class="sidebar-pick" href="{{ route('posts.show', $post) }}">
                        <img src="{{ $post->thumbnail_url }}" alt="{{ $post->title }}">
                        <span>{{ $post->title }}</span>
                    </a>
                @endforeach
            </div>

            <div class="sidebar-widget">
                <h3>Most Read</h3>
                <ol class="most-read-list">
                    @foreach($popular as $post)
                        <li><a href="{{ route('posts.show', $post) }}">{{ $post->title }}</a></li>
                    @endforeach
                </ol>
            </div>

            <div class="sidebar-widget">
                <h3>Kategori Populer</h3>
                <div class="tag-cloud">
                    @foreach($categories->take(10) as $category)
                        <a href="{{ route('category.show', $category) }}">{{ $category->name }}</a>
                    @endforeach
                </div>
            </div>

            <div class="sidebar-widget">
                <h3>Tag Populer</h3>
                <div class="tag-cloud">
                    @foreach($categories->take(12) as $category)
                        <a href="{{ route('category.show', $category) }}">#{{ str($category->name)->slug() }}</a>
                    @endforeach
                </div>
            </div>
        </aside>
    </section>

    <section class="newsletter pro-newsletter">
        <div class="container">
            <h2>Berlangganan Newsletter</h2>
            <p>Dapatkan berita utama dan analisis pilihan langsung ke email Anda.</p>
            <form>
                <input type="email" placeholder="Masukkan email" aria-label="Email newsletter" required>
                <button type="submit">Berlangganan</button>
            </form>
        </div>
    </section>
@endsection
