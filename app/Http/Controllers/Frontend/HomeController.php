<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\FrontendSearchRequest;
use App\Models\Category;
use App\Models\Post;
use App\Services\NewsArticleSchema;
use App\Services\PostViewCounterService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct(
        private readonly NewsArticleSchema $newsArticleSchema,
        private readonly PostViewCounterService $postViewCounter,
    ) {
    }

    public function index(): View
    {
        $headline = Post::with(['category', 'author'])->published()->latest('published_at')->first();
        $headlineId = $headline?->id;
        $breaking = Post::with('category')->published()->latest('published_at')->limit(8)->get();
        $sideNews = Post::with('category')->published()->when($headlineId, fn ($query) => $query->whereKeyNot($headlineId))->latest('published_at')->limit(4)->get();
        $trending = Post::with('category')->published()->orderByDesc('views')->latest('published_at')->limit(5)->get();
        $latest = Post::with('category')->published()->latest('published_at')->limit(8)->get();
        $popular = Post::with('category')->published()->orderByDesc('views')->latest('published_at')->limit(6)->get();
        $editorsPick = Post::with('category')->published()->whereNotNull('excerpt')->latest('updated_at')->limit(5)->get();
        $categories = Category::where('is_active', true)->withCount('publishedPosts')->orderByDesc('published_posts_count')->orderBy('name')->get();
        $categorySections = $this->categorySections();

        return view('frontend.home', compact(
            'headline',
            'breaking',
            'sideNews',
            'trending',
            'latest',
            'popular',
            'editorsPick',
            'categories',
            'categorySections'
        ));
    }

    public function category(Category $category): View
    {
        $posts = $category->publishedPosts()->with('category')->latest('published_at')->paginate(9);
        $categories = Category::where('is_active', true)->orderBy('name')->get();

        return view('frontend.category', compact('category', 'posts', 'categories'));
    }

    public function show(Request $request, Post $post): View
    {
        $post->loadMissing(['category', 'author']);

        abort_unless($post->status === Post::STATUS_PUBLISHED && $post->published_at?->isPast(), 404);

        $this->postViewCounter->record($post, $request);
        $post->refresh();

        $related = Post::with('category')
            ->published()
            ->where('category_id', $post->category_id)
            ->whereKeyNot($post->id)
            ->latest('published_at')
            ->limit(3)
            ->get();

        $popular = Post::published()->orderByDesc('views')->limit(4)->get();
        $newsArticleSchema = $this->newsArticleSchema->build($post);

        return view('frontend.show', compact('post', 'related', 'popular', 'newsArticleSchema'));
    }

    public function search(FrontendSearchRequest $request): View
    {
        $query = $request->validated('q');
        $posts = Post::with('category')
            ->published()
            ->when($query, fn ($builder) => $builder->where('title', 'like', "%{$query}%"))
            ->latest('published_at')
            ->paginate(9)
            ->withQueryString();

        return view('frontend.search', compact('posts', 'query'));
    }

    private function categorySections(): array
    {
        $names = ['Politik', 'Nasional', 'Teknologi', 'Ekonomi', 'Olahraga', 'Lifestyle', 'Hiburan', 'Internasional'];

        return Category::query()
            ->whereIn('name', $names)
            ->where('is_active', true)
            ->with(['publishedPosts' => fn ($query) => $query->with('category')->latest('published_at')->limit(4)])
            ->get()
            ->sortBy(fn (Category $category) => array_search($category->name, $names, true))
            ->filter(fn (Category $category) => $category->publishedPosts->isNotEmpty())
            ->all();
    }
}
