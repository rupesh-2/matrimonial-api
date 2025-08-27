# Profile API Documentation
## Matrimonial API - Profile Management Endpoints

### Overview
This documentation covers all the API endpoints related to user profile management in your matrimonial API, including viewing, editing, and updating preferences.

---

## 🔗 **Available Endpoints**

### **1. Get User Profile**
**GET** `/api/profile`

Returns the current user's profile information including preferences.

#### **Headers**
```
Authorization: Bearer YOUR_TOKEN
Content-Type: application/json
```

#### **Example Request**
```bash
curl -X GET http://localhost:8000/api/profile \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json"
```

#### **Example Response**
```json
{
  "user": {
    "id": 1,
    "name": "Alice Johnson",
    "email": "alice@test.com",
    "email_verified_at": "2024-01-15T10:00:00.000000Z",
    "age": 25,
    "gender": "female",
    "religion": "Christian",
    "caste": "General",
    "income": 50000,
    "education": "Bachelor's Degree",
    "location": "New York",
    "occupation": "Software Engineer",
    "bio": "I'm a passionate software engineer looking for a meaningful relationship.",
    "profile_picture": "https://example.com/profile.jpg",
    "created_at": "2024-01-15T10:00:00.000000Z",
    "updated_at": "2024-01-15T10:30:00.000000Z",
    "preferences": {
      "id": 1,
      "user_id": 1,
      "preferred_age_min": 23,
      "preferred_age_max": 30,
      "preferred_gender": "male",
      "preferred_religion": "Christian",
      "preferred_caste": null,
      "preferred_income_min": 40000,
      "preferred_income_max": 80000,
      "preferred_education": "Bachelor's Degree",
      "preferred_location": "New York",
      "preferred_occupation": null,
      "age_weight": 1.0,
      "gender_weight": 1.0,
      "religion_weight": 1.0,
      "caste_weight": 1.0,
      "income_weight": 1.0,
      "education_weight": 1.0,
      "location_weight": 1.0,
      "occupation_weight": 1.0,
      "created_at": "2024-01-15T10:00:00.000000Z",
      "updated_at": "2024-01-15T10:30:00.000000Z"
    }
  }
}
```

---

### **2. Update User Profile**
**PUT** `/api/profile`

Updates the current user's profile information.

#### **Headers**
```
Authorization: Bearer YOUR_TOKEN
Content-Type: application/json
```

#### **Request Body Parameters**
| Parameter | Type | Required | Validation | Description |
|-----------|------|----------|------------|-------------|
| `name` | string | No | max:255 | User's full name |
| `age` | integer | No | min:18, max:100 | User's age |
| `gender` | string | No | in:male,female,other | User's gender |
| `religion` | string | No | max:255 | User's religion |
| `caste` | string | No | max:255 | User's caste |
| `income` | integer | No | min:0 | User's annual income |
| `education` | string | No | max:255 | User's education level |
| `location` | string | No | max:255 | User's location/city |
| `occupation` | string | No | max:255 | User's occupation |
| `bio` | string | No | max:1000 | User's bio/description |
| `profile_picture` | string | No | max:255 | URL to profile picture |

#### **Example Request**
```bash
curl -X PUT http://localhost:8000/api/profile \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Alice Johnson",
    "age": 26,
    "gender": "female",
    "religion": "Christian",
    "caste": "General",
    "income": 55000,
    "education": "Master'\''s Degree",
    "location": "New York",
    "occupation": "Senior Software Engineer",
    "bio": "I'\''m a passionate software engineer with a love for technology and meaningful relationships.",
    "profile_picture": "https://example.com/new-profile.jpg"
  }'
```

#### **Example Response (200)**
```json
{
  "message": "Profile updated successfully",
  "user": {
    "id": 1,
    "name": "Alice Johnson",
    "email": "alice@test.com",
    "email_verified_at": "2024-01-15T10:00:00.000000Z",
    "age": 26,
    "gender": "female",
    "religion": "Christian",
    "caste": "General",
    "income": 55000,
    "education": "Master's Degree",
    "location": "New York",
    "occupation": "Senior Software Engineer",
    "bio": "I'm a passionate software engineer with a love for technology and meaningful relationships.",
    "profile_picture": "https://example.com/new-profile.jpg",
    "created_at": "2024-01-15T10:00:00.000000Z",
    "updated_at": "2024-01-15T11:00:00.000000Z",
    "preferences": {
      // ... preferences data
    }
  }
}
```

#### **Error Response (422)**
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "age": [
      "The age must be at least 18."
    ],
    "gender": [
      "The selected gender is invalid."
    ]
  }
}
```

---

### **3. Update User Preferences**
**POST** `/api/profile/preferences`

Updates the current user's matching preferences and weights.

#### **Headers**
```
Authorization: Bearer YOUR_TOKEN
Content-Type: application/json
```

#### **Request Body Parameters**

##### **Preference Ranges**
| Parameter | Type | Required | Validation | Description |
|-----------|------|----------|------------|-------------|
| `preferred_age_min` | integer | No | min:18, max:100 | Minimum preferred age |
| `preferred_age_max` | integer | No | min:18, max:100 | Maximum preferred age |
| `preferred_income_min` | integer | No | min:0 | Minimum preferred income |
| `preferred_income_max` | integer | No | min:0 | Maximum preferred income |

##### **Preference Values**
| Parameter | Type | Required | Validation | Description |
|-----------|------|----------|------------|-------------|
| `preferred_gender` | string | No | in:male,female,other | Preferred gender |
| `preferred_religion` | string | No | max:255 | Preferred religion |
| `preferred_caste` | string | No | max:255 | Preferred caste |
| `preferred_education` | string | No | max:255 | Preferred education level |
| `preferred_location` | string | No | max:255 | Preferred location |
| `preferred_occupation` | string | No | max:255 | Preferred occupation |

##### **Preference Weights**
| Parameter | Type | Required | Validation | Description |
|-----------|------|----------|------------|-------------|
| `age_weight` | numeric | No | min:0, max:10 | Weight for age matching |
| `gender_weight` | numeric | No | min:0, max:10 | Weight for gender matching |
| `religion_weight` | numeric | No | min:0, max:10 | Weight for religion matching |
| `caste_weight` | numeric | No | min:0, max:10 | Weight for caste matching |
| `income_weight` | numeric | No | min:0, max:10 | Weight for income matching |
| `education_weight` | numeric | No | min:0, max:10 | Weight for education matching |
| `location_weight` | numeric | No | min:0, max:10 | Weight for location matching |
| `occupation_weight` | numeric | No | min:0, max:10 | Weight for occupation matching |

#### **Example Request**
```bash
curl -X POST http://localhost:8000/api/profile/preferences \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "preferred_age_min": 24,
    "preferred_age_max": 32,
    "preferred_gender": "male",
    "preferred_religion": "Christian",
    "preferred_caste": null,
    "preferred_income_min": 45000,
    "preferred_income_max": 90000,
    "preferred_education": "Bachelor'\''s Degree",
    "preferred_location": "New York",
    "preferred_occupation": null,
    "age_weight": 1.5,
    "gender_weight": 1.0,
    "religion_weight": 1.2,
    "caste_weight": 1.0,
    "income_weight": 0.8,
    "education_weight": 1.0,
    "location_weight": 1.3,
    "occupation_weight": 0.9
  }'
```

#### **Example Response (200)**
```json
{
  "message": "Preferences updated successfully",
  "preferences": {
    "id": 1,
    "user_id": 1,
    "preferred_age_min": 24,
    "preferred_age_max": 32,
    "preferred_gender": "male",
    "preferred_religion": "Christian",
    "preferred_caste": null,
    "preferred_income_min": 45000,
    "preferred_income_max": 90000,
    "preferred_education": "Bachelor's Degree",
    "preferred_location": "New York",
    "preferred_occupation": null,
    "age_weight": 1.5,
    "gender_weight": 1.0,
    "religion_weight": 1.2,
    "caste_weight": 1.0,
    "income_weight": 0.8,
    "education_weight": 1.0,
    "location_weight": 1.3,
    "occupation_weight": 0.9,
    "created_at": "2024-01-15T10:00:00.000000Z",
    "updated_at": "2024-01-15T11:30:00.000000Z"
  }
}
```

#### **Error Response (422)**
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "preferred_age_min": [
      "The preferred age min must be at least 18."
    ],
    "preferred_age_max": [
      "The preferred age max must not be greater than 100."
    ],
    "age_weight": [
      "The age weight must be between 0 and 10."
    ]
  }
}
```

---

## 📊 **Response Status Codes**

| Status Code | Description |
|-------------|-------------|
| 200 | Success (GET/PUT/POST requests) |
| 422 | Validation Error (invalid data) |
| 401 | Unauthorized (invalid token) |
| 500 | Internal Server Error |

---

## 🧪 **Testing Examples**

### **Complete Profile Management Flow**
```bash
# 1. Login and get token
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email": "alice@test.com", "password": "password123"}'

# 2. Get current profile
curl -X GET http://localhost:8000/api/profile \
  -H "Authorization: Bearer YOUR_TOKEN"

# 3. Update profile information
curl -X PUT http://localhost:8000/api/profile \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Alice Johnson",
    "age": 26,
    "gender": "female",
    "religion": "Christian",
    "location": "New York",
    "bio": "I am a software engineer looking for a meaningful relationship."
  }'

# 4. Update preferences
curl -X POST http://localhost:8000/api/profile/preferences \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "preferred_age_min": 24,
    "preferred_age_max": 32,
    "preferred_gender": "male",
    "preferred_religion": "Christian",
    "age_weight": 1.5,
    "religion_weight": 1.2
  }'

# 5. Verify updated profile
curl -X GET http://localhost:8000/api/profile \
  -H "Authorization: Bearer YOUR_TOKEN"
```

---

## ⚠️ **Important Notes**

### **Profile Update Rules**
1. **Authentication Required**: All endpoints require a valid Bearer token
2. **Partial Updates**: You can update only specific fields, others remain unchanged
3. **Validation**: All fields are validated according to the rules above
4. **Age Restrictions**: Minimum age is 18 for both profile and preferences
5. **Gender Options**: Only 'male', 'female', or 'other' are accepted

### **Preference Weights**
- **Default Weight**: 1.0 for all categories
- **Weight Range**: 0.0 to 10.0
- **Higher Weight**: More importance in matching algorithm
- **Lower Weight**: Less importance in matching algorithm

### **Data Persistence**
- **Profile Updates**: Immediately saved to database
- **Preference Updates**: Creates new preferences if none exist, updates existing ones
- **Validation**: All data is validated before saving

---

## 🔄 **Field Descriptions**

### **Profile Fields**
- **name**: User's full name
- **age**: User's age (18-100)
- **gender**: User's gender (male/female/other)
- **religion**: User's religious affiliation
- **caste**: User's caste (if applicable)
- **income**: Annual income in currency units
- **education**: Highest education level achieved
- **location**: City or location
- **occupation**: Current job or profession
- **bio**: Personal description/bio
- **profile_picture**: URL to profile image

### **Preference Fields**
- **preferred_age_min/max**: Age range for potential matches
- **preferred_gender**: Preferred gender for matches
- **preferred_religion/caste**: Preferred religious/caste background
- **preferred_income_min/max**: Income range for matches
- **preferred_education/location/occupation**: Specific preferences
- **weight fields**: Importance of each category in matching (0-10)

---

*This documentation covers all profile management API endpoints in your matrimonial API system.*
