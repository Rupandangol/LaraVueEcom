<?php

namespace Tests\Feature;

use App\Models\BlogCategory;
use Database\Factories\BlogCategoryFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BlogCategoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A index endpoint for blog category
     */
    public function test_index_blog_category(): void
    {
        BlogCategory::factory()->create([
            'status' => 'enable',
        ]);

        BlogCategory::factory()->create([
            'status' => 'disable',
        ]);
        $response = $this->json('GET', '/api/blog-categories');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [ // * means that it expects an array of items
                    'status',
                    'priority',
                    'description',
                    'blog_category',
                    'time_of_creation',
                    'random',     // These are appended attributes
                    'blog_count', // These are appended attributes
                ]
            ],
            'status',
        ]);
    }

    /**
     *  Test for show endpoint for blog category
     */
    public function test_show_blog_category(): void
    {
        $bc=BlogCategory::factory()->create();
        $response = $this->json('GET', '/api/blog-categories/'.$bc->id);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'data' => [
                'status',
                'priority',
                'description',
                'blog_category',
                'time_of_creation'
            ]
        ]);
    }
}
