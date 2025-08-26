# Discover & Like System API Guide

## Overview

The Discover & Like System is the core feature of the matrimonial API that allows users to:

1. **Discover** potential matches through personalized recommendations
2. **Like** profiles they're interested in
3. **Get matched** automatically when both users like each other
4. **View matches** in a dedicated section

## How It Works

### Flow Diagram
```
User A → Discovers User B → Likes User B
User B → Discovers User A → Likes User A
Result: Automatic Match Created! 🎉
```

### Key Concepts

- **Discover Section**: Shows profiles you haven't interacted with yet
- **Like**: Express interest in a profile (one-way)
- **Match**: Mutual interest (both users liked each other)
- **Matches Section**: Only shows mutual matches

## API Endpoints

### 1. Get Discover Profiles

**Endpoint**: `GET /api/discover`

**Description**: Get personalized profile recommendations for the discover section.

**Query Parameters**:
- `limit` (optional): Number of profiles to return (default: 10, max: 50)
- `page` (optional): Page number for pagination (default: 1)

**Headers**:
```
Authorization: Bearer {token}
```

**Response**:
```json
{
    "discover_profiles": [
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
                "bio": "Looking for a caring partner",
                "profile_picture": "https://example.com/photo.jpg"
            },
            "score": 0.85,
            "compatibility_percentage": 85
        }
    ],
    "total": 25,
    "current_page": 1,
    "per_page": 10,
    "has_more": true
}
```

**Example**:
```bash
curl -X GET "http://localhost:8000/api/discover?limit=5&page=1" \
  -H "Authorization: Bearer {your_token}"
```

### 2. Like a Profile

**Endpoint**: `POST /api/discover/like/{user_id}`

**Description**: Like a profile from the discover section. If the other user has already liked you, this creates a match.

**Path Parameters**:
- `user_id`: ID of the user to like

**Headers**:
```
Authorization: Bearer {token}
```

**Response (Not a Match)**:
```json
{
    "message": "Profile liked successfully",
    "is_match": false,
    "liked_user": {
        "id": 2,
        "name": "Jane Doe",
        "age": 26,
        "gender": "female"
    }
}
```

**Response (It's a Match!)**:
```json
{
    "message": "It's a match! 🎉",
    "is_match": true,
    "matched_user": {
        "id": 2,
        "name": "Jane Doe",
        "age": 26,
        "gender": "female"
    }
}
```

**Example**:
```bash
curl -X POST "http://localhost:8000/api/discover/like/2" \
  -H "Authorization: Bearer {your_token}"
```

### 3. Unlike a Profile

**Endpoint**: `DELETE /api/discover/unlike/{user_id}`

**Description**: Remove a like from a profile. If you were matched, this also removes the match.

**Path Parameters**:
- `user_id`: ID of the user to unlike

**Headers**:
```
Authorization: Bearer {token}
```

**Response**:
```json
{
    "message": "Profile unliked successfully"
}
```

**Example**:
```bash
curl -X DELETE "http://localhost:8000/api/discover/unlike/2" \
  -H "Authorization: Bearer {your_token}"
```

### 4. Get Profiles That Liked You

**Endpoint**: `GET /api/discover/liked-by`

**Description**: Get profiles of users who liked you but you haven't liked back yet.

**Query Parameters**:
- `limit` (optional): Number of profiles to return (default: 10, max: 50)
- `page` (optional): Page number for pagination (default: 1)

**Headers**:
```
Authorization: Bearer {token}
```

**Response**:
```json
{
    "liked_by_profiles": [
        {
            "id": 3,
            "name": "Sarah Johnson",
            "age": 24,
            "gender": "female",
            "religion": "Christian",
            "caste": null,
            "income": 500000,
            "education": "Bachelors",
            "location": "Delhi",
            "occupation": "Marketing Manager",
            "bio": "Looking for someone special"
        }
    ],
    "total": 5,
    "current_page": 1,
    "per_page": 10,
    "has_more": false
}
```

**Example**:
```bash
curl -X GET "http://localhost:8000/api/discover/liked-by?limit=5" \
  -H "Authorization: Bearer {your_token}"
```

### 5. Get Matches

**Endpoint**: `GET /api/matches`

**Description**: Get all mutual matches (users who liked each other).

**Query Parameters**:
- `limit` (optional): Number of matches to return (default: 10, max: 50)
- `page` (optional): Page number for pagination (default: 1)

**Headers**:
```
Authorization: Bearer {token}
```

**Response**:
```json
{
    "matches": [
        {
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
        }
    ],
    "total": 3,
    "current_page": 1,
    "per_page": 10,
    "has_more": false
}
```

**Example**:
```bash
curl -X GET "http://localhost:8000/api/matches?limit=5" \
  -H "Authorization: Bearer {your_token}"
```

## Error Responses

### Common Error Codes

**400 Bad Request**:
```json
{
    "message": "Cannot like your own profile"
}
```

**400 Bad Request**:
```json
{
    "message": "Already liked this profile"
}
```

**400 Bad Request**:
```json
{
    "message": "Already matched with this user"
}
```

**404 Not Found**:
```json
{
    "message": "User not found"
}
```

**401 Unauthorized**:
```json
{
    "message": "Unauthenticated"
}
```

## Frontend Integration Guide

### Typical User Flow

1. **Load Discover Page**:
   ```javascript
   const response = await fetch('/api/discover?limit=10', {
     headers: { 'Authorization': `Bearer ${token}` }
   });
   const data = await response.json();
   // Display data.discover_profiles
   ```

2. **Like a Profile**:
   ```javascript
   const response = await fetch(`/api/discover/like/${userId}`, {
     method: 'POST',
     headers: { 'Authorization': `Bearer ${token}` }
   });
   const data = await response.json();
   
   if (data.is_match) {
     // Show match celebration!
     showMatchNotification(data.matched_user);
   } else {
     // Show like confirmation
     showLikeConfirmation();
   }
   ```

3. **Check for New Matches**:
   ```javascript
   const response = await fetch('/api/matches', {
     headers: { 'Authorization': `Bearer ${token}` }
   });
   const data = await response.json();
   // Update matches list
   ```

### UI/UX Recommendations

1. **Discover Section**:
   - Show one profile at a time (Tinder-style)
   - Large like/dislike buttons
   - Show compatibility percentage
   - Display profile photos prominently

2. **Match Notifications**:
   - Celebrate when a match is created
   - Show both users' photos
   - Provide quick action to start chatting

3. **Matches Section**:
   - Grid or list view of all matches
   - Quick access to chat
   - Profile preview on hover/click

## Algorithm Details

### Recommendation Algorithm

The discover system uses a sophisticated algorithm that considers:

1. **User Preferences**: Age, gender, religion, caste, income, education, location, occupation
2. **Collaborative Filtering**: Based on what similar users have liked
3. **Exclusion Logic**: 
   - Excludes already liked profiles
   - Excludes profiles that liked you (to avoid confusion)
   - Excludes existing matches
   - Excludes your own profile

### Scoring System

Each profile gets a compatibility score (0-1) based on:
- Preference matching (weighted by user preferences)
- Collaborative filtering bonus
- Normalized to percentage (0-100%)

## Testing

### Test Scenarios

1. **Basic Like Flow**:
   - User A likes User B
   - User B likes User A
   - Verify match is created

2. **Discover Exclusions**:
   - Verify liked profiles don't appear in discover
   - Verify matches don't appear in discover

3. **Match Creation**:
   - Test automatic match creation
   - Test match removal when unlike

4. **Pagination**:
   - Test discover pagination
   - Test matches pagination

### Sample Test Data

The seeder creates 10 diverse users for testing:
- Priya Sharma (25, Female, Hindu, Brahmin)
- Rahul Patel (28, Male, Hindu, Patel)
- Aisha Khan (24, Female, Muslim, Sunni)
- Amit Singh (30, Male, Sikh, Jat)
- Neha Gupta (26, Female, Hindu, Agarwal)
- Vikram Malhotra (29, Male, Hindu, Khatri)
- Fatima Ali (23, Female, Muslim, Shia)
- Rajesh Kumar (27, Male, Hindu, Yadav)
- Sneha Reddy (25, Female, Hindu, Reddy)
- Arjun Mehta (31, Male, Hindu, Brahmin)

## Migration from Old System

The old like system endpoints are deprecated but still available:

- `POST /api/likes/{user}` → Use `POST /api/discover/like/{user}`
- `DELETE /api/likes/{user}` → Use `DELETE /api/discover/unlike/{user}`
- `GET /api/likes` → Use `GET /api/discover/liked-by`

Old endpoints return 410 Gone status with deprecation message.
