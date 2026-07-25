<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PostFactory extends Factory
{
    public function definition(): array
    {
        $title = fake()->sentence(6);

        return [
            'category_id' => Category::factory(),
            'author_id' => User::factory(),
            'title' => $title,
            'slug' => Str::slug($title).'-'.fake()->unique()->numberBetween(100, 999),
            'meta_title' => $title,
            'meta_description' => fake()->sentence(18),
            'excerpt' => fake()->sentence(20),
            'thumbnail' => fake()->randomElement(['images/berita1.svg', 'images/berita2.svg', 'images/berita3.svg', 'images/headline.svg']),
            'og_image' => null,
            'content' => '<p>'.implode('</p><p>', fake()->paragraphs(6)).'</p>',
            'status' => Post::STATUS_PUBLISHED,
            'published_at' => fake()->dateTimeBetween('-30 days', 'now'),
            'views' => fake()->numberBetween(100, 5000),
        ];
    }
}
