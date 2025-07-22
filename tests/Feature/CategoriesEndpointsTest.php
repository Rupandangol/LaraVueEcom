<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoriesEndpointsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_categories_create_unauthorized(): void
    {
        $category = [
            'name' => 'Test Category',
            'slug' => 'test-category',
            'description' => 'Test Description',
        ];
        $response = $this->json('post', '/api/V1/categories', $category);

        $response->assertStatus(401);
    }
}
