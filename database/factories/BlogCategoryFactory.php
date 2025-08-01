<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BlogCategory>
 */
class BlogCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'blog_category' => $this->faker->word(),
            'priority' => $this->faker->randomNumber(),
            'description' => $this->faker->sentence(),
            'status' => $this->faker->randomElement(['enable', 'disable']),
        ];
    }
}
