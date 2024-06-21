<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Website;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class VoteTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_vote_for_website()
    {
        Sanctum::actingAs(User::factory()->create(), ['*']);

        $website = Website::factory()->create();

        $response = $this->postJson('/api/websites/' . $website->id . '/vote');

        $response->assertStatus(200)
                 ->assertJsonStructure(['message']);
    }

    public function test_user_can_unvote_for_website()
    {
        Sanctum::actingAs(User::factory()->create(), ['*']);

        $website = Website::factory()->create();

        $this->postJson('/api/websites/' . $website->id . '/vote');

        $response = $this->postJson('/api/websites/' . $website->id . '/unvote');

        $response->assertStatus(200)
                 ->assertJsonStructure(['message']);
    }
}
