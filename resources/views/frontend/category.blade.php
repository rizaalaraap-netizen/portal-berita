@extends('layouts.frontend')

@section('title', $category->name.' | Portal Berita')

@section('content')
    <section class="container page-hero">
        <h1 class="page-title">{{ $category->name }}</h1>
        <p class="page-description">{{ $category->description }}</p>
    </section>

    <section class="container">
        <div class="category-menu">
            @foreach($categories as $item)
                <a href="{{ route('category.show', $item) }}">{{ $item->name }}</a>
            @endforeach
        </div>
    </section>

    <section class="container">
        <h2 class="section-title">Artikel {{ $category->name }}</h2>
        <div class="news-grid">
            @forelse($posts as $post)
                @include('frontend.partials.card', ['post' => $post])
            @empty
                <p>Belum ada berita pada kategori ini.</p>
            @endforelse
        </div>
        <div style="margin-top:24px">{{ $posts->links() }}</div>
    </section>
@endsection
