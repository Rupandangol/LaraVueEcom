<?php

namespace Tests\Feature;

use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class BlogTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_get_all_blog(): void
    {
        User::factory(2)->create();
        BlogCategory::factory(2)->create();
        Blog::factory(2)->create();
        $response = $this->json('GET', '/api/blogs');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'data' => [
                '*' => [
                    'status',
                    'comment_enabled',
                    'is_featured',
                    'title',
                    'slug',
                    'content',
                    'written_from',
                    'user_id',
                    'test_money',
                    'blog_category',
                ]
            ]
        ]);
    }

    public function test_store_blog(): void
    {
        $u = User::factory()->create();
        $bc = BlogCategory::factory()->create();
        $data = [
            "title" => "test test3",
            "status" => "enable",
            "blog_category_id" => $bc->id,
            "content" => "test",
            "latitude" => "27.7212",
            "longitude" => "85.2883"
        ];
        Event::fake();
        $response = $this->json('POST', '/api/blogs', $data);
        $response->assertStatus(201);
    }
    public function test_already_deleted_blog_delete(): void
    {
        $response = $this->json('DELETE', '/api/blogs/12-asdfasdf-asdf');
        $response->assertStatus(404);
    }

    public function test_delete_blog(): void
    {
        $u = User::factory()->create();
        $u = BlogCategory::factory()->create();
        $b = Blog::factory()->create();
        $response = $this->json('DELETE', '/api/blogs/' . $b->id . '-' . $b->slug);
        $response->assertStatus(200);
        $this->assertDatabaseMissing('blogs', [
            'id' => $b->id,
            'title' => $b->title
        ]);
    }
}
