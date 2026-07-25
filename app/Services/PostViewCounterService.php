<?php

namespace App\Services;

use App\Models\Post;
use App\Models\PostView;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostViewCounterService
{
    public function record(Post $post, Request $request): bool
    {
        if ($post->status !== Post::STATUS_PUBLISHED || ! $post->published_at?->isPast()) {
            return false;
        }

        $data = [
            'post_id' => $post->id,
            'visitor_hash' => $this->visitorHash($request),
            'viewed_on' => now()->toDateString(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ];

        try {
            return DB::transaction(function () use ($post, $data): bool {
                $view = PostView::firstOrCreate([
                    'post_id' => $data['post_id'],
                    'visitor_hash' => $data['visitor_hash'],
                    'viewed_on' => $data['viewed_on'],
                ], [
                    'ip_address' => $data['ip_address'],
                    'user_agent' => $data['user_agent'],
                ]);

                if (! $view->wasRecentlyCreated) {
                    return false;
                }

                $post->increment('views');

                return true;
            });
        } catch (QueryException) {
            return false;
        }
    }

    private function visitorHash(Request $request): string
    {
        return hash('sha256', implode('|', [
            $request->ip() ?: 'unknown-ip',
            $request->userAgent() ?: 'unknown-agent',
        ]));
    }
}
