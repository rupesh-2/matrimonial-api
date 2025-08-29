# 📊 Data Dictionary - Matrimonial API

## 🎯 **Overview**

This Data Dictionary provides a comprehensive documentation of all database tables, fields, relationships, and constraints in the Matrimonial API system. It serves as a reference for developers, database administrators, and system analysts.

## 📋 **Table of Contents**

1. [Users Table](#users-table)
2. [Preferences Table](#preferences-table)
3. [Matches Table](#matches-table)
4. [Likes Table](#likes-table)
5. [Messages Table](#messages-table)
6. [Notifications Table](#notifications-table)
7. [Personal Access Tokens Table](#personal-access-tokens-table)
8. [Database Relationships](#database-relationships)
9. [Indexes and Constraints](#indexes-and-constraints)
10. [Data Types and Standards](#data-types-and-standards)

---

## 👥 **Users Table**

**Table Name:** `users`  
**Description:** Stores user account information, profiles, and authentication data

| Field Name | Data Type | Length | Nullable | Default | Description |
|------------|-----------|--------|----------|---------|-------------|
| `id` | BIGINT | - | NO | AUTO_INCREMENT | Primary key, unique identifier |
| `name` | VARCHAR | 255 | NO | - | User's full name |
| `email` | VARCHAR | 255 | NO | - | User's email address (unique) |
| `email_verified_at` | TIMESTAMP | - | YES | NULL | Email verification timestamp |
| `password` | VARCHAR | 255 | NO | - | Hashed password |
| `gender` | ENUM | - | NO | - | User's gender ('male', 'female', 'other') |
| `date_of_birth` | DATE | - | YES | NULL | User's date of birth |
| `phone` | VARCHAR | 20 | YES | NULL | User's phone number |
| `location` | VARCHAR | 255 | YES | NULL | User's city/location |
| `bio` | TEXT | - | YES | NULL | User's biography/description |
| `profile_photo` | VARCHAR | 255 | YES | NULL | Profile photo URL/path |
| `fcm_token` | VARCHAR | 500 | YES | NULL | Firebase Cloud Messaging token |
| `notification_enabled` | BOOLEAN | - | NO | TRUE | User's notification preference |
| `is_active` | BOOLEAN | - | NO | TRUE | Account active status |
| `last_login_at` | TIMESTAMP | - | YES | NULL | Last login timestamp |
| `created_at` | TIMESTAMP | - | NO | CURRENT_TIMESTAMP | Record creation timestamp |
| `updated_at` | TIMESTAMP | - | NO | CURRENT_TIMESTAMP | Record update timestamp |

**Indexes:**
- Primary Key: `id`
- Unique: `email`
- Index: `gender`, `location`, `is_active`

---

## ⚙️ **Preferences Table**

**Table Name:** `preferences`  
**Description:** Stores user matching preferences and criteria

| Field Name | Data Type | Length | Nullable | Default | Description |
|------------|-----------|--------|----------|---------|-------------|
| `id` | BIGINT | - | NO | AUTO_INCREMENT | Primary key, unique identifier |
| `user_id` | BIGINT | - | NO | - | Foreign key to users table |
| `min_age` | INT | - | YES | 18 | Minimum preferred age |
| `max_age` | INT | - | YES | 65 | Maximum preferred age |
| `preferred_gender` | ENUM | - | NO | - | Preferred gender ('male', 'female', 'any') |
| `religion` | VARCHAR | 100 | YES | NULL | Preferred religion |
| `education_level` | VARCHAR | 100 | YES | NULL | Preferred education level |
| `location_preference` | VARCHAR | 255 | YES | NULL | Preferred location |
| `max_distance` | INT | - | YES | 50 | Maximum distance in km |
| `marital_status` | VARCHAR | 50 | YES | NULL | Preferred marital status |
| `occupation` | VARCHAR | 100 | YES | NULL | Preferred occupation |
| `income_range` | VARCHAR | 100 | YES | NULL | Preferred income range |
| `family_type` | VARCHAR | 50 | YES | NULL | Preferred family type |
| `created_at` | TIMESTAMP | - | NO | CURRENT_TIMESTAMP | Record creation timestamp |
| `updated_at` | TIMESTAMP | - | NO | CURRENT_TIMESTAMP | Record update timestamp |

**Indexes:**
- Primary Key: `id`
- Foreign Key: `user_id` → `users.id`
- Index: `preferred_gender`, `religion`, `location_preference`

---

## 💕 **Matches Table**

**Table Name:** `matches`  
**Description:** Stores mutual matches between users (pivot table)

| Field Name | Data Type | Length | Nullable | Default | Description |
|------------|-----------|--------|----------|---------|-------------|
| `id` | BIGINT | - | NO | AUTO_INCREMENT | Primary key, unique identifier |
| `user_id` | BIGINT | - | NO | - | First user in the match |
| `matched_user_id` | BIGINT | - | NO | - | Second user in the match |
| `match_date` | TIMESTAMP | - | NO | CURRENT_TIMESTAMP | When the match was created |
| `status` | ENUM | - | NO | 'active' | Match status ('active', 'inactive', 'blocked') |
| `created_at` | TIMESTAMP | - | NO | CURRENT_TIMESTAMP | Record creation timestamp |
| `updated_at` | TIMESTAMP | - | NO | CURRENT_TIMESTAMP | Record update timestamp |

**Indexes:**
- Primary Key: `id`
- Foreign Key: `user_id` → `users.id`
- Foreign Key: `matched_user_id` → `users.id`
- Unique: `user_id, matched_user_id`
- Index: `status`, `match_date`

---

## ❤️ **Likes Table**

**Table Name:** `likes`  
**Description:** Stores one-way likes between users

| Field Name | Data Type | Length | Nullable | Default | Description |
|------------|-----------|--------|----------|---------|-------------|
| `id` | BIGINT | - | NO | AUTO_INCREMENT | Primary key, unique identifier |
| `user_id` | BIGINT | - | NO | - | User who liked |
| `liked_user_id` | BIGINT | - | NO | - | User who was liked |
| `status` | ENUM | - | NO | 'active' | Like status ('active', 'removed') |
| `created_at` | TIMESTAMP | - | NO | CURRENT_TIMESTAMP | Record creation timestamp |
| `updated_at` | TIMESTAMP | - | NO | CURRENT_TIMESTAMP | Record update timestamp |

**Indexes:**
- Primary Key: `id`
- Foreign Key: `user_id` → `users.id`
- Foreign Key: `liked_user_id` → `users.id`
- Unique: `user_id, liked_user_id`
- Index: `status`, `created_at`

---

## 💬 **Messages Table**

**Table Name:** `messages`  
**Description:** Stores chat messages between matched users

| Field Name | Data Type | Length | Nullable | Default | Description |
|------------|-----------|--------|----------|---------|-------------|
| `id` | BIGINT | - | NO | AUTO_INCREMENT | Primary key, unique identifier |
| `from_user_id` | BIGINT | - | NO | - | Sender user ID |
| `to_user_id` | BIGINT | - | NO | - | Recipient user ID |
| `message` | TEXT | - | NO | - | Message content |
| `is_read` | BOOLEAN | - | NO | FALSE | Message read status |
| `read_at` | TIMESTAMP | - | YES | NULL | When message was read |
| `message_type` | ENUM | - | NO | 'text' | Message type ('text', 'image', 'file') |
| `attachment_url` | VARCHAR | 500 | YES | NULL | Attachment file URL |
| `created_at` | TIMESTAMP | - | NO | CURRENT_TIMESTAMP | Record creation timestamp |
| `updated_at` | TIMESTAMP | - | NO | CURRENT_TIMESTAMP | Record update timestamp |

**Indexes:**
- Primary Key: `id`
- Foreign Key: `from_user_id` → `users.id`
- Foreign Key: `to_user_id` → `users.id`
- Index: `is_read`, `created_at`, `from_user_id, to_user_id`

---

## 🔔 **Notifications Table**

**Table Name:** `notifications`  
**Description:** Stores push notification records and history

| Field Name | Data Type | Length | Nullable | Default | Description |
|------------|-----------|--------|----------|---------|-------------|
| `id` | BIGINT | - | NO | AUTO_INCREMENT | Primary key, unique identifier |
| `user_id` | BIGINT | - | NO | - | Recipient user ID |
| `type` | ENUM | - | NO | - | Notification type ('match', 'message', 'like', 'profile_view', 'system') |
| `title` | VARCHAR | 255 | NO | - | Notification title |
| `body` | TEXT | - | NO | - | Notification body/content |
| `data` | JSON | - | YES | NULL | Additional notification data |
| `is_read` | BOOLEAN | - | NO | FALSE | Notification read status |
| `read_at` | TIMESTAMP | - | YES | NULL | When notification was read |
| `fcm_message_id` | VARCHAR | 255 | YES | NULL | FCM message ID for tracking |
| `status` | ENUM | - | NO | 'sent' | Delivery status ('sent', 'delivered', 'failed') |
| `error_message` | TEXT | - | YES | NULL | Error message if delivery failed |
| `created_at` | TIMESTAMP | - | NO | CURRENT_TIMESTAMP | Record creation timestamp |
| `updated_at` | TIMESTAMP | - | NO | CURRENT_TIMESTAMP | Record update timestamp |

**Indexes:**
- Primary Key: `id`
- Foreign Key: `user_id` → `users.id`
- Index: `type`, `is_read`, `status`, `created_at`

---

## 🔑 **Personal Access Tokens Table**

**Table Name:** `personal_access_tokens`  
**Description:** Stores Laravel Sanctum authentication tokens

| Field Name | Data Type | Length | Nullable | Default | Description |
|------------|-----------|--------|----------|---------|-------------|
| `id` | BIGINT | - | NO | AUTO_INCREMENT | Primary key, unique identifier |
| `tokenable_type` | VARCHAR | 255 | NO | - | Model class name |
| `tokenable_id` | BIGINT | - | NO | - | Model instance ID |
| `name` | VARCHAR | 255 | NO | - | Token name/description |
| `token` | VARCHAR | 64 | NO | - | Hashed token value |
| `abilities` | TEXT | - | YES | NULL | Token abilities/permissions |
| `last_used_at` | TIMESTAMP | - | YES | NULL | Last token usage timestamp |
| `expires_at` | TIMESTAMP | - | YES | NULL | Token expiration timestamp |
| `created_at` | TIMESTAMP | - | NO | CURRENT_TIMESTAMP | Record creation timestamp |
| `updated_at` | TIMESTAMP | - | NO | CURRENT_TIMESTAMP | Record update timestamp |

**Indexes:**
- Primary Key: `id`
- Unique: `token`
- Index: `tokenable_type, tokenable_id`, `last_used_at`

---

## 🔗 **Database Relationships**

### **One-to-One Relationships**
- **User ↔ Preference**: Each user has one preference record
  - `users.id` → `preferences.user_id`

### **One-to-Many Relationships**
- **User → Messages (Sent)**: User can send multiple messages
  - `users.id` → `messages.from_user_id`
- **User → Messages (Received)**: User can receive multiple messages
  - `users.id` → `messages.to_user_id`
- **User → Notifications**: User can have multiple notifications
  - `users.id` → `notifications.user_id`
- **User → Personal Access Tokens**: User can have multiple tokens
  - `users.id` → `personal_access_tokens.tokenable_id`

### **Many-to-Many Relationships**
- **Users ↔ Users (Matches)**: Users can match with multiple users
  - `matches.user_id` → `users.id`
  - `matches.matched_user_id` → `users.id`
- **Users ↔ Users (Likes)**: Users can like multiple users
  - `likes.user_id` → `users.id`
  - `likes.liked_user_id` → `users.id`

---

## 📊 **Indexes and Constraints**

### **Primary Keys**
- All tables have `id` as primary key with AUTO_INCREMENT

### **Foreign Key Constraints**
```sql
-- Preferences
ALTER TABLE preferences ADD CONSTRAINT fk_preferences_user 
FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE;

-- Matches
ALTER TABLE matches ADD CONSTRAINT fk_matches_user 
FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE;
ALTER TABLE matches ADD CONSTRAINT fk_matches_matched_user 
FOREIGN KEY (matched_user_id) REFERENCES users(id) ON DELETE CASCADE;

-- Likes
ALTER TABLE likes ADD CONSTRAINT fk_likes_user 
FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE;
ALTER TABLE likes ADD CONSTRAINT fk_likes_liked_user 
FOREIGN KEY (liked_user_id) REFERENCES users(id) ON DELETE CASCADE;

-- Messages
ALTER TABLE messages ADD CONSTRAINT fk_messages_from_user 
FOREIGN KEY (from_user_id) REFERENCES users(id) ON DELETE CASCADE;
ALTER TABLE messages ADD CONSTRAINT fk_messages_to_user 
FOREIGN KEY (to_user_id) REFERENCES users(id) ON DELETE CASCADE;

-- Notifications
ALTER TABLE notifications ADD CONSTRAINT fk_notifications_user 
FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE;
```

### **Unique Constraints**
```sql
-- Users
ALTER TABLE users ADD CONSTRAINT uk_users_email UNIQUE (email);

-- Matches (prevent duplicate matches)
ALTER TABLE matches ADD CONSTRAINT uk_matches_users UNIQUE (user_id, matched_user_id);

-- Likes (prevent duplicate likes)
ALTER TABLE likes ADD CONSTRAINT uk_likes_users UNIQUE (user_id, liked_user_id);

-- Personal Access Tokens
ALTER TABLE personal_access_tokens ADD CONSTRAINT uk_pat_token UNIQUE (token);
```

### **Performance Indexes**
```sql
-- Users
CREATE INDEX idx_users_gender ON users(gender);
CREATE INDEX idx_users_location ON users(location);
CREATE INDEX idx_users_is_active ON users(is_active);

-- Preferences
CREATE INDEX idx_preferences_gender ON preferences(preferred_gender);
CREATE INDEX idx_preferences_religion ON preferences(religion);
CREATE INDEX idx_preferences_location ON preferences(location_preference);

-- Matches
CREATE INDEX idx_matches_status ON matches(status);
CREATE INDEX idx_matches_date ON matches(match_date);

-- Likes
CREATE INDEX idx_likes_status ON likes(status);
CREATE INDEX idx_likes_created ON likes(created_at);

-- Messages
CREATE INDEX idx_messages_read ON messages(is_read);
CREATE INDEX idx_messages_created ON messages(created_at);
CREATE INDEX idx_messages_conversation ON messages(from_user_id, to_user_id);

-- Notifications
CREATE INDEX idx_notifications_type ON notifications(type);
CREATE INDEX idx_notifications_read ON notifications(is_read);
CREATE INDEX idx_notifications_status ON notifications(status);
CREATE INDEX idx_notifications_created ON notifications(created_at);
```

---

## 📝 **Data Types and Standards**

### **String Fields**
- **Names**: VARCHAR(255) - Sufficient for full names
- **Emails**: VARCHAR(255) - Standard email length
- **Phone Numbers**: VARCHAR(20) - International format support
- **URLs**: VARCHAR(500) - Long URLs for attachments
- **Descriptions**: TEXT - Unlimited length for bios and messages

### **Numeric Fields**
- **IDs**: BIGINT - Large range for scalability
- **Ages**: INT - Reasonable range (0-150)
- **Distances**: INT - Kilometers, reasonable range

### **Date/Time Fields**
- **Timestamps**: TIMESTAMP - Automatic timezone handling
- **Dates**: DATE - For birth dates and events
- **Nullable**: Most date fields are nullable for flexibility

### **Boolean Fields**
- **Status Flags**: BOOLEAN - TRUE/FALSE for simple flags
- **Default Values**: Most boolean fields default to TRUE

### **Enum Fields**
- **Gender**: ENUM('male', 'female', 'other')
- **Match Status**: ENUM('active', 'inactive', 'blocked')
- **Like Status**: ENUM('active', 'removed')
- **Message Type**: ENUM('text', 'image', 'file')
- **Notification Type**: ENUM('match', 'message', 'like', 'profile_view', 'system')
- **Delivery Status**: ENUM('sent', 'delivered', 'failed')

### **JSON Fields**
- **Notification Data**: JSON - Flexible structure for additional data
- **Token Abilities**: TEXT - Comma-separated or JSON array

---

## 🔒 **Security Considerations**

### **Sensitive Data**
- **Passwords**: Hashed using Laravel's bcrypt
- **Tokens**: Hashed before storage
- **Personal Info**: Encrypted in transit and at rest

### **Data Privacy**
- **Soft Deletes**: Consider implementing for user data
- **Data Retention**: Implement policies for old data
- **GDPR Compliance**: User data export and deletion capabilities

### **Access Control**
- **Row-Level Security**: Users can only access their own data
- **API Authentication**: Required for all endpoints
- **Rate Limiting**: Prevent abuse and spam

---

## 📈 **Performance Considerations**

### **Query Optimization**
- **Indexes**: Strategic indexes on frequently queried fields
- **Composite Indexes**: For complex queries (user_id + status)
- **Covering Indexes**: Include frequently selected fields

### **Data Archiving**
- **Old Messages**: Archive after 1 year
- **Old Notifications**: Archive after 6 months
- **Inactive Users**: Mark as inactive after 1 year

### **Caching Strategy**
- **User Profiles**: Cache frequently accessed profiles
- **Preferences**: Cache user preferences
- **Match Lists**: Cache user matches

---

## 🔄 **Data Migration Guidelines**

### **Adding New Fields**
1. Create migration with proper defaults
2. Update models and validation
3. Update API documentation
4. Test thoroughly

### **Modifying Existing Fields**
1. Create migration with data transformation
2. Validate existing data
3. Update application code
4. Test with real data

### **Removing Fields**
1. Mark as deprecated first
2. Remove from application code
3. Create migration to drop column
4. Update documentation

---

This Data Dictionary serves as the authoritative reference for the Matrimonial API database schema. It should be updated whenever the database structure changes to maintain accuracy and consistency across the development team.
