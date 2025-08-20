# Matrimonial API - Schema Diagram

## 🏗️ System Architecture Overview

The Matrimonial API follows a layered architecture pattern with clear separation of concerns, RESTful API design, and scalable database structure.

## 📊 Complete System Schema Diagram

```mermaid
graph TB
    %% External Clients
    subgraph "Client Applications"
        WebApp[Web Application]
        MobileApp[Mobile Application]
        AdminPanel[Admin Panel]
    end

    %% API Gateway Layer
    subgraph "API Gateway Layer"
        LoadBalancer[Load Balancer]
        RateLimiter[Rate Limiter]
        CORS[CORS Handler]
    end

    %% Application Layer
    subgraph "Laravel Application Layer"
        subgraph "Authentication Layer"
            Sanctum[Laravel Sanctum]
            Middleware[Auth Middleware]
            TokenManager[Token Manager]
        end

        subgraph "Controller Layer"
            AuthController[Auth Controller]
            UserController[User Controller]
            MatchController[Match Controller]
            LikeController[Like Controller]
            MessageController[Message Controller]
            RecommendationController[Recommendation Controller]
        end

        subgraph "Service Layer"
            MatchmakingService[Matchmaking Service]
            NotificationService[Notification Service]
            ValidationService[Validation Service]
        end

        subgraph "Model Layer"
            UserModel[User Model]
            PreferenceModel[Preference Model]
            MatchModel[Match Model]
            LikeModel[Like Model]
            MessageModel[Message Model]
        end
    end

    %% Data Layer
    subgraph "Data Layer"
        subgraph "Database"
            MySQL[(MySQL Database)]
            Redis[(Redis Cache)]
        end

        subgraph "File Storage"
            Firebase[Firebase Storage]
        end
    end

    %% External Services
    subgraph "External Services"
        EmailService[Email Service]
        PushNotification[Push Notifications]
        Analytics[Analytics Service]
    end

    %% Connections
    WebApp --> LoadBalancer
    MobileApp --> LoadBalancer
    AdminPanel --> LoadBalancer

    LoadBalancer --> RateLimiter
    RateLimiter --> CORS
    CORS --> Sanctum

    Sanctum --> Middleware
    Middleware --> AuthController
    Middleware --> UserController
    Middleware --> MatchController
    Middleware --> LikeController
    Middleware --> MessageController
    Middleware --> RecommendationController

    AuthController --> TokenManager
    UserController --> ValidationService
    MatchController --> MatchmakingService
    LikeController --> MatchmakingService
    MessageController --> NotificationService
    RecommendationController --> MatchmakingService

    MatchmakingService --> UserModel
    MatchmakingService --> PreferenceModel
    MatchmakingService --> MatchModel
    MatchmakingService --> LikeModel

    UserController --> UserModel
    MatchController --> MatchModel
    LikeController --> LikeModel
    MessageController --> MessageModel

    UserModel --> MySQL
    PreferenceModel --> MySQL
    MatchModel --> MySQL
    LikeModel --> MySQL
    MessageModel --> MySQL

    MatchmakingService --> Redis
    NotificationService --> Redis

    UserController --> Firebase
    NotificationService --> EmailService
    NotificationService --> PushNotification
    Analytics --> MySQL
```

## 🔄 Data Flow Schema

```mermaid
flowchart TD
    %% User Registration Flow
    subgraph "User Registration Flow"
        A1[User Registration Request] --> A2[Validate Input Data]
        A2 --> A3[Check Email Uniqueness]
        A3 --> A4[Hash Password]
        A4 --> A5[Create User Record]
        A5 --> A6[Generate Auth Token]
        A6 --> A7[Return User Data & Token]
    end

    %% Authentication Flow
    subgraph "Authentication Flow"
        B1[Login Request] --> B2[Validate Credentials]
        B2 --> B3[Generate New Token]
        B3 --> B4[Store Token Hash]
        B4 --> B5[Return User Data & Token]
    end

    %% Matchmaking Flow
    subgraph "Matchmaking Flow"
        C1[Get Recommendations Request] --> C2[Load User Preferences]
        C2 --> C3[Filter Eligible Users]
        C3 --> C4[Calculate Compatibility Scores]
        C4 --> C5[Apply Collaborative Filtering]
        C5 --> C6[Rank Recommendations]
        C6 --> C7[Return Ranked Results]
    end

    %% Like System Flow
    subgraph "Like System Flow"
        D1[Like User Request] --> D2[Validate User Exists]
        D2 --> D3[Create Like Record]
        D3 --> D4[Check for Mutual Like]
        D4 --> D5{Mutual Like?}
        D5 -->|Yes| D6[Create Match]
        D5 -->|No| D7[Return Confirmation]
        D6 --> D8[Send Notifications]
        D8 --> D7
    end

    %% Messaging Flow
    subgraph "Messaging Flow"
        E1[Send Message Request] --> E2[Validate Match Exists]
        E2 --> E3[Create Message Record]
        E3 --> E4[Send Notification]
        E4 --> E5[Return Confirmation]
    end

    %% Data Relationships
    A5 -.->|Creates| UserRecord[User Record]
    A5 -.->|Creates| PreferenceRecord[Preference Record]
    D3 -.->|Creates| LikeRecord[Like Record]
    D6 -.->|Creates| MatchRecord[Match Record]
    E3 -.->|Creates| MessageRecord[Message Record]
```

## 🗄️ Database Schema Architecture

```mermaid
erDiagram
    %% Core User Entity
    users {
        bigint id PK
        varchar name
        varchar email UK
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
        timestamp created_at
        timestamp updated_at
    }

    %% User Preferences
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

    %% Authentication Tokens
    personal_access_tokens {
        bigint id PK
        bigint tokenable_id FK
        varchar tokenable_type
        varchar name
        varchar token UK
        text abilities
        timestamp last_used_at
        timestamp expires_at
        timestamp created_at
        timestamp updated_at
    }

    %% User Relationships
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
    users ||--o{ personal_access_tokens : "has"
    users ||--o{ matches : "user_matches"
    users ||--o{ matches : "matched_by"
    users ||--o{ likes : "likes"
    users ||--o{ likes : "liked_by"
    users ||--o{ messages : "sends"
    users ||--o{ messages : "receives"
```

## 🔧 Component Architecture

```mermaid
graph LR
    %% API Controllers
    subgraph "API Controllers"
        AC1[AuthController]
        AC2[UserController]
        AC3[MatchController]
        AC4[LikeController]
        AC5[MessageController]
        AC6[RecommendationController]
    end

    %% Services
    subgraph "Business Services"
        BS1[MatchmakingService]
        BS2[NotificationService]
        BS3[ValidationService]
        BS4[FileUploadService]
    end

    %% Models
    subgraph "Data Models"
        DM1[User]
        DM2[Preference]
        DM3[Match]
        DM4[Like]
        DM5[Message]
    end

    %% Database
    subgraph "Data Storage"
        DS1[(MySQL)]
        DS2[(Redis)]
        Firebase[(Firebase Storage)]
    end

    %% External Services
    subgraph "External Services"
        ES1[Email Service]
        ES2[Push Notifications]
        ES3[Analytics]
    end

    %% Connections
    AC1 --> BS3
    AC2 --> BS3
    AC2 --> BS4
    AC3 --> BS1
    AC4 --> BS1
    AC5 --> BS2
    AC6 --> BS1

    BS1 --> DM1
    BS1 --> DM2
    BS1 --> DM3
    BS1 --> DM4
    BS2 --> DM5
    BS3 --> DM1
            BS4 --> Firebase

    DM1 --> DS1
    DM2 --> DS1
    DM3 --> DS1
    DM4 --> DS1
    DM5 --> DS1

    BS1 --> DS2
    BS2 --> ES1
    BS2 --> ES2
    ES3 --> DS1
```

## 📡 API Endpoint Schema

```mermaid
graph TD
    %% Authentication Endpoints
    subgraph "Authentication Endpoints"
        AE1[POST /api/register]
        AE2[POST /api/login]
        AE3[POST /api/logout]
        AE4[GET /api/user]
    end

    %% User Profile Endpoints
    subgraph "User Profile Endpoints"
        PE1[GET /api/profile]
        PE2[PUT /api/profile]
        PE3[POST /api/profile/preferences]
    end

    %% Matchmaking Endpoints
    subgraph "Matchmaking Endpoints"
        ME1[GET /api/recommendations]
        ME2[POST /api/matches/{user_id}]
        ME3[GET /api/matches]
        ME4[DELETE /api/matches/{user_id}]
    end

    %% Like System Endpoints
    subgraph "Like System Endpoints"
        LE1[POST /api/likes/{user_id}]
        LE2[DELETE /api/likes/{user_id}]
        LE3[GET /api/likes]
    end

    %% Messaging Endpoints
    subgraph "Messaging Endpoints"
        MGE1[POST /api/messages/send]
        MGE2[GET /api/messages/{user_id}]
        MGE3[GET /api/messages]
    end

    %% Controller Connections
    AE1 --> AC1
    AE2 --> AC1
    AE3 --> AC1
    AE4 --> AC1

    PE1 --> AC2
    PE2 --> AC2
    PE3 --> AC2

    ME1 --> AC6
    ME2 --> AC3
    ME3 --> AC3
    ME4 --> AC3

    LE1 --> AC4
    LE2 --> AC4
    LE3 --> AC4

    MGE1 --> AC5
    MGE2 --> AC5
    MGE3 --> AC5
```

## 🔐 Security Schema

```mermaid
graph TD
    %% Security Layers
    subgraph "Security Layers"
        SL1[Rate Limiting]
        SL2[CORS Protection]
        SL3[Authentication]
        SL4[Authorization]
        SL5[Input Validation]
        SL6[Data Encryption]
    end

    %% Security Components
    subgraph "Security Components"
        SC1[Load Balancer]
        SC2[API Gateway]
        SC3[Laravel Sanctum]
        SC4[Middleware Stack]
        SC5[Validation Rules]
        SC6[Database Encryption]
    end

    %% Security Flow
    SF1[Client Request] --> SC1
    SC1 --> SL1
    SL1 --> SC2
    SC2 --> SL2
    SL2 --> SL3
    SL3 --> SC3
    SC3 --> SL4
    SL4 --> SC4
    SC4 --> SL5
    SL5 --> SC5
    SC5 --> SL6
    SL6 --> SC6
```

## 🚀 Performance Schema

```mermaid
graph LR
    %% Caching Layers
    subgraph "Caching Strategy"
        CS1[Application Cache]
        CS2[Database Cache]
        CS3[CDN Cache]
        CS4[Browser Cache]
    end

    %% Performance Components
    subgraph "Performance Components"
        PC1[Redis Cache]
        PC2[Database Indexes]
        PC3[Query Optimization]
        PC4[Connection Pooling]
        PC5[Load Balancing]
        PC6[CDN]
    end

    %% Optimization Flow
    OF1[User Request] --> PC5
    PC5 --> CS4
    CS4 --> CS3
    CS3 --> PC6
    PC6 --> CS1
    CS1 --> PC1
    PC1 --> CS2
    CS2 --> PC2
    PC2 --> PC3
    PC3 --> PC4
```

## 📊 Monitoring Schema

```mermaid
graph TD
    %% Monitoring Components
    subgraph "Monitoring System"
        MS1[Application Monitoring]
        MS2[Database Monitoring]
        MS3[Performance Monitoring]
        MS4[Error Tracking]
        MS5[User Analytics]
    end

    %% Metrics Collection
    subgraph "Metrics Collection"
        MC1[Request/Response Logs]
        MC2[Database Query Logs]
        MC3[Performance Metrics]
        MC4[Error Logs]
        MC5[User Behavior Data]
    end

    %% Monitoring Flow
    MF1[System Activity] --> MC1
    MF1 --> MC2
    MF1 --> MC3
    MF1 --> MC4
    MF1 --> MC5

    MC1 --> MS1
    MC2 --> MS2
    MC3 --> MS3
    MC4 --> MS4
    MC5 --> MS5
```

## 🎯 Schema Benefits

### Architecture Benefits

1. **Scalability**: Horizontal scaling with load balancers
2. **Maintainability**: Clear separation of concerns
3. **Security**: Multiple security layers
4. **Performance**: Caching at multiple levels
5. **Reliability**: Fault-tolerant design

### Data Flow Benefits

1. **Efficiency**: Optimized data flow paths
2. **Consistency**: ACID compliance in database
3. **Performance**: Indexed queries and caching
4. **Security**: Data validation at each layer

### Component Benefits

1. **Modularity**: Independent service components
2. **Reusability**: Shared services across controllers
3. **Testability**: Isolated components for testing
4. **Flexibility**: Easy to extend and modify

---

**This schema diagram provides a comprehensive view of the matrimonial API system architecture, data flow, and component relationships. It serves as a technical blueprint for understanding the system design and implementation.**
