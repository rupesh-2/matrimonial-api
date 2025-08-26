<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Preference;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DiscoverSystemTest extends TestCase
{
    use RefreshDatabase;

    protected $user1;
    protected $user2;
    protected $user3;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create test users
        $this->user1 = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'age' => 28,
            'gender' => 'male',
            'religion' => 'Hindu',
            'caste' => 'Brahmin',
        ]);

        $this->user2 = User::factory()->create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'age' => 26,
            'gender' => 'female',
            'religion' => 'Hindu',
            'caste' => 'Brahmin',
        ]);

        $this->user3 = User::factory()->create([
            'name' => 'Alice Johnson',
            'email' => 'alice@example.com',
            'age' => 25,
            'gender' => 'female',
            'religion' => 'Christian',
            'caste' => null,
        ]);

        // Create preferences for user1
        Preference::factory()->create([
            'user_id' => $this->user1->id,
            'preferred_age_min' => 25,
            'preferred_age_max' => 30,
            'preferred_gender' => 'female',
            'preferred_religion' => 'Hindu',
            'age_weight' => 2.0,
            'religion_weight' => 1.5,
        ]);
    }

    /** @test */
    public function user_can_get_discover_profiles()
    {
        $response = $this->actingAs($this->user1)
                        ->getJson('/api/discover');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'discover_profiles',
                    'total',
                    'current_page',
                    'per_page',
                    'has_more'
                ]);

        // Should see user2 and user3 in discover
        $this->assertCount(2, $response->json('discover_profiles'));
    }

    /** @test */
    public function user_can_like_a_profile()
    {
        $response = $this->actingAs($this->user1)
                        ->postJson("/api/discover/like/{$this->user2->id}");

        $response->assertStatus(201)
                ->assertJson([
                    'message' => 'Profile liked successfully',
                    'is_match' => false
                ]);

        // Verify like was created
        $this->assertTrue($this->user1->hasLiked($this->user2));
    }

    /** @test */
    public function user_cannot_like_own_profile()
    {
        $response = $this->actingAs($this->user1)
                        ->postJson("/api/discover/like/{$this->user1->id}");

        $response->assertStatus(400)
                ->assertJson([
                    'message' => 'Cannot like your own profile'
                ]);
    }

    /** @test */
    public function user_cannot_like_already_liked_profile()
    {
        // First like
        $this->actingAs($this->user1)
             ->postJson("/api/discover/like/{$this->user2->id}");

        // Try to like again
        $response = $this->actingAs($this->user1)
                        ->postJson("/api/discover/like/{$this->user2->id}");

        $response->assertStatus(400)
                ->assertJson([
                    'message' => 'Already liked this profile'
                ]);
    }

    /** @test */
    public function mutual_like_creates_match()
    {
        // User1 likes User2
        $this->actingAs($this->user1)
             ->postJson("/api/discover/like/{$this->user2->id}");

        // User2 likes User1 (should create match)
        $response = $this->actingAs($this->user2)
                        ->postJson("/api/discover/like/{$this->user1->id}");

        $response->assertStatus(201)
                ->assertJson([
                    'message' => 'It\'s a match! 🎉',
                    'is_match' => true
                ]);

        // Verify match was created
        $this->assertTrue($this->user1->isMatchedWith($this->user2));
        $this->assertTrue($this->user2->isMatchedWith($this->user1));
    }

    /** @test */
    public function user_can_unlike_profile()
    {
        // First like
        $this->actingAs($this->user1)
             ->postJson("/api/discover/like/{$this->user2->id}");

        // Then unlike
        $response = $this->actingAs($this->user1)
                        ->deleteJson("/api/discover/unlike/{$this->user2->id}");

        $response->assertStatus(200)
                ->assertJson([
                    'message' => 'Profile unliked successfully'
                ]);

        // Verify like was removed
        $this->assertFalse($this->user1->hasLiked($this->user2));
    }

    /** @test */
    public function unlike_removes_match_if_exists()
    {
        // Create mutual like (match)
        $this->actingAs($this->user1)
             ->postJson("/api/discover/like/{$this->user2->id}");
        $this->actingAs($this->user2)
             ->postJson("/api/discover/like/{$this->user1->id}");

        // Verify match exists
        $this->assertTrue($this->user1->isMatchedWith($this->user2));

        // Unlike
        $this->actingAs($this->user1)
             ->deleteJson("/api/discover/unlike/{$this->user2->id}");

        // Verify match was removed
        $this->assertFalse($this->user1->isMatchedWith($this->user2));
    }

    /** @test */
    public function liked_profiles_dont_appear_in_discover()
    {
        // User1 likes User2
        $this->actingAs($this->user1)
             ->postJson("/api/discover/like/{$this->user2->id}");

        // Get discover profiles
        $response = $this->actingAs($this->user1)
                        ->getJson('/api/discover');

        $discoverProfiles = $response->json('discover_profiles');
        
        // User2 should not appear in discover
        $userIds = collect($discoverProfiles)->pluck('user.id');
        $this->assertNotContains($this->user2->id, $userIds);
        
        // User3 should still appear
        $this->assertContains($this->user3->id, $userIds);
    }

    /** @test */
    public function matches_dont_appear_in_discover()
    {
        // Create mutual like (match)
        $this->actingAs($this->user1)
             ->postJson("/api/discover/like/{$this->user2->id}");
        $this->actingAs($this->user2)
             ->postJson("/api/discover/like/{$this->user1->id}");

        // Get discover profiles for user1
        $response = $this->actingAs($this->user1)
                        ->getJson('/api/discover');

        $discoverProfiles = $response->json('discover_profiles');
        
        // User2 should not appear in discover (because they're matched)
        $userIds = collect($discoverProfiles)->pluck('user.id');
        $this->assertNotContains($this->user2->id, $userIds);
    }

    /** @test */
    public function user_can_get_profiles_that_liked_them()
    {
        // User2 likes User1
        $this->actingAs($this->user2)
             ->postJson("/api/discover/like/{$this->user1->id}");

        // User1 gets profiles that liked them
        $response = $this->actingAs($this->user1)
                        ->getJson('/api/discover/liked-by');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'liked_by_profiles',
                    'total',
                    'current_page',
                    'per_page',
                    'has_more'
                ]);

        $likedByProfiles = $response->json('liked_by_profiles');
        $this->assertCount(1, $likedByProfiles);
        $this->assertEquals($this->user2->id, $likedByProfiles[0]['id']);
    }

    /** @test */
    public function mutual_likes_dont_appear_in_liked_by()
    {
        // User2 likes User1
        $this->actingAs($this->user2)
             ->postJson("/api/discover/like/{$this->user1->id}");

        // User1 likes User2 back (creates match)
        $this->actingAs($this->user1)
             ->postJson("/api/discover/like/{$this->user2->id}");

        // User1 gets profiles that liked them
        $response = $this->actingAs($this->user1)
                        ->getJson('/api/discover/liked-by');

        $likedByProfiles = $response->json('liked_by_profiles');
        
        // User2 should not appear (because it's now a mutual match)
        $this->assertCount(0, $likedByProfiles);
    }

    /** @test */
    public function user_can_get_matches()
    {
        // Create mutual like (match)
        $this->actingAs($this->user1)
             ->postJson("/api/discover/like/{$this->user2->id}");
        $this->actingAs($this->user2)
             ->postJson("/api/discover/like/{$this->user1->id}");

        // Get matches
        $response = $this->actingAs($this->user1)
                        ->getJson('/api/matches');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'matches',
                    'total',
                    'current_page',
                    'per_page',
                    'has_more'
                ]);

        $matches = $response->json('matches');
        $this->assertCount(1, $matches);
        $this->assertEquals($this->user2->id, $matches[0]['id']);
    }

    /** @test */
    public function discover_supports_pagination()
    {
        // Create more users for pagination testing
        User::factory()->count(15)->create([
            'gender' => 'female'
        ]);

        $response = $this->actingAs($this->user1)
                        ->getJson('/api/discover?limit=5&page=1');

        $response->assertStatus(200);
        
        $this->assertEquals(5, count($response->json('discover_profiles')));
        $this->assertTrue($response->json('has_more'));
        $this->assertEquals(1, $response->json('current_page'));
    }

    /** @test */
    public function discover_excludes_opposite_gender_only()
    {
        // Create a male user
        $maleUser = User::factory()->create([
            'gender' => 'male'
        ]);

        $response = $this->actingAs($this->user1)
                        ->getJson('/api/discover');

        $discoverProfiles = $response->json('discover_profiles');
        $userIds = collect($discoverProfiles)->pluck('user.id');
        
        // Male user should not appear in discover for male user1
        $this->assertNotContains($maleUser->id, $userIds);
    }
}
