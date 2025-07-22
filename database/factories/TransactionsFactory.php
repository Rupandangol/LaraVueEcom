<?php

namespace Database\Factories;

use App\Models\Admin;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\transactions>
 */
class TransactionsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $admin_id = Admin::take(1)->first();

        return [
            'date_time' => Carbon::now()->format('Y-m-d H:i:s'),
            'description' => fake()->sentence(),
            'debit' => fake()->randomElement([100, 200, 300, 1999, 2000]),
            'credit' => fake()->randomElement([100, 200, 300, 1999, 2000]),
            'status' => fake()->randomElement(['COMPLETE', 'REVERTED']),
            'channel' => fake()->randomElement(['App', 'THIRDPARTY']),
            'admin_id' => $admin_id,
        ];
    }
}
