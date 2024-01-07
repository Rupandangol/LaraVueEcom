<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\TestModel;
use App\Models\User;
use Database\Factories\TestModelFactory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        // TestModel::factory(10)->create();
        // User::factory(10)->create();
        // Category::factory(10)->create();
        // Product::factory(10)->create();
        // Order::factory(10)->create();
        // OrderDetail::factory(10)->create();
        Cart::factory(10)->create();

    }
}