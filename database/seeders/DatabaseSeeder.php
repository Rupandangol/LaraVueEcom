<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Admin;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\Rating;
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
        // TestModel::truncate();
        // User::truncate();
        TestModel::factory(10)->create();
        User::factory(1000)->create();
        Admin::factory(10)->create();
        $this->call([
            UserSeeder::class,
            AdminSeeder::class
        ]);
        Category::factory(50)->create();
        Product::factory(1000)->create();
        Order::factory(100)->create();
        OrderDetail::factory(1000)->create();
        Cart::factory(50)->create();
        Rating::factory(100)->create();
        BlogCategory::factory(10)->create();
        Blog::factory(10)->create();
    }
}
