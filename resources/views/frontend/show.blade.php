@extends('layouts.frontend')

@section('title', $post->title.' | Portal Berita')

@section('meta')
    <meta name="description" content="{{ $post->seo_description }}">
    <link rel="canonical" href="{{ route('posts.show', $post) }}">

    <meta property="og:type" content="article">
    <meta property="og:site_name" content="PortalBerita">
    <meta property="og:title" content="{{ $post->seo_title }}">
    <meta property="og:description" content="{{ $post->seo_description }}">
    <meta property="og:image" content="{{ $post->og_image_url }}">
    <meta property="og:url" content="{{ route('posts.show', $post) }}">
    <meta property="article:published_time" content="{{ $post->published_at?->toIso8601String() }}">
    <meta property="article:modified_time" content="{{ $post->updated_at->toIso8601String() }}">
    <meta property="article:author" content="{{ $post->author->name }}">
    <meta property="article:section" content="{{ $post->category->name }}">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $post->seo_title }}">
    <meta name="twitter:description" content="{{ $post->seo_description }}">
    <meta name="twitter:image" content="{{ $post->og_image_url }}">

    <script type="application/ld+json">
        {!! json_encode($newsArticleSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
@endsection

@section('content')
    <section class="container article-page">
        <article class="article-content">
            <span class="category">{{ $post->category->name }}</span>
            <h1>{{ $post->title }}</h1>
            <div class="article-info">
                <span><i class="fa-regular fa-pen-to-square"></i> {{ $post->author->name }}</span>
                <span><i class="fa-regular fa-calendar"></i> {{ $post->published_at?->translatedFormat('d F Y') }}</span>
                <span><i class="fa-regular fa-eye"></i> {{ number_format($post->views, 0, ',', '.') }} dibaca</span>
            </div>

            <img src="{{ $post->thumbnail_url }}" alt="{{ $post->title }}" class="article-image">
            {!! $post->content !!}

            <div class="share">
                <h3>Bagikan Artikel</h3>
                <a href="#">Facebook</a>
                <a href="#">Instagram</a>
                <a href="#">WhatsApp</a>
                <a href="#">Twitter</a>
            </div>
        </article>

        <aside class="sidebar">
            <h3>Berita Populer</h3>
            @foreach($popular as $item)
                <a class="sidebar-news" href="{{ route('posts.show', $item) }}">
                    <img src="{{ $item->thumbnail_url }}" alt="{{ $item->title }}">
                    <span>{{ $item->title }}</span>
                </a>
            @endforeach
        </aside>
    </section>

    <section class="container">
        <h2 class="section-title">Artikel Terkait</h2>
        <div class="news-grid">
            @foreach($related as $item)
                @include('frontend.partials.card', ['post' => $item])
            @endforeach
        </div>
    </section>
@endsection
