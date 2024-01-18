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
            'user_id'=>$this->faker->randomElement(User::pluck('id')),
            'total_price'=>$this->faker->randomDigit(),
            'shipping_address'=>$this->faker->streetAddress(),
            'status'=>$this->faker->randomElement(['pending','delivered','on_transit'])
        ];
    }
}
