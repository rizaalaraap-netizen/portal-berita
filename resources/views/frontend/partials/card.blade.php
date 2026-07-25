<article class="card">
    <img src="{{ $post->thumbnail_url }}" alt="{{ $post->title }}">
    <div class="card-body">
        <span>{{ $post->category->name }}</span>
        <h3>{{ $post->title }}</h3>
        <p>{{ str($post->content)->stripTags()->limit(90) }}</p>
        <a href="{{ route('posts.show', $post) }}">Baca Selengkapnya -></a>
    </div>
</article>
