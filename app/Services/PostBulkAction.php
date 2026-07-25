<?php

namespace App\Services;

use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class PostBulkAction
{
    public function __construct(private readonly ActivityLogService $activityLog)
    {
    }

    public function execute(User $user, string $action, array $postIds): int
    {
        $posts = Post::query()
            ->whereKey($postIds)
            ->when($user->isAuthor(), fn ($query) => $query->where('author_id', $user->id))
            ->get();

        $processed = 0;

        foreach ($posts as $post) {
            if ($action === 'delete') {
                if (Gate::forUser($user)->denies('delete', $post)) {
                    continue;
                }

                $post->delete();
                $this->record($user, 'delete', 'menghapus', $post);
                $processed++;

                continue;
            }

            $ability = match ($action) {
                'publish' => 'publish',
                'archive' => 'archive',
                default => 'update',
            };

            if (Gate::forUser($user)->denies($ability, $post)) {
                continue;
            }

            $status = match ($action) {
                'publish' => Post::STATUS_PUBLISHED,
                'archive' => Post::STATUS_ARCHIVED,
                default => Post::STATUS_DRAFT,
            };

            $post->update([
                'status' => $status,
                'published_at' => $status === Post::STATUS_PUBLISHED ? ($post->published_at ?? now()) : null,
            ]);

            $verb = match ($action) {
                'publish' => 'mempublish',
                'archive' => 'mengarsipkan',
                default => 'mengubah ke draft',
            };

            $this->record($user, $action, $verb, $post);
            $processed++;
        }

        return $processed;
    }

    private function record(User $user, string $action, string $verb, Post $post): void
    {
        $this->activityLog->record(
            user: $user,
            action: $action,
            module: 'post',
            description: "{$user->name} {$verb} berita '{$post->title}'.",
        );
    }
}
