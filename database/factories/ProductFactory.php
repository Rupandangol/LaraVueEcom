<?php

namespace Database\Factories;

use App\Models\Category;
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
            'name' => $this->faker->name(),
            'description' => $this->faker->paragraph(),
            'price' => $this->faker->randomDigitNotZero(),
            'stock_quantity' => $this->faker->randomDigit(),
            // 'image'=>$this->faker->image(storage_path('app/public/images'),400,300, null, false),
            'image' => '1709721491.png',
            'category_id' => $this->faker->randomElement(Category::pluck('id')),
        ];
    }
}
