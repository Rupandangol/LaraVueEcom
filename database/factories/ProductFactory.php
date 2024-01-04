<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
          'name'=>$this->faker->name(),
          'description'=>$this->faker->paragraph(),
          'price'=>$this->faker->randomDigit(),
          'stock_quantity'=>$this->faker->randomDigit(),
          'category_id'=>$this->faker->randomElement(Category::pluck('id'))
        ];
    }
}
