<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostBulkActionRequest;
use App\Http\Requests\PostIndexRequest;
use App\Http\Requests\PostRequest;
use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use App\Services\MediaLibrary;
use App\Services\ActivityLogService;
use App\Services\PostBulkAction;
use App\Services\NewsArticleSchema;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PostController extends Controller
{
    public function __construct(
        private readonly MediaLibrary $mediaLibrary,
        private readonly PostBulkAction $postBulkAction,
        private readonly NewsArticleSchema $newsArticleSchema,
        private readonly ActivityLogService $activityLog,
    ) {
    }

    public function index(PostIndexRequest $request): View
    {
        Gate::authorize('viewAny', Post::class);

        $filters = $request->validated();

        $posts = Post::query()
            ->with(['category', 'author'])
            ->when($request->user()->isAuthor(), fn ($query) => $query->where('author_id', $request->user()->id))
            ->when(($filters['status'] ?? null) === 'trashed', fn ($query) => $query->onlyTrashed())
            ->when(($filters['status'] ?? null) && $filters['status'] !== 'trashed', fn ($query) => $query->where('status', $filters['status']))
            ->when($filters['search'] ?? null, fn ($query, $search) => $query->where('title', 'like', '%'.$search.'%'))
            ->when($filters['category_id'] ?? null, fn ($query, $categoryId) => $query->where('category_id', $categoryId))
            ->when($filters['author_id'] ?? null, fn ($query, $authorId) => $query->where('author_id', $authorId))
            ->when(($filters['sort'] ?? 'latest') === 'oldest', fn ($query) => $query->orderBy('published_at')->orderBy('created_at'))
            ->when(($filters['sort'] ?? 'latest') === 'latest', fn ($query) => $query->orderByDesc('published_at')->orderByDesc('created_at'))
            ->paginate(10)
            ->withQueryString();

        return view('admin.posts.index', [
            'posts' => $posts,
            'categories' => Category::orderBy('name')->get(),
            'authors' => $this->authors($request->user()),
            'isTrash' => ($filters['status'] ?? null) === 'trashed',
            'statuses' => Post::STATUS_LABELS,
        ]);
    }

    public function trash(PostIndexRequest $request): View
    {
        $request->merge(['status' => 'trashed']);

        return $this->index($request);
    }

    public function create(): View
    {
        Gate::authorize('create', Post::class);

        return view('admin.posts.create', [
            'post' => new Post(['status' => 'draft', 'published_at' => now()]),
            'categories' => Category::where('is_active', true)->orderBy('name')->get(),
            'authors' => $this->authors(request()->user()),
            'canChooseAuthor' => ! request()->user()->isAuthor(),
            'statusOptions' => $this->statusOptions(request()->user()),
            'mediaItems' => $this->mediaLibrary->allImages()->take(24),
        ]);
    }

    public function store(PostRequest $request): RedirectResponse
    {
        Gate::authorize('create', Post::class);

        $data = $this->postData($request);

        $post = Post::create($data);

        $this->recordPostActivity('create', 'membuat', $post);

        return redirect()->route('admin.posts.index')->with('success', 'Berita berhasil dibuat.');
    }

    public function edit(Post $post): View
    {
        Gate::authorize('update', $post);

        return view('admin.posts.edit', [
            'post' => $post,
            'categories' => Category::where('is_active', true)->orderBy('name')->get(),
            'authors' => $this->authors(request()->user()),
            'canChooseAuthor' => ! request()->user()->isAuthor(),
            'statusOptions' => $this->statusOptions(request()->user()),
            'mediaItems' => $this->mediaLibrary->allImages()->take(24),
        ]);
    }

    public function bulk(PostBulkActionRequest $request): RedirectResponse
    {
        Gate::authorize('viewAny', Post::class);

        $data = $request->validated();
        $processed = $this->postBulkAction->execute($request->user(), $data['action'], $data['post_ids']);

        return back()->with('success', "{$processed} berita berhasil diproses.");
    }

    public function update(PostRequest $request, Post $post): RedirectResponse
    {
        Gate::authorize('update', $post);

        $post->update($this->postData($request, $post));

        $this->recordPostActivity('update', 'mengedit', $post);

        return redirect()->route('admin.posts.index')->with('success', 'Berita berhasil diperbarui.');
    }

    public function destroy(Post $post): RedirectResponse
    {
        Gate::authorize('delete', $post);

        if ($post->trashed()) {
            return back()->with('error', 'Berita sudah berada di tempat sampah.');
        }

        $post->delete();

        $this->recordPostActivity('delete', 'menghapus', $post);

        return redirect()->route('admin.posts.index')->with('success', 'Berita berhasil dipindahkan ke tempat sampah.');
    }

    public function restore(Post $post): RedirectResponse
    {
        Gate::authorize('restore', $post);

        if (! $post->trashed()) {
            return back()->with('error', 'Berita ini belum dihapus.');
        }

        $post->restore();

        $this->recordPostActivity('restore', 'merestore', $post);

        return redirect()->route('admin.posts.index', ['status' => 'trashed'])->with('success', 'Berita berhasil direstore.');
    }

    public function forceDelete(Post $post): RedirectResponse
    {
        Gate::authorize('forceDelete', $post);

        if (! $post->trashed()) {
            return back()->with('error', 'Force delete hanya tersedia untuk berita yang sudah dihapus.');
        }

        $this->deleteThumbnail($post);
        $this->deleteOgImage($post);
        $post->forceDelete();

        return redirect()->route('admin.posts.index', ['status' => 'trashed'])->with('success', 'Berita berhasil dihapus permanen.');
    }

    public function submitReview(Post $post): RedirectResponse
    {
        Gate::authorize('submitReview', $post);

        $post->update([
            'status' => Post::STATUS_REVIEW,
            'published_at' => null,
        ]);

        $this->recordPostActivity('submit-review', 'mengirim review', $post);

        return back()->with('success', 'Berita berhasil dikirim untuk review.');
    }

    public function approve(Post $post): RedirectResponse
    {
        Gate::authorize('approve', $post);

        $post->update([
            'status' => Post::STATUS_PUBLISHED,
            'published_at' => $post->published_at ?? now(),
        ]);

        $this->recordPostActivity('approve', 'menyetujui', $post);

        return back()->with('success', 'Berita berhasil disetujui dan dipublish.');
    }

    public function returnToDraft(Post $post): RedirectResponse
    {
        Gate::authorize('returnToDraft', $post);

        $post->update([
            'status' => Post::STATUS_DRAFT,
            'published_at' => null,
        ]);

        $this->recordPostActivity('return-draft', 'mengembalikan ke draft', $post);

        return back()->with('success', 'Berita dikembalikan ke Draft.');
    }

    public function publish(Post $post): RedirectResponse
    {
        Gate::authorize('publish', $post);

        $post->update([
            'status' => Post::STATUS_PUBLISHED,
            'published_at' => $post->published_at ?? now(),
        ]);

        $this->recordPostActivity('publish', 'mempublish', $post);

        return back()->with('success', 'Berita berhasil dipublish.');
    }

    public function unpublish(Post $post): RedirectResponse
    {
        Gate::authorize('unpublish', $post);

        $post->update([
            'status' => Post::STATUS_DRAFT,
            'published_at' => null,
        ]);

        $this->recordPostActivity('unpublish', 'meng-unpublish', $post);

        return back()->with('success', 'Berita berhasil di-unpublish.');
    }

    public function archive(Post $post): RedirectResponse
    {
        Gate::authorize('archive', $post);

        $post->update([
            'status' => Post::STATUS_ARCHIVED,
            'published_at' => null,
        ]);

        $this->recordPostActivity('archive', 'mengarsipkan', $post);

        return back()->with('success', 'Berita berhasil diarsipkan.');
    }

    public function restoreArchived(Post $post): RedirectResponse
    {
        Gate::authorize('restoreArchived', $post);

        $post->update([
            'status' => Post::STATUS_DRAFT,
            'published_at' => null,
        ]);

        $this->recordPostActivity('restore-archive', 'merestore arsip', $post);

        return back()->with('success', 'Berita arsip berhasil dikembalikan ke Draft.');
    }

    public function preview(Post $post): View
    {
        Gate::authorize('view', $post);

        $post->load(['category', 'author']);

        $related = Post::with('category')
            ->where('category_id', $post->category_id)
            ->whereKeyNot($post->id)
            ->where('status', Post::STATUS_PUBLISHED)
            ->latest('published_at')
            ->limit(3)
            ->get();

        $popular = Post::published()->orderByDesc('views')->limit(4)->get();
        $newsArticleSchema = $this->newsArticleSchema->build($post);

        return view('frontend.show', compact('post', 'related', 'popular', 'newsArticleSchema'));
    }

    private function postData(PostRequest $request, ?Post $post = null): array
    {
        $data = $request->validated();
        $data['slug'] = $this->uniqueSlug($data['slug'] ?? null, $data['title'], $post);
        $data['author_id'] = $request->user()->isAuthor()
            ? $request->user()->id
            : ($data['author_id'] ?? $request->user()->id);
        $data['status'] = $this->statusForRequest($request, $post);
        $data['published_at'] = $data['status'] === Post::STATUS_PUBLISHED
            ? ($data['published_at'] ?? now())
            : null;
        $data['content'] = $this->cleanContent($data['content']);

        if ($request->hasFile('thumbnail')) {
            $this->deleteThumbnail($post);
            $data['thumbnail'] = $this->mediaLibrary
                ->store($request->file('thumbnail'), 'thumbnails', $request->user())
                ->path;
        } elseif ($this->mediaLibrary->isMediaLibraryPath($data['thumbnail_media_path'] ?? null)) {
            $this->deleteThumbnail($post);
            $data['thumbnail'] = $data['thumbnail_media_path'];
        } else {
            unset($data['thumbnail']);
        }

        if ($request->hasFile('og_image')) {
            $this->deleteOgImage($post);
            $data['og_image'] = $this->mediaLibrary
                ->store($request->file('og_image'), 'seo', $request->user())
                ->path;
        } elseif ($this->mediaLibrary->isMediaLibraryPath($data['og_image_media_path'] ?? null)) {
            $this->deleteOgImage($post);
            $data['og_image'] = $data['og_image_media_path'];
        } else {
            unset($data['og_image']);
        }

        unset($data['thumbnail_media_path'], $data['og_image_media_path']);

        return $data;
    }

    private function authors(User $user)
    {
        return User::query()
            ->when($user->isAuthor(), fn ($query) => $query->whereKey($user->id))
            ->orderBy('name')
            ->get(['id', 'name']);
    }

    private function statusOptions(User $user): array
    {
        if ($user->isAuthor()) {
            return [
                Post::STATUS_DRAFT => Post::STATUS_LABELS[Post::STATUS_DRAFT],
            ];
        }

        if ($user->isEditor()) {
            return [
                Post::STATUS_DRAFT => Post::STATUS_LABELS[Post::STATUS_DRAFT],
                Post::STATUS_REVIEW => Post::STATUS_LABELS[Post::STATUS_REVIEW],
                Post::STATUS_PUBLISHED => Post::STATUS_LABELS[Post::STATUS_PUBLISHED],
            ];
        }

        return Post::STATUS_LABELS;
    }

    private function statusForRequest(PostRequest $request, ?Post $post = null): string
    {
        if ($request->user()->isAuthor()) {
            return $post?->status === Post::STATUS_REVIEW
                ? Post::STATUS_REVIEW
                : Post::STATUS_DRAFT;
        }

        $status = $request->validated('status') ?: ($post?->status ?? Post::STATUS_DRAFT);

        abort_unless(array_key_exists($status, $this->statusOptions($request->user())), 403);

        return $status;
    }

    private function uniqueSlug(?string $slug, string $title, ?Post $post = null): string
    {
        $baseSlug = Str::slug($slug ?: $title);
        $candidate = $baseSlug;
        $counter = 2;

        while (Post::withTrashed()
            ->where('slug', $candidate)
            ->when($post, fn ($query) => $query->whereKeyNot($post->id))
            ->exists()) {
            $candidate = "{$baseSlug}-{$counter}";
            $counter++;
        }

        return $candidate;
    }

    private function cleanContent(string $content): string
    {
        $allowedHtml = strip_tags($content, '<p><br><strong><b><em><i><u><ul><ol><li><blockquote><h2><h3><h4><a><img><figure><figcaption><table><thead><tbody><tr><th><td><pre><code>');
        $withoutEvents = preg_replace('/\s+on[a-z]+\s*=\s*(".*?"|\'.*?\'|[^\s>]+)/i', '', $allowedHtml) ?? $allowedHtml;

        return preg_replace('/\s+(href|src)\s*=\s*(["\'])\s*javascript:[^"\']*\2/i', ' $1="#"', $withoutEvents) ?? $withoutEvents;
    }

    private function deleteThumbnail(?Post $post): void
    {
        if ($post?->thumbnail && $this->mediaLibrary->isPostOwnedImage($post->thumbnail) && ! $this->mediaLibrary->delete($post->thumbnail)) {
            Log::warning('Post thumbnail could not be deleted.', ['post_id' => $post->id, 'path' => $post->thumbnail]);
        }
    }

    private function deleteOgImage(?Post $post): void
    {
        if ($post?->og_image && $this->mediaLibrary->isPostOwnedImage($post->og_image) && ! $this->mediaLibrary->delete($post->og_image)) {
            Log::warning('Post Open Graph image could not be deleted.', ['post_id' => $post->id, 'path' => $post->og_image]);
        }
    }

    private function recordPostActivity(string $action, string $verb, Post $post): void
    {
        $user = request()->user();

        $this->activityLog->record(
            user: $user,
            action: $action,
            module: 'post',
            description: "{$user->name} {$verb} berita '{$post->title}'.",
        );
    }
}
