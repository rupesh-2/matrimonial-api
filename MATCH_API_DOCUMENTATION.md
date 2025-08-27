# Match API Documentation

## Matrimonial API - Match Endpoints

### Overview

This documentation covers all the API endpoints related to matches in your matrimonial API. A match is created when two users mutually like each other.

---

## 🔗 **Available Endpoints**

### **1. Get All Matches**

**GET** `/api/matches`

Returns all users who have mutually liked each other (matches).

#### **Headers**

```
Authorization: Bearer YOUR_TOKEN
Content-Type: application/json
```

#### **Query Parameters**

| Parameter | Type    | Required | Default | Description                          |
| --------- | ------- | -------- | ------- | ------------------------------------ |
| `limit`   | integer | No       | 10      | Number of matches per page (max: 50) |
| `page`    | integer | No       | 1       | Page number                          |

#### **Example Request**

```bash
curl -X GET "http://localhost:8000/api/matches?limit=5&page=1" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json"
```

#### **Example Response**

```json
{
    "matches": [
        {
            "id": 2,
            "name": "Bob Smith",
            "email": "bob@test.com",
            "age": 28,
            "gender": "male",
            "religion": "Christian",
            "caste": null,
            "income": null,
            "education": null,
            "location": "New York",
            "occupation": null,
            "bio": null,
            "profile_picture": null,
            "preferences": {
                "id": 2,
                "user_id": 2,
                "preferred_age_min": 20,
                "preferred_age_max": 30,
                "preferred_gender": "female",
                "preferred_religion": "Christian",
                "preferred_caste": null,
                "preferred_income_min": null,
                "preferred_income_max": null,
                "preferred_education": null,
                "preferred_location": null,
                "preferred_occupation": null,
                "age_weight": 1.0,
                "gender_weight": 1.0,
                "religion_weight": 1.0,
                "caste_weight": 1.0,
                "income_weight": 1.0,
                "education_weight": 1.0,
                "location_weight": 1.0,
                "occupation_weight": 1.0,
                "created_at": "2024-01-15T10:30:00.000000Z",
                "updated_at": "2024-01-15T10:30:00.000000Z"
            },
            "matched_at": "2024-01-15T10:30:00.000000Z",
            "user_created_at": "2024-01-15T10:00:00.000000Z",
            "user_updated_at": "2024-01-15T10:00:00.000000Z"
        }
    ],
    "total": 1,
    "current_page": 1,
    "per_page": 5,
    "has_more": false,
    "summary": {
        "total_matches": 1,
        "current_page_matches": 1
    }
}
```

---

### **2. Get Match Statistics**

**GET** `/api/matches/stats`

Returns statistics about the user's matches and likes.

#### **Headers**

```
Authorization: Bearer YOUR_TOKEN
Content-Type: application/json
```

#### **Example Request**

```bash
curl -X GET http://localhost:8000/api/matches/stats \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json"
```

#### **Example Response**

```json
{
    "stats": {
        "total_matches": 5,
        "total_likes_given": 15,
        "total_likes_received": 12,
        "recent_matches_30_days": 2,
        "match_rate": 41.67
    }
}
```

#### **Statistics Explained**

-   **total_matches**: Total number of mutual matches
-   **total_likes_given**: Total likes sent by the user
-   **total_likes_received**: Total likes received by the user
-   **recent_matches_30_days**: Matches created in the last 30 days
-   **match_rate**: Percentage of received likes that resulted in matches

---

### **3. Get Specific Match**

**GET** `/api/matches/{user_id}`

Returns detailed information about a specific match with a user.

#### **Headers**

```
Authorization: Bearer YOUR_TOKEN
Content-Type: application/json
```

#### **Path Parameters**

| Parameter | Type    | Required | Description            |
| --------- | ------- | -------- | ---------------------- |
| `user_id` | integer | Yes      | ID of the matched user |

#### **Example Request**

```bash
curl -X GET http://localhost:8000/api/matches/2 \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json"
```

#### **Example Response**

```json
{
    "match": {
        "id": 2,
        "name": "Bob Smith",
        "email": "bob@test.com",
        "age": 28,
        "gender": "male",
        "religion": "Christian",
        "caste": null,
        "income": null,
        "education": null,
        "location": "New York",
        "occupation": null,
        "bio": null,
        "profile_picture": null,
        "preferences": {
            "id": 2,
            "user_id": 2,
            "preferred_age_min": 20,
            "preferred_age_max": 30,
            "preferred_gender": "female",
            "preferred_religion": "Christian",
            "preferred_caste": null,
            "preferred_income_min": null,
            "preferred_income_max": null,
            "preferred_education": null,
            "preferred_location": null,
            "preferred_occupation": null,
            "age_weight": 1.0,
            "gender_weight": 1.0,
            "religion_weight": 1.0,
            "caste_weight": 1.0,
            "income_weight": 1.0,
            "education_weight": 1.0,
            "location_weight": 1.0,
            "occupation_weight": 1.0,
            "created_at": "2024-01-15T10:30:00.000000Z",
            "updated_at": "2024-01-15T10:30:00.000000Z"
        },
        "matched_at": "2024-01-15T10:30:00.000000Z",
        "user_created_at": "2024-01-15T10:00:00.000000Z",
        "user_updated_at": "2024-01-15T10:00:00.000000Z"
    }
}
```

#### **Error Response (404)**

```json
{
    "message": "No match found with this user"
}
```

---

### **4. Create Match (Manual)**

**POST** `/api/matches/{user_id}`

Manually create a match with another user (both users must have liked each other).

#### **Headers**

```
Authorization: Bearer YOUR_TOKEN
Content-Type: application/json
```

#### **Path Parameters**

| Parameter | Type    | Required | Description                  |
| --------- | ------- | -------- | ---------------------------- |
| `user_id` | integer | Yes      | ID of the user to match with |

#### **Example Request**

```bash
curl -X POST http://localhost:8000/api/matches/2 \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json"
```

#### **Example Response (201)**

```json
{
    "message": "Match created successfully",
    "matched_user": {
        "id": 2,
        "name": "Bob Smith",
        "email": "bob@test.com"
        // ... other user data
    }
}
```

#### **Error Response (400)**

```json
{
    "message": "Already matched with this user"
}
```

```json
{
    "message": "Both users must like each other to create a match"
}
```

---

### **5. Remove Match**

**DELETE** `/api/matches/{user_id}`

Remove a match with another user.

#### **Headers**

```
Authorization: Bearer YOUR_TOKEN
Content-Type: application/json
```

#### **Path Parameters**

| Parameter | Type    | Required | Description               |
| --------- | ------- | -------- | ------------------------- |
| `user_id` | integer | Yes      | ID of the user to unmatch |

#### **Example Request**

```bash
curl -X DELETE http://localhost:8000/api/matches/2 \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json"
```

#### **Example Response**

```json
{
    "message": "Match removed successfully"
}
```

---

## 🔄 **How Matches Work**

### **Match Creation Process**

1. **User A likes User B** → Creates like record, no match
2. **User B likes User A** → Creates like record + **automatically creates match**
3. **Match is stored** in the `matches` table in both directions
4. **Match API** returns users from the `matches` table

### **Match Data Structure**

```sql
-- matches table
user_id: 1, matched_user_id: 2, created_at: 2024-01-15 10:30:00
user_id: 2, matched_user_id: 1, created_at: 2024-01-15 10:30:00
```

### **Match vs Like**

-   **Like**: One-way interest (User A likes User B)
-   **Match**: Mutual interest (User A likes User B AND User B likes User A)

---

## 📊 **Response Status Codes**

| Status Code | Description                     |
| ----------- | ------------------------------- |
| 200         | Success (GET requests)          |
| 201         | Created (POST requests)         |
| 400         | Bad Request (validation errors) |
| 401         | Unauthorized (invalid token)    |
| 404         | Not Found (match doesn't exist) |
| 500         | Internal Server Error           |

---

## 🧪 **Testing Examples**

### **Complete Match Flow**

```bash
# 1. Login and get token
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email": "alice@test.com", "password": "password123"}'

# 2. Like a user (no match yet)
curl -X POST http://localhost:8000/api/discover/like/2 \
  -H "Authorization: Bearer YOUR_TOKEN"

# 3. Check matches (should be empty)
curl -X GET http://localhost:8000/api/matches \
  -H "Authorization: Bearer YOUR_TOKEN"

# 4. Get match statistics
curl -X GET http://localhost:8000/api/matches/stats \
  -H "Authorization: Bearer YOUR_TOKEN"

# 5. After mutual like, check matches again
curl -X GET http://localhost:8000/api/matches \
  -H "Authorization: Bearer YOUR_TOKEN"

# 6. Get specific match details
curl -X GET http://localhost:8000/api/matches/2 \
  -H "Authorization: Bearer YOUR_TOKEN"
```

---

## ⚠️ **Important Notes**

1. **Authentication Required**: All endpoints require a valid Bearer token
2. **Match Creation**: Matches are automatically created when there are mutual likes
3. **Data Consistency**: Matches are stored in both directions for data integrity
4. **Pagination**: Use `limit` and `page` parameters for large datasets
5. **Error Handling**: Always check status codes and error messages

---

_This documentation covers all match-related API endpoints in your matrimonial API system._
