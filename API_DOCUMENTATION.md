# Matrimonial API - Complete Documentation

## 🚀 Quick Start Guide

### Prerequisites

-   PHP 8.2 or higher
-   Composer
-   MySQL/SQLite database
-   Laravel 12.x

### Local Setup Instructions

1. **Clone and Install**

```bash
git clone <repository-url>
cd matrimonial-api
composer install
```

2. **Environment Setup**

```bash
cp .env.example .env
php artisan key:generate
```

3. **Database Configuration**

```env
# For SQLite (default)
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite

# For MySQL
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=matrimonial_api
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

4. **Run Migrations and Seeders**

```bash
php artisan migrate
php artisan db:seed
```

5. **Start Development Server**

```bash
php artisan serve
```

The API will be available at: `http://127.0.0.1:8000`

## 📋 API Endpoints Overview

### Authentication Endpoints

-   `POST /api/register` - Register new user
-   `POST /api/login` - User login
-   `POST /api/logout` - User logout
-   `GET /api/user` - Get authenticated user

### Profile Management

-   `GET /api/profile` - Get user profile
-   `PUT /api/profile` - Update user profile
-   `POST /api/profile/preferences` - Update preferences

### Matchmaking

-   `GET /api/recommendations` - Get matchmaking recommendations

### Matches

-   `POST /api/matches/{user}` - Create match
-   `GET /api/matches` - Get all matches
-   `DELETE /api/matches/{user}` - Remove match

### Likes

-   `POST /api/likes/{user}` - Like user
-   `DELETE /api/likes/{user}` - Unlike user
-   `GET /api/likes` - Get likes

### Messages

-   `POST /api/messages/send` - Send message
-   `GET /api/messages/{user}` - Get chat history
-   `GET /api/messages` - Get conversations

---

## 🔐 Authentication Endpoints

### 1. Register User

**Endpoint:** `POST /api/register`

**Description:** Register a new user with matrimonial profile

**Request Body:**

```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "age": 28,
    "gender": "male",
    "religion": "Hindu",
    "caste": "Brahmin",
    "income": 800000,
    "education": "Masters",
    "location": "Mumbai",
    "occupation": "Software Engineer",
    "bio": "Looking for a life partner"
}
```

**Response (201):**

```json
{
    "message": "User registered successfully",
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "age": 28,
        "gender": "male",
        "religion": "Hindu",
        "caste": "Brahmin",
        "income": 800000,
        "education": "Masters",
        "location": "Mumbai",
        "occupation": "Software Engineer",
        "bio": "Looking for a life partner",
        "created_at": "2025-07-17T17:00:00.000000Z",
        "updated_at": "2025-07-17T17:00:00.000000Z"
    },
    "token": "1|abc123def456..."
}
```

**cURL Example:**

```bash
curl -X POST http://127.0.0.1:8000/api/register \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "age": 28,
    "gender": "male",
    "religion": "Hindu",
    "caste": "Brahmin",
    "income": 800000,
    "education": "Masters",
    "location": "Mumbai",
    "occupation": "Software Engineer",
    "bio": "Looking for a life partner"
  }'
```

### 2. Login User

**Endpoint:** `POST /api/login`

**Description:** Authenticate user and get access token

**Request Body:**

```json
{
    "email": "john@example.com",
    "password": "password123"
}
```

**Response (200):**

```json
{
    "message": "Login successful",
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "age": 28,
        "gender": "male",
        "religion": "Hindu",
        "caste": "Brahmin",
        "income": 800000,
        "education": "Masters",
        "location": "Mumbai",
        "occupation": "Software Engineer",
        "bio": "Looking for a life partner"
    },
    "token": "1|abc123def456..."
}
```

**cURL Example:**

```bash
curl -X POST http://127.0.0.1:8000/api/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "john@example.com",
    "password": "password123"
  }'
```

### 3. Logout User

**Endpoint:** `POST /api/logout`

**Description:** Invalidate current access token

**Headers:** `Authorization: Bearer {token}`

**Response (200):**

```json
{
    "message": "Logged out successfully"
}
```

**cURL Example:**

```bash
curl -X POST http://127.0.0.1:8000/api/logout \
  -H "Authorization: Bearer 1|abc123def456..." \
  -H "Accept: application/json"
```

### 4. Get Authenticated User

**Endpoint:** `GET /api/user`

**Description:** Get current authenticated user details

**Headers:** `Authorization: Bearer {token}`

**Response (200):**

```json
{
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "age": 28,
        "gender": "male",
        "religion": "Hindu",
        "caste": "Brahmin",
        "income": 800000,
        "education": "Masters",
        "location": "Mumbai",
        "occupation": "Software Engineer",
        "bio": "Looking for a life partner",
        "preferences": {
            "id": 1,
            "user_id": 1,
            "preferred_age_min": 25,
            "preferred_age_max": 35,
            "preferred_gender": "female",
            "preferred_religion": "Hindu",
            "preferred_caste": "Brahmin",
            "preferred_income_min": 600000,
            "preferred_income_max": 1200000,
            "preferred_education": "Masters",
            "preferred_location": "Mumbai",
            "preferred_occupation": "Software Engineer",
            "age_weight": 1.0,
            "gender_weight": 1.0,
            "religion_weight": 1.0,
            "caste_weight": 1.0,
            "income_weight": 1.0,
            "education_weight": 1.0,
            "location_weight": 1.0,
            "occupation_weight": 1.0
        }
    }
}
```

**cURL Example:**

```bash
curl -X GET http://127.0.0.1:8000/api/user \
  -H "Authorization: Bearer 1|abc123def456..." \
  -H "Accept: application/json"
```

---

## 👤 Profile Management Endpoints

### 1. Get Profile

**Endpoint:** `GET /api/profile`

**Description:** Get user profile with preferences

**Headers:** `Authorization: Bearer {token}`

**Response (200):**

```json
{
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "age": 28,
        "gender": "male",
        "religion": "Hindu",
        "caste": "Brahmin",
        "income": 800000,
        "education": "Masters",
        "location": "Mumbai",
        "occupation": "Software Engineer",
        "bio": "Looking for a life partner",
        "preferences": {
            "id": 1,
            "user_id": 1,
            "preferred_age_min": 25,
            "preferred_age_max": 35,
            "preferred_gender": "female",
            "preferred_religion": "Hindu",
            "preferred_caste": "Brahmin",
            "preferred_income_min": 600000,
            "preferred_income_max": 1200000,
            "preferred_education": "Masters",
            "preferred_location": "Mumbai",
            "preferred_occupation": "Software Engineer",
            "age_weight": 1.0,
            "gender_weight": 1.0,
            "religion_weight": 1.0,
            "caste_weight": 1.0,
            "income_weight": 1.0,
            "education_weight": 1.0,
            "location_weight": 1.0,
            "occupation_weight": 1.0
        }
    }
}
```

### 2. Update Profile

**Endpoint:** `PUT /api/profile`

**Description:** Update user profile information

**Headers:** `Authorization: Bearer {token}`

**Request Body:**

```json
{
    "name": "John Doe Updated",
    "age": 29,
    "bio": "Updated bio information",
    "location": "Delhi",
    "occupation": "Senior Software Engineer"
}
```

**Response (200):**

```json
{
    "message": "Profile updated successfully",
    "user": {
        "id": 1,
        "name": "John Doe Updated",
        "email": "john@example.com",
        "age": 29,
        "gender": "male",
        "religion": "Hindu",
        "caste": "Brahmin",
        "income": 800000,
        "education": "Masters",
        "location": "Delhi",
        "occupation": "Senior Software Engineer",
        "bio": "Updated bio information"
    }
}
```

**cURL Example:**

```bash
curl -X PUT http://127.0.0.1:8000/api/profile \
  -H "Authorization: Bearer 1|abc123def456..." \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "John Doe Updated",
    "age": 29,
    "bio": "Updated bio information",
    "location": "Delhi",
    "occupation": "Senior Software Engineer"
  }'
```

### 3. Update Preferences

**Endpoint:** `POST /api/profile/preferences`

**Description:** Update user matchmaking preferences

**Headers:** `Authorization: Bearer {token}`

**Request Body:**

```json
{
    "preferred_age_min": 25,
    "preferred_age_max": 35,
    "preferred_gender": "female",
    "preferred_religion": "Hindu",
    "preferred_caste": "Brahmin",
    "preferred_income_min": 600000,
    "preferred_income_max": 1200000,
    "preferred_education": "Masters",
    "preferred_location": "Mumbai",
    "preferred_occupation": "Software Engineer",
    "age_weight": 1.5,
    "religion_weight": 2.0,
    "caste_weight": 1.8,
    "income_weight": 1.2,
    "education_weight": 1.0,
    "location_weight": 1.3,
    "occupation_weight": 1.1
}
```

**Response (200):**

```json
{
    "message": "Preferences updated successfully",
    "preferences": {
        "id": 1,
        "user_id": 1,
        "preferred_age_min": 25,
        "preferred_age_max": 35,
        "preferred_gender": "female",
        "preferred_religion": "Hindu",
        "preferred_caste": "Brahmin",
        "preferred_income_min": 600000,
        "preferred_income_max": 1200000,
        "preferred_education": "Masters",
        "preferred_location": "Mumbai",
        "preferred_occupation": "Software Engineer",
        "age_weight": 1.5,
        "gender_weight": 1.0,
        "religion_weight": 2.0,
        "caste_weight": 1.8,
        "income_weight": 1.2,
        "education_weight": 1.0,
        "location_weight": 1.3,
        "occupation_weight": 1.1
    }
}
```

**cURL Example:**

```bash
curl -X POST http://127.0.0.1:8000/api/profile/preferences \
  -H "Authorization: Bearer 1|abc123def456..." \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "preferred_age_min": 25,
    "preferred_age_max": 35,
    "preferred_gender": "female",
    "preferred_religion": "Hindu",
    "preferred_caste": "Brahmin",
    "preferred_income_min": 600000,
    "preferred_income_max": 1200000,
    "preferred_education": "Masters",
    "preferred_location": "Mumbai",
    "preferred_occupation": "Software Engineer",
    "age_weight": 1.5,
    "religion_weight": 2.0,
    "caste_weight": 1.8,
    "income_weight": 1.2,
    "education_weight": 1.0,
    "location_weight": 1.3,
    "occupation_weight": 1.1
  }'
```

---

## 🧑‍🤝‍🧑 Matchmaking Endpoints

### 1. Get Recommendations

**Endpoint:** `GET /api/recommendations`

**Description:** Get matchmaking recommendations using collaborative filtering

**Headers:** `Authorization: Bearer {token}`

**Query Parameters:**

-   `limit` (optional): Number of recommendations (default: 10, max: 50)

**Response (200):**

```json
{
    "recommendations": [
        {
            "user": {
                "id": 2,
                "name": "Jane Doe",
                "email": "jane@example.com",
                "age": 26,
                "gender": "female",
                "religion": "Hindu",
                "caste": "Brahmin",
                "income": 700000,
                "education": "Masters",
                "location": "Mumbai",
                "occupation": "Data Scientist",
                "bio": "Looking for a caring partner"
            },
            "score": 0.85,
            "compatibility_percentage": 85
        },
        {
            "user": {
                "id": 3,
                "name": "Priya Sharma",
                "email": "priya@example.com",
                "age": 25,
                "gender": "female",
                "religion": "Hindu",
                "caste": "Brahmin",
                "income": 500000,
                "education": "Masters",
                "location": "Mumbai",
                "occupation": "Software Engineer",
                "bio": "Looking for a caring and understanding partner"
            },
            "score": 0.78,
            "compatibility_percentage": 78
        }
    ],
    "total": 2
}
```

**cURL Example:**

```bash
curl -X GET "http://127.0.0.1:8000/api/recommendations?limit=5" \
  -H "Authorization: Bearer 1|abc123def456..." \
  -H "Accept: application/json"
```

---

## 💕 Match Management Endpoints

### 1. Create Match

**Endpoint:** `POST /api/matches/{user}`

**Description:** Create a match with another user

**Headers:** `Authorization: Bearer {token}`

**Response (201):**

```json
{
    "message": "Match created successfully",
    "matched_user": {
        "id": 2,
        "name": "Jane Doe",
        "email": "jane@example.com",
        "age": 26,
        "gender": "female",
        "religion": "Hindu",
        "caste": "Brahmin",
        "income": 700000,
        "education": "Masters",
        "location": "Mumbai",
        "occupation": "Data Scientist"
    }
}
```

**cURL Example:**

```bash
curl -X POST http://127.0.0.1:8000/api/matches/2 \
  -H "Authorization: Bearer 1|abc123def456..." \
  -H "Accept: application/json"
```

### 2. Get Matches

**Endpoint:** `GET /api/matches`

**Description:** Get all matches for the authenticated user

**Headers:** `Authorization: Bearer {token}`

**Response (200):**

```json
{
    "matches": [
        {
            "id": 2,
            "name": "Jane Doe",
            "email": "jane@example.com",
            "age": 26,
            "gender": "female",
            "religion": "Hindu",
            "caste": "Brahmin",
            "income": 700000,
            "education": "Masters",
            "location": "Mumbai",
            "occupation": "Data Scientist",
            "bio": "Looking for a caring partner",
            "preferences": {
                "id": 2,
                "user_id": 2,
                "preferred_age_min": 25,
                "preferred_age_max": 35,
                "preferred_gender": "male",
                "preferred_religion": "Hindu",
                "preferred_caste": "Brahmin",
                "preferred_income_min": 600000,
                "preferred_income_max": 1200000,
                "preferred_education": "Masters",
                "preferred_location": "Mumbai",
                "preferred_occupation": "Software Engineer"
            }
        }
    ],
    "total": 1
}
```

**cURL Example:**

```bash
curl -X GET http://127.0.0.1:8000/api/matches \
  -H "Authorization: Bearer 1|abc123def456..." \
  -H "Accept: application/json"
```

### 3. Remove Match

**Endpoint:** `DELETE /api/matches/{user}`

**Description:** Remove a match with another user

**Headers:** `Authorization: Bearer {token}`

**Response (200):**

```json
{
    "message": "Match removed successfully"
}
```

**cURL Example:**

```bash
curl -X DELETE http://127.0.0.1:8000/api/matches/2 \
  -H "Authorization: Bearer 1|abc123def456..." \
  -H "Accept: application/json"
```

---

## ❤️ Like System Endpoints

### 1. Like User

**Endpoint:** `POST /api/likes/{user}`

**Description:** Like a user (creates mutual match if both users like each other)

**Headers:** `Authorization: Bearer {token}`

**Response (201):**

```json
{
    "message": "User liked successfully",
    "is_match": true,
    "liked_user": {
        "id": 2,
        "name": "Jane Doe",
        "email": "jane@example.com",
        "age": 26,
        "gender": "female",
        "religion": "Hindu",
        "caste": "Brahmin",
        "income": 700000,
        "education": "Masters",
        "location": "Mumbai",
        "occupation": "Data Scientist"
    }
}
```

**cURL Example:**

```bash
curl -X POST http://127.0.0.1:8000/api/likes/2 \
  -H "Authorization: Bearer 1|abc123def456..." \
  -H "Accept: application/json"
```

### 2. Unlike User

**Endpoint:** `DELETE /api/likes/{user}`

**Description:** Remove like for a user

**Headers:** `Authorization: Bearer {token}`

**Response (200):**

```json
{
    "message": "User unliked successfully"
}
```

**cURL Example:**

```bash
curl -X DELETE http://127.0.0.1:8000/api/likes/2 \
  -H "Authorization: Bearer 1|abc123def456..." \
  -H "Accept: application/json"
```

### 3. Get Likes

**Endpoint:** `GET /api/likes`

**Description:** Get all likes and users who liked the authenticated user

**Headers:** `Authorization: Bearer {token}`

**Response (200):**

```json
{
    "likes": [
        {
            "id": 2,
            "name": "Jane Doe",
            "email": "jane@example.com",
            "age": 26,
            "gender": "female",
            "religion": "Hindu",
            "caste": "Brahmin",
            "income": 700000,
            "education": "Masters",
            "location": "Mumbai",
            "occupation": "Data Scientist",
            "bio": "Looking for a caring partner"
        }
    ],
    "liked_by": [
        {
            "id": 3,
            "name": "Priya Sharma",
            "email": "priya@example.com",
            "age": 25,
            "gender": "female",
            "religion": "Hindu",
            "caste": "Brahmin",
            "income": 500000,
            "education": "Masters",
            "location": "Mumbai",
            "occupation": "Software Engineer",
            "bio": "Looking for a caring and understanding partner"
        }
    ],
    "total_likes": 1,
    "total_liked_by": 1
}
```

**cURL Example:**

```bash
curl -X GET http://127.0.0.1:8000/api/likes \
  -H "Authorization: Bearer 1|abc123def456..." \
  -H "Accept: application/json"
```

---

## 💬 Chat System Endpoints

### 1. Send Message

**Endpoint:** `POST /api/messages/send`

**Description:** Send a message to a matched user

**Headers:** `Authorization: Bearer {token}`

**Request Body:**

```json
{
    "to_user_id": 2,
    "message": "Hello! How are you? Nice to meet you."
}
```

**Response (201):**

```json
{
    "message": "Message sent successfully",
    "data": {
        "id": 1,
        "from_user_id": 1,
        "to_user_id": 2,
        "message": "Hello! How are you? Nice to meet you.",
        "is_read": false,
        "created_at": "2025-07-17T17:00:00.000000Z",
        "updated_at": "2025-07-17T17:00:00.000000Z",
        "sender": {
            "id": 1,
            "name": "John Doe"
        },
        "recipient": {
            "id": 2,
            "name": "Jane Doe"
        }
    }
}
```

**cURL Example:**

```bash
curl -X POST http://127.0.0.1:8000/api/messages/send \
  -H "Authorization: Bearer 1|abc123def456..." \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "to_user_id": 2,
    "message": "Hello! How are you? Nice to meet you."
  }'
```

### 2. Get Chat History

**Endpoint:** `GET /api/messages/{user}`

**Description:** Get chat history with a specific matched user

**Headers:** `Authorization: Bearer {token}`

**Response (200):**

```json
{
    "messages": [
        {
            "id": 1,
            "from_user_id": 1,
            "to_user_id": 2,
            "message": "Hello! How are you?",
            "is_read": true,
            "created_at": "2025-07-17T17:00:00.000000Z",
            "updated_at": "2025-07-17T17:00:00.000000Z",
            "sender": {
                "id": 1,
                "name": "John Doe"
            },
            "recipient": {
                "id": 2,
                "name": "Jane Doe"
            }
        },
        {
            "id": 2,
            "from_user_id": 2,
            "to_user_id": 1,
            "message": "Hi! I'm doing great, thanks for asking!",
            "is_read": false,
            "created_at": "2025-07-17T17:05:00.000000Z",
            "updated_at": "2025-07-17T17:05:00.000000Z",
            "sender": {
                "id": 2,
                "name": "Jane Doe"
            },
            "recipient": {
                "id": 1,
                "name": "John Doe"
            }
        }
    ],
    "total": 2
}
```

**cURL Example:**

```bash
curl -X GET http://127.0.0.1:8000/api/messages/2 \
  -H "Authorization: Bearer 1|abc123def456..." \
  -H "Accept: application/json"
```

### 3. Get Conversations

**Endpoint:** `GET /api/messages`

**Description:** Get all conversations for the authenticated user

**Headers:** `Authorization: Bearer {token}`

**Response (200):**

```json
{
    "conversations": [
        {
            "user": {
                "id": 2,
                "name": "Jane Doe",
                "email": "jane@example.com",
                "age": 26,
                "gender": "female",
                "religion": "Hindu",
                "caste": "Brahmin",
                "income": 700000,
                "education": "Masters",
                "location": "Mumbai",
                "occupation": "Data Scientist",
                "bio": "Looking for a caring partner"
            },
            "last_message": {
                "id": 2,
                "from_user_id": 2,
                "to_user_id": 1,
                "message": "Hi! I'm doing great, thanks for asking!",
                "is_read": false,
                "created_at": "2025-07-17T17:05:00.000000Z",
                "updated_at": "2025-07-17T17:05:00.000000Z"
            },
            "unread_count": 1
        }
    ],
    "total": 1
}
```

**cURL Example:**

```bash
curl -X GET http://127.0.0.1:8000/api/messages \
  -H "Authorization: Bearer 1|abc123def456..." \
  -H "Accept: application/json"
```

---

## 🧪 Testing the API

### Using Postman

1. **Import Collection**

    - Create a new collection in Postman
    - Add the base URL: `http://127.0.0.1:8000`
    - Set up environment variables for `token`

2. **Test Flow**
    - Register a new user
    - Login to get token
    - Use token in Authorization header for subsequent requests
    - Test all endpoints

### Using cURL

**Complete Test Flow:**

```bash
# 1. Register a user
curl -X POST http://127.0.0.1:8000/api/register \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "Test User",
    "email": "test@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "age": 28,
    "gender": "male",
    "religion": "Hindu",
    "caste": "Brahmin",
    "income": 800000,
    "education": "Masters",
    "location": "Mumbai",
    "occupation": "Software Engineer",
    "bio": "Looking for a life partner"
  }'

# 2. Login to get token
curl -X POST http://127.0.0.1:8000/api/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "test@example.com",
    "password": "password123"
  }'

# 3. Get recommendations (use token from login response)
curl -X GET http://127.0.0.1:8000/api/recommendations \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Accept: application/json"

# 4. Like a user
curl -X POST http://127.0.0.1:8000/api/likes/2 \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Accept: application/json"

# 5. Send a message (if matched)
curl -X POST http://127.0.0.1:8000/api/messages/send \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "to_user_id": 2,
    "message": "Hello! Nice to meet you."
  }'
```

### Using PowerShell

**PowerShell Test Script:**

```powershell
# 1. Register user
$registerBody = @{
    name = "Test User"
    email = "test@example.com"
    password = "password123"
    password_confirmation = "password123"
    age = 28
    gender = "male"
    religion = "Hindu"
    caste = "Brahmin"
    income = 800000
    education = "Masters"
    location = "Mumbai"
    occupation = "Software Engineer"
    bio = "Looking for a life partner"
} | ConvertTo-Json

$registerResponse = Invoke-WebRequest -Uri "http://127.0.0.1:8000/api/register" -Method POST -Headers @{"Content-Type"="application/json"; "Accept"="application/json"} -Body $registerBody

# 2. Login
$loginBody = @{
    email = "test@example.com"
    password = "password123"
} | ConvertTo-Json

$loginResponse = Invoke-WebRequest -Uri "http://127.0.0.1:8000/api/login" -Method POST -Headers @{"Content-Type"="application/json"; "Accept"="application/json"} -Body $loginBody

# Extract token from login response
$loginData = $loginResponse.Content | ConvertFrom-Json
$token = $loginData.token

# 3. Get recommendations
$recommendationsResponse = Invoke-WebRequest -Uri "http://127.0.0.1:8000/api/recommendations" -Method GET -Headers @{"Authorization"="Bearer $token"; "Accept"="application/json"}

Write-Host "Recommendations:"
Write-Host $recommendationsResponse.Content
```

---

## 📊 Sample Data

The application comes with 10 pre-seeded users:

1. **Priya Sharma** (25, Female, Hindu, Brahmin) - Software Engineer
2. **Rahul Patel** (28, Male, Hindu, Patel) - Business Analyst
3. **Aisha Khan** (24, Female, Muslim, Sunni) - Teacher
4. **Amit Singh** (30, Male, Sikh, Jat) - Doctor
5. **Neha Gupta** (26, Female, Hindu, Agarwal) - Data Scientist
6. **Vikram Malhotra** (29, Male, Hindu, Khatri) - Architect
7. **Fatima Ali** (23, Female, Muslim, Shia) - Designer
8. **Rajesh Kumar** (27, Male, Hindu, Yadav) - Marketing Manager
9. **Sneha Reddy** (25, Female, Hindu, Reddy) - HR Manager
10. **Arjun Mehta** (31, Male, Hindu, Brahmin) - Investment Banker

**Default Login Credentials:**

-   Email: `priya@example.com` / Password: `password123`
-   Email: `rahul@example.com` / Password: `password123`
-   Email: `aisha@example.com` / Password: `password123`
-   (and so on for all users)

---

## 🔧 Error Handling

### Common HTTP Status Codes

-   **200** - Success
-   **201** - Created
-   **400** - Bad Request (validation errors)
-   **401** - Unauthorized (invalid token)
-   **403** - Forbidden (insufficient permissions)
-   **404** - Not Found
-   **422** - Validation Error
-   **500** - Server Error

### Error Response Format

```json
{
    "message": "The given data was invalid.",
    "errors": {
        "email": ["The email field is required."],
        "password": ["The password field is required."]
    }
}
```

---

## 🚀 Production Deployment

### Environment Variables

```env
APP_NAME="Matrimonial API"
APP_ENV=production
APP_KEY=base64:your-generated-key
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=your-db-host
DB_PORT=3306
DB_DATABASE=matrimonial_api
DB_USERNAME=your-db-username
DB_PASSWORD=your-db-password

CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis
```

### Performance Optimization

1. **Caching**: Implement Redis for recommendations
2. **Database**: Add indexes for frequently queried fields
3. **CDN**: Use CDN for static assets
4. **Load Balancing**: Set up load balancer for high traffic

---

## 📝 Notes

-   All API responses are in JSON format
-   Authentication is required for all endpoints except register and login
-   Use `Accept: application/json` header for proper API responses
-   Token expires after 24 hours (configurable)
-   Messages can only be sent between matched users
-   Collaborative filtering improves recommendations over time

---

## 🆘 Support

For issues or questions:

1. Check the Laravel logs: `storage/logs/laravel.log`
2. Run tests: `php artisan test`
3. Clear cache: `php artisan cache:clear`
4. Check database: `php artisan migrate:status`

The API is now ready for production use with comprehensive documentation and testing capabilities!
