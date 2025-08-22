# Real Entity Relationship Diagram (ERD)

## Matrimonial API Database Schema

### Traditional ER Notation

---

## 1. Entity Relationship Diagram

```
┌─────────────────────────────────────────────────────────────────────────────────┐
│                                    USERS                                        │
├─────────────────────────────────────────────────────────────────────────────────┤
│ PK: id (BIGINT)                                                                 │
│     name (VARCHAR(255))                                                         │
│     email (VARCHAR(255)) UNIQUE                                                 │
│     email_verified_at (TIMESTAMP) NULL                                          │
│     password (VARCHAR(255))                                                     │
│     age (INTEGER) NULL                                                          │
│     gender (ENUM('male','female','other')) NULL                                │
│     religion (VARCHAR(255)) NULL                                                │
│     caste (VARCHAR(255)) NULL                                                   │
│     income (INTEGER) NULL                                                       │
│     education (VARCHAR(255)) NULL                                               │
│     location (VARCHAR(255)) NULL                                                │
│     occupation (VARCHAR(255)) NULL                                              │
│     bio (TEXT) NULL                                                             │
│     profile_picture (VARCHAR(255)) NULL                                         │
│     remember_token (VARCHAR(100)) NULL                                          │
│     created_at (TIMESTAMP)                                                      │
│     updated_at (TIMESTAMP)                                                      │
└─────────────────────────────────────────────────────────────────────────────────┘
                                        │
                                        │ 1
                                        │
                                        ▼
┌─────────────────────────────────────────────────────────────────────────────────┐
│                                 PREFERENCES                                     │
├─────────────────────────────────────────────────────────────────────────────────┤
│ PK: id (BIGINT)                                                                 │
│ FK: user_id (BIGINT) → USERS(id) CASCADE DELETE                                │
│     preferred_age_min (INTEGER) NULL                                            │
│     preferred_age_max (INTEGER) NULL                                            │
│     preferred_gender (ENUM('male','female','other')) NULL                      │
│     preferred_religion (VARCHAR(255)) NULL                                      │
│     preferred_caste (VARCHAR(255)) NULL                                         │
│     preferred_income_min (INTEGER) NULL                                         │
│     preferred_income_max (INTEGER) NULL                                         │
│     preferred_education (VARCHAR(255)) NULL                                     │
│     preferred_location (VARCHAR(255)) NULL                                      │
│     preferred_occupation (VARCHAR(255)) NULL                                    │
│     age_weight (FLOAT) DEFAULT 1.0                                              │
│     gender_weight (FLOAT) DEFAULT 1.0                                           │
│     religion_weight (FLOAT) DEFAULT 1.0                                         │
│     caste_weight (FLOAT) DEFAULT 1.0                                            │
│     income_weight (FLOAT) DEFAULT 1.0                                           │
│     education_weight (FLOAT) DEFAULT 1.0                                        │
│     location_weight (FLOAT) DEFAULT 1.0                                         │
│     occupation_weight (FLOAT) DEFAULT 1.0                                       │
│     created_at (TIMESTAMP)                                                      │
│     updated_at (TIMESTAMP)                                                      │
└─────────────────────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────────────────────┐
│                                    MATCHES                                      │
├─────────────────────────────────────────────────────────────────────────────────┤
│ PK: id (BIGINT)                                                                 │
│ FK: user_id (BIGINT) → USERS(id) CASCADE DELETE                                │
│ FK: matched_user_id (BIGINT) → USERS(id) CASCADE DELETE                        │
│     created_at (TIMESTAMP)                                                      │
│     updated_at (TIMESTAMP)                                                      │
│                                                                                 │
│ UNIQUE CONSTRAINT: (user_id, matched_user_id)                                  │
└─────────────────────────────────────────────────────────────────────────────────┘
                                        ▲
                                        │
                                        │ M:N
                                        │
┌─────────────────────────────────────────────────────────────────────────────────┐
│                                    LIKES                                        │
├─────────────────────────────────────────────────────────────────────────────────┤
│ PK: id (BIGINT)                                                                 │
│ FK: user_id (BIGINT) → USERS(id) CASCADE DELETE                                │
│ FK: liked_user_id (BIGINT) → USERS(id) CASCADE DELETE                          │
│     created_at (TIMESTAMP)                                                      │
│     updated_at (TIMESTAMP)                                                      │
│                                                                                 │
│ UNIQUE CONSTRAINT: (user_id, liked_user_id)                                    │
└─────────────────────────────────────────────────────────────────────────────────┘
                                        ▲
                                        │
                                        │ M:N
                                        │
┌─────────────────────────────────────────────────────────────────────────────────┐
│                                   MESSAGES                                       │
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
│                            PERSONAL_ACCESS_TOKENS                               │
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

┌─────────────────────────────────────────────────────────────────────────────────┐
│                            PASSWORD_RESET_TOKENS                                │
├─────────────────────────────────────────────────────────────────────────────────┤
│ PK: email (VARCHAR(255))                                                        │
│     token (VARCHAR(255))                                                        │
│     created_at (TIMESTAMP) NULL                                                 │
└─────────────────────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────────────────────┐
│                                   SESSIONS                                       │
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
│                                    CACHE                                        │
├─────────────────────────────────────────────────────────────────────────────────┤
│ PK: key (VARCHAR(255))                                                          │
│     value (LONGTEXT)                                                            │
│     expiration (INTEGER)                                                        │
└─────────────────────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────────────────────┐
│                                    JOBS                                         │
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

## 2. Entity Definitions

### 2.1 Primary Entities

#### **USERS** (Primary Entity)

-   **Purpose**: Central entity storing user profile information
-   **Cardinality**: 1 (Primary entity)
-   **Key Attributes**:
    -   `id` (Primary Key)
    -   `email` (Unique identifier)
    -   `name`, `password` (Required fields)
    -   All other fields are optional (NULL)

#### **PREFERENCES** (Dependent Entity)

-   **Purpose**: User matching criteria and weights
-   **Cardinality**: 1:1 with USERS
-   **Key Attributes**:
    -   `id` (Primary Key)
    -   `user_id` (Foreign Key to USERS)
    -   Preference ranges and weights for matching algorithm

### 2.2 Relationship Entities

#### **MATCHES** (Junction Entity)

-   **Purpose**: Records mutual matches between users
-   **Cardinality**: M:N between USERS
-   **Key Attributes**:
    -   `id` (Primary Key)
    -   `user_id`, `matched_user_id` (Foreign Keys to USERS)
    -   Unique constraint prevents duplicate matches

#### **LIKES** (Junction Entity)

-   **Purpose**: Records one-way user interests
-   **Cardinality**: M:N between USERS
-   **Key Attributes**:
    -   `id` (Primary Key)
    -   `user_id`, `liked_user_id` (Foreign Keys to USERS)
    -   Unique constraint prevents duplicate likes

#### **MESSAGES** (Junction Entity)

-   **Purpose**: Communication between matched users
-   **Cardinality**: M:N between USERS
-   **Key Attributes**:
    -   `id` (Primary Key)
    -   `from_user_id`, `to_user_id` (Foreign Keys to USERS)
    -   `message`, `is_read` (Message content and status)

### 2.3 Authentication Entities

#### **PERSONAL_ACCESS_TOKENS** (Polymorphic Entity)

-   **Purpose**: API authentication tokens
-   **Cardinality**: 1:M with USERS (polymorphic)
-   **Key Attributes**:
    -   `id` (Primary Key)
    -   `tokenable_type`, `tokenable_id` (Polymorphic relationship)
    -   `token` (Unique API token)

#### **PASSWORD_RESET_TOKENS** (Standalone Entity)

-   **Purpose**: Password reset functionality
-   **Cardinality**: Independent
-   **Key Attributes**:
    -   `email` (Primary Key)
    -   `token` (Reset token)

#### **SESSIONS** (Dependent Entity)

-   **Purpose**: User session management
-   **Cardinality**: 1:M with USERS
-   **Key Attributes**:
    -   `id` (Primary Key, string)
    -   `user_id` (Foreign Key to USERS, nullable)

### 2.4 System Entities

#### **CACHE** (Standalone Entity)

-   **Purpose**: Application caching
-   **Cardinality**: Independent
-   **Key Attributes**:
    -   `key` (Primary Key)
    -   `value`, `expiration`

#### **JOBS** (Standalone Entity)

-   **Purpose**: Background job processing
-   **Cardinality**: Independent
-   **Key Attributes**:
    -   `id` (Primary Key)
    -   `queue`, `payload`, `attempts`

---

## 3. Relationship Definitions

### 3.1 Primary Relationships

1. **USERS ⟷ PREFERENCES (1:1)**

    - Each user has exactly one preference set
    - Cascade delete: When user is deleted, preferences are deleted

2. **USERS ⟷ USERS through MATCHES (M:N)**

    - Self-referential many-to-many relationship
    - Junction table: MATCHES
    - Bidirectional: If A matches B, B matches A

3. **USERS ⟷ USERS through LIKES (M:N)**

    - Self-referential many-to-many relationship
    - Junction table: LIKES
    - Asymmetric: A can like B without B liking A

4. **USERS ⟷ USERS through MESSAGES (M:N)**
    - Self-referential many-to-many relationship
    - Junction table: MESSAGES
    - Bidirectional communication

### 3.2 Authentication Relationships

1. **USERS ⟷ PERSONAL_ACCESS_TOKENS (1:M)**

    - Polymorphic relationship
    - Users can have multiple API tokens
    - Flexible token ownership

2. **USERS ⟷ SESSIONS (1:M)**
    - Users can have multiple active sessions
    - Sessions track user activity

---

## 4. Constraints and Business Rules

### 4.1 Primary Key Constraints

-   All entities have primary keys
-   Most use auto-incrementing `id` (BIGINT)
-   Exceptions: `password_reset_tokens.email`, `sessions.id` (VARCHAR)

### 4.2 Foreign Key Constraints

-   All foreign keys reference `USERS.id`
-   Cascade delete ensures referential integrity
-   Nullable foreign keys where appropriate

### 4.3 Unique Constraints

-   `USERS.email` - Unique email addresses
-   `MATCHES(user_id, matched_user_id)` - No duplicate matches
-   `LIKES(user_id, liked_user_id)` - No duplicate likes
-   `PERSONAL_ACCESS_TOKENS.token` - Unique API tokens

### 4.4 Business Rules

1. Users must have unique email addresses
2. Users can only match with each other once
3. Users can only like each other once
4. Messages can only be sent between users
5. Preferences are automatically deleted when user is deleted
6. All user-related data is deleted when user is deleted (cascade)

---

## 5. Normalization Analysis

### 5.1 First Normal Form (1NF)

✅ All tables are in 1NF

-   All attributes contain atomic values
-   No repeating groups
-   Primary keys are defined

### 5.2 Second Normal Form (2NF)

✅ All tables are in 2NF

-   All non-key attributes are fully dependent on primary keys
-   No partial dependencies

### 5.3 Third Normal Form (3NF)

✅ All tables are in 3NF

-   No transitive dependencies
-   Junction tables properly separate many-to-many relationships

---

_This is a proper Entity Relationship Diagram showing the actual database entities, their attributes, data types, constraints, and relationships as implemented in the Matrimonial API project._
