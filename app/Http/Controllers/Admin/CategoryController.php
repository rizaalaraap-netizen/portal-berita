<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryIndexRequest;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use App\Services\ActivityLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function __construct(private readonly ActivityLogService $activityLog)
    {
    }

    public function index(CategoryIndexRequest $request): View
    {
        Gate::authorize('viewAny', Category::class);

        $filters = $request->validated();

        $categories = Category::query()
            ->withCount('posts')
            ->when($filters['search'] ?? null, fn ($query, $search) => $query->where('name', 'like', '%'.$search.'%'))
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('admin.categories.index', compact('categories'));
    }

    public function create(): View
    {
        Gate::authorize('create', Category::class);

        return view('admin.categories.create', ['category' => new Category(['is_active' => true])]);
    }

    public function store(CategoryRequest $request): RedirectResponse
    {
        Gate::authorize('create', Category::class);

        $category = Category::create($this->data($request));

        $this->recordCategoryActivity('create', 'membuat', $category);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil dibuat.');
    }

    public function edit(Category $category): View
    {
        Gate::authorize('update', $category);

        return view('admin.categories.edit', compact('category'));
    }

    public function update(CategoryRequest $request, Category $category): RedirectResponse
    {
        Gate::authorize('update', $category);

        $category->update($this->data($request, $category));

        $this->recordCategoryActivity('update', 'mengedit', $category);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        Gate::authorize('delete', $category);

        if ($category->posts()->exists()) {
            return back()->with('error', 'Kategori tidak dapat dihapus karena masih memiliki berita.');
        }

        $category->delete();

        $this->recordCategoryActivity('delete', 'menghapus', $category);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil dihapus.');
    }

    private function data(CategoryRequest $request, ?Category $category = null): array
    {
        $data = $request->validated();
        $data['slug'] = $this->uniqueSlug($data['slug'] ?? null, $data['name'], $category);
        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }

    private function uniqueSlug(?string $slug, string $name, ?Category $category = null): string
    {
        $baseSlug = Str::slug($slug ?: $name);
        $candidate = $baseSlug;
        $counter = 2;

        while (Category::where('slug', $candidate)
            ->when($category, fn ($query) => $query->whereKeyNot($category->id))
            ->exists()) {
            $candidate = "{$baseSlug}-{$counter}";
            $counter++;
        }

        return $candidate;
    }

    private function recordCategoryActivity(string $action, string $verb, Category $category): void
    {
        $user = request()->user();

        $this->activityLog->record(
            user: $user,
            action: $action,
            module: 'category',
            description: "{$user->name} {$verb} kategori {$category->name}.",
        );
    }
}
