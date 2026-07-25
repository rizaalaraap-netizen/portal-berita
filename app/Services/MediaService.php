<?php

namespace App\Services;

use App\Models\Media;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaService
{
    private const DIRECTORIES = [
        'media-library',
        'thumbnails',
        'seo',
        'editor-images',
    ];

    public function paginate(array $filters, User $user, int $perPage = 18): LengthAwarePaginator
    {
        return Media::query()
            ->with('user:id,name,role')
            ->when($user->isAuthor(), fn ($query) => $query->where('user_id', $user->id))
            ->when(($filters['status'] ?? 'active') === 'trash', fn ($query) => $query->onlyTrashed())
            ->when($filters['search'] ?? null, function ($query, string $search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('filename', 'like', "%{$search}%")
                        ->orWhere('original_name', 'like', "%{$search}%")
                        ->orWhere('alt', 'like', "%{$search}%")
                        ->orWhere('caption', 'like', "%{$search}%");
                });
            })
            ->when($filters['extension'] ?? null, fn ($query, string $extension) => $query->where('extension', $extension))
            ->when(($filters['sort'] ?? 'latest') === 'oldest', fn ($query) => $query->oldest())
            ->when(($filters['sort'] ?? 'latest') === 'name', fn ($query) => $query->orderBy('original_name'))
            ->when(($filters['sort'] ?? 'latest') === 'size', fn ($query) => $query->orderByDesc('size'))
            ->when(($filters['sort'] ?? 'latest') === 'latest', fn ($query) => $query->latest())
            ->paginate($perPage)
            ->withQueryString();
    }

    public function store(UploadedFile $file, string $directory, ?User $user = null): Media
    {
        $directory = $this->allowedDirectory($directory);
        $extension = strtolower($file->extension() ?: $file->getClientOriginalExtension());
        $filename = Str::uuid().'.'.$extension;
        $disk = config('media.disk', 'public');
        $path = $file->storeAs($directory, $filename, $disk);
        [$width, $height] = $this->dimensions($file->getRealPath(), $extension);

        $media = Media::create([
            'user_id' => $user?->id,
            'filename' => $filename,
            'original_name' => $this->sanitizeOriginalName($file->getClientOriginalName()),
            'mime_type' => $file->getMimeType() ?: 'application/octet-stream',
            'extension' => $extension,
            'disk' => $disk,
            'path' => $path,
            'size' => Storage::disk($disk)->size($path),
            'width' => $width,
            'height' => $height,
            'alt' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
            'thumbnail_path' => $this->thumbnailPath($path, $extension),
        ]);

        $this->generateThumbnail($media);

        return $media;
    }

    public function findByPath(?string $path): ?Media
    {
        if (! filled($path)) {
            return null;
        }

        return Media::where('path', $path)->first();
    }

    public function url(Media|string $media): string
    {
        if (is_string($media)) {
            return Storage::disk(config('media.disk', 'public'))->url($media);
        }

        return $media->url;
    }

    public function softDelete(Media $media): void
    {
        $media->delete();
    }

    public function restore(Media $media): void
    {
        $media->restore();
    }

    public function forceDelete(Media $media): bool
    {
        $disk = $media->disk;
        $paths = array_filter([$media->path, $media->thumbnail_path]);
        $deleted = Storage::disk($disk)->delete($paths);

        $media->forceDelete();

        return $deleted;
    }

    public function delete(string $path): bool
    {
        $media = Media::withTrashed()->where('path', $path)->first();

        if (! $media) {
            return Storage::disk(config('media.disk', 'public'))->delete($path);
        }

        return $this->forceDelete($media);
    }

    public function decodePath(string $encoded): string
    {
        $base64 = strtr($encoded, '-_', '+/');
        $base64 .= str_repeat('=', (4 - strlen($base64) % 4) % 4);

        return base64_decode($base64, true) ?: '';
    }

    public function isAllowedPath(?string $path): bool
    {
        return filled($path)
            && collect(self::DIRECTORIES)->contains(fn (string $directory) => str_starts_with($path, "{$directory}/"))
            && Media::withTrashed()->where('path', $path)->exists();
    }

    public function isMediaLibraryPath(?string $path): bool
    {
        return filled($path)
            && str_starts_with($path, 'media-library/')
            && Media::where('path', $path)->exists();
    }

    public function isPostOwnedImage(string $path): bool
    {
        return str_starts_with($path, 'thumbnails/') || str_starts_with($path, 'seo/');
    }

    public function allImages(?string $search = null): Collection
    {
        return Media::query()
            ->when($search, function ($query, string $search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('filename', 'like', "%{$search}%")
                        ->orWhere('original_name', 'like', "%{$search}%")
                        ->orWhere('path', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->get()
            ->map(fn (Media $media) => [
                'id' => $media->id,
                'path' => $media->path,
                'name' => $media->original_name,
                'directory' => dirname($media->path),
                'url' => $media->url,
                'thumbnail_url' => $media->thumbnail_url,
                'encoded' => $this->encodePath($media->path),
                'size' => $media->size,
                'last_modified' => $media->updated_at?->timestamp,
            ]);
    }

    private function allowedDirectory(string $directory): string
    {
        abort_unless(in_array($directory, self::DIRECTORIES, true), 422);

        return $directory;
    }

    private function encodePath(string $path): string
    {
        return rtrim(strtr(base64_encode($path), '+/', '-_'), '=');
    }

    private function sanitizeOriginalName(string $name): string
    {
        $extension = pathinfo($name, PATHINFO_EXTENSION);
        $base = Str::slug(pathinfo($name, PATHINFO_FILENAME)) ?: 'image';

        return $extension ? "{$base}.{$extension}" : $base;
    }

    private function dimensions(string $path, string $extension): array
    {
        if ($extension === 'svg') {
            return [null, null];
        }

        $size = @getimagesize($path);

        return $size ? [$size[0], $size[1]] : [null, null];
    }

    private function thumbnailPath(string $path, string $extension): ?string
    {
        if ($extension === 'svg') {
            return null;
        }

        return 'media-thumbnails/'.pathinfo($path, PATHINFO_FILENAME).'.'.$extension;
    }

    private function generateThumbnail(Media $media): void
    {
        if (! $media->thumbnail_path || ! Storage::disk($media->disk)->exists($media->path)) {
            return;
        }

        Storage::disk($media->disk)->copy($media->path, $media->thumbnail_path);
    }
}
