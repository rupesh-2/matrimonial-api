<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MatrimonialApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'age' => 28,
            'gender' => 'male',
            'religion' => 'Hindu',
            'caste' => 'Brahmin',
            'income' => 800000,
            'education' => 'Masters',
            'location' => 'Mumbai',
            'occupation' => 'Software Engineer',
            'bio' => 'Looking for a life partner',
        ]);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'message',
                    'user' => [
                        'id',
                        'name',
                        'email',
                        'age',
                        'gender',
                    ],
                    'token',
                ]);

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'name' => 'Test User',
        ]);
    }

    public function test_user_can_login()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'message',
                    'user',
                    'token',
                ]);
    }

    public function test_user_can_get_recommendations()
    {
        // Create test users
        $user = User::factory()->create([
            'gender' => 'male',
            'age' => 28,
        ]);

        $potentialMatch = User::factory()->create([
            'gender' => 'female',
            'age' => 26,
        ]);

        $response = $this->actingAs($user)
                        ->getJson('/api/recommendations');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'recommendations',
                    'total',
                ]);
    }

    public function test_user_can_like_another_user()
    {
        $user = User::factory()->create();
        $likedUser = User::factory()->create();

        $response = $this->actingAs($user)
                        ->postJson("/api/likes/{$likedUser->id}");

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'message',
                    'is_match',
                    'liked_user',
                ]);
    }

    public function test_user_can_send_message_to_matched_user()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        // Create a match
        $user1->matches()->attach($user2->id);

        $response = $this->actingAs($user1)
                        ->postJson('/api/messages/send', [
                            'to_user_id' => $user2->id,
                            'message' => 'Hello! How are you?',
                        ]);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'message',
                    'data' => [
                        'id',
                        'message',
                        'from_user_id',
                        'to_user_id',
                    ],
                ]);
    }

    public function test_user_cannot_send_message_to_unmatched_user()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $response = $this->actingAs($user1)
                        ->postJson('/api/messages/send', [
                            'to_user_id' => $user2->id,
                            'message' => 'Hello! How are you?',
                        ]);

        $response->assertStatus(403);
    }
} 