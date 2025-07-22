<?php

namespace Database\Factories;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Mood>
 */
class MoodFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'mood' => $this->faker->randomElement(['happy', 'angry', 'sad', 'excited', 'tired']),
            'note' => $this->faker->paragraph(),
            'admin_id' => $this->faker->randomElement(Admin::pluck('id')),
        ];
    }
}
