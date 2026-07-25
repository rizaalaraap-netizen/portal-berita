<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    public function before(User $user): ?bool
    {
        return $user->isSuperAdmin() ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return $user->canManagePosts();
    }

    public function view(User $user, Post $post): bool
    {
        return $user->hasAnyRole([User::ROLE_ADMIN, User::ROLE_EDITOR])
            || $post->author_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->canManagePosts();
    }

    public function update(User $user, Post $post): bool
    {
        if ($user->hasAnyRole([User::ROLE_ADMIN, User::ROLE_EDITOR])) {
            return true;
        }

        return $user->isAuthor()
            && $post->author_id === $user->id
            && $post->status === Post::STATUS_DRAFT;
    }

    public function delete(User $user, Post $post): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->isAuthor()
            && $post->author_id === $user->id
            && $post->status === Post::STATUS_DRAFT;
    }

    public function restore(User $user, Post $post): bool
    {
        return $user->isAdmin();
    }

    public function forceDelete(User $user, Post $post): bool
    {
        return $user->isAdmin();
    }

    public function submitReview(User $user, Post $post): bool
    {
        return $user->isAuthor()
            && $post->author_id === $user->id
            && $post->status === Post::STATUS_DRAFT;
    }

    public function approve(User $user, Post $post): bool
    {
        return $user->hasAnyRole([User::ROLE_ADMIN, User::ROLE_EDITOR])
            && $post->status === Post::STATUS_REVIEW;
    }

    public function returnToDraft(User $user, Post $post): bool
    {
        return $user->hasAnyRole([User::ROLE_ADMIN, User::ROLE_EDITOR])
            && $post->status === Post::STATUS_REVIEW;
    }

    public function publish(User $user, Post $post): bool
    {
        return $user->canPublishPosts()
            && $post->status !== Post::STATUS_PUBLISHED;
    }

    public function unpublish(User $user, Post $post): bool
    {
        return $user->isAdmin()
            && $post->status === Post::STATUS_PUBLISHED;
    }

    public function archive(User $user, Post $post): bool
    {
        return $user->isAdmin()
            && $post->status !== Post::STATUS_ARCHIVED;
    }

    public function restoreArchived(User $user, Post $post): bool
    {
        return $user->isAdmin()
            && $post->status === Post::STATUS_ARCHIVED;
    }
}
