# Laravel API Testing Guide

## 🧪 Overview

Laravel provides excellent built-in tools for API testing. This guide covers testing your matrimonial API using Laravel's testing framework, Artisan commands, and additional tools.

## 🛠️ Built-in Laravel Testing Tools

### 1. PHPUnit Integration

Laravel comes with PHPUnit pre-configured for testing.

### 2. Artisan Test Commands

-   `php artisan test` - Run all tests
-   `php artisan test --filter=TestName` - Run specific test
-   `php artisan test --coverage` - Generate coverage report

### 3. HTTP Testing

Laravel provides fluent HTTP testing methods for APIs.

---

## 📝 Creating API Tests

### 1. Generate Test File

```bash
php artisan make:test MatrimonialApiTest
```

### 2. Basic Test Structure

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
    }
}
```

---

## 🧪 Complete Test Examples

### 1. Authentication Tests

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register()
    {
        $userData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
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
        ];

        $response = $this->postJson('/api/register', $userData);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'message',
                    'user' => [
                        'id',
                        'name',
                        'email',
                        'age',
                        'gender',
                        'religion',
                        'caste',
                        'income',
                        'education',
                        'location',
                        'occupation',
                        'bio',
                    ],
                    'token',
                ]);

        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
            'name' => 'John Doe',
        ]);
    }

    public function test_user_can_login()
    {
        $user = User::factory()->create([
            'email' => 'john@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'john@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'message',
                    'user',
                    'token',
                ]);

        $this->assertNotEmpty($response->json('token'));
    }

    public function test_user_cannot_login_with_invalid_credentials()
    {
        $user = User::factory()->create([
            'email' => 'john@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'john@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(422);
    }

    public function test_user_can_logout()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/logout');

        $response->assertStatus(200)
                ->assertJson([
                    'message' => 'Logged out successfully',
                ]);
    }
}
```

### 2. Profile Management Tests

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProfileApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_get_profile()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/profile');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'user' => [
                        'id',
                        'name',
                        'email',
                        'age',
                        'gender',
                        'religion',
                        'caste',
                        'income',
                        'education',
                        'location',
                        'occupation',
                        'bio',
                        'preferences',
                    ],
                ]);
    }

    public function test_user_can_update_profile()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $updateData = [
            'name' => 'Updated Name',
            'age' => 29,
            'bio' => 'Updated bio',
            'location' => 'Delhi',
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->putJson('/api/profile', $updateData);

        $response->assertStatus(200)
                ->assertJson([
                    'message' => 'Profile updated successfully',
                ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'age' => 29,
        ]);
    }

    public function test_user_can_update_preferences()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $preferences = [
            'preferred_age_min' => 25,
            'preferred_age_max' => 35,
            'preferred_gender' => 'female',
            'preferred_religion' => 'Hindu',
            'preferred_caste' => 'Brahmin',
            'preferred_income_min' => 600000,
            'preferred_income_max' => 1200000,
            'preferred_education' => 'Masters',
            'preferred_location' => 'Mumbai',
            'preferred_occupation' => 'Software Engineer',
            'age_weight' => 1.5,
            'religion_weight' => 2.0,
            'caste_weight' => 1.8,
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/profile/preferences', $preferences);

        $response->assertStatus(200)
                ->assertJson([
                    'message' => 'Preferences updated successfully',
                ]);

        $this->assertDatabaseHas('preferences', [
            'user_id' => $user->id,
            'preferred_age_min' => 25,
            'preferred_age_max' => 35,
            'preferred_gender' => 'female',
        ]);
    }
}
```

### 3. Matchmaking Tests

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Preference;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MatchmakingApiTest extends TestCase
{
    use RefreshDatabase;

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
            'religion' => 'Hindu',
            'caste' => 'Brahmin',
        ]);

        // Create preferences for the user
        Preference::create([
            'user_id' => $user->id,
            'preferred_age_min' => 25,
            'preferred_age_max' => 35,
            'preferred_gender' => 'female',
            'preferred_religion' => 'Hindu',
            'preferred_caste' => 'Brahmin',
            'age_weight' => 1.0,
            'gender_weight' => 1.0,
            'religion_weight' => 1.0,
            'caste_weight' => 1.0,
        ]);

        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/recommendations');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'recommendations',
                    'total',
                ]);

        $this->assertGreaterThan(0, $response->json('total'));
    }

    public function test_recommendations_include_compatibility_score()
    {
        $user = User::factory()->create([
            'gender' => 'male',
            'age' => 28,
        ]);

        $potentialMatch = User::factory()->create([
            'gender' => 'female',
            'age' => 26,
        ]);

        Preference::create([
            'user_id' => $user->id,
            'preferred_age_min' => 25,
            'preferred_age_max' => 35,
            'preferred_gender' => 'female',
        ]);

        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/recommendations');

        $recommendations = $response->json('recommendations');

        if (!empty($recommendations)) {
            $this->assertArrayHasKey('score', $recommendations[0]);
            $this->assertArrayHasKey('compatibility_percentage', $recommendations[0]);
            $this->assertArrayHasKey('user', $recommendations[0]);
        }
    }
}
```

### 4. Like System Tests

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LikeApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_like_another_user()
    {
        $user = User::factory()->create();
        $likedUser = User::factory()->create();

        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson("/api/likes/{$likedUser->id}");

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'message',
                    'is_match',
                    'liked_user',
                ]);

        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'liked_user_id' => $likedUser->id,
        ]);
    }

    public function test_mutual_like_creates_match()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        // User1 likes User2
        $token1 = $user1->createToken('test-token')->plainTextToken;
        $this->withHeaders([
            'Authorization' => 'Bearer ' . $token1,
        ])->postJson("/api/likes/{$user2->id}");

        // User2 likes User1 (should create match)
        $token2 = $user2->createToken('test-token')->plainTextToken;
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token2,
        ])->postJson("/api/likes/{$user1->id}");

        $response->assertStatus(201)
                ->assertJson([
                    'is_match' => true,
                ]);

        $this->assertDatabaseHas('matches', [
            'user_id' => $user1->id,
            'matched_user_id' => $user2->id,
        ]);
    }

    public function test_user_can_unlike_another_user()
    {
        $user = User::factory()->create();
        $likedUser = User::factory()->create();

        // Create like
        $user->likes()->attach($likedUser->id);

        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->deleteJson("/api/likes/{$likedUser->id}");

        $response->assertStatus(200)
                ->assertJson([
                    'message' => 'User unliked successfully',
                ]);

        $this->assertDatabaseMissing('likes', [
            'user_id' => $user->id,
            'liked_user_id' => $likedUser->id,
        ]);
    }
}
```

### 5. Message System Tests

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MessageApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_send_message_to_matched_user()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        // Create match
        $user1->matches()->attach($user2->id);

        $token = $user1->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/messages/send', [
            'to_user_id' => $user2->id,
            'message' => 'Hello! How are you?',
        ]);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'message',
                    'data' => [
                        'id',
                        'from_user_id',
                        'to_user_id',
                        'message',
                        'is_read',
                        'created_at',
                        'updated_at',
                        'sender',
                        'recipient',
                    ],
                ]);

        $this->assertDatabaseHas('messages', [
            'from_user_id' => $user1->id,
            'to_user_id' => $user2->id,
            'message' => 'Hello! How are you?',
        ]);
    }

    public function test_user_cannot_send_message_to_unmatched_user()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $token = $user1->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/messages/send', [
            'to_user_id' => $user2->id,
            'message' => 'Hello! How are you?',
        ]);

        $response->assertStatus(403);
    }

    public function test_user_can_get_chat_history()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        // Create match
        $user1->matches()->attach($user2->id);

        // Create messages
        $user1->sentMessages()->create([
            'to_user_id' => $user2->id,
            'message' => 'Hello!',
        ]);

        $user2->sentMessages()->create([
            'to_user_id' => $user1->id,
            'message' => 'Hi there!',
        ]);

        $token = $user1->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson("/api/messages/{$user2->id}");

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'messages',
                    'total',
                ]);

        $this->assertEquals(2, $response->json('total'));
    }
}
```

---

## 🏭 Using Factories for Testing

### 1. User Factory

```php
<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'remember_token' => Str::random(10),
            'age' => fake()->numberBetween(18, 60),
            'gender' => fake()->randomElement(['male', 'female']),
            'religion' => fake()->randomElement(['Hindu', 'Muslim', 'Sikh', 'Christian']),
            'caste' => fake()->randomElement(['Brahmin', 'Patel', 'Agarwal', 'Khatri']),
            'income' => fake()->numberBetween(300000, 2000000),
            'education' => fake()->randomElement(['Bachelors', 'Masters', 'PhD']),
            'location' => fake()->randomElement(['Mumbai', 'Delhi', 'Bangalore', 'Chennai']),
            'occupation' => fake()->randomElement(['Software Engineer', 'Doctor', 'Teacher', 'Business Analyst']),
            'bio' => fake()->paragraph(),
        ];
    }

    public function male(): static
    {
        return $this->state(fn (array $attributes) => [
            'gender' => 'male',
        ]);
    }

    public function female(): static
    {
        return $this->state(fn (array $attributes) => [
            'gender' => 'female',
        ]);
    }

    public function hindu(): static
    {
        return $this->state(fn (array $attributes) => [
            'religion' => 'Hindu',
        ]);
    }
}
```

### 2. Using Factories in Tests

```php
// Create a single user
$user = User::factory()->create();

// Create multiple users
$users = User::factory()->count(5)->create();

// Create user with specific attributes
$user = User::factory()->create([
    'gender' => 'female',
    'religion' => 'Hindu',
]);

// Create male user
$maleUser = User::factory()->male()->create();

// Create female Hindu user
$femaleHindu = User::factory()->female()->hindu()->create();
```

---

## 🧪 Running Tests

### 1. Run All Tests

```bash
php artisan test
```

### 2. Run Specific Test Class

```bash
php artisan test --filter=AuthApiTest
```

### 3. Run Specific Test Method

```bash
php artisan test --filter=test_user_can_register
```

### 4. Run Tests with Coverage

```bash
php artisan test --coverage
```

### 5. Run Tests in Parallel

```bash
php artisan test --parallel
```

### 6. Run Tests with Verbose Output

```bash
php artisan test -v
```

---

## 📊 Test Assertions

### 1. HTTP Response Assertions

```php
// Status code
$response->assertStatus(200);
$response->assertCreated(); // 201
$response->assertNoContent(); // 204
$response->assertBadRequest(); // 400
$response->assertUnauthorized(); // 401
$response->assertForbidden(); // 403
$response->assertNotFound(); // 404
$response->assertUnprocessable(); // 422

// JSON structure
$response->assertJsonStructure([
    'message',
    'user' => [
        'id',
        'name',
        'email',
    ],
]);

// JSON content
$response->assertJson([
    'message' => 'User registered successfully',
]);

// JSON fragment
$response->assertJsonFragment([
    'name' => 'John Doe',
]);

// JSON missing
$response->assertJsonMissing([
    'password',
]);

// JSON count
$response->assertJsonCount(3, 'users');
```

### 2. Database Assertions

```php
// Check if record exists
$this->assertDatabaseHas('users', [
    'email' => 'john@example.com',
    'name' => 'John Doe',
]);

// Check if record doesn't exist
$this->assertDatabaseMissing('users', [
    'email' => 'nonexistent@example.com',
]);

// Check if record count
$this->assertDatabaseCount('users', 5);

// Check if table is empty
$this->assertDatabaseEmpty('users');
```

### 3. Authentication Assertions

```php
// Check if user is authenticated
$this->assertAuthenticated();

// Check if specific user is authenticated
$this->assertAuthenticatedAs($user);

// Check if user is not authenticated
$this->assertGuest();
```

---

## 🔧 Test Configuration

### 1. phpunit.xml Configuration

```xml
<?xml version="1.0" encoding="UTF-8"?>
<phpunit xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
         xsi:noNamespaceSchemaLocation="vendor/phpunit/phpunit/phpunit.xsd"
         bootstrap="vendor/autoload.php"
         colors="true"
>
    <testsuites>
        <testsuite name="Unit">
            <directory>tests/Unit</directory>
        </testsuite>
        <testsuite name="Feature">
            <directory>tests/Feature</directory>
        </testsuite>
    </testsuites>
    <source>
        <include>
            <directory>app</directory>
        </include>
    </source>
    <php>
        <env name="APP_ENV" value="testing"/>
        <env name="BCRYPT_ROUNDS" value="4"/>
        <env name="CACHE_DRIVER" value="array"/>
        <env name="DB_CONNECTION" value="sqlite"/>
        <env name="DB_DATABASE" value=":memory:"/>
        <env name="MAIL_MAILER" value="array"/>
        <env name="QUEUE_CONNECTION" value="sync"/>
        <env name="SESSION_DRIVER" value="array"/>
        <env name="TELESCOPE_ENABLED" value="false"/>
    </php>
</phpunit>
```

### 2. Test Database Setup

```php
// In your test class
use RefreshDatabase;

// Or manually in setUp method
public function setUp(): void
{
    parent::setUp();
    $this->artisan('migrate:fresh');
    $this->artisan('db:seed');
}
```

---

## 🚀 Advanced Testing Techniques

### 1. Testing with Sanctum

```php
public function test_protected_endpoint_requires_authentication()
{
    $response = $this->getJson('/api/profile');
    $response->assertStatus(401);
}

public function test_authenticated_user_can_access_protected_endpoint()
{
    $user = User::factory()->create();
    $token = $user->createToken('test-token')->plainTextToken;

    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token,
    ])->getJson('/api/profile');

    $response->assertStatus(200);
}
```

### 2. Testing Validation Errors

```php
public function test_registration_requires_valid_email()
{
    $response = $this->postJson('/api/register', [
        'name' => 'Test User',
        'email' => 'invalid-email',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
}

public function test_password_confirmation_must_match()
{
    $response = $this->postJson('/api/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password123',
        'password_confirmation' => 'differentpassword',
    ]);

    $response->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
}
```

### 3. Testing Rate Limiting

```php
public function test_api_rate_limiting()
{
    $user = User::factory()->create();
    $token = $user->createToken('test-token')->plainTextToken;

    // Make multiple requests
    for ($i = 0; $i < 60; $i++) {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/profile');

        if ($response->status() === 429) {
            break;
        }
    }

    $response->assertStatus(429);
}
```

---

## 📈 Test Coverage

### 1. Generate Coverage Report

```bash
php artisan test --coverage --min=80
```

### 2. Coverage Configuration

```xml
<!-- In phpunit.xml -->
<coverage>
    <include>
        <directory suffix=".php">app</directory>
    </include>
    <exclude>
        <directory suffix=".php">app/Console</directory>
        <directory suffix=".php">app/Exceptions</directory>
    </exclude>
</coverage>
```

---

## 🎯 Best Practices

### 1. Test Organization

-   Group related tests in the same class
-   Use descriptive test method names
-   Follow AAA pattern (Arrange, Act, Assert)

### 2. Database Testing

-   Use `RefreshDatabase` trait for clean state
-   Use factories for test data
-   Test both positive and negative scenarios

### 3. API Testing

-   Test all HTTP methods (GET, POST, PUT, DELETE)
-   Test validation errors
-   Test authentication and authorization
-   Test edge cases and error conditions

### 4. Performance Testing

```php
public function test_api_response_time()
{
    $start = microtime(true);

    $response = $this->getJson('/api/recommendations');

    $end = microtime(true);
    $responseTime = ($end - $start) * 1000; // Convert to milliseconds

    $this->assertLessThan(500, $responseTime); // Should respond within 500ms
    $response->assertStatus(200);
}
```

---

## 🔍 Debugging Tests

### 1. Dump Response

```php
$response = $this->getJson('/api/profile');
dd($response->json()); // Dump and die
```

### 2. Log Test Data

```php
Log::info('Test response', $response->json());
```

### 3. Use Laravel Telescope (if installed)

```php
// Telescope will automatically capture API requests in tests
```

---

## 📚 Additional Resources

1. **Laravel Testing Documentation**: https://laravel.com/docs/testing
2. **PHPUnit Documentation**: https://phpunit.de/documentation.html
3. **Laravel Sanctum Testing**: https://laravel.com/docs/sanctum#testing

---

## 🎉 Summary

This guide covers:

-   ✅ Complete test examples for all API endpoints
-   ✅ Factory usage for test data
-   ✅ Authentication testing with Sanctum
-   ✅ Database assertions and validation
-   ✅ Best practices and debugging techniques
-   ✅ Performance and coverage testing

Your matrimonial API now has comprehensive test coverage ensuring reliability and maintainability!
