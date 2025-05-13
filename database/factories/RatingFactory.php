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
            'review' => $this->faker->randomElement([
                'Good',
                'Excellent product! Highly recommend!',
                'This is a game-changer. Love it!',
                'Great value for the money!',
                'Easy to use and very helpful.',
                'Highly satisfied with my purchase',
                'Not what I expected. Disappointed.',
                "Poor quality, doesn't last.",
                "Difficult to use, confusing instructions.",
                "The product broke right after use.",
                "Wouldn't recommend this product."
            ]),
            'user_id' => $this->faker->randomElement(User::pluck('id')),
            'product_id' => $this->faker->randomElement(Product::pluck('id')),
        ];
    }
}
