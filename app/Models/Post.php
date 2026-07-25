<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    public const STATUS_DRAFT = 'draft';
    public const STATUS_REVIEW = 'review';
    public const STATUS_PUBLISHED = 'published';
    public const STATUS_ARCHIVED = 'archived';

    public const STATUSES = [
        self::STATUS_DRAFT,
        self::STATUS_REVIEW,
        self::STATUS_PUBLISHED,
        self::STATUS_ARCHIVED,
    ];

    public const STATUS_LABELS = [
        self::STATUS_DRAFT => 'Draft',
        self::STATUS_REVIEW => 'Review',
        self::STATUS_PUBLISHED => 'Published',
        self::STATUS_ARCHIVED => 'Archived',
    ];

    public const STATUS_BADGES = [
        self::STATUS_DRAFT => 'secondary',
        self::STATUS_REVIEW => 'warning',
        self::STATUS_PUBLISHED => 'success',
        self::STATUS_ARCHIVED => 'danger',
    ];

    protected $fillable = [
        'title',
        'slug',
        'category_id',
        'author_id',
        'meta_title',
        'meta_description',
        'excerpt',
        'thumbnail',
        'og_image',
        'content',
        'status',
        'published_at',
        'views',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'views' => 'integer',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function viewEvents(): HasMany
    {
        return $this->hasMany(PostView::class);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->where('status', self::STATUS_PUBLISHED)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_LABELS[$this->status] ?? ucfirst((string) $this->status);
    }

    public function getStatusBadgeAttribute(): string
    {
        return self::STATUS_BADGES[$this->status] ?? 'secondary';
    }

    public function getThumbnailUrlAttribute(): string
    {
        return $this->imageUrl($this->thumbnail);
    }

    public function getOgImageUrlAttribute(): string
    {
        return $this->imageUrl($this->og_image ?: $this->thumbnail);
    }

    public function getSeoTitleAttribute(): string
    {
        return $this->meta_title ?: $this->title;
    }

    public function getSeoDescriptionAttribute(): string
    {
        return $this->meta_description ?: ($this->excerpt ?: str($this->content)->stripTags()->squish()->limit(155));
    }

    private function imageUrl(?string $path): string
    {
        if (! $path) {
            return asset('images/headline.svg');
        }

        if (str_starts_with($path, 'images/')) {
            return asset($path);
        }

        return Storage::url($path);
    }
}
