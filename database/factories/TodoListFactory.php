<?php

namespace Database\Factories;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TodoList>
 */
class TodoListFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'task' => $this->faker->sentence(),
            'description' => $this->faker->sentence(),
            'is_completed' => $this->faker->randomElement([0, 1]),
            'due_date' => null,
            'admin_id' => $this->faker->randomElement(Admin::pluck('id')),
        ];
    }
}
