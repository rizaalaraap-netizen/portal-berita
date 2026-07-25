<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\ActivityLog;
use App\Models\Media;
use App\Models\Post;
use App\Models\User;
use App\Policies\ActivityLogPolicy;
use App\Policies\CategoryPolicy;
use App\Policies\MediaPolicy;
use App\Policies\PostPolicy;
use App\Policies\UserPolicy;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Post::class, PostPolicy::class);
        Gate::policy(Category::class, CategoryPolicy::class);
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(ActivityLog::class, ActivityLogPolicy::class);
        Gate::policy(Media::class, MediaPolicy::class);

        Paginator::useBootstrapFive();

        View::composer('layouts.frontend', function ($view) {
            $view->with('navCategories', Category::where('is_active', true)->orderBy('name')->get());
        });
    }
}
