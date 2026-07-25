<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->randomElement([
            'Nasional',
            'Internasional',
            'Politik',
            'Ekonomi',
            'Bisnis',
            'Teknologi',
            'Otomotif',
            'Olahraga',
            'Lifestyle',
            'Hiburan',
            'Kesehatan',
        ]);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => fake()->sentence(),
            'is_active' => true,
        ];
    }
}
