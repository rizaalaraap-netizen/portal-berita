<?php

namespace App\Http\Requests;

use App\Services\MediaLibrary;
use Closure;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\Post;
use App\Models\User;
use Illuminate\Validation\Rule;

class PostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $postId = $this->route('post')?->id;

        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('posts', 'slug')->ignore($postId)],
            'meta_title' => ['nullable', 'string', 'max:70'],
            'meta_description' => ['nullable', 'string', 'max:170'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'category_id' => ['required', 'exists:categories,id'],
            'author_id' => ['nullable', 'integer', 'exists:users,id'],
            'thumbnail' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'thumbnail_media_path' => ['nullable', 'string', 'max:255', $this->mediaPathRule()],
            'og_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'og_image_media_path' => ['nullable', 'string', 'max:255', $this->mediaPathRule()],
            'content' => ['required', 'string', 'min:20'],
            'status' => ['nullable', Rule::in($this->availableStatuses())],
            'published_at' => ['nullable', 'date'],
        ];
    }

    public function availableStatuses(): array
    {
        /** @var User|null $user */
        $user = $this->user();

        if (! $user) {
            return [Post::STATUS_DRAFT];
        }

        if ($user->isAuthor()) {
            return [Post::STATUS_DRAFT];
        }

        if ($user->isEditor()) {
            return [Post::STATUS_DRAFT, Post::STATUS_REVIEW, Post::STATUS_PUBLISHED];
        }

        return Post::STATUSES;
    }

    private function mediaPathRule(): Closure
    {
        return function (string $attribute, mixed $value, Closure $fail): void {
            if (filled($value) && ! app(MediaLibrary::class)->isMediaLibraryPath($value)) {
                $fail('Gambar yang dipilih dari media manager tidak valid.');
            }
        };
    }
}
