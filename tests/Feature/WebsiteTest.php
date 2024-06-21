<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use Laravel\Sanctum\Sanctum;
use App\Models\Website;
class WebsiteTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_website()
    {
        Sanctum::actingAs(User::factory()->create(), ['*']);

        $category = Category::factory()->create();

        $response = $this->postJson('/api/websites', [
            'name' => 'Example Website',
            'url' => 'https://example.com',
            'categories' => [$category->id],
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure(['id', 'name', 'url', 'categories']);
    }

    public function test_get_websites()
    {
        $response = $this->getJson('/api/websites');

        $response->assertStatus(200)
                 ->assertJsonStructure([['id', 'name', 'url', 'categories', 'votes_count']]);
    }
}
