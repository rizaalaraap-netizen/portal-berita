<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\Category;
use App\Models\Post;
use App\Models\PostView;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class AnalyticsDashboardService
{
    public function data(array $filters = []): array
    {
        [$startDate, $endDate, $period] = $this->period($filters);
        $cacheKey = 'analytics-dashboard:'.md5(json_encode([$period, $startDate->toDateString(), $endDate->toDateString()]));

        return Cache::remember($cacheKey, now()->addMinutes(5), function () use ($startDate, $endDate, $period): array {
            return [
                'period' => $period,
                'startDate' => $startDate,
                'endDate' => $endDate,
                'stats' => $this->stats(),
                'published7Days' => $this->publishedChart(now()->subDays(6)->startOfDay(), now()->endOfDay()),
                'published30Days' => $this->publishedChart(now()->subDays(29)->startOfDay(), now()->endOfDay()),
                'mostRead' => $this->mostRead(),
                'trendingToday' => $this->trending(now()->startOfDay(), now()->endOfDay()),
                'trendingWeek' => $this->trending(now()->subDays(6)->startOfDay(), now()->endOfDay()),
                'trendingMonth' => $this->trending(now()->subDays(29)->startOfDay(), now()->endOfDay()),
                'topCategoriesByPosts' => $this->topCategoriesByPosts(),
                'topCategoriesByViews' => $this->topCategoriesByViews(),
                'topAuthors' => $this->topAuthors($startDate, $endDate),
                'recentActivities' => $this->recentActivities($startDate, $endDate),
            ];
        });
    }

    private function stats(): array
    {
        return [
            'totalPosts' => Post::count(),
            'totalPublished' => Post::where('status', Post::STATUS_PUBLISHED)->count(),
            'totalDrafts' => Post::where('status', Post::STATUS_DRAFT)->count(),
            'totalReviews' => Post::where('status', Post::STATUS_REVIEW)->count(),
            'totalArchived' => Post::where('status', Post::STATUS_ARCHIVED)->count(),
            'totalUsers' => User::count(),
            'totalCategories' => Category::count(),
            'totalViews' => Post::sum('views'),
        ];
    }

    private function publishedChart(Carbon $startDate, Carbon $endDate): array
    {
        $posts = Post::query()
            ->where('status', Post::STATUS_PUBLISHED)
            ->whereBetween('published_at', [$startDate, $endDate])
            ->selectRaw('DATE(published_at) as published_date, COUNT(*) as total')
            ->groupBy('published_date')
            ->pluck('total', 'published_date');

        $labels = [];
        $values = [];

        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $key = $date->toDateString();
            $labels[] = $date->translatedFormat('d M');
            $values[] = (int) ($posts[$key] ?? 0);
        }

        return compact('labels', 'values');
    }

    private function mostRead(): Collection
    {
        return Post::with(['category', 'author'])
            ->where('status', Post::STATUS_PUBLISHED)
            ->orderByDesc('views')
            ->latest('published_at')
            ->limit(10)
            ->get();
    }

    private function trending(Carbon $startDate, Carbon $endDate): Collection
    {
        $postIds = PostView::query()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select('post_id', DB::raw('COUNT(*) as view_count'))
            ->groupBy('post_id')
            ->orderByDesc('view_count')
            ->limit(10)
            ->pluck('view_count', 'post_id');

        if ($postIds->isEmpty()) {
            return collect();
        }

        return Post::with(['category', 'author'])
            ->whereKey($postIds->keys())
            ->where('status', Post::STATUS_PUBLISHED)
            ->get()
            ->map(function (Post $post) use ($postIds): Post {
                $post->period_views = (int) $postIds[$post->id];

                return $post;
            })
            ->sortByDesc('period_views')
            ->values();
    }

    private function topCategoriesByPosts(): Collection
    {
        return Category::withCount('posts')
            ->orderByDesc('posts_count')
            ->orderBy('name')
            ->limit(8)
            ->get();
    }

    private function topCategoriesByViews(): Collection
    {
        return Category::query()
            ->leftJoin('posts', 'posts.category_id', '=', 'categories.id')
            ->select('categories.id', 'categories.name', DB::raw('COALESCE(SUM(posts.views), 0) as total_views'))
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total_views')
            ->orderBy('categories.name')
            ->limit(8)
            ->get();
    }

    private function topAuthors(Carbon $startDate, Carbon $endDate): Collection
    {
        return User::query()
            ->withCount(['posts as published_posts_count' => function ($query) use ($startDate, $endDate): void {
                $query->where('status', Post::STATUS_PUBLISHED)
                    ->whereBetween('published_at', [$startDate, $endDate]);
            }])
            ->orderByDesc('published_posts_count')
            ->orderBy('name')
            ->limit(8)
            ->get()
            ->filter(fn (User $user) => $user->published_posts_count > 0)
            ->values();
    }

    private function recentActivities(Carbon $startDate, Carbon $endDate): Collection
    {
        return ActivityLog::with('user:id,name,role')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->limit(10)
            ->get();
    }

    private function period(array $filters): array
    {
        $period = $filters['period'] ?? 'month';

        return match ($period) {
            'day' => [now()->startOfDay(), now()->endOfDay(), 'day'],
            'week' => [now()->subDays(6)->startOfDay(), now()->endOfDay(), 'week'],
            'custom' => [
                Carbon::parse($filters['start_date'])->startOfDay(),
                Carbon::parse($filters['end_date'])->endOfDay(),
                'custom',
            ],
            default => [now()->subDays(29)->startOfDay(), now()->endOfDay(), 'month'],
        };
    }
}
