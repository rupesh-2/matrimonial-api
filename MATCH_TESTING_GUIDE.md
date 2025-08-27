# Match Creation Testing Guide

## Matrimonial API - Match System Testing

### Overview

This guide helps you test the match creation functionality in your matrimonial API. The system should automatically create matches when two users like each other.

---

## 🔧 **Fixed Issues**

### 1. **DiscoverController::likeProfile()**

-   ✅ **Fixed**: Now creates matches in both directions
-   ✅ **Fixed**: Properly checks for mutual likes before creating matches
-   ✅ **Fixed**: Creates match records in the `matches` table

### 2. **MatchController::createMatch()**

-   ✅ **Fixed**: Validates that both users have liked each other
-   ✅ **Fixed**: Creates matches in both directions
-   ✅ **Fixed**: Proper error handling

### 3. **MatchController::getMatches()**

-   ✅ **Fixed**: Now uses the `matches` table instead of calculating mutual likes
-   ✅ **Fixed**: Proper pagination and data retrieval

---

## 🧪 **Testing Steps**

### **Step 1: Create Test Users**

```bash
# Create User 1
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Alice Johnson",
    "email": "alice@test.com",
    "password": "password123",
    "age": 25,
    "gender": "female",
    "religion": "Christian",
    "location": "New York"
  }'

# Create User 2
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Bob Smith",
    "email": "bob@test.com",
    "password": "password123",
    "age": 28,
    "gender": "male",
    "religion": "Christian",
    "location": "New York"
  }'

# Create User 3
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Charlie Brown",
    "email": "charlie@test.com",
    "password": "password123",
    "age": 26,
    "gender": "male",
    "religion": "Christian",
    "location": "New York"
  }'
```

### **Step 2: Login and Get Tokens**

```bash
# Login as Alice
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "alice@test.com",
    "password": "password123"
  }'

# Login as Bob
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "bob@test.com",
    "password": "password123"
  }'

# Login as Charlie
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "charlie@test.com",
    "password": "password123"
  }'
```

**Save the tokens from each response for the next steps.**

### **Step 3: Test One-Way Like (No Match)**

```bash
# Alice likes Bob (one-way)
curl -X POST http://localhost:8000/api/discover/like/2 \
  -H "Authorization: Bearer ALICE_TOKEN" \
  -H "Content-Type: application/json"

# Expected Response:
# {
#   "message": "Profile liked successfully",
#   "is_match": false,
#   "liked_user": { ... }
# }
```

### **Step 4: Test Mutual Like (Creates Match)**

```bash
# Bob likes Alice back (mutual like - should create match)
curl -X POST http://localhost:8000/api/discover/like/1 \
  -H "Authorization: Bearer BOB_TOKEN" \
  -H "Content-Type: application/json"

# Expected Response:
# {
#   "message": "It's a match! 🎉",
#   "is_match": true,
#   "matched_user": { ... }
# }
```

### **Step 5: Verify Match Creation**

```bash
# Check Alice's matches
curl -X GET http://localhost:8000/api/matches \
  -H "Authorization: Bearer ALICE_TOKEN"

# Check Bob's matches
curl -X GET http://localhost:8000/api/matches \
  -H "Authorization: Bearer BOB_TOKEN"

# Both should return Bob/Alice in their matches list
```

### **Step 6: Test Database Verification**

```bash
# Check the matches table directly
php artisan tinker

# In tinker:
DB::table('matches')->get();
DB::table('likes')->get();

# Should see:
# matches table: records for user_id=1, matched_user_id=2 AND user_id=2, matched_user_id=1
# likes table: records for user_id=1, liked_user_id=2 AND user_id=2, liked_user_id=1
```

### **Step 7: Test Edge Cases**

```bash
# Try to like the same user again (should fail)
curl -X POST http://localhost:8000/api/discover/like/2 \
  -H "Authorization: Bearer ALICE_TOKEN" \
  -H "Content-Type: application/json"

# Expected Response:
# {
#   "message": "Already liked this profile"
# }

# Try to like yourself (should fail)
curl -X POST http://localhost:8000/api/discover/like/1 \
  -H "Authorization: Bearer ALICE_TOKEN" \
  -H "Content-Type: application/json"

# Expected Response:
# {
#   "message": "Cannot like your own profile"
# }
```

### **Step 8: Test Unmatch Functionality**

```bash
# Alice unlikes Bob
curl -X DELETE http://localhost:8000/api/discover/unlike/2 \
  -H "Authorization: Bearer ALICE_TOKEN"

# Check matches again (should be empty)
curl -X GET http://localhost:8000/api/matches \
  -H "Authorization: Bearer ALICE_TOKEN"
```

---

## 🔍 **Database Verification**

### **Check Matches Table**

```sql
SELECT * FROM matches ORDER BY created_at DESC;
```

### **Check Likes Table**

```sql
SELECT * FROM likes ORDER BY created_at DESC;
```

### **Expected Results After Mutual Like:**

-   **matches table**: 2 records (bidirectional)
-   **likes table**: 2 records (bidirectional)

---

## 🐛 **Common Issues & Solutions**

### **Issue 1: No matches created**

**Solution**: Check if the `DiscoverController::likeProfile()` method is being called correctly.

### **Issue 2: Matches not showing in API**

**Solution**: Verify the `MatchController::getMatches()` method is using the correct relationships.

### **Issue 3: Database constraints failing**

**Solution**: Check if the unique constraints are properly set up in migrations.

### **Issue 4: Cascade deletes not working**

**Solution**: Verify foreign key constraints in the migration files.

---

## 📊 **Expected API Responses**

### **Successful Like (No Match)**

```json
{
    "message": "Profile liked successfully",
    "is_match": false,
    "liked_user": {
        "id": 2,
        "name": "Bob Smith",
        "email": "bob@test.com"
        // ... other user data
    }
}
```

### **Successful Match**

```json
{
    "message": "It's a match! 🎉",
    "is_match": true,
    "matched_user": {
        "id": 2,
        "name": "Bob Smith",
        "email": "bob@test.com"
        // ... other user data
    }
}
```

### **Get Matches Response**

```json
{
    "matches": [
        {
            "id": 2,
            "name": "Bob Smith",
            "email": "bob@test.com",
            "preferences": {
                // ... preferences data
            }
        }
    ],
    "total": 1,
    "current_page": 1,
    "per_page": 10,
    "has_more": false
}
```

---

## ✅ **Success Criteria**

1. **One-way like**: Creates like record, no match
2. **Mutual like**: Creates like records + match records in both directions
3. **Match retrieval**: Returns correct matches from database
4. **Unmatch**: Removes both likes and matches
5. **Error handling**: Proper validation and error messages
6. **Database integrity**: No orphaned records

---

_Use this guide to systematically test your match creation functionality and ensure everything works as expected._
