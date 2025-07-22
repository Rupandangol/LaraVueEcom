<?php

namespace Database\Factories;

use App\Enums\DailyScheduleStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RecurringDailySchedule>
 */
class RecurringDailyScheduleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->word(),
            'description' => fake()->sentence(),
            'date' => fake()->date('Y-m-d'),
            'start_time' => fake()->time('H:i:s'),
            'end_time' => fake()->time('H:i:s'),
            'is_all_day' => fake()->boolean(50),
            'location' => fake()->randomElement(['kathmandu', 'pokhara', 'chitwan']),
            'status' => fake()->randomElement(DailyScheduleStatus::cases())->value,
        ];
    }
}
