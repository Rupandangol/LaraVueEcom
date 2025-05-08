<?php

namespace Database\Factories;

use App\Enums\DailyScheduleStatus;
use App\Models\Admin;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DailySchedule>
 */
class DailyScheduleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->randomElement(['Gym-chest', 'Gym-push', 'Gym-pull', 'Gym-leg', 'Gym-back', 'Gym-arms', 'Collage', 'sleep', 'work', 'meeting']),
            'description' => fake()->sentence(),
            'date' => fake()->date('Y-m-d'),
            'start_time' => fake()->time('H:i:s'),
            'end_time' => fake()->time('H:i:s'),
            'is_all_day' => fake()->boolean(50),
            'location' => fake()->randomElement(['kathmandu', 'pokhara', 'chitwan']),
            'status' => fake()->randomElement(DailyScheduleStatus::cases())->value,
            'admin_id' =>  Admin::factory()
        ];
    }
}
