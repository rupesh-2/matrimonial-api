# Test Users Guide for Matrimonial API

## Overview

This document provides all the test user credentials and testing scenarios for the matrimonial API discover and like system.

## 🎯 **Primary Test Users (Already Matched)**

### **User 1 - John Doe (Male)**

-   **Email:** `john@test.com`
-   **Password:** `password123`
-   **Age:** 28
-   **Gender:** Male
-   **Religion:** Hindu
-   **Caste:** Brahmin
-   **Income:** 800,000
-   **Education:** Masters
-   **Location:** Mumbai
-   **Occupation:** Software Engineer
-   **Bio:** "Looking for a caring and understanding life partner. I love reading books and traveling."

### **User 2 - Priya Sharma (Female)**

-   **Email:** `priya@test.com`
-   **Password:** `password123`
-   **Age:** 26
-   **Gender:** Female
-   **Religion:** Hindu
-   **Caste:** Brahmin
-   **Income:** 600,000
-   **Education:** Masters
-   **Location:** Mumbai
-   **Occupation:** Data Scientist
-   **Bio:** "Seeking a loving and supportive partner. I enjoy cooking and yoga."

**Status:** ✅ **Already Matched** - These users have mutual likes and will appear in each other's matches section.

---

## 🔍 **Discover Section Test Users**

### **User 3 - Alice Johnson (Female)**

-   **Email:** `alice@test.com`
-   **Password:** `password123`
-   **Age:** 25
-   **Gender:** Female
-   **Religion:** Christian
-   **Caste:** None
-   **Income:** 500,000
-   **Education:** Bachelors
-   **Location:** Delhi
-   **Occupation:** Marketing Manager
-   **Bio:** "Looking for someone special who shares my values."

### **User 4 - Rahul Patel (Male)**

-   **Email:** `rahul@test.com`
-   **Password:** `password123`
-   **Age:** 29
-   **Gender:** Male
-   **Religion:** Hindu
-   **Caste:** Patel
-   **Income:** 900,000
-   **Education:** Masters
-   **Location:** Bangalore
-   **Occupation:** Product Manager
-   **Bio:** "Passionate about technology and innovation."

### **User 5 - Neha Gupta (Female)**

-   **Email:** `neha@test.com`
-   **Password:** `password123`
-   **Age:** 27
-   **Gender:** Female
-   **Religion:** Hindu
-   **Caste:** Agarwal
-   **Income:** 700,000
-   **Education:** Masters
-   **Location:** Mumbai
-   **Occupation:** Doctor
-   **Bio:** "Dedicated healthcare professional seeking a caring partner."

### **User 6 - Amit Singh (Male)**

-   **Email:** `amit@test.com`
-   **Password:** `password123`
-   **Age:** 30
-   **Gender:** Male
-   **Religion:** Sikh
-   **Caste:** Jat
-   **Income:** 1,100,000
-   **Education:** PhD
-   **Location:** Pune
-   **Occupation:** Research Scientist
-   **Bio:** "Love for science and nature. Looking for an intellectual partner."

---

## 🧪 **Testing Scenarios**

### **1. Test Existing Matches**

#### **Login as John:**

```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "john@test.com",
    "password": "password123"
  }'
```

#### **Login as Priya:**

```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "priya@test.com",
    "password": "password123"
  }'
```

#### **Check Matches (should show each other):**

```bash
curl -X GET http://localhost:8000/api/matches \
  -H "Authorization: Bearer {token}"
```

**Expected Response:**

```json
{
    "matches": [
        {
            "id": 2,
            "name": "Priya Sharma",
            "age": 26,
            "gender": "female",
            "religion": "Hindu",
            "caste": "Brahmin",
            "income": 600000,
            "education": "Masters",
            "location": "Mumbai",
            "occupation": "Data Scientist",
            "bio": "Seeking a loving and supportive partner. I enjoy cooking and yoga."
        }
    ],
    "total": 1,
    "current_page": 1,
    "per_page": 10,
    "has_more": false
}
```

### **2. Test Discover Section**

#### **John's Discover (should see Alice and Neha):**

```bash
curl -X GET http://localhost:8000/api/discover \
  -H "Authorization: Bearer {john_token}"
```

#### **Priya's Discover (should see Rahul and Amit):**

```bash
curl -X GET http://localhost:8000/api/discover \
  -H "Authorization: Bearer {priya_token}"
```

**Expected Response:**

```json
{
    "discover_profiles": [
        {
            "user": {
                "id": 3,
                "name": "Alice Johnson",
                "age": 25,
                "gender": "female",
                "religion": "Christian",
                "caste": null,
                "income": 500000,
                "education": "Bachelors",
                "location": "Delhi",
                "occupation": "Marketing Manager",
                "bio": "Looking for someone special who shares my values."
            },
            "score": 0.75,
            "compatibility_percentage": 75
        }
    ],
    "total": 2,
    "current_page": 1,
    "per_page": 10,
    "has_more": false
}
```

### **3. Test Like System**

#### **John likes Alice:**

```bash
curl -X POST http://localhost:8000/api/discover/like/3 \
  -H "Authorization: Bearer {john_token}"
```

**Expected Response (Not a Match):**

```json
{
    "message": "Profile liked successfully",
    "is_match": false,
    "liked_user": {
        "id": 3,
        "name": "Alice Johnson",
        "age": 25,
        "gender": "female"
    }
}
```

#### **Alice likes John back (creates match):**

```bash
curl -X POST http://localhost:8000/api/discover/like/{john_id} \
  -H "Authorization: Bearer {alice_token}"
```

**Expected Response (It's a Match!):**

```json
{
    "message": "It's a match! 🎉",
    "is_match": true,
    "matched_user": {
        "id": 1,
        "name": "John Doe",
        "age": 28,
        "gender": "male"
    }
}
```

### **4. Test Unlike System**

#### **John unlikes Alice:**

```bash
curl -X DELETE http://localhost:8000/api/discover/unlike/3 \
  -H "Authorization: Bearer {john_token}"
```

**Expected Response:**

```json
{
    "message": "Profile unliked successfully"
}
```

### **5. Test Liked By Section**

#### **Check who liked you:**

```bash
curl -X GET http://localhost:8000/api/discover/liked-by \
  -H "Authorization: Bearer {token}"
```

**Expected Response:**

```json
{
    "liked_by_profiles": [
        {
            "id": 3,
            "name": "Alice Johnson",
            "age": 25,
            "gender": "female",
            "religion": "Christian",
            "caste": null,
            "income": 500000,
            "education": "Bachelors",
            "location": "Delhi",
            "occupation": "Marketing Manager",
            "bio": "Looking for someone special who shares my values."
        }
    ],
    "total": 1,
    "current_page": 1,
    "per_page": 10,
    "has_more": false
}
```

---

## 🔄 **Complete Testing Flow**

### **Step 1: Login and Get Token**

```bash
# Login as John
response=$(curl -s -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email": "john@test.com", "password": "password123"}')

# Extract token
token=$(echo $response | jq -r '.token')
```

### **Step 2: Check Discover Profiles**

```bash
curl -X GET http://localhost:8000/api/discover \
  -H "Authorization: Bearer $token"
```

### **Step 3: Like a Profile**

```bash
curl -X POST http://localhost:8000/api/discover/like/3 \
  -H "Authorization: Bearer $token"
```

### **Step 4: Check Matches**

```bash
curl -X GET http://localhost:8000/api/matches \
  -H "Authorization: Bearer $token"
```

### **Step 5: Unlike a Profile**

```bash
curl -X DELETE http://localhost:8000/api/discover/unlike/3 \
  -H "Authorization: Bearer $token"
```

---

## 📊 **User Preferences Summary**

### **John's Preferences:**

-   **Age Range:** 24-30
-   **Gender:** Female
-   **Religion:** Hindu
-   **Caste:** Brahmin
-   **Income Range:** 500,000-1,000,000
-   **Education:** Masters
-   **Location:** Mumbai
-   **Occupation:** Data Scientist

### **Priya's Preferences:**

-   **Age Range:** 26-32
-   **Gender:** Male
-   **Religion:** Hindu
-   **Caste:** Brahmin
-   **Income Range:** 600,000-1,200,000
-   **Education:** Masters
-   **Location:** Mumbai
-   **Occupation:** Software Engineer

---

## 🚀 **Quick Start Commands**

### **1. Start the server:**

```bash
php artisan serve
```

### **2. List all users:**

```bash
php artisan users:list
```

### **3. Run tests:**

```bash
php artisan test tests/Feature/DiscoverSystemTest.php
```

### **4. Reset database and seed:**

```bash
php artisan migrate:fresh --seed
php artisan db:seed --class=MatchedUsersSeeder
php artisan db:seed --class=DiscoverTestUsersSeeder
```

---

## 📝 **Notes**

-   All users have the same password: `password123`
-   John and Priya are already matched (mutual likes)
-   Discover section excludes already liked, matched, and self profiles
-   Matches section only shows mutual likes
-   The system supports pagination with `limit` and `page` parameters
-   All API responses include proper error handling and validation

---

## 🔗 **Related Documentation**

-   [API Documentation](README.md)
-   [Discover System Implementation](DISCOVER_SYSTEM_IMPLEMENTATION.md)
-   [Discover API Guide](DISCOVER_API_GUIDE.md)
-   [Database Schema](DATABASE_SCHEMA.md)

---

_Last updated: $(date)_
