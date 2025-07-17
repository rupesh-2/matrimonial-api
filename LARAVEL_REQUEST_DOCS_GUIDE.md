# Laravel Request Docs (LRD) Guide

## What is Laravel Request Docs?

Laravel Request Docs (LRD) is a powerful tool that automatically generates API documentation from your Laravel routes and controllers. It creates interactive documentation that you can use to test your APIs directly from the browser.

## Installation

### 1. Install via Composer

```bash
composer require --dev knuckleswtf/scribe
```

### 2. Publish Configuration

```bash
php artisan vendor:publish --provider="Knuckles\Scribe\ScribeServiceProvider" --tag=scribe-config
```

### 3. Generate Documentation

```bash
php artisan scribe:generate
```

## Configuration

### Basic Configuration (`config/scribe.php`)

```php
return [
    'theme' => 'default',
    'title' => 'Matrimonial API Documentation',
    'description' => 'API documentation for the Matrimonial Application',
    'base_url' => env('APP_URL', 'http://localhost:8000'),
    'routes' => [
        [
            'match' => [
                'domains' => ['*'],
                'prefixes' => ['api/*'],
                'versions' => ['v1'],
            ],
            'include' => [
                // Include all API routes
            ],
            'exclude' => [
                // Exclude specific routes if needed
            ],
        ],
    ],
    'auth' => [
        'enabled' => true,
        'default' => false,
        'in' => 'bearer',
        'name' => 'Authorization',
        'use_value' => env('SCRIBE_AUTH_KEY'),
        'placeholder' => '{YOUR_AUTH_KEY_HERE}',
        'extra_info' => 'You can retrieve your token by making a POST request to `/api/auth/login`.',
    ],
];
```

## Adding Documentation to Controllers

### 1. Basic Route Documentation

```php
/**
 * @group Authentication
 *
 * APIs for managing user authentication
 */
class AuthController extends Controller
{
    /**
     * User Registration
     *
     * Register a new user account.
     *
     * @bodyParam name string required The user's full name. Example: John Doe
     * @bodyParam email string required The user's email address. Example: john@example.com
     * @bodyParam password string required The user's password (min 8 characters). Example: password123
     * @bodyParam password_confirmation string required Password confirmation. Example: password123
     * @bodyParam date_of_birth date required The user's date of birth. Example: 1990-01-01
     * @bodyParam gender string required The user's gender (male/female). Example: male
     * @bodyParam location string required The user's location. Example: New York, USA
     *
     * @response 201 {
     *   "message": "User registered successfully",
     *   "user": {
     *     "id": 1,
     *     "name": "John Doe",
     *     "email": "john@example.com",
     *     "date_of_birth": "1990-01-01",
     *     "gender": "male",
     *     "location": "New York, USA",
     *     "created_at": "2024-01-01T00:00:00.000000Z",
     *     "updated_at": "2024-01-01T00:00:00.000000Z"
     *   }
     * }
     *
     * @response 422 {
     *   "message": "The given data was invalid.",
     *   "errors": {
     *     "email": ["The email has already been taken."]
     *   }
     * }
     */
    public function register(Request $request)
    {
        // Your registration logic
    }
}
```

### 2. Authentication Documentation

```php
/**
 * User Login
 *
 * Authenticate user and return access token.
 *
 * @bodyParam email string required The user's email address. Example: john@example.com
 * @bodyParam password string required The user's password. Example: password123
 *
 * @response 200 {
     *   "message": "Login successful",
     *   "user": {
     *     "id": 1,
     *     "name": "John Doe",
     *     "email": "john@example.com"
     *   },
     *   "token": "1|abc123def456..."
     * }
     *
     * @response 401 {
     *   "message": "Invalid credentials"
     * }
     */
public function login(Request $request)
{
    // Your login logic
}
```

### 3. Protected Route Documentation

```php
/**
 * Get User Profile
 *
 * Retrieve the authenticated user's profile information.
 *
 * @authenticated
 *
 * @response 200 {
     *   "user": {
     *     "id": 1,
     *     "name": "John Doe",
     *     "email": "john@example.com",
     *     "date_of_birth": "1990-01-01",
     *     "gender": "male",
     *     "location": "New York, USA",
     *     "bio": "I'm looking for a life partner...",
     *     "preferences": {
     *       "min_age": 25,
     *       "max_age": 35,
     *       "preferred_gender": "female",
     *       "location_preference": "New York"
     *     }
     *   }
     * }
     *
     * @response 401 {
     *   "message": "Unauthenticated."
     * }
     */
public function profile()
{
    // Your profile logic
}
```

### 4. Query Parameters Documentation

```php
/**
 * Get Recommendations
 *
 * Get personalized match recommendations for the authenticated user.
 *
 * @authenticated
 *
 * @queryParam page integer The page number for pagination. Example: 1
 * @queryParam per_page integer Number of items per page (max 20). Example: 10
 * @queryParam min_age integer Filter by minimum age. Example: 25
 * @queryParam max_age integer Filter by maximum age. Example: 35
 * @queryParam gender string Filter by gender. Example: female
 * @queryParam location string Filter by location. Example: New York
 *
 * @response 200 {
     *   "data": [
     *     {
     *       "id": 2,
     *       "name": "Jane Smith",
     *       "age": 28,
     *       "location": "New York, USA",
     *       "bio": "Looking for someone special...",
     *       "compatibility_score": 85,
     *       "photos": ["photo1.jpg", "photo2.jpg"]
     *     }
     *   ],
     *   "current_page": 1,
     *   "per_page": 10,
     *   "total": 50
     * }
     */
public function getRecommendations(Request $request)
{
    // Your recommendations logic
}
```

## Advanced Documentation Features

### 1. Response Examples with Different Status Codes

```php
/**
 * @response 200 {
     *   "success": true,
     *   "data": {...}
     * }
     *
     * @response 400 {
     *   "success": false,
     *   "message": "Bad request"
     * }
     *
     * @response 404 {
     *   "success": false,
     *   "message": "Resource not found"
     * }
     *
     * @response 500 {
     *   "success": false,
     *   "message": "Internal server error"
     * }
     */
```

### 2. File Upload Documentation

```php
/**
 * Upload Profile Photo
 *
 * @bodyParam photo file required The profile photo (max 5MB, jpg/png). Example: photo.jpg
 *
 * @response 200 {
     *   "message": "Photo uploaded successfully",
     *   "photo_url": "https://example.com/photos/photo.jpg"
     * }
     */
public function uploadPhoto(Request $request)
{
    // Your upload logic
}
```

### 3. Grouping Routes

```php
/**
 * @group User Management
 *
 * APIs for managing user profiles and preferences
 */
class UserController extends Controller
{
    // Your controller methods
}

/**
 * @group Matching
 *
 * APIs for matchmaking and recommendations
 */
class MatchController extends Controller
{
    // Your controller methods
}
```

## Generating and Viewing Documentation

### 1. Generate Documentation

```bash
# Generate documentation
php artisan scribe:generate

# Generate with specific config
php artisan scribe:generate --config=scribe.php

# Generate for specific routes
php artisan scribe:generate --routes=api/*
```

### 2. View Documentation

After generation, you can view the documentation at:

-   **HTML**: `http://your-app.com/docs`
-   **JSON**: `http://your-app.com/docs.json`

### 3. Customize Output

```bash
# Generate only HTML
php artisan scribe:generate --type=html

# Generate only JSON
php artisan scribe:generate --type=json

# Generate only Postman collection
php artisan scribe:generate --type=postman
```

## Testing with LRD

### 1. Interactive Testing

Once you generate the documentation, you can:

1. Visit `http://your-app.com/docs`
2. Click on any endpoint
3. Fill in the required parameters
4. Click "Try it out" to test the API

### 2. Authentication Testing

```php
// In your .env file
SCRIBE_AUTH_KEY=your-test-token-here

// Or set it in the documentation interface
```

### 3. Example Request/Response

```php
/**
 * @example {
     *   "name": "John Doe",
     *   "email": "john@example.com",
     *   "password": "password123",
     *   "password_confirmation": "password123",
     *   "date_of_birth": "1990-01-01",
     *   "gender": "male",
     *   "location": "New York, USA"
     * }
     */
```

## Best Practices

### 1. Consistent Documentation

```php
// Always include:
// - @group for organization
// - @bodyParam for request parameters
// - @queryParam for query parameters
// - @response for response examples
// - @authenticated for protected routes
```

### 2. Clear Examples

```php
// Use realistic examples
@bodyParam email string required The user's email address. Example: john.doe@example.com

// Include validation rules
@bodyParam password string required The user's password (min 8 characters, must contain letters and numbers). Example: MySecurePass123
```

### 3. Error Handling

```php
// Always document error responses
@response 422 {
    "message": "The given data was invalid.",
    "errors": {
        "email": ["The email has already been taken."],
        "password": ["The password must be at least 8 characters."]
    }
}
```

## Integration with Your Matrimonial API

### 1. Update Your Controllers

Add documentation to your existing controllers:

```php
// In AuthController
/**
 * @group Authentication
 *
 * APIs for managing user authentication
 */
class AuthController extends Controller
{
    // Add @bodyParam, @response annotations to your methods
}

// In UserController
/**
 * @group User Management
 *
 * APIs for managing user profiles and preferences
 */
class UserController extends Controller
{
    // Add documentation to your methods
}
```

### 2. Generate Documentation

```bash
php artisan scribe:generate
```

### 3. Test Your APIs

Visit `http://localhost:8000/docs` to test your matrimonial API endpoints interactively.

## Troubleshooting

### Common Issues

1. **Routes not showing up**: Check your `config/scribe.php` routes configuration
2. **Authentication not working**: Verify your auth configuration and token
3. **Response examples not showing**: Make sure you have proper `@response` annotations

### Debug Commands

```bash
# Check what routes Scribe can see
php artisan scribe:generate --verbose

# Clear cache and regenerate
php artisan config:clear
php artisan route:clear
php artisan scribe:generate
```

This guide will help you create comprehensive, interactive API documentation for your matrimonial application using Laravel Request Docs!
