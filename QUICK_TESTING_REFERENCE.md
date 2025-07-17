# Laravel API Testing - Quick Reference

## 🚀 Essential Commands

### Run Tests

```bash
# Run all tests
php artisan test

# Run specific test class
php artisan test --filter=AuthApiTest

# Run specific test method
php artisan test --filter=test_user_can_register

# Run with verbose output
php artisan test -v

# Run with coverage
php artisan test --coverage

# Run in parallel
php artisan test --parallel
```

### Generate Test Files

```bash
# Create feature test
php artisan make:test AuthApiTest

# Create unit test
php artisan make:test UserTest --unit

# Create factory
php artisan make:factory UserFactory
```

---

## 🧪 Common Test Patterns

### 1. Basic API Test Structure

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_endpoint_name()
    {
        // Arrange
        $user = User::factory()->create();

        // Act
        $response = $this->postJson('/api/endpoint', [
            'data' => 'value'
        ]);

        // Assert
        $response->assertStatus(200);
    }
}
```

### 2. Authentication Testing

```php
// Test without authentication
$response = $this->getJson('/api/protected-endpoint');
$response->assertStatus(401);

// Test with authentication
$user = User::factory()->create();
$token = $user->createToken('test-token')->plainTextToken;

$response = $this->withHeaders([
    'Authorization' => 'Bearer ' . $token,
])->getJson('/api/protected-endpoint');

$response->assertStatus(200);
```

### 3. Factory Usage

```php
// Basic factory
$user = User::factory()->create();

// Factory with specific attributes
$user = User::factory()->create([
    'email' => 'test@example.com',
    'gender' => 'male',
]);

// Factory states
$maleUser = User::factory()->male()->create();
$femaleHindu = User::factory()->female()->hindu()->create();

// Multiple users
$users = User::factory()->count(5)->create();
```

---

## 📊 Assertion Methods

### HTTP Response Assertions

```php
// Status codes
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
    'user' => ['id', 'name', 'email'],
]);

// JSON content
$response->assertJson([
    'message' => 'Success',
    'user' => ['name' => 'John Doe'],
]);

// JSON fragment
$response->assertJsonFragment(['name' => 'John Doe']);

// JSON missing
$response->assertJsonMissing(['password']);

// JSON count
$response->assertJsonCount(3, 'users');
```

### Database Assertions

```php
// Check if record exists
$this->assertDatabaseHas('users', [
    'email' => 'test@example.com',
]);

// Check if record doesn't exist
$this->assertDatabaseMissing('users', [
    'email' => 'nonexistent@example.com',
]);

// Check record count
$this->assertDatabaseCount('users', 5);

// Check if table is empty
$this->assertDatabaseEmpty('users');
```

### Authentication Assertions

```php
// Check if user is authenticated
$this->assertAuthenticated();

// Check if specific user is authenticated
$this->assertAuthenticatedAs($user);

// Check if user is not authenticated
$this->assertGuest();
```

---

## 🔧 HTTP Testing Methods

### GET Requests

```php
// Basic GET
$response = $this->get('/api/users');

// GET with headers
$response = $this->withHeaders([
    'Authorization' => 'Bearer ' . $token,
])->get('/api/profile');

// GET with query parameters
$response = $this->get('/api/users?page=1&limit=10');
```

### POST Requests

```php
// Basic POST
$response = $this->post('/api/register', [
    'name' => 'John Doe',
    'email' => 'john@example.com',
]);

// POST with JSON
$response = $this->postJson('/api/register', [
    'name' => 'John Doe',
    'email' => 'john@example.com',
]);

// POST with headers
$response = $this->withHeaders([
    'Content-Type' => 'application/json',
    'Accept' => 'application/json',
])->postJson('/api/register', $data);
```

### PUT/PATCH Requests

```php
// PUT request
$response = $this->putJson('/api/profile', [
    'name' => 'Updated Name',
]);

// PATCH request
$response = $this->patchJson('/api/profile', [
    'bio' => 'Updated bio',
]);
```

### DELETE Requests

```php
// DELETE request
$response = $this->deleteJson('/api/users/1');

// DELETE with authentication
$response = $this->withHeaders([
    'Authorization' => 'Bearer ' . $token,
])->deleteJson('/api/users/1');
```

---

## 🏭 Factory States (Matrimonial API)

### User Factory States

```php
// Gender states
$maleUser = User::factory()->male()->create();
$femaleUser = User::factory()->female()->create();

// Religion states
$hinduUser = User::factory()->hindu()->create();
$muslimUser = User::factory()->muslim()->create();
$sikhUser = User::factory()->sikh()->create();

// Age range
$youngUser = User::factory()->ageRange(18, 25)->create();
$matureUser = User::factory()->ageRange(30, 40)->create();

// Income
$highIncomeUser = User::factory()->highIncome()->create();

// Location
$mumbaiUser = User::factory()->location('Mumbai')->create();
```

### Complex Factory Combinations

```php
// Female Hindu user from Mumbai with high income
$user = User::factory()
    ->female()
    ->hindu()
    ->location('Mumbai')
    ->highIncome()
    ->ageRange(25, 35)
    ->create();
```

---

## 🧪 Test Data Setup

### Using Seeders in Tests

```php
public function setUp(): void
{
    parent::setUp();
    $this->artisan('db:seed');
}

// Or run specific seeder
$this->artisan('db:seed', ['--class' => 'MatrimonialSeeder']);
```

### Manual Data Creation

```php
// Create user with preferences
$user = User::factory()->create();
$preferences = Preference::create([
    'user_id' => $user->id,
    'preferred_age_min' => 25,
    'preferred_age_max' => 35,
    'preferred_gender' => 'female',
]);

// Create matches
$user1 = User::factory()->create();
$user2 = User::factory()->create();
$user1->matches()->attach($user2->id);

// Create likes
$user1->likes()->attach($user2->id);

// Create messages
$user1->sentMessages()->create([
    'to_user_id' => $user2->id,
    'message' => 'Hello!',
]);
```

---

## 🔍 Debugging Tests

### Dump Response

```php
$response = $this->getJson('/api/profile');
dd($response->json()); // Dump and die
```

### Log Test Data

```php
Log::info('Test response', $response->json());
```

### Check Database State

```php
// Dump all users
dd(User::all()->toArray());

// Dump specific table
dd(DB::table('users')->get()->toArray());
```

---

## 📈 Performance Testing

### Response Time Testing

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

### Memory Usage Testing

```php
public function test_memory_usage()
{
    $memoryBefore = memory_get_usage();

    $response = $this->getJson('/api/recommendations');

    $memoryAfter = memory_get_usage();
    $memoryUsed = $memoryAfter - $memoryBefore;

    $this->assertLessThan(50 * 1024 * 1024, $memoryUsed); // Less than 50MB
}
```

---

## 🎯 Common Test Scenarios

### 1. Registration Flow

```php
public function test_complete_registration_flow()
{
    // 1. Register user
    $response = $this->postJson('/api/register', [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'age' => 28,
        'gender' => 'male',
    ]);

    $response->assertStatus(201);
    $token = $response->json('token');

    // 2. Get profile
    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token,
    ])->getJson('/api/profile');

    $response->assertStatus(200);

    // 3. Update preferences
    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token,
    ])->postJson('/api/profile/preferences', [
        'preferred_gender' => 'female',
        'preferred_age_min' => 25,
        'preferred_age_max' => 35,
    ]);

    $response->assertStatus(200);
}
```

### 2. Matchmaking Flow

```php
public function test_matchmaking_flow()
{
    // Create users
    $user1 = User::factory()->male()->create();
    $user2 = User::factory()->female()->create();

    // Set preferences
    Preference::create([
        'user_id' => $user1->id,
        'preferred_gender' => 'female',
        'preferred_age_min' => 20,
        'preferred_age_max' => 30,
    ]);

    $token = $user1->createToken('test-token')->plainTextToken;

    // Get recommendations
    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token,
    ])->getJson('/api/recommendations');

    $response->assertStatus(200);
    $this->assertGreaterThan(0, $response->json('total'));
}
```

### 3. Chat Flow

```php
public function test_chat_flow()
{
    // Create matched users
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $user1->matches()->attach($user2->id);

    $token = $user1->createToken('test-token')->plainTextToken;

    // Send message
    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token,
    ])->postJson('/api/messages/send', [
        'to_user_id' => $user2->id,
        'message' => 'Hello!',
    ]);

    $response->assertStatus(201);

    // Get chat history
    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token,
    ])->getJson("/api/messages/{$user2->id}");

    $response->assertStatus(200);
    $this->assertEquals(1, $response->json('total'));
}
```

---

## 🚨 Error Testing

### Validation Errors

```php
public function test_validation_errors()
{
    $response = $this->postJson('/api/register', [
        'name' => '', // Invalid
        'email' => 'invalid-email', // Invalid
        'password' => '123', // Too short
    ]);

    $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'email', 'password']);
}
```

### Authentication Errors

```php
public function test_authentication_errors()
{
    // Invalid token
    $response = $this->withHeaders([
        'Authorization' => 'Bearer invalid-token',
    ])->getJson('/api/profile');

    $response->assertStatus(401);

    // Missing token
    $response = $this->getJson('/api/profile');
    $response->assertStatus(401);
}
```

### Authorization Errors

```php
public function test_authorization_errors()
{
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    $token = $user1->createToken('test-token')->plainTextToken;

    // Try to send message to unmatched user
    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token,
    ])->postJson('/api/messages/send', [
        'to_user_id' => $user2->id,
        'message' => 'Hello!',
    ]);

    $response->assertStatus(403);
}
```

---

## 📋 Test Checklist

### Before Running Tests

-   [ ] Database is configured for testing
-   [ ] Migrations are up to date
-   [ ] Factories are properly set up
-   [ ] Environment is set to testing

### Test Coverage Checklist

-   [ ] All API endpoints tested
-   [ ] Authentication tested
-   [ ] Validation errors tested
-   [ ] Authorization tested
-   [ ] Database state verified
-   [ ] Error scenarios covered
-   [ ] Edge cases handled

### Performance Checklist

-   [ ] Response times acceptable
-   [ ] Memory usage reasonable
-   [ ] Database queries optimized
-   [ ] No N+1 queries

---

## 🎉 Quick Start Commands

```bash
# 1. Run all tests
php artisan test

# 2. Run specific test
php artisan test --filter=test_user_can_register

# 3. Run with coverage
php artisan test --coverage

# 4. Run in parallel
php artisan test --parallel

# 5. Generate test file
php artisan make:test NewApiTest
```

This quick reference covers all the essential Laravel API testing patterns you'll need for your matrimonial API!
