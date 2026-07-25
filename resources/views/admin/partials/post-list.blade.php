@forelse($posts as $post)
    <div class="d-flex gap-3 py-3 border-bottom">
        <img src="{{ $post->thumbnail_url }}" alt="{{ $post->title }}" style="width: 78px; height: 54px; object-fit: cover; border-radius: .5rem;">
        <div class="min-width-0">
            <a class="fw-semibold text-dark text-decoration-none" href="{{ route('admin.posts.edit', $post) }}">
                {{ $post->title }}
            </a>
            <div class="small text-muted">
                {{ $post->category->name }}
                <span class="mx-1">|</span>
                {{ $post->created_at->format('d/m/Y') }}
                @if($showViews ?? false)
                    <span class="mx-1">|</span>
                    {{ number_format($post->views, 0, ',', '.') }} view
                @endif
                @if($showPeriodViews ?? false)
                    <span class="mx-1">|</span>
                    {{ number_format($post->period_views ?? 0, 0, ',', '.') }} view periode
                @endif
            </div>
        </div>
    </div>
@empty
    <p class="text-muted mb-0">{{ $emptyText }}</p>
@endforelse
