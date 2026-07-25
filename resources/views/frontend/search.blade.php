@extends('layouts.frontend')

@section('title', 'Search | Portal Berita')

@section('content')
    <section class="container page-hero">
        <h1 class="page-title">Hasil Pencarian</h1>
        <p class="page-description">Menampilkan hasil untuk: {{ $query ?: 'semua berita' }}</p>
    </section>

    <section class="container">
        <div class="news-grid">
            @forelse($posts as $post)
                @include('frontend.partials.card', ['post' => $post])
            @empty
                <p>Tidak ada berita yang cocok.</p>
            @endforelse
        </div>
        <div style="margin-top:24px">{{ $posts->links() }}</div>
    </section>
@endsection
