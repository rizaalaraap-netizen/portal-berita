<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $posts = Post::published()
            ->orderByDesc('updated_at')
            ->get(['slug', 'updated_at']);

        $categories = Category::where('is_active', true)
            ->orderBy('name')
            ->get(['slug', 'updated_at']);

        return response()
            ->view('frontend.sitemap', compact('posts', 'categories'))
            ->header('Content-Type', 'application/xml');
    }
}
