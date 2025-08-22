# Matrimonial API ER Diagram (Mermaid Version)

## Complete Entity Relationship Diagram

```mermaid
erDiagram
    users {
        bigint id PK
        varchar name
        varchar email UK
        timestamp email_verified_at
        varchar password
        integer age
        enum gender
        varchar religion
        varchar caste
        integer income
        varchar education
        varchar location
        varchar occupation
        text bio
        varchar profile_picture
        varchar remember_token
        timestamp created_at
        timestamp updated_at
    }

    preferences {
        bigint id PK
        bigint user_id FK
        integer preferred_age_min
        integer preferred_age_max
        enum preferred_gender
        varchar preferred_religion
        varchar preferred_caste
        integer preferred_income_min
        integer preferred_income_max
        varchar preferred_education
        varchar preferred_location
        varchar preferred_occupation
        float age_weight
        float gender_weight
        float religion_weight
        float caste_weight
        float income_weight
        float education_weight
        float location_weight
        float occupation_weight
        timestamp created_at
        timestamp updated_at
    }

    matches {
        bigint id PK
        bigint user_id FK
        bigint matched_user_id FK
        timestamp created_at
        timestamp updated_at
    }

    likes {
        bigint id PK
        bigint user_id FK
        bigint liked_user_id FK
        timestamp created_at
        timestamp updated_at
    }

    messages {
        bigint id PK
        bigint from_user_id FK
        bigint to_user_id FK
        text message
        boolean is_read
        timestamp created_at
        timestamp updated_at
    }

    personal_access_tokens {
        bigint id PK
        varchar tokenable_type
        bigint tokenable_id
        text name
        varchar token UK
        text abilities
        timestamp last_used_at
        timestamp expires_at
        timestamp created_at
        timestamp updated_at
    }

    password_reset_tokens {
        varchar email PK
        varchar token
        timestamp created_at
    }

    sessions {
        varchar id PK
        bigint user_id FK
        varchar ip_address
        text user_agent
        longtext payload
        integer last_activity
    }

    cache {
        varchar key PK
        longtext value
        integer expiration
    }

    jobs {
        bigint id PK
        varchar queue
        longtext payload
        tinyint attempts
        integer reserved_at
        integer available_at
        integer created_at
    }

    %% Core Business Relationships
    users ||--o{ preferences : "has preferences"
    users ||--o{ matches : "user_matches"
    users ||--o{ matches : "matched_by"
    users ||--o{ likes : "likes"
    users ||--o{ likes : "liked_by"
    users ||--o{ messages : "sends"
    users ||--o{ messages : "receives"

    %% Authentication Relationships
    users ||--o{ personal_access_tokens : "has tokens"
    users ||--o{ sessions : "has sessions"

    %% Self-referential relationships
    users ||--o{ users : "matches_with"
    users ||--o{ users : "likes"
    users ||--o{ users : "messages"
```

## Key Features of the ER Diagram

### 1. **Core Business Entities**

-   **Users**: Comprehensive user profiles with demographic, professional, and personal information
-   **Preferences**: User-defined matching criteria with weighted scoring system
-   **Matches**: Mutual connections between users
-   **Likes**: One-way user interests
-   **Messages**: Communication system between matched users

### 2. **Authentication & Security**

-   **Personal Access Tokens**: API authentication with polymorphic relationships
-   **Password Reset Tokens**: Secure password recovery system
-   **Sessions**: User session management

### 3. **System Tables**

-   **Cache**: Application-level caching for performance
-   **Jobs**: Background job processing with queue management

### 4. **Data Integrity Features**

-   **Primary Keys**: Auto-incrementing IDs for most tables
-   **Foreign Keys**: Cascade delete for referential integrity
-   **Unique Constraints**: Prevents duplicate data
-   **Indexes**: Optimized for query performance

### 5. **Relationship Types**

-   **One-to-One**: User ↔ Preferences
-   **One-to-Many**: User ↔ Tokens, User ↔ Sessions
-   **Many-to-Many**: User ↔ User (through matches, likes, messages)
-   **Self-Referential**: Users can connect with other users

## Database Schema Summary

| Table                    | Purpose                  | Key Relationships                              |
| ------------------------ | ------------------------ | ---------------------------------------------- |
| `users`                  | User profiles            | Primary entity, referenced by all other tables |
| `preferences`            | Matching criteria        | One-to-one with users                          |
| `matches`                | Mutual connections       | Many-to-many between users                     |
| `likes`                  | User interests           | Many-to-many between users                     |
| `messages`               | Communication            | Many-to-many between users                     |
| `personal_access_tokens` | API authentication       | Polymorphic relationship                       |
| `password_reset_tokens`  | Password recovery        | Email-based                                    |
| `sessions`               | Session management       | One-to-many with users                         |
| `cache`                  | Performance optimization | Standalone                                     |
| `jobs`                   | Background processing    | Standalone                                     |

## Constraints and Indexes

### Unique Constraints

-   `users.email` - Unique email addresses
-   `matches(user_id, matched_user_id)` - No duplicate matches
-   `likes(user_id, liked_user_id)` - No duplicate likes
-   `personal_access_tokens.token` - Unique API tokens

### Foreign Key Constraints

-   All foreign keys have `ON DELETE CASCADE`
-   Ensures data integrity when users are deleted

### Performance Indexes

-   Messages: `(from_user_id, to_user_id)` and `(to_user_id, from_user_id)`
-   Sessions: `user_id` and `last_activity`
-   Jobs: `queue` and `reserved_at`

This ER diagram represents the complete database structure of the Matrimonial API project, suitable for academic documentation and technical analysis.
