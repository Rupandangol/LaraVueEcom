<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => $this->faker->randomElement(User::pluck('id')),
            'total_price' => $this->faker->randomDigit(),
            'country' => $this->faker->country(),
            'zone' => $this->faker->word(),
            'district' => $this->faker->word(),
            'street' => $this->faker->streetAddress(),
            'zip_code' => $this->faker->randomDigit(),
            'status' => $this->faker->randomElement(['pending', 'delivered', 'in_transit']),
        ];
    }
}
