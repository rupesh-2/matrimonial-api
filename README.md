# Matrimonial API

A comprehensive Laravel REST API for a matrimonial application with advanced matchmaking using collaborative filtering.

## Features

🔐 **Authentication**

-   Laravel Sanctum token-based authentication
-   Secure user registration and login
-   Protected API endpoints

🧑‍🤝‍🧑 **Advanced Matchmaking**

-   Collaborative filtering algorithm
-   Weighted scoring based on user preferences
-   Configurable preference weights
-   Age, gender, religion, caste, income, education, location, and occupation matching

💬 **Chat System**

-   Real-time messaging between matched users
-   Chat history and conversation management
-   Unread message tracking

❤️ **Like System**

-   User likes and mutual matching
-   Like-based collaborative filtering
-   User behavior analysis

## Technology Stack

-   **Backend**: Laravel 12.x
-   **Authentication**: Laravel Sanctum
-   **Database**: MySQL
-   **API**: RESTful API
-   **Algorithm**: Collaborative Filtering

## Installation

1. **Clone the repository**

```bash
git clone <repository-url>
cd matrimonial-api
```

2. **Install dependencies**

```bash
composer install
```

3. **Environment setup**

```bash
cp .env.example .env
php artisan key:generate
```

4. **Configure database**
   Update your `.env` file with your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=matrimonial_api
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

5. **Run migrations and seeders**

```bash
php artisan migrate
php artisan db:seed
```

6. **Start the server**

```bash
php artisan serve
```

## API Endpoints

### Authentication

#### Register User

```http
POST /api/register
Content-Type: application/json

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

#### Login

```http
POST /api/login
Content-Type: application/json

{
    "email": "john@example.com",
    "password": "password123"
}
```

#### Logout

```http
POST /api/logout
Authorization: Bearer {token}
```

#### Get User Profile

```http
GET /api/user
Authorization: Bearer {token}
```

### User Profile

#### Get Profile

```http
GET /api/profile
Authorization: Bearer {token}
```

#### Update Profile

```http
PUT /api/profile
Authorization: Bearer {token}
Content-Type: application/json

{
    "name": "John Doe",
    "age": 29,
    "bio": "Updated bio"
}
```

#### Update Preferences

```http
POST /api/profile/preferences
Authorization: Bearer {token}
Content-Type: application/json

{
    "preferred_age_min": 25,
    "preferred_age_max": 35,
    "preferred_gender": "female",
    "preferred_religion": "Hindu",
    "age_weight": 1.5,
    "religion_weight": 2.0
}
```

### Matchmaking

#### Get Recommendations

```http
GET /api/recommendations?limit=10
Authorization: Bearer {token}
```

Response:

```json
{
    "recommendations": [
        {
            "user": {
                "id": 2,
                "name": "Jane Doe",
                "age": 26,
                "gender": "female",
                "religion": "Hindu",
                "caste": "Brahmin",
                "income": 600000,
                "education": "Masters",
                "location": "Mumbai",
                "occupation": "Data Scientist",
                "bio": "Looking for a caring partner"
            },
            "score": 0.85,
            "compatibility_percentage": 85
        }
    ],
    "total": 1
}
```

### Matches

#### Create Match

```http
POST /api/matches/{user_id}
Authorization: Bearer {token}
```

#### Get Matches

```http
GET /api/matches
Authorization: Bearer {token}
```

#### Remove Match

```http
DELETE /api/matches/{user_id}
Authorization: Bearer {token}
```

### Likes

#### Like User

```http
POST /api/likes/{user_id}
Authorization: Bearer {token}
```

#### Unlike User

```http
DELETE /api/likes/{user_id}
Authorization: Bearer {token}
```

#### Get Likes

```http
GET /api/likes
Authorization: Bearer {token}
```

### Messages

#### Send Message

```http
POST /api/messages/send
Authorization: Bearer {token}
Content-Type: application/json

{
    "to_user_id": 2,
    "message": "Hello! How are you?"
}
```

#### Get Chat History

```http
GET /api/messages/{user_id}
Authorization: Bearer {token}
```

#### Get Conversations

```http
GET /api/messages
Authorization: Bearer {token}
```

## Database Schema

### Users Table

-   `id` - Primary key
-   `name` - User's full name
-   `email` - Unique email address
-   `password` - Hashed password
-   `age` - User's age
-   `gender` - Gender (male/female/other)
-   `religion` - Religious background
-   `caste` - Caste information
-   `income` - Annual income
-   `education` - Education level
-   `location` - City/location
-   `occupation` - Job title
-   `bio` - Personal description
-   `profile_picture` - Profile image URL
-   `created_at`, `updated_at` - Timestamps

### Preferences Table

-   `id` - Primary key
-   `user_id` - Foreign key to users
-   `preferred_age_min/max` - Age range preferences
-   `preferred_gender` - Gender preference
-   `preferred_religion/caste` - Religious/caste preferences
-   `preferred_income_min/max` - Income range preferences
-   `preferred_education/location/occupation` - Other preferences
-   `*_weight` - Weight for each preference (0-10)

### Matches Table

-   `id` - Primary key
-   `user_id` - First user
-   `matched_user_id` - Second user
-   `created_at`, `updated_at` - Timestamps

### Likes Table

-   `id` - Primary key
-   `user_id` - User who liked
-   `liked_user_id` - User who was liked
-   `created_at`, `updated_at` - Timestamps

### Messages Table

-   `id` - Primary key
-   `from_user_id` - Sender
-   `to_user_id` - Recipient
-   `message` - Message content
-   `is_read` - Read status
-   `created_at`, `updated_at` - Timestamps

## Matchmaking Algorithm

The application uses a sophisticated collaborative filtering algorithm with the following components:

### 1. Weighted Scoring

Each user preference has a configurable weight (0-10):

-   Age compatibility
-   Gender preference
-   Religion matching
-   Caste compatibility
-   Income range
-   Education level
-   Location preference
-   Occupation matching

### 2. Collaborative Filtering

-   Analyzes user behavior (likes)
-   Finds users with similar preferences
-   Boosts recommendations based on mutual likes
-   Learns from user interactions

### 3. Scoring Formula

```
Total Score = (Σ(Attribute Score × Weight) + Collaborative Bonus) / Total Weight
```

## Sample Data

The seeder creates 10 sample users with diverse profiles:

-   Priya Sharma (25, Female, Hindu, Brahmin)
-   Rahul Patel (28, Male, Hindu, Patel)
-   Aisha Khan (24, Female, Muslim, Sunni)
-   Amit Singh (30, Male, Sikh, Jat)
-   Neha Gupta (26, Female, Hindu, Agarwal)
-   Vikram Malhotra (29, Male, Hindu, Khatri)
-   Fatima Ali (23, Female, Muslim, Shia)
-   Rajesh Kumar (27, Male, Hindu, Yadav)
-   Sneha Reddy (25, Female, Hindu, Reddy)
-   Arjun Mehta (31, Male, Hindu, Brahmin)

## Testing

Run the test suite:

```bash
php artisan test
```

## Sample API Usage

### 1. Register and Login

```bash
# Register
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Test User",
    "email": "test@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "age": 28,
    "gender": "male"
  }'

# Login
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "test@example.com",
    "password": "password123"
  }'
```

### 2. Get Recommendations

```bash
curl -X GET http://localhost:8000/api/recommendations \
  -H "Authorization: Bearer {your_token}"
```

### 3. Like a User

```bash
curl -X POST http://localhost:8000/api/likes/2 \
  -H "Authorization: Bearer {your_token}"
```

### 4. Send Message

```bash
curl -X POST http://localhost:8000/api/messages/send \
  -H "Authorization: Bearer {your_token}" \
  -H "Content-Type: application/json" \
  -d '{
    "to_user_id": 2,
    "message": "Hello! Nice to meet you."
  }'
```

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests
5. Submit a pull request

## License

This project is licensed under the MIT License.
