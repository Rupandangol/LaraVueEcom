<?php

namespace Database\Factories;

use App\Models\BlogCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Blog>
 */
class BlogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->sentence();
        $slug = Str::slug($title);

        return [
            'title' => $title,
            'slug' => $slug,
            'status' => $this->faker->randomElement(['enable', 'disable']),
            'content' => $this->faker->paragraph(),
            'user_id' => $this->faker->randomElement(User::pluck('id')),
            'blog_category_id' => $this->faker->randomElement(BlogCategory::pluck('id')),
        ];
    }
}
