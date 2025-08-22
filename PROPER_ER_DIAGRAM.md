# Proper Entity Relationship Diagram (ERD)

## Matrimonial API Database Schema

### Traditional ER Notation with Proper Arrows

---

## 1. Entity Relationship Diagram

```
                    ┌─────────────────────────────────────────────────────────────┐
                    │                            USERS                           │
                    ├─────────────────────────────────────────────────────────────┤
                    │ PK: id (BIGINT)                                            │
                    │     name (VARCHAR(255))                                    │
                    │     email (VARCHAR(255)) UNIQUE                            │
                    │     email_verified_at (TIMESTAMP) NULL                     │
                    │     password (VARCHAR(255))                                │
                    │     age (INTEGER) NULL                                     │
                    │     gender (ENUM('male','female','other')) NULL           │
                    │     religion (VARCHAR(255)) NULL                           │
                    │     caste (VARCHAR(255)) NULL                              │
                    │     income (INTEGER) NULL                                  │
                    │     education (VARCHAR(255)) NULL                          │
                    │     location (VARCHAR(255)) NULL                           │
                    │     occupation (VARCHAR(255)) NULL                         │
                    │     bio (TEXT) NULL                                        │
                    │     profile_picture (VARCHAR(255)) NULL                    │
                    │     remember_token (VARCHAR(100)) NULL                     │
                    │     created_at (TIMESTAMP)                                 │
                    │     updated_at (TIMESTAMP)                                 │
                    └─────────────────────────────────────────────────────────────┘
                                    │
                                    │ 1
                                    │
                                    ▼
                    ┌─────────────────────────────────────────────────────────────┐
                    │                         PREFERENCES                        │
                    ├─────────────────────────────────────────────────────────────┤
                    │ PK: id (BIGINT)                                            │
                    │ FK: user_id (BIGINT) → USERS(id) CASCADE DELETE           │
                    │     preferred_age_min (INTEGER) NULL                       │
                    │     preferred_age_max (INTEGER) NULL                       │
                    │     preferred_gender (ENUM('male','female','other')) NULL │
                    │     preferred_religion (VARCHAR(255)) NULL                 │
                    │     preferred_caste (VARCHAR(255)) NULL                    │
                    │     preferred_income_min (INTEGER) NULL                    │
                    │     preferred_income_max (INTEGER) NULL                    │
                    │     preferred_education (VARCHAR(255)) NULL                │
                    │     preferred_location (VARCHAR(255)) NULL                 │
                    │     preferred_occupation (VARCHAR(255)) NULL               │
                    │     age_weight (FLOAT) DEFAULT 1.0                         │
                    │     gender_weight (FLOAT) DEFAULT 1.0                      │
                    │     religion_weight (FLOAT) DEFAULT 1.0                    │
                    │     caste_weight (FLOAT) DEFAULT 1.0                       │
                    │     income_weight (FLOAT) DEFAULT 1.0                      │
                    │     education_weight (FLOAT) DEFAULT 1.0                   │
                    │     location_weight (FLOAT) DEFAULT 1.0                    │
                    │     occupation_weight (FLOAT) DEFAULT 1.0                  │
                    │     created_at (TIMESTAMP)                                 │
                    │     updated_at (TIMESTAMP)                                 │
                    └─────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────────────────────┐
│                                MATCHES                                          │
├─────────────────────────────────────────────────────────────────────────────────┤
│ PK: id (BIGINT)                                                                 │
│ FK: user_id (BIGINT) → USERS(id) CASCADE DELETE                                │
│ FK: matched_user_id (BIGINT) → USERS(id) CASCADE DELETE                        │
│     created_at (TIMESTAMP)                                                      │
│     updated_at (TIMESTAMP)                                                      │
│                                                                                 │
│ UNIQUE CONSTRAINT: (user_id, matched_user_id)                                  │
└─────────────────────────────────────────────────────────────────────────────────┘
                    ▲                                     ▲
                    │                                     │
                    │ M                                   │ M
                    │                                     │
                    └─────────────────────────────────────┘
                                 │
                                 │ N
                                 │
                                 ▼
┌─────────────────────────────────────────────────────────────────────────────────┐
│                                LIKES                                            │
├─────────────────────────────────────────────────────────────────────────────────┤
│ PK: id (BIGINT)                                                                 │
│ FK: user_id (BIGINT) → USERS(id) CASCADE DELETE                                │
│ FK: liked_user_id (BIGINT) → USERS(id) CASCADE DELETE                          │
│     created_at (TIMESTAMP)                                                      │
│     updated_at (TIMESTAMP)                                                      │
│                                                                                 │
│ UNIQUE CONSTRAINT: (user_id, liked_user_id)                                    │
└─────────────────────────────────────────────────────────────────────────────────┘
                    ▲                                     ▲
                    │                                     │
                    │ M                                   │ M
                    │                                     │
                    └─────────────────────────────────────┘
                                 │
                                 │ N
                                 │
                                 ▼
┌─────────────────────────────────────────────────────────────────────────────────┐
│                               MESSAGES                                          │
├─────────────────────────────────────────────────────────────────────────────────┤
│ PK: id (BIGINT)                                                                 │
│ FK: from_user_id (BIGINT) → USERS(id) CASCADE DELETE                           │
│ FK: to_user_id (BIGINT) → USERS(id) CASCADE DELETE                             │
│     message (TEXT)                                                              │
│     is_read (BOOLEAN) DEFAULT FALSE                                            │
│     created_at (TIMESTAMP)                                                      │
│     updated_at (TIMESTAMP)                                                      │
│                                                                                 │
│ INDEXES: (from_user_id, to_user_id), (to_user_id, from_user_id)               │
└─────────────────────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────────────────────┐
│                        PERSONAL_ACCESS_TOKENS                                  │
├─────────────────────────────────────────────────────────────────────────────────┤
│ PK: id (BIGINT)                                                                 │
│     tokenable_type (VARCHAR(255))                                               │
│     tokenable_id (BIGINT)                                                       │
│     name (TEXT)                                                                 │
│     token (VARCHAR(64)) UNIQUE                                                  │
│     abilities (TEXT) NULL                                                       │
│     last_used_at (TIMESTAMP) NULL                                               │
│     expires_at (TIMESTAMP) NULL                                                 │
│     created_at (TIMESTAMP)                                                      │
│     updated_at (TIMESTAMP)                                                      │
│                                                                                 │
│ POLYMORPHIC: tokenable_type + tokenable_id → USERS(id)                         │
└─────────────────────────────────────────────────────────────────────────────────┘
                    ▲
                    │
                    │ M
                    │
                    └─────────────────┐
                                      │
                                      │ 1
                                      │
                                      ▼
┌─────────────────────────────────────────────────────────────────────────────────┐
│                               SESSIONS                                          │
├─────────────────────────────────────────────────────────────────────────────────┤
│ PK: id (VARCHAR(255))                                                           │
│ FK: user_id (BIGINT) → USERS(id) NULL                                           │
│     ip_address (VARCHAR(45)) NULL                                               │
│     user_agent (TEXT) NULL                                                      │
│     payload (LONGTEXT)                                                          │
│     last_activity (INTEGER)                                                     │
│                                                                                 │
│ INDEXES: user_id, last_activity                                                 │
└─────────────────────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────────────────────┐
│                            PASSWORD_RESET_TOKENS                                │
├─────────────────────────────────────────────────────────────────────────────────┤
│ PK: email (VARCHAR(255))                                                        │
│     token (VARCHAR(255))                                                        │
│     created_at (TIMESTAMP) NULL                                                 │
└─────────────────────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────────────────────┐
│                                CACHE                                            │
├─────────────────────────────────────────────────────────────────────────────────┤
│ PK: key (VARCHAR(255))                                                          │
│     value (LONGTEXT)                                                            │
│     expiration (INTEGER)                                                        │
└─────────────────────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────────────────────┐
│                                JOBS                                             │
├─────────────────────────────────────────────────────────────────────────────────┤
│ PK: id (BIGINT)                                                                 │
│     queue (VARCHAR(255))                                                        │
│     payload (LONGTEXT)                                                          │
│     attempts (TINYINT)                                                          │
│     reserved_at (INTEGER) NULL                                                  │
│     available_at (INTEGER) NULL                                                 │
│     created_at (INTEGER)                                                        │
│                                                                                 │
│ INDEXES: queue, reserved_at                                                     │
└─────────────────────────────────────────────────────────────────────────────────┘
```

---

## 2. Relationship Legend

### 2.1 Cardinality Notation

-   **1** = One (exactly one)
-   **M** = Many (zero or more)
-   **N** = Many (zero or more)

### 2.2 Relationship Types

-   **1:1** = One-to-One relationship
-   **1:M** = One-to-Many relationship
-   **M:N** = Many-to-Many relationship

### 2.3 Arrow Meanings

-   **→** = Foreign key reference
-   **▲** = Many side of relationship
-   **▼** = One side of relationship

---

## 3. Key Relationships Explained

### 3.1 Primary Relationships

1. **USERS → PREFERENCES (1:1)**

    - Each user has exactly one preference set
    - Cascade delete when user is deleted

2. **USERS ↔ MATCHES ↔ USERS (M:N)**

    - Self-referential many-to-many through MATCHES junction table
    - Bidirectional: If A matches B, B matches A

3. **USERS ↔ LIKES ↔ USERS (M:N)**

    - Self-referential many-to-many through LIKES junction table
    - Asymmetric: A can like B without B liking A

4. **USERS ↔ MESSAGES ↔ USERS (M:N)**
    - Self-referential many-to-many through MESSAGES junction table
    - Bidirectional communication

### 3.2 Authentication Relationships

1. **USERS → PERSONAL_ACCESS_TOKENS (1:M)**

    - Polymorphic relationship
    - Users can have multiple API tokens

2. **USERS → SESSIONS (1:M)**
    - Users can have multiple active sessions

### 3.3 Independent Entities

-   **PASSWORD_RESET_TOKENS** - Standalone entity
-   **CACHE** - Standalone entity
-   **JOBS** - Standalone entity

---

_This ER diagram shows the proper relationships with arrows and cardinality notation as used in traditional database design._
