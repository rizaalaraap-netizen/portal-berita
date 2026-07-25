<?php

namespace App\Services;

use App\Models\Post;

class NewsArticleSchema
{
    public function build(Post $post): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'NewsArticle',
            'headline' => $post->seo_title,
            'description' => $post->seo_description,
            'image' => [$post->og_image_url],
            'datePublished' => $post->published_at?->toIso8601String(),
            'dateModified' => $post->updated_at->toIso8601String(),
            'author' => [
                '@type' => 'Person',
                'name' => $post->author->name,
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => 'PortalBerita',
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => asset('images/logo.svg'),
                ],
            ],
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id' => route('posts.show', $post),
            ],
        ];
    }
}
