<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderDetail>
 */
class OrderDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id' => $this->faker->randomElement(Order::pluck('id')),
            'product_id' => $this->faker->randomElement(Product::pluck('id')),
            'quantity' => $this->faker->randomDigit(),
            'price' => $this->faker->randomDigitNotZero(),
        ];
    }
}
