# Database Seeder Guide

This guide will help you populate your matrimonial API database with realistic dummy data for testing and development.

## 🚀 Quick Start

### 1. Navigate to the Project Directory
```bash
cd matromonial-api
```

### 2. Run the Seeders
```bash
php artisan db:seed
```

This will create:
- **16 Users** (8 females, 8 males) with diverse profiles
- **User Preferences** for each user with realistic matching criteria
- **Sample Likes** between users
- **Mutual Matches** (4 matched pairs)
- **Sample Messages** between matched users

## 📊 Sample Data Overview

### Users Created
**Female Users:**
- Priya Sharma (25, Software Engineer, Mumbai)
- Aisha Khan (24, Teacher, Hyderabad)
- Neha Gupta (26, Data Scientist, Bangalore)
- Fatima Ali (23, Designer, Lucknow)
- Sneha Reddy (25, HR Manager, Hyderabad)
- Kavya Iyer (27, Research Scientist, Chennai)
- Zara Ahmed (24, Journalist, Delhi)
- Anjali Verma (26, Marketing Manager, Pune)

**Male Users:**
- Rahul Patel (28, Business Analyst, Delhi)
- Amit Singh (30, Doctor, Punjab)
- Vikram Malhotra (29, Architect, Chennai)
- Rajesh Kumar (27, Marketing Manager, Pune)
- Arjun Mehta (31, Investment Banker, Mumbai)
- Karan Sharma (26, Product Manager, Bangalore)
- Aditya Verma (28, Consultant, Delhi)
- Rohan Kapoor (25, Film Director, Mumbai)

### Login Credentials
All users have the same password: `password123`

**Sample Login:**
- Email: `priya@example.com`
- Password: `password123`

### Matches Created
1. **Priya Sharma** ↔ **Rahul Patel**
2. **Neha Gupta** ↔ **Amit Singh**
3. **Sneha Reddy** ↔ **Vikram Malhotra**
4. **Kavya Iyer** ↔ **Karan Sharma**

### Sample Conversations
Each matched pair has realistic conversation history with 5-6 messages.

## 🔄 Reset and Reseed

If you want to start fresh:

```bash
# Reset database and run migrations
php artisan migrate:fresh

# Run seeders
php artisan db:seed
```

## 🧪 Testing the API

### 1. Login as a User
```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "priya@example.com",
    "password": "password123"
  }'
```

### 2. Get Recommendations (with token)
```bash
curl -X GET http://localhost:8000/api/recommendations \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

### 3. Get Matches
```bash
curl -X GET http://localhost:8000/api/matches \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

### 4. Get Messages
```bash
curl -X GET http://localhost:8000/api/messages \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

## 📝 Customizing the Data

To modify the sample data, edit the `database/seeders/MatrimonialSeeder.php` file:

1. **Add More Users**: Add entries to the `$users` array
2. **Modify Preferences**: Adjust the `createPreferences()` method
3. **Change Matches**: Modify the `createSampleLikesAndMatches()` method
4. **Add Messages**: Update the `createSampleMessages()` method

## 🎯 Key Features Demonstrated

- **Diverse User Profiles**: Different religions, castes, locations, occupations
- **Realistic Preferences**: Age ranges, income expectations, education preferences
- **Matching Algorithm**: Users with compatible preferences
- **Chat System**: Real conversations between matched users
- **Like System**: One-sided and mutual likes

## 🔍 Database Tables Populated

- `users` - User profiles
- `preferences` - Matching preferences
- `likes` - User likes/interests
- `matches` - Mutual matches
- `messages` - Chat messages
- `personal_access_tokens` - Authentication tokens

## 🚨 Troubleshooting

### Common Issues:

1. **Database Connection Error**
   - Check your `.env` file database configuration
   - Ensure MySQL is running

2. **Migration Errors**
   - Run `php artisan migrate:fresh` to reset

3. **Seeder Errors**
   - Check if all models exist
   - Verify database table structure

### Reset Everything:
```bash
php artisan migrate:fresh --seed
```

This will give you a fresh database with all the sample data!
