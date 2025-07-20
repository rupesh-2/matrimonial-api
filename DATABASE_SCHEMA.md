# Matrimonial API Database Schema

## Entity Relationship Diagram

```plantuml
@startuml MatrimonialAPI_Schema

!define table(x) class x << (T,#FFAAAA) >>
!define primary_key(x) <b>x</b>
!define foreign_key(x) <i>x</i>

' Users Table
table(users) {
    primary_key(id) : bigint
    name : varchar
    email : varchar (unique)
    email_verified_at : timestamp (nullable)
    password : varchar
    age : integer (nullable)
    gender : enum('male', 'female', 'other') (nullable)
    religion : varchar (nullable)
    caste : varchar (nullable)
    income : integer (nullable)
    education : varchar (nullable)
    location : varchar (nullable)
    occupation : varchar (nullable)
    bio : text (nullable)
    profile_picture : varchar (nullable)
    remember_token : varchar (nullable)
    created_at : timestamp
    updated_at : timestamp
}

' Preferences Table
table(preferences) {
    primary_key(id) : bigint
    foreign_key(user_id) : bigint
    preferred_age_min : integer (nullable)
    preferred_age_max : integer (nullable)
    preferred_gender : enum('male', 'female', 'other') (nullable)
    preferred_religion : varchar (nullable)
    preferred_caste : varchar (nullable)
    preferred_income_min : integer (nullable)
    preferred_income_max : integer (nullable)
    preferred_education : varchar (nullable)
    preferred_location : varchar (nullable)
    preferred_occupation : varchar (nullable)
    age_weight : float (default: 1.0)
    gender_weight : float (default: 1.0)
    religion_weight : float (default: 1.0)
    caste_weight : float (default: 1.0)
    income_weight : float (default: 1.0)
    education_weight : float (default: 1.0)
    location_weight : float (default: 1.0)
    occupation_weight : float (default: 1.0)
    created_at : timestamp
    updated_at : timestamp
}

' Matches Table
table(matches) {
    primary_key(id) : bigint
    foreign_key(user_id) : bigint
    foreign_key(matched_user_id) : bigint
    created_at : timestamp
    updated_at : timestamp
}

' Likes Table
table(likes) {
    primary_key(id) : bigint
    foreign_key(user_id) : bigint
    foreign_key(liked_user_id) : bigint
    created_at : timestamp
    updated_at : timestamp
}

' Messages Table
table(messages) {
    primary_key(id) : bigint
    foreign_key(from_user_id) : bigint
    foreign_key(to_user_id) : bigint
    message : text
    is_read : boolean (default: false)
    created_at : timestamp
    updated_at : timestamp
}

' Relationships
users ||--o{ preferences : "has"
users ||--o{ matches : "user_matches"
users ||--o{ matches : "matched_by"
users ||--o{ likes : "likes"
users ||--o{ likes : "liked_by"
users ||--o{ messages : "sends"
users ||--o{ messages : "receives"

' Constraints
note right of matches
  Unique constraint on (user_id, matched_user_id)
end note

note right of likes
  Unique constraint on (user_id, liked_user_id)
end note

note right of messages
  Indexes on (from_user_id, to_user_id)
  and (to_user_id, from_user_id)
end note

@enduml
```

## Database Schema Description

### Core Tables

#### 1. **users** - Main user profiles

-   **Primary Key**: `id` (auto-incrementing bigint)
-   **Unique Fields**: `email`
-   **Profile Information**: name, age, gender, religion, caste, income, education, location, occupation, bio, profile_picture
-   **Authentication**: email, password, email_verified_at, remember_token
-   **Timestamps**: created_at, updated_at

#### 2. **preferences** - User matching preferences

-   **Primary Key**: `id` (auto-incrementing bigint)
-   **Foreign Key**: `user_id` → users.id (cascade delete)
-   **Preference Ranges**: age_min/max, income_min/max
-   **Preference Values**: gender, religion, caste, education, location, occupation
-   **Weight System**: Individual weights for each preference category (default: 1.0)
-   **Timestamps**: created_at, updated_at

#### 3. **matches** - Mutual matches between users

-   **Primary Key**: `id` (auto-incrementing bigint)
-   **Foreign Keys**:
    -   `user_id` → users.id (cascade delete)
    -   `matched_user_id` → users.id (cascade delete)
-   **Unique Constraint**: (user_id, matched_user_id) prevents duplicate matches
-   **Timestamps**: created_at, updated_at

#### 4. **likes** - User likes/interests

-   **Primary Key**: `id` (auto-incrementing bigint)
-   **Foreign Keys**:
    -   `user_id` → users.id (cascade delete)
    -   `liked_user_id` → users.id (cascade delete)
-   **Unique Constraint**: (user_id, liked_user_id) prevents duplicate likes
-   **Timestamps**: created_at, updated_at

#### 5. **messages** - Communication between matched users

-   **Primary Key**: `id` (auto-incrementing bigint)
-   **Foreign Keys**:
    -   `from_user_id` → users.id (cascade delete)
    -   `to_user_id` → users.id (cascade delete)
-   **Message Data**: message (text), is_read (boolean)
-   **Indexes**: Optimized for conversation queries
-   **Timestamps**: created_at, updated_at

### Key Features

1. **Cascade Deletes**: When a user is deleted, all related records (preferences, matches, likes, messages) are automatically removed
2. **Unique Constraints**: Prevents duplicate matches and likes
3. **Indexed Queries**: Messages table has indexes for efficient conversation retrieval
4. **Flexible Preferences**: Weight-based preference system allows for customizable matching algorithms
5. **Comprehensive User Profiles**: Rich profile data including cultural and professional information

### Relationships

-   **One-to-One**: User ↔ Preferences (each user has one preference set)
-   **Many-to-Many**: Users ↔ Users (through matches, likes, and messages)
-   **Self-Referential**: Users can match with, like, and message other users

This schema supports a complete matrimonial matching system with profile management, preference-based matching, mutual connections, and messaging capabilities.
