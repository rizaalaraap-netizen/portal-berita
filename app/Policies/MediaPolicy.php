<?php

namespace App\Policies;

use App\Models\Media;
use App\Models\User;

class MediaPolicy
{
    public function before(User $user): ?bool
    {
        return $user->isSuperAdmin() || $user->isAdmin() ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return $user->canManagePosts();
    }

    public function create(User $user): bool
    {
        return $user->canManagePosts();
    }

    public function update(User $user, Media $media): bool
    {
        return $user->isEditor() || ($user->isAuthor() && $media->user_id === $user->id);
    }

    public function delete(User $user, Media $media): bool
    {
        return $user->isEditor() || ($user->isAuthor() && $media->user_id === $user->id);
    }

    public function restore(User $user, Media $media): bool
    {
        return $user->isEditor() || ($user->isAuthor() && $media->user_id === $user->id);
    }

    public function forceDelete(User $user, Media $media): bool
    {
        return $user->isEditor() || ($user->isAuthor() && $media->user_id === $user->id);
    }
}
