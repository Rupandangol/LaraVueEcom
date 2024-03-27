<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Rating>
 */
class RatingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'rating' => random_int(0, 5),
            'review'=> $this->faker->paragraph(),
            'user_id' => $this->faker->randomElement(User::pluck('id')),
            'product_id' => $this->faker->randomElement(Product::pluck('id')),
        ];
    }
}
