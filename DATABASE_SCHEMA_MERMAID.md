# Matrimonial API Database Schema (Mermaid)

## Entity Relationship Diagram

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

    %% Relationships
    users ||--|| preferences : "has"
    users ||--o{ matches : "user_matches"
    users ||--o{ matches : "matched_by"
    users ||--o{ likes : "likes"
    users ||--o{ likes : "liked_by"
    users ||--o{ messages : "sends"
    users ||--o{ messages : "receives"
```

## Database Schema Summary

### Table Structure

| Table           | Purpose                          | Key Relationships                              |
| --------------- | -------------------------------- | ---------------------------------------------- |
| **users**       | User profiles and authentication | Primary entity, referenced by all other tables |
| **preferences** | User matching preferences        | One-to-one with users                          |
| **matches**     | Mutual matches between users     | Many-to-many self-referential through users    |
| **likes**       | User likes/interests             | Many-to-many self-referential through users    |
| **messages**    | Communication between users      | Many-to-many self-referential through users    |

### Key Features

1. **Comprehensive User Profiles**: Rich data including cultural, professional, and personal information
2. **Flexible Matching System**: Weight-based preferences for customizable algorithms
3. **Mutual Connections**: Matches require mutual interest
4. **Communication System**: Messaging between matched users
5. **Data Integrity**: Foreign key constraints with cascade deletes
6. **Performance Optimization**: Indexed queries for efficient data retrieval

### Constraints and Indexes

-   **Unique Constraints**:

    -   `users.email` - No duplicate emails
    -   `matches(user_id, matched_user_id)` - No duplicate matches
    -   `likes(user_id, liked_user_id)` - No duplicate likes

-   **Indexes**:

    -   `messages(from_user_id, to_user_id)` - Fast conversation queries
    -   `messages(to_user_id, from_user_id)` - Fast conversation queries

-   **Foreign Key Constraints**:
    -   All foreign keys have cascade delete for data consistency

This schema provides a solid foundation for a matrimonial matching platform with all essential features for user management, matching, and communication.
