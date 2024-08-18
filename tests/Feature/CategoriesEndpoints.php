<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use function PHPSTORM_META\map;

class CategoriesEndpoints extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testCategoriesCreate(): void
    {
        $category = [
            'name' => 'Test Category',
            'slug' => 'test-category',
            'description' => 'Test Description'
        ];
        $this->json('post', '/api/V1/categories', $category)
            ->assertStatus(201)
            ->assertJsonStructure([
                "name",
                "description",
                "slug",
                "updated_at",
                "created_at",
                "id"
            ]);
    }
}
